const http = require('http');
const { spawn } = require('child_process');
const fs = require('fs');
const path = require('path');

async function run() {
    const port = 9530;
    const url = 'http://localhost:8080/profil/tentang';
    const chrome = spawn('C:/Program Files/Google/Chrome/Application/chrome.exe', [
        '--remote-debugging-port=' + port,
        '--headless=new',
        '--window-size=1440,1200',
        '--user-data-dir=C:/Users/Miez/AppData/Local/Temp/cdp_tentang_9530',
        url
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

    // Wait for page to settle
    await new Promise(r => setTimeout(r, 1500));

    const artifactDir = 'C:/Users/Miez/.gemini/antigravity/brain/9ae60431-3fe3-45ec-9f5d-32eaac2d8f5a';
    let svgAttrs;

    const updateRes = await send('Runtime.evaluate', {
        expression: `
            (() => {
                try {
                    if (typeof updateVisiMisiSpine !== 'function') return 'Not a function: ' + typeof updateVisiMisiSpine;
                    updateVisiMisiSpine();
                    return 'Updated successfully!';
                } catch(e) {
                    return 'Error: ' + e.message + ' at ' + e.stack;
                }
            })()
        `,
        returnByValue: true
    });
    console.log('updateRes:', updateRes.result.value);
    const debugRows = await send('Runtime.evaluate', {
        expression: `
            (() => {
                const stage = document.getElementById('gitVisiMisiStage');
                const stageRect = stage.getBoundingClientRect();
                const rows = [1, 2, 3, 4, 5, 6].map(i => {
                    const el = document.getElementById('vmRow' + i);
                    if (!el) return null;
                    const r = el.getBoundingClientRect();
                    return {
                        id: 'vmRow' + i,
                        stageTop: stageRect.top,
                        rowTop: r.top,
                        rowMid: (r.top + r.bottom) / 2,
                        diff: (r.top + r.bottom) / 2 - stageRect.top
                    };
                });
                return { stageRect, rows };
            })()
        `,
        returnByValue: true
    });
    console.log('debugRows:', JSON.stringify(debugRows.result.value, null, 2));

    await send('Runtime.evaluate', {
        expression: `
            document.documentElement.setAttribute('data-theme', 'ai_unmul_dark');
            document.documentElement.classList.add('dark');
            const el = document.getElementById('visiMisiSection');
            if (el) el.scrollIntoView({ behavior: 'instant', block: 'center' });
            if (typeof updateVisiMisiSpine === 'function') updateVisiMisiSpine();
            if (window.ScrollTrigger) ScrollTrigger.refresh();
        `
    });
    await new Promise(r => setTimeout(r, 800));

    // Force strokes visible if needed for screenshot
    await send('Runtime.evaluate', {
        expression: `
            ['vmPathTop', 'vmPathForkLeft', 'vmPathForkRight', 'vmPathBottom'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.style.strokeDashoffset = '0';
            });
        `
    });
    await new Promise(r => setTimeout(r, 300));

    // Check SVG attributes 2
    svgAttrs = await send('Runtime.evaluate', {
        expression: `
            (() => {
                return {
                    dot1: { cx: document.getElementById('vmDot1')?.getAttribute('cx'), cy: document.getElementById('vmDot1')?.getAttribute('cy') },
                    dot2: { cx: document.getElementById('vmDot2')?.getAttribute('cx'), cy: document.getElementById('vmDot2')?.getAttribute('cy') },
                    dot3: { cx: document.getElementById('vmDot3')?.getAttribute('cx'), cy: document.getElementById('vmDot3')?.getAttribute('cy') },
                    dot4: { cx: document.getElementById('vmDot4')?.getAttribute('cx'), cy: document.getElementById('vmDot4')?.getAttribute('cy') },
                    pTop: document.getElementById('vmPathTop')?.getAttribute('d'),
                    pForkLeft: document.getElementById('vmPathForkLeft')?.getAttribute('d'),
                    pForkRight: document.getElementById('vmPathForkRight')?.getAttribute('d'),
                    pBottom: document.getElementById('vmPathBottom')?.getAttribute('d'),
                    svgRect: document.getElementById('gitVmSvg')?.getBoundingClientRect()
                };
            })()
        `,
        returnByValue: true
    });
    console.log('svgAttrs:', JSON.stringify(svgAttrs.result.value, null, 2));

    // Get bounding box of #visiMisiSection
    const boxDark = await send('Runtime.evaluate', {
        expression: `
            (() => {
                const el = document.getElementById('visiMisiSection');
                if (!el) return null;
                const r = el.getBoundingClientRect();
                return { x: r.left, y: r.top + window.scrollY, width: r.width, height: r.height };
            })()
        `,
        returnByValue: true
    });

    const valDark = (boxDark && boxDark.result) ? boxDark.result.value : (boxDark ? boxDark.value : null);
    console.log('boxDark:', JSON.stringify(valDark));

    if (valDark) {
        const clip = {
            x: Math.max(0, valDark.x),
            y: Math.max(0, valDark.y),
            width: Math.min(1440, valDark.width),
            height: valDark.height,
            scale: 1
        };
        const shotDark = await send('Page.captureScreenshot', { format: 'png', clip });
        fs.writeFileSync(path.join(artifactDir, 'tentang_visimisi_dark.png'), Buffer.from(shotDark.data, 'base64'));
        console.log('Saved dark mode screenshot');
    }

    // 2. Light Mode
    await send('Page.navigate', { url: 'http://localhost:8080/profil/tentang?theme=light' });
    await new Promise(r => setTimeout(r, 1200));

    await send('Runtime.evaluate', {
        expression: `
            const el = document.getElementById('visiMisiSection');
            if (el) el.scrollIntoView({ behavior: 'instant', block: 'center' });
            if (typeof updateVisiMisiSpine === 'function') updateVisiMisiSpine();
            if (window.ScrollTrigger) ScrollTrigger.refresh();
        `
    });
    await new Promise(r => setTimeout(r, 600));

    // Force strokes visible if needed for screenshot
    await send('Runtime.evaluate', {
        expression: `
            ['vmPathTop', 'vmPathForkLeft', 'vmPathForkRight', 'vmPathBottom'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.style.strokeDashoffset = '0';
            });
        `
    });
    await new Promise(r => setTimeout(r, 300));

    // Check SVG attributes 2
    svgAttrs = await send('Runtime.evaluate', {
        expression: `
            (() => {
                return {
                    dot1: { cx: document.getElementById('vmDot1')?.getAttribute('cx'), cy: document.getElementById('vmDot1')?.getAttribute('cy') },
                    dot2: { cx: document.getElementById('vmDot2')?.getAttribute('cx'), cy: document.getElementById('vmDot2')?.getAttribute('cy') },
                    dot3: { cx: document.getElementById('vmDot3')?.getAttribute('cx'), cy: document.getElementById('vmDot3')?.getAttribute('cy') },
                    dot4: { cx: document.getElementById('vmDot4')?.getAttribute('cx'), cy: document.getElementById('vmDot4')?.getAttribute('cy') },
                    pTop: document.getElementById('vmPathTop')?.getAttribute('d'),
                    pForkLeft: document.getElementById('vmPathForkLeft')?.getAttribute('d'),
                    pForkRight: document.getElementById('vmPathForkRight')?.getAttribute('d'),
                    pBottom: document.getElementById('vmPathBottom')?.getAttribute('d'),
                    svgRect: document.getElementById('gitVmSvg')?.getBoundingClientRect()
                };
            })()
        `,
        returnByValue: true
    });
    console.log('svgAttrs:', JSON.stringify(svgAttrs.result.value, null, 2));

    const boxLight = await send('Runtime.evaluate', {
        expression: `
            (() => {
                const el = document.getElementById('visiMisiSection');
                if (!el) return null;
                const r = el.getBoundingClientRect();
                return { x: r.left, y: r.top + window.scrollY, width: r.width, height: r.height };
            })()
        `,
        returnByValue: true
    });

    const valLight = (boxLight && boxLight.result) ? boxLight.result.value : (boxLight ? boxLight.value : null);
    console.log('boxLight:', JSON.stringify(valLight));

    if (valLight) {
        const clip = {
            x: Math.max(0, valLight.x),
            y: Math.max(0, valLight.y),
            width: Math.min(1440, valLight.width),
            height: valLight.height,
            scale: 1
        };
        const shotLight = await send('Page.captureScreenshot', { format: 'png', clip });
        fs.writeFileSync(path.join(artifactDir, 'tentang_visimisi_light.png'), Buffer.from(shotLight.data, 'base64'));
        console.log('Saved light mode screenshot');
    }

    ws.close();
    chrome.kill();
    console.log('Done capturing screenshots!');
}

run();
