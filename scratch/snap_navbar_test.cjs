const http = require('http');
const { spawn } = require('child_process');
const fs = require('fs');
const path = require('path');

async function run() {
    const port = 9590;
    const filePath = 'file:///' + path.resolve(__dirname, 'test_navbar_port.html').replace(/\\/g, '/');

    const chrome = spawn('C:/Program Files/Google/Chrome/Application/chrome.exe', [
        '--remote-debugging-port=' + port,
        '--headless=new',
        '--window-size=1440,900',
        '--user-data-dir=C:/Users/Miez/AppData/Local/Temp/cdp_nav_9590',
        filePath
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

    // Scroll to 95% of the Visi Misi track
    const info = await send('Runtime.evaluate', {
        expression: `
            (() => {
                const w = document.getElementById('vmScrollTrack');
                const rect = w.getBoundingClientRect();
                return {
                    top: w.offsetTop,
                    total: rect.height - window.innerHeight
                };
            })()
        `,
        returnByValue: true
    });

    const targetY = info.result.value.top + info.result.value.total * 0.95;
    await send('Runtime.evaluate', {
        expression: `
            window.scrollTo(0, ${targetY});
            window.dispatchEvent(new Event('scroll'));
        `
    });
    await new Promise(r => setTimeout(r, 400));

    const snap = await send('Page.captureScreenshot', { format: 'png' });
    fs.writeFileSync('C:/Users/Miez/.gemini/antigravity/brain/9ae60431-3fe3-45ec-9f5d-32eaac2d8f5a/test_navbar_full.png', Buffer.from(snap.data, 'base64'));
    console.log('Saved test_navbar_full.png');

    ws.close();
    chrome.kill();
}
run().catch(console.error);

