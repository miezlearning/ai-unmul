const { spawn } = require('child_process');
const http = require('http');
const fs = require('fs');

async function main() {
    const port = 9523;
    const chrome = spawn('C:/Program Files/Google/Chrome/Application/chrome.exe', [
        `--remote-debugging-port=${port}`,
        '--headless=new',
        '--window-size=1440,1100',
        '--user-data-dir=C:/Users/Miez/AppData/Local/Temp/cdp_prof_9523',
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

    const outDir = 'C:/Users/Miez/.gemini/antigravity/brain/9ae60431-3fe3-45ec-9f5d-32eaac2d8f5a';

    const snap = async (name) => {
        const shot = await send('Page.captureScreenshot', { format: 'png', captureBeyondViewport: false });
        fs.writeFileSync(`${outDir}/${name}.png`, Buffer.from(shot.data, 'base64'));
        console.log(`Saved ${name}.png`);
    };

    const setTheme = async (isDark) => {
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
    };

    // 1. Home hero lockup check
    console.log('Capturing Home Hero...');
    await send('Page.navigate', { url: 'http://localhost:8080/' });
    await new Promise(r => setTimeout(r, 1500));
    await setTheme(true);
    await snap('home_hero_dark');
    await setTheme(false);
    await snap('home_hero_light');

    // 2. Tentang - Top & Identity
    console.log('Capturing Tentang Top...');
    await send('Page.navigate', { url: 'http://localhost:8080/profil/tentang' });
    await new Promise(r => setTimeout(r, 1200));
    await setTheme(true);
    await snap('profile_tentang_dark_top');

    // 3. Tentang - Scrolled to Visi & Misi
    console.log('Capturing Tentang Visi & Misi...');
    await send('Runtime.evaluate', {
        expression: `
            const el = document.querySelector('section[aria-label="Visi dan Misi Organisasi"]') || document.querySelector('h2');
            if (el) el.scrollIntoView({ behavior: 'instant', block: 'start' });
            else window.scrollTo(0, 650);
        `
    });
    await new Promise(r => setTimeout(r, 600));
    await snap('profile_tentang_dark_visimisi');

    // Also in light mode
    await setTheme(false);
    await snap('profile_tentang_light_visimisi');

    // 4. Tentang - Scrolled to Logo & Tridatu
    console.log('Capturing Tentang Logo & Tridatu...');
    await setTheme(true);
    await send('Runtime.evaluate', {
        expression: `
            const el = document.querySelector('section[aria-label="Lambang dan Filosofi Identitas Visual"]');
            if (el) el.scrollIntoView({ behavior: 'instant', block: 'start' });
            else window.scrollTo(0, 1600);
        `
    });
    await new Promise(r => setTimeout(r, 600));
    await snap('profile_tentang_dark_logo');

    // 5. Kabinet
    console.log('Capturing Kabinet...');
    await send('Page.navigate', { url: 'http://localhost:8080/profil/kabinet' });
    await new Promise(r => setTimeout(r, 1200));
    await setTheme(true);
    await snap('profile_kabinet_dark');
    await send('Runtime.evaluate', { expression: `window.scrollTo(0, 750);` });
    await new Promise(r => setTimeout(r, 600));
    await snap('profile_kabinet_dark_dept');

    // 6. Dari Masa ke Masa
    console.log('Capturing Dari Masa ke Masa...');
    await send('Page.navigate', { url: 'http://localhost:8080/profil/dari-masa-ke-masa' });
    await new Promise(r => setTimeout(r, 1200));
    await setTheme(true);
    await snap('profile_masa_dark');

    ws.close();
    chrome.kill();
    console.log('All screenshots captured successfully!');
}

main().catch(console.error);
