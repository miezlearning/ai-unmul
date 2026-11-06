const http = require('http');
const { spawn } = require('child_process');

async function run() {
    const port = 9527;
    const chrome = spawn('C:/Program Files/Google/Chrome/Application/chrome.exe', [
        '--remote-debugging-port=' + port,
        '--headless=new',
        '--user-data-dir=C:/Users/Miez/AppData/Local/Temp/cdp_debug_9527',
        'http://localhost:8080/profil/tentang'
    ]);
    let wsUrl = null;
    for (let i = 0; i < 30; i++) {
        await new Promise(r => setTimeout(r, 200));
        try {
            const list = await new Promise((res, rej) => {
                http.get('http://127.0.0.1:' + port + '/json', r => {
                    let d = ''; r.on('data', c => d += c); r.on('end', () => res(JSON.parse(d)));
                }).on('error', rej);
            });
            const p = list.find(x => x.type === 'page');
            if (p) { wsUrl = p.webSocketDebuggerUrl; break; }
        } catch(e) {}
    }
    const ws = new WebSocket(wsUrl);
    await new Promise(r => ws.onopen = r);
    let id = 1;
    const send = (method, params = {}) => new Promise(r => {
        const curId = id++;
        const handler = evt => {
            const msg = JSON.parse(evt.data);
            if (msg.id === curId) { ws.removeEventListener('message', handler); r(msg.result); }
        };
        ws.addEventListener('message', handler);
        ws.send(JSON.stringify({ id: curId, method, params }));
    });
    ws.addEventListener('message', evt => {
        const msg = JSON.parse(evt.data);
        if (msg.method === 'Runtime.consoleAPICalled') {
            console.log('CONSOLE:', msg.params.type, msg.params.args.map(a => a.value));
        }
        if (msg.method === 'Runtime.exceptionThrown') {
            console.error('EXCEPTION:', msg.params.exceptionDetails);
        }
    });
    await send('Runtime.enable');
    await send('Page.enable');
    await new Promise(r => setTimeout(r, 2000));
    
    // Check computed styles and positions
    const info = await send('Runtime.evaluate', {
        expression: `
            (() => {
                let err = null;
                try {
                    updateVisiMisiSpine();
                } catch(e) {
                    err = e.message;
                }
                return JSON.stringify({
                    fnType: typeof updateVisiMisiSpine,
                    callErr: err,
                    dot1: { cx: document.getElementById('vmDot1')?.getAttribute('cx'), cy: document.getElementById('vmDot1')?.getAttribute('cy') },
                    dot2: { cx: document.getElementById('vmDot2')?.getAttribute('cx'), cy: document.getElementById('vmDot2')?.getAttribute('cy') },
                    pTop: document.getElementById('vmPathTop')?.getAttribute('d')
                });
            })()
        `
    });
    console.log('INFO:', info.result.value);
    ws.close();
    chrome.kill();
}
run();

