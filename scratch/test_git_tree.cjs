const http = require('http');
const { spawn } = require('child_process');
const fs = require('fs');

async function run() {
    const port = 9526;
    const chrome = spawn('C:/Program Files/Google/Chrome/Application/chrome.exe', [
        '--remote-debugging-port=' + port,
        '--headless=new',
        '--window-size=1440,1100',
        '--user-data-dir=C:/Users/Miez/AppData/Local/Temp/cdp_gittree_9526',
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

    const outDir = 'C:/Users/Miez/.gemini/antigravity/brain/9ae60431-3fe3-45ec-9f5d-32eaac2d8f5a';

    const snap = async (name) => {
        const shot = await send('Page.captureScreenshot', { format: 'png' });
        fs.writeFileSync(`${outDir}/${name}.png`, Buffer.from(shot.data, 'base64'));
        console.log(`Saved ${name}.png`);
    };

    console.log('Navigating to Dari Masa ke Masa...');
    await send('Page.navigate', { url: 'http://localhost:8080/profil/dari-masa-ke-masa' });
    await new Promise(r => setTimeout(r, 1200));

    // Dark mode
    await send('Runtime.evaluate', {
        expression: `
            document.documentElement.setAttribute('data-theme', 'ai_unmul_dark');
            document.documentElement.classList.add('dark');
            renderGitTree();
        `
    });
    await new Promise(r => setTimeout(r, 500));
    await snap('git_tree_overview_dark');

    // Click Diff Tab on Era 1
    console.log('Switching to Diff Tab on Era 1...');
    await send('Runtime.evaluate', {
        expression: `switchCardTab('era-1', 'diff');`
    });
    await new Promise(r => setTimeout(r, 400));
    await snap('git_tree_diff_tab_dark');

    // Scroll down to Era 2 and Era 3
    console.log('Scrolling to Era 2 & Era 3...');
    await send('Runtime.evaluate', {
        expression: `window.scrollTo({ top: 600, behavior: 'instant' });`
    });
    await new Promise(r => setTimeout(r, 600));
    await snap('git_tree_scrolled_dark');

    // Scroll to bottom (Era 3 HEAD)
    console.log('Scrolling to Era 3 HEAD...');
    await send('Runtime.evaluate', {
        expression: `window.scrollTo({ top: 1100, behavior: 'instant' });`
    });
    await new Promise(r => setTimeout(r, 600));
    await snap('git_tree_era3_head_dark');

    // Light mode
    console.log('Capturing Light Mode...');
    await send('Runtime.evaluate', {
        expression: `
            document.documentElement.setAttribute('data-theme', 'ai_unmul');
            document.documentElement.classList.remove('dark');
            window.scrollTo({ top: 0, behavior: 'instant' });
        `
    });
    await new Promise(r => setTimeout(r, 500));
    await snap('git_tree_overview_light');

    ws.close();
    chrome.kill();
    console.log('All git tree screenshots captured!');
}
run().catch(console.error);

