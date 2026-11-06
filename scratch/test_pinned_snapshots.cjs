const http = require('http');
const { spawn } = require('child_process');
const fs = require('fs');
const path = require('path');

async function run() {
    const port = 9535;
    const protoPath = 'file:///' + path.resolve('scratch/test_pinned_visimisi.html').replace(/\\/g, '/');
    const chrome = spawn('C:/Program Files/Google/Chrome/Application/chrome.exe', [
        '--remote-debugging-port=' + port,
        '--headless=new',
        '--window-size=1440,900',
        '--user-data-dir=C:/Users/Miez/AppData/Local/Temp/cdp_pinned_9535',
        protoPath
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

    ws.addEventListener('message', evt => {
        const msg = JSON.parse(evt.data);
        if (msg.method === 'Runtime.consoleAPICalled') {
            console.log('CONSOLE:', msg.params.type, msg.params.args.map(a => a.value));
        }
        if (msg.method === 'Runtime.exceptionThrown') {
            console.error('EXCEPTION:', msg.params.exceptionDetails);
        }
    });

    await send('Page.enable');
    await send('Runtime.enable');

    await send('Page.navigate', { url: protoPath });
    await new Promise(r => setTimeout(r, 1500));

    const artifactDir = 'C:/Users/Miez/.gemini/antigravity/brain/9ae60431-3fe3-45ec-9f5d-32eaac2d8f5a';

    // Helper to scroll to specific trigger progress
    async function snapAtProgress(progress, filename) {
        const info = await send('Runtime.evaluate', {
            expression: `
                (() => {
                    const all = ScrollTrigger.getAll();
                    if (!all.length) return { error: 'No triggers' };
                    const st = all[0];
                    const targetScroll = st.start + (st.end - st.start) * ${progress};
                    window.scrollTo(0, targetScroll);
                    ScrollTrigger.update();
                    const el = document.getElementById('visiMisiSection');
                    const r = el ? el.getBoundingClientRect() : null;
                    return {
                        start: st.start,
                        end: st.end,
                        targetScroll,
                        scrollY: window.scrollY,
                        elRect: r ? { top: r.top, left: r.left, width: r.width, height: r.height } : null,
                        dot1: document.getElementById('vmDot1')?.getAttribute('style'),
                        row1: document.getElementById('vmRow1')?.getAttribute('style')
                    };
                })()
            `,
            returnByValue: true
        });
        console.log('Scroll info at', progress, ':', JSON.stringify(info.result.value));
        await new Promise(r => setTimeout(r, 400));
        const shot = await send('Page.captureScreenshot', { format: 'png' });
        fs.writeFileSync(path.join(artifactDir, filename), Buffer.from(shot.data, 'base64'));
        console.log('Saved', filename, 'at progress', progress);
    }

    await snapAtProgress(0.1, 'pinned_step1_visi.png');
    await snapAtProgress(0.35, 'pinned_step2_misi1.png');
    await snapAtProgress(0.65, 'pinned_step3_fork.png');
    await snapAtProgress(1.0, 'pinned_step4_full.png');

    ws.close();
    chrome.kill();
    console.log('Done capturing pinned sequence snapshots!');
}

run();
