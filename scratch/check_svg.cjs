const http = require('http');
const { spawn } = require('child_process');

async function check() {
    const port = 9528;
    const chrome = spawn('C:/Program Files/Google/Chrome/Application/chrome.exe', [
        '--remote-debugging-port=' + port,
        '--headless=new',
        '--user-data-dir=C:/Users/Miez/AppData/Local/Temp/cdp_chk_9528',
        'http://localhost:8080/profil/dari-masa-ke-masa'
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
    await send('Runtime.enable');
    await new Promise(r => setTimeout(r, 1500));
    const paths = await send('Runtime.evaluate', {
        expression: `
            JSON.stringify({
                main: document.getElementById("pathMainTrunk").getAttribute("d"),
                gov: document.getElementById("pathGovernanceBranch").getAttribute("d"),
                node1: document.getElementById("node-anchor-1").getBoundingClientRect(),
                node2: document.getElementById("node-anchor-2").getBoundingClientRect(),
                node3: document.getElementById("node-anchor-3").getBoundingClientRect(),
                svgRect: document.getElementById("gitTreeCanvas").getBoundingClientRect(),
                contRect: document.getElementById("gitTimelineContainer").getBoundingClientRect()
            })
        `
    });
    console.log('SVG DATA:', paths.result.value);
    ws.close();
    chrome.kill();
}
check();

