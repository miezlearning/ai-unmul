const http = require('http');

http.get('http://localhost:8080/profil/tentang', res => {
    let data = '';
    res.on('data', chunk => data += chunk);
    res.on('end', () => {
        console.log('Status:', res.statusCode);
        console.log('Includes updateVisiMisiSpine?', data.includes('updateVisiMisiSpine'));
        console.log('Total length:', data.length);
        const idx = data.indexOf('updateVisiMisiSpine');
        if (idx !== -1) {
            console.log('Snippet:', data.substring(idx - 100, idx + 150));
        } else {
            console.log('Last 500 chars of HTML:', data.substring(data.length - 500));
        }
    });
}).on('error', err => console.error(err));

