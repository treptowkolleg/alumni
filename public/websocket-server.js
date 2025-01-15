const WebSocket = require('ws');
const http = require('http');

// Erstelle einen HTTP-Server, um WebSocket zu unterstützen
const server = http.createServer((req, res) => {
    res.writeHead(200, { 'Content-Type': 'text/plain' });
    res.end('Hello World\n');
});

// Erstelle einen WebSocket-Server
const wss = new WebSocket.Server({ server });

wss.on('connection', ws => {
    ws.on('message', message => {
        console.log(`received: ${message}`);
    });

    ws.send('Hallo, WebSocket-Verbindung erfolgreich!');
});

// Der Server hört auf Port 3000
server.listen(3000, () => {
    console.log('Server läuft auf http://localhost:3000');
});