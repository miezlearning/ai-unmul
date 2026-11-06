const http = require('http');
const { spawn } = require('child_process');

async function test() {
    const port = 9565;
    const chrome = spawn('C:/Program Files/Google/Chrome/Application/chrome.exe', [
        '--remote-debugging-port=' + port,
        '--headless=new',
        '--window-size=1440,900',
        '--user-data-dir=C:/Users/Miez/AppData/Local/Temp/cdp_debug_9565',
        'file:///C:/Users/Miez/Documents/AI UNMUL/ai-unmul/scratch/test_perfect_tree.html'
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
    await new Promise(r => setTimeout(r, 1500));
    const res = await send('Runtime.evaluate', {
        expression: `
            (() => {
                const st = ScrollTrigger.getById("perfectTreePin");
                window.scrollTo(0, 2500);
                ScrollTrigger.update();
                return {
                    start: st.start,
                    end: st.end,
                    scrollY: window.scrollY,
                    progress: st.progress,
                    maxScroll: ScrollTrigger.maxScroll(window)
                };
            })()
        `,
        returnByValue: true
    });
    console.log('Result:', res.result.value);
    ws.close();
    chrome.kill();
}
test().catch(console.error);

