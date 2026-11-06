const http = require('http');
const { spawn } = require('child_process');
const fs = require('fs');
const path = require('path');

async function run() {
    const port = 9585;
    const url = 'http://127.0.0.1:8080/profil/tentang';
    console.log('Testing live Go app at:', url);

    const chrome = spawn('C:/Program Files/Google/Chrome/Application/chrome.exe', [
        '--remote-debugging-port=' + port,
        '--headless=new',
        '--window-size=1440,900',
        '--user-data-dir=C:/Users/Miez/AppData/Local/Temp/cdp_live_9585',
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

    // Listen for console errors
    ws.addEventListener('message', evt => {
        const msg = JSON.parse(evt.data);
        if (msg.method === 'Runtime.consoleAPICalled') {
            console.log('BROWSER CONSOLE:', msg.params.type, msg.params.args.map(a => a.value || a.description).join(' '));
        }
        if (msg.method === 'Runtime.exceptionThrown') {
            console.error('BROWSER EXCEPTION:', msg.params.exceptionDetails);
        }
    });

    console.log('Navigating to', url);
    await send('Page.navigate', { url });

    // Wait until document.readyState === 'complete' and #vmScrollTrack exists
    let ready = false;
    for (let i = 0; i < 30; i++) {
        await new Promise(r => setTimeout(r, 300));
        const res = await send('Runtime.evaluate', {
            expression: `document.readyState === 'complete' && !!document.getElementById('vmScrollTrack')`,
            returnByValue: true
        });
        if (res.result && res.result.value) {
            ready = true;
            console.log('Page ready and #vmScrollTrack found!');
            break;
        }
    }

    if (!ready) {
        console.error('Timed out waiting for #vmScrollTrack to be found!');
        ws.close();
        chrome.kill();
        return;
    }

    const ancestors = await send('Runtime.evaluate', {
        expression: `
            (() => {
                let el = document.getElementById('vmStickyFrame');
                const list = [];
                while (el && el !== document.documentElement) {
                    const s = window.getComputedStyle(el);
                    list.push({
                        tag: el.tagName,
                        id: el.id,
                        className: el.className,
                        overflow: s.overflow,
                        overflowX: s.overflowX,
                        overflowY: s.overflowY,
                        position: s.position,
                        height: s.height
                    });
                    el = el.parentElement;
                }
                return list;
            })()
        `,
        returnByValue: true
    });
    console.log('ANCESTORS OF #vmStickyFrame:', JSON.stringify(ancestors.result.value, null, 2));

    // Ensure dark mode
    await send('Runtime.evaluate', {
        expression: `
            document.documentElement.classList.add('dark');
            document.documentElement.setAttribute('data-theme', 'ai_unmul_dark');
            window.dispatchEvent(new Event('resize'));
        `
    });
    await new Promise(r => setTimeout(r, 300));

    const snap = async (name) => {
        const res = await send('Page.captureScreenshot', { format: 'png' });
        const buf = Buffer.from(res.data, 'base64');
        const artifactDir = 'C:\\Users\\Miez\\.gemini\\antigravity\\brain\\9ae60431-3fe3-45ec-9f5d-32eaac2d8f5a';
        fs.writeFileSync(path.join(artifactDir, name + '.png'), buf);
        console.log('Saved screenshot:', name + '.png');
    };

    // Find bounding box of #vmScrollTrack
    const trackInfo = await send('Runtime.evaluate', {
        expression: `
            (() => {
                const el = document.getElementById('vmScrollTrack');
                if (!el) return null;
                const r = el.getBoundingClientRect();
                return {
                    top: r.top + window.scrollY,
                    height: el.offsetHeight,
                    viewportH: window.innerHeight
                };
            })()
        `,
        returnByValue: true
    });

    console.log('Track Info:', trackInfo.result.value);
    const { top: startY, height: trackH, viewportH } = trackInfo.result.value;
    const totalDist = trackH - viewportH;

    // Step 1: Initial entering (Visi visible)
    await send('Runtime.evaluate', { expression: `window.scrollTo(0, ${startY + 20});` });
    await new Promise(r => setTimeout(r, 300));
    await snap('live_final_step1_visi');

    // Step 2: 25% progress (Misi 1)
    await send('Runtime.evaluate', { expression: `window.scrollTo(0, ${startY + totalDist * 0.25});` });
    await new Promise(r => setTimeout(r, 300));
    await snap('live_final_step2_misi1');

    // Step 3: 50% progress (Fork & Apex)
    await send('Runtime.evaluate', { expression: `window.scrollTo(0, ${startY + totalDist * 0.50});` });
    await new Promise(r => setTimeout(r, 300));
    await snap('live_final_step3_fork');

    // Step 4: 85% progress (Full Git Tree)
    await send('Runtime.evaluate', { expression: `window.scrollTo(0, ${startY + totalDist * 0.85}); window.dispatchEvent(new Event('scroll'));` });
    await new Promise(r => setTimeout(r, 400));
    const debugInfo = await send('Runtime.evaluate', {
        expression: `
            (() => {
                const cont = document.getElementById('vmContainer');
                const cr = cont ? cont.getBoundingClientRect() : null;
                const stickyFrame = document.getElementById('vmStickyFrame');
                const sr = stickyFrame ? stickyFrame.getBoundingClientRect() : null;
                const svg = document.getElementById('vmSvg');
                const rows = Array.from(document.querySelectorAll('.vm-row')).map(r => {
                    const rr = r.getBoundingClientRect();
                    return {
                        rect: { top: rr.top, left: rr.left, width: rr.width, height: rr.height },
                        opacity: r.style.opacity,
                        transform: r.style.transform,
                        color: window.getComputedStyle(r).color,
                        text: r.textContent.trim().slice(0, 40)
                    };
                });
                const dots = Array.from(document.querySelectorAll('.vm-commit-dot')).map(d => ({
                    opacity: d.style.opacity,
                    cx: d.getAttribute('cx'),
                    cy: d.getAttribute('cy')
                }));
                const rect = document.getElementById('vmScrollTrack').getBoundingClientRect();
                return {
                    scrollY: window.scrollY,
                    trackRectTop: rect.top,
                    trackHeight: rect.height,
                    stickyRect: sr ? { top: sr.top, left: sr.left, width: sr.width, height: sr.height } : null,
                    contRect: cr ? { top: cr.top, left: cr.left, width: cr.width, height: cr.height } : null,
                    contDisplay: cont ? window.getComputedStyle(cont).display : null,
                    contVisibility: cont ? window.getComputedStyle(cont).visibility : null,
                    progress: -rect.top / (rect.height - window.innerHeight),
                    containerTransform: cont ? cont.style.transform : null,
                    svgHtmlLen: svg ? svg.innerHTML.length : 0,
                    rows,
                    dots
                };
            })()
        `,
        returnByValue: true
    });
    console.log('DEBUG INFO AT STEP 4:', JSON.stringify(debugInfo.result.value, null, 2));
    await snap('live_final_step4_full');

    // Step 5: Unpinned (smooth scroll into Section 3)
    await send('Runtime.evaluate', { expression: `window.scrollTo(0, ${startY + totalDist + 500});` });
    await new Promise(r => setTimeout(r, 300));
    await snap('live_final_step5_unpinned');

    // Step 6: 768px laptop height test
    await send('Emulation.setDeviceMetricsOverride', {
        width: 1366,
        height: 768,
        deviceScaleFactor: 1,
        mobile: false
    });
    await send('Runtime.evaluate', { expression: `window.scrollTo(0, ${startY + (trackH - 768) * 0.85});` });
    await new Promise(r => setTimeout(r, 300));
    await snap('live_final_step4_full_768h');

    // Step 7: Light mode test
    await send('Runtime.evaluate', {
        expression: `
            document.documentElement.classList.remove('dark');
            document.documentElement.setAttribute('data-theme', 'ai_unmul_light');
            window.dispatchEvent(new Event('resize'));
        `
    });
    await new Promise(r => setTimeout(r, 300));
    await snap('live_final_step4_full_light');

    ws.close();
    chrome.kill();
    console.log('All live tests completed!');
}

run().catch(console.error);
