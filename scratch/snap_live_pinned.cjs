const http = require('http');
const { spawn } = require('child_process');
const fs = require('fs');
const path = require('path');

async function run() {
    const port = 9570;
    const url = 'http://localhost:8080/profil/tentang';
    console.log('Launching Headless Chrome on port', port, 'to', url);
    const chrome = spawn('C:/Program Files/Google/Chrome/Application/chrome.exe', [
        '--remote-debugging-port=' + port,
        '--headless=new',
        '--window-size=1440,900',
        '--user-data-dir=C:/Users/Miez/AppData/Local/Temp/cdp_live_new_9570',
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

    // Wait for initial render
    await new Promise(r => setTimeout(r, 2000));

    // Get trigger info and check height
    const infoRes = await send('Runtime.evaluate', {
        expression: `
            (() => {
                const wrap = document.getElementById('pinnedVisiMisiWrap');
                const stage = document.getElementById('gitVisiMisiStage');
                const st = window.ScrollTrigger ? ScrollTrigger.getById('visiMisiPin') : null;
                const wrapRect = wrap ? wrap.getBoundingClientRect() : null;
                const stageRect = stage ? stage.getBoundingClientRect() : null;
                return {
                    wrapExists: !!wrap,
                    stageExists: !!stage,
                    hasST: !!st,
                    start: st ? st.start : null,
                    end: st ? st.end : null,
                    wrapHeight: wrapRect ? wrapRect.height : null,
                    stageHeight: stageRect ? stageRect.height : null,
                    windowHeight: window.innerHeight
                };
            })()
        `,
        returnByValue: true
    });
    console.log('ScrollTrigger & Sizing Info:', infoRes.result.value);

    const stInfo = infoRes.result.value;
    if (!stInfo.hasST) {
        console.error('ScrollTrigger not initialized!');
        ws.close();
        chrome.kill();
        return;
    }

    const artifactDir = 'C:/Users/Miez/.gemini/antigravity/brain/9ae60431-3fe3-45ec-9f5d-32eaac2d8f5a';
    const startY = stInfo.start;
    const endY = stInfo.end;
    const distance = endY - startY;

    const testPoints = [
        { name: 'new_step1_visi', targetY: startY + distance * 0.10, desc: 'Progress 10% - Visi Utama only' },
        { name: 'new_step2_misi1', targetY: startY + distance * 0.35, desc: 'Progress 35% - S-Curve to Misi 1' },
        { name: 'new_step3_fork', targetY: startY + distance * 0.65, desc: 'Progress 65% - Fork Loop to Misi 2 & Hollow dot' },
        { name: 'new_step4_full', targetY: startY + distance * 0.95, desc: 'Progress 95% - All 6 commits visible' },
        { name: 'new_step5_unpinned', targetY: endY + 450, desc: 'Unpinned - Next Section (Tujuan & Fungsi)' }
    ];

    for (const pt of testPoints) {
        console.log(`Scrolling to Y=${pt.targetY} for ${pt.name} (${pt.desc})...`);
        await send('Runtime.evaluate', {
            expression: `
                window.scrollTo({ top: ${pt.targetY}, behavior: 'instant' });
                if (window.ScrollTrigger) {
                    ScrollTrigger.update();
                    const st = ScrollTrigger.getById('visiMisiPin');
                    if (st && st.animation) st.animation.progress(st.progress);
                }
            `
        });
        await new Promise(r => setTimeout(r, 600));

        const stateRes = await send('Runtime.evaluate', {
            expression: `
                (() => {
                    const st = ScrollTrigger.getById('visiMisiPin');
                    const badge = document.getElementById('vmProgressPercent');
                    const stage = document.getElementById('gitVisiMisiStage');
                    const stageRect = stage ? stage.getBoundingClientRect() : null;
                    return {
                        progress: st ? (st.progress * 100).toFixed(1) + '%' : null,
                        badgeText: badge ? badge.textContent : null,
                        stageTop: stageRect ? stageRect.top : null,
                        stageBottom: stageRect ? stageRect.bottom : null,
                        isBottomVisible: stageRect ? (stageRect.bottom <= window.innerHeight) : null
                    };
                })()
            `,
            returnByValue: true
        });
        console.log(`State at ${pt.name}:`, stateRes.result.value);

        const snap = await send('Page.captureScreenshot', { format: 'png' });
        fs.writeFileSync(path.join(artifactDir, `${pt.name}.png`), Buffer.from(snap.data, 'base64'));
        console.log(`Saved screenshot: ${pt.name}.png`);
    }

    // Capture Light Mode at full tree
    console.log('Capturing Light Mode full tree...');
    await send('Runtime.evaluate', {
        expression: `
            document.documentElement.setAttribute('data-theme', 'ai_unmul');
            document.documentElement.classList.remove('dark');
            window.scrollTo({ top: ${startY + distance * 0.95}, behavior: 'instant' });
            if (window.ScrollTrigger) {
                ScrollTrigger.update();
                const st = ScrollTrigger.getById('visiMisiPin');
                if (st && st.animation) st.animation.progress(st.progress);
            }
        `
    });
    await new Promise(r => setTimeout(r, 600));
    const lightSnap = await send('Page.captureScreenshot', { format: 'png' });
    fs.writeFileSync(path.join(artifactDir, 'new_step4_full_light.png'), Buffer.from(lightSnap.data, 'base64'));
    console.log('Saved screenshot: new_step4_full_light.png');

    // Also test 768px height (laptop display) to PROVE bottom is not cut off
    console.log('Testing 768px height viewport (Laptop test)...');
    await send('Emulation.setDeviceMetricsOverride', {
        width: 1366,
        height: 768,
        deviceScaleFactor: 1,
        mobile: false
    });
    await new Promise(r => setTimeout(r, 400));
    await send('Runtime.evaluate', {
        expression: `
            document.documentElement.setAttribute('data-theme', 'ai_unmul_dark');
            document.documentElement.classList.add('dark');
            initPinnedVisiMisi();
            const st = ScrollTrigger.getById('visiMisiPin');
            if (st) {
                window.scrollTo({ top: st.start + (st.end - st.start) * 0.95, behavior: 'instant' });
                ScrollTrigger.update();
                if (st.animation) st.animation.progress(st.progress);
            }
        `
    });
    await new Promise(r => setTimeout(r, 600));
    const laptopCheck = await send('Runtime.evaluate', {
        expression: `
            (() => {
                const stage = document.getElementById('gitVisiMisiStage');
                const rect = stage ? stage.getBoundingClientRect() : null;
                return {
                    stageTop: rect ? rect.top : null,
                    stageBottom: rect ? rect.bottom : null,
                    windowHeight: window.innerHeight,
                    isBottomVisible: rect ? (rect.bottom <= window.innerHeight) : null,
                    remainingBottomMargin: rect ? (window.innerHeight - rect.bottom) : null
                };
            })()
        `,
        returnByValue: true
    });
    console.log('Laptop 768px check:', laptopCheck.result.value);

    const snap768 = await send('Page.captureScreenshot', { format: 'png' });
    fs.writeFileSync(path.join(artifactDir, 'new_step4_full_768h.png'), Buffer.from(snap768.data, 'base64'));
    console.log('Saved screenshot: new_step4_full_768h.png');

    ws.close();
    chrome.kill();
    console.log('Finished live pinned test.');
}

run().catch(console.error);
