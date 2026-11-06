const http = require('http');
const { spawn } = require('child_process');
const fs = require('fs');
const path = require('path');

async function run() {
    const port = 9580;
    const filePath = 'file:///' + path.resolve(__dirname, 'test_exact_ascii_port.html').replace(/\\/g, '/');
    console.log('Testing Exact ASCII Port at:', filePath);

    const chrome = spawn('C:/Program Files/Google/Chrome/Application/chrome.exe', [
        '--remote-debugging-port=' + port,
        '--headless=new',
        '--window-size=1440,900',
        '--user-data-dir=C:/Users/Miez/AppData/Local/Temp/cdp_ascii_9580',
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

    if (!wsUrl) {
        console.error('Failed to get WebSocket debugger URL');
        chrome.kill();
        return;
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
    await send('DOM.enable');

    await new Promise(r => setTimeout(r, 1200));

    const info = await send('Runtime.evaluate', {
        expression: `
            (() => {
                const w = document.getElementById('vmScrollTrack');
                const rect = w ? w.getBoundingClientRect() : null;
                const total = rect ? rect.height - window.innerHeight : 0;
                return {
                    offsetTop: w.offsetTop,
                    trackHeight: rect ? rect.height : 0,
                    totalScroll: total
                };
            })()
        `,
        returnByValue: true
    });
    console.log('Track Info:', info.result.value);

    const artifactDir = 'C:/Users/Miez/.gemini/antigravity/brain/9ae60431-3fe3-45ec-9f5d-32eaac2d8f5a';
    const offsetTop = info.result.value.offsetTop;
    const total = info.result.value.totalScroll;

    const points = [
        { name: 'ascii_step1_visi', pct: 0.10 },
        { name: 'ascii_step2_misi1', pct: 0.35 },
        { name: 'ascii_step3_fork', pct: 0.65 },
        { name: 'ascii_step4_full', pct: 0.95 },
        { name: 'ascii_step5_unpinned', pct: 1.25 }
    ];

    for (const pt of points) {
        const targetY = offsetTop + total * pt.pct;
        console.log(`Scrolling to Y=${targetY} for ${pt.name} (${Math.round(pt.pct * 100)}%)...`);
        await send('Runtime.evaluate', {
            expression: `
                window.scrollTo(0, ${targetY});
                window.dispatchEvent(new Event('scroll'));
            `
        });
        await new Promise(r => setTimeout(r, 300));

        const snap = await send('Page.captureScreenshot', { format: 'png' });
        fs.writeFileSync(path.join(artifactDir, `${pt.name}.png`), Buffer.from(snap.data, 'base64'));
        console.log(`Saved screenshot: ${pt.name}.png`);
    }

    ws.close();
    chrome.kill();
    console.log('Done test.');
}

run().catch(console.error);

