const http = require('http');
const { spawn } = require('child_process');

async function check() {
    const port = 9585;
    const chrome = spawn('C:/Program Files/Google/Chrome/Application/chrome.exe', [
        '--remote-debugging-port=' + port,
        '--headless=new',
        '--window-size=1440,900',
        '--user-data-dir=C:/Users/Miez/AppData/Local/Temp/cdp_chk_9585',
        'file:///C:/Users/Miez/Documents/AI UNMUL/ai-unmul/scratch/test_exact_ascii_port.html'
    ]);
    let wsUrl = null;
    for (let i = 0; i < 40; i++) {
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
    await send('Page.enable');
    await send('Runtime.enable');
    await new Promise(r => setTimeout(r, 1000));
    const res = await send('Runtime.evaluate', {
        expression: `
            (() => {
                const c = document.getElementById("vmContainer");
                const rows = Array.from(document.querySelectorAll(".vm-row"));
                return {
                    containerHeight: c.offsetHeight,
                    windowHeight: window.innerHeight,
                    rowHeights: rows.map(r => r.offsetHeight),
                    rowTops: rows.map(r => r.offsetTop)
                };
            })()
        `,
        returnByValue: true
    });
    console.log('Metrics:', res.result.value);
    ws.close();
    chrome.kill();
}
check().catch(console.error);

