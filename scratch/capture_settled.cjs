const http = require('http');
const { spawn } = require('child_process');
const fs = require('fs');

async function run() {
    const port = 9524;
    const chrome = spawn('C:/Program Files/Google/Chrome/Application/chrome.exe', [
        '--remote-debugging-port=' + port,
        '--headless=new',
        '--window-size=1440,1100',
        '--user-data-dir=C:/Users/Miez/AppData/Local/Temp/cdp_hero_9524',
        'about:blank'
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
    await send('Page.enable');
    await send('Runtime.enable');
    await send('Page.navigate', { url: 'http://localhost:8080/' });
    await new Promise(r => setTimeout(r, 3800));

    // Dark mode
    await send('Runtime.evaluate', {
        expression: `
            document.documentElement.setAttribute('data-theme', 'ai_unmul_dark');
            document.documentElement.classList.add('dark');
        `
    });
    await new Promise(r => setTimeout(r, 400));

    const shotDark = await send('Page.captureScreenshot', { format: 'png' });
    fs.writeFileSync('C:/Users/Miez/.gemini/antigravity/brain/9ae60431-3fe3-45ec-9f5d-32eaac2d8f5a/hero_settled_dark.png', Buffer.from(shotDark.data, 'base64'));

    // Light mode
    await send('Runtime.evaluate', {
        expression: `
            document.documentElement.setAttribute('data-theme', 'ai_unmul_light');
            document.documentElement.classList.remove('dark');
        `
    });
    await new Promise(r => setTimeout(r, 400));
    const shotLight = await send('Page.captureScreenshot', { format: 'png' });
    fs.writeFileSync('C:/Users/Miez/.gemini/antigravity/brain/9ae60431-3fe3-45ec-9f5d-32eaac2d8f5a/hero_settled_light.png', Buffer.from(shotLight.data, 'base64'));

    ws.close();
    chrome.kill();
    console.log('Settled hero saved!');
}
run();

