const http = require('http');
const { spawn } = require('child_process');
const fs = require('fs');
const path = require('path');

async function run() {
    const port = 9560;
    const filePath = 'file:///' + path.resolve(__dirname, 'test_perfect_tree.html').replace(/\\/g, '/');
    console.log('Testing prototype at:', filePath);

    // Launch with standard 1440x900 desktop screen
    const chrome = spawn('C:/Program Files/Google/Chrome/Application/chrome.exe', [
        '--remote-debugging-port=' + port,
        '--headless=new',
        '--window-size=1440,900',
        '--user-data-dir=C:/Users/Miez/AppData/Local/Temp/cdp_perfect_9560',
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

    await new Promise(r => setTimeout(r, 1500));

    // Check trigger and stage dimensions
    const info = await send('Runtime.evaluate', {
        expression: `
            (() => {
                const st = ScrollTrigger.getById('perfectTreePin');
                const stage = document.getElementById('gitVisiMisiStage');
                const rect = stage ? stage.getBoundingClientRect() : null;
                return {
                    start: st ? st.start : null,
                    end: st ? st.end : null,
                    stageHeight: rect ? rect.height : null,
                    stageTop: rect ? rect.top : null,
                    stageBottom: rect ? rect.bottom : null,
                    windowHeight: window.innerHeight
                };
            })()
        `,
        returnByValue: true
    });
    console.log('Prototype Stage info:', info.result.value);

    const artifactDir = 'C:/Users/Miez/.gemini/antigravity/brain/9ae60431-3fe3-45ec-9f5d-32eaac2d8f5a';
    const startY = info.result.value.start;
    const endY = info.result.value.end;
    const distance = endY - startY;

    // Test at 95% progress (full tree)
    console.log('Scrolling to full tree at Y =', startY + distance * 0.95);
    await send('Runtime.evaluate', {
        expression: `
            window.scrollTo({ top: ${startY + distance * 0.95}, behavior: 'instant' });
            if (window.ScrollTrigger) {
                ScrollTrigger.update();
                const st = ScrollTrigger.getById('perfectTreePin');
                if (st && st.animation) st.animation.progress(st.progress);
            }
        `
    });
    await new Promise(r => setTimeout(r, 600));

    const snap = await send('Page.captureScreenshot', { format: 'png' });
    fs.writeFileSync(path.join(artifactDir, 'perfect_tree_full_900h.png'), Buffer.from(snap.data, 'base64'));
    console.log('Saved perfect_tree_full_900h.png');

    // Also test on smaller laptop height 768px!
    await send('Emulation.setDeviceMetricsOverride', {
        width: 1366,
        height: 768,
        deviceScaleFactor: 1,
        mobile: false
    });
    await new Promise(r => setTimeout(r, 300));

    const snap768 = await send('Page.captureScreenshot', { format: 'png' });
    fs.writeFileSync(path.join(artifactDir, 'perfect_tree_full_768h.png'), Buffer.from(snap768.data, 'base64'));
    console.log('Saved perfect_tree_full_768h.png');

    ws.close();
    chrome.kill();
    console.log('Done prototype test.');
}

run().catch(console.error);
