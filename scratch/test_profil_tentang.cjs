const puppeteer = require('puppeteer');
const path = require('path');

(async () => {
    let browser;
    try {
        browser = await puppeteer.launch({
            headless: 'new',
            args: ['--no-sandbox', '--disable-setuid-sandbox']
        });
        const page = await browser.newPage();
        await page.setViewport({ width: 1280, height: 900 });

        console.log('Navigating to http://localhost:8080/profil/tentang ...');
        await page.goto('http://localhost:8080/profil/tentang', { waitUntil: 'networkidle2' });

        // Set to Dark Mode
        await page.evaluate(() => {
            document.documentElement.setAttribute('data-theme', 'ai_unmul_dark');
            document.documentElement.classList.add('dark');
            if (typeof updateVisiMisiSpine === 'function') {
                updateVisiMisiSpine();
            }
        });

        // Wait a bit
        await new Promise(r => setTimeout(r, 600));

        // Scroll to the Visi & Misi section
        await page.evaluate(() => {
            const el = document.getElementById('visiMisiSection');
            if (el) {
                el.scrollIntoView({ behavior: 'instant', block: 'start' });
            }
            if (typeof updateVisiMisiSpine === 'function') {
                updateVisiMisiSpine();
            }
        });

        await new Promise(r => setTimeout(r, 500));

        // Capture Dark Mode
        const artifactDir = 'C:\\Users\\Miez\\.gemini\\antigravity\\brain\\9ae60431-3fe3-45ec-9f5d-32eaac2d8f5a';
        const darkPath = path.join(artifactDir, 'profil_tentang_visimisi_dark.png');
        const stageEl = await page.$('#visiMisiSection');
        if (stageEl) {
            await stageEl.screenshot({ path: darkPath });
            console.log('Dark screenshot saved to', darkPath);
        } else {
            await page.screenshot({ path: darkPath });
            console.log('Full dark screenshot saved to', darkPath);
        }

        // Switch to Light Mode
        await page.evaluate(() => {
            document.documentElement.setAttribute('data-theme', 'ai_unmul');
            document.documentElement.classList.remove('dark');
            if (typeof updateVisiMisiSpine === 'function') {
                updateVisiMisiSpine();
            }
        });

        await new Promise(r => setTimeout(r, 500));

        const lightPath = path.join(artifactDir, 'profil_tentang_visimisi_light.png');
        if (stageEl) {
            await stageEl.screenshot({ path: lightPath });
            console.log('Light screenshot saved to', lightPath);
        }

    } catch (err) {
        console.error('Error during screenshot capture:', err);
    } finally {
        if (browser) await browser.close();
    }
})();

