const WebSocket = require('ws');
const wss = new WebSocket.Server({ port: 8080 });  // Hier kannst du den Port anpassen

wss.on('connection', function connection(ws) {
    console.log('Client connected');

    // Nachricht vom Client empfangen
    ws.on('message', function incoming(message) {
        console.log('received: %s', message);

        // Sende die Nachricht an alle Clients
        wss.clients.forEach(function each(client) {
            if (client.readyState === WebSocket.OPEN) {
                client.send(message);
            }
        });
    });

    // Eine Begrüßungsnachricht an den Client senden
    ws.send('Welcome to the WebSocket server!');
});

console.log('WebSocket server started on ws://localhost:8080');