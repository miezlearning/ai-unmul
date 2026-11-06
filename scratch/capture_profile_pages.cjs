const { spawn } = require('child_process');
const http = require('http');
const fs = require('fs');

async function main() {
    const port = 9522;
    const chrome = spawn('C:/Program Files/Google/Chrome/Application/chrome.exe', [
        `--remote-debugging-port=${port}`,
        '--headless=new',
        '--window-size=1440,1100',
        '--user-data-dir=C:/Users/Miez/AppData/Local/Temp/cdp_prof_9522',
        'about:blank'
    ]);

    let wsUrl = null;
    for (let i = 0; i < 30; i++) {
        await new Promise(r => setTimeout(r, 200));
        try {
            const list = await new Promise((res, rej) => {
                http.get(`http://127.0.0.1:${port}/json`, r => {
                    let d = ''; r.on('data', c => d += c); r.on('end', () => res(JSON.parse(d)));
                }).on('error', rej);
            });
            const p = list.find(x => x.type === 'page');
            if (p) {
                wsUrl = p.webSocketDebuggerUrl;
                break;
            }
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
        const currentId = id++;
        const handler = evt => {
            const msg = JSON.parse(evt.data);
            if (msg.id === currentId) {
                ws.removeEventListener('message', handler);
                r(msg.result);
            }
        };
        ws.addEventListener('message', handler);
        ws.send(JSON.stringify({ id: currentId, method, params }));
    });

    await send('Page.enable');
    await send('Runtime.enable');

    const tasks = [
        { name: 'profile_tentang_dark', url: 'http://localhost:8080/profil/tentang?theme=dark' },
        { name: 'profile_tentang_light', url: 'http://localhost:8080/profil/tentang?theme=light' },
        { name: 'profile_kabinet_dark', url: 'http://localhost:8080/profil/kabinet?theme=dark' },
        { name: 'profile_kabinet_light', url: 'http://localhost:8080/profil/kabinet?theme=light' },
        { name: 'profile_masa_dark', url: 'http://localhost:8080/profil/dari-masa-ke-masa?theme=dark' },
        { name: 'profile_masa_light', url: 'http://localhost:8080/profil/dari-masa-ke-masa?theme=light' },
    ];

    const outDir = 'C:/Users/Miez/.gemini/antigravity/brain/9ae60431-3fe3-45ec-9f5d-32eaac2d8f5a';

    for (const t of tasks) {
        console.log(`Navigating to ${t.url}...`);
        await send('Page.navigate', { url: t.url });
        await new Promise(r => setTimeout(r, 1200));

        const isDark = t.url.includes('theme=dark');
        await send('Runtime.evaluate', {
            expression: `
                document.documentElement.setAttribute('data-theme', '${isDark ? 'ai_unmul_dark' : 'ai_unmul_light'}');
                if (${isDark}) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            `
        });
        await new Promise(r => setTimeout(r, 400));

        const shot = await send('Page.captureScreenshot', { 
            format: 'png',
            captureBeyondViewport: false
        });
        fs.writeFileSync(`${outDir}/${t.name}.png`, Buffer.from(shot.data, 'base64'));
        console.log(`Saved ${t.name}.png`);
    }

    // Scroll snapshots for Tentang (Anatomi Logo & Tridatu)
    await send('Page.navigate', { url: 'http://localhost:8080/profil/tentang?theme=dark' });
    await new Promise(r => setTimeout(r, 800));
    await send('Runtime.evaluate', {
        expression: `
            document.documentElement.setAttribute('data-theme', 'ai_unmul_dark');
            document.documentElement.classList.add('dark');
            window.scrollTo(0, 850);
        `
    });
    await new Promise(r => setTimeout(r, 600));
    const shotTentangScroll = await send('Page.captureScreenshot', { format: 'png' });
    fs.writeFileSync(`${outDir}/profile_tentang_logo_section.png`, Buffer.from(shotTentangScroll.data, 'base64'));
    console.log('Saved profile_tentang_logo_section.png');

    // Scroll snapshots for Kabinet Departemen
    await send('Page.navigate', { url: 'http://localhost:8080/profil/kabinet?theme=dark' });
    await new Promise(r => setTimeout(r, 800));
    await send('Runtime.evaluate', {
        expression: `
            document.documentElement.setAttribute('data-theme', 'ai_unmul_dark');
            document.documentElement.classList.add('dark');
            window.scrollTo(0, 950);
        `
    });
    await new Promise(r => setTimeout(r, 600));
    const shotKabinetScroll = await send('Page.captureScreenshot', { format: 'png' });
    fs.writeFileSync(`${outDir}/profile_kabinet_dept_section.png`, Buffer.from(shotKabinetScroll.data, 'base64'));
    console.log('Saved profile_kabinet_dept_section.png');

    ws.close();
    chrome.kill();
    console.log('Done capturing all screenshots!');
}

main().catch(console.error);
