// Importation des modules requis
const WebSocket = require('ws');
const mysql = require('mysql');
const session = require('express-session');
const express = require('express');
const app = express();

// Configure les paramètres de connexion à la bd
const db = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: 'karim34500',
  database: 'coolchat'
});

// Connecte l'application à la bd
db.connect((err) => {
  if (err) {
    console.error('Erreur de connexion à la base de données :', err);
  } else {
    console.log('Connecté à la base de données');
  }
});

// Configuration de session
const sessionMiddleware = session({
  secret: 'clef-secrete-bien-securise',
  resave: false,
  saveUninitialized: true
});

// Utilise le middleware de session dans l'application Express
app.use(sessionMiddleware);

// Crée un serveur WebSocket à partir de l'application Express
const server = app.listen(9999, () => {
  console.log('Server started on port 9999');
});

const wss = new WebSocket.Server({ server });

const clients = [];

// Middleware pour gérer les connexions WebSocket
wss.on('connection', function connection(ws, req) {
  // Utilise le middleware de session pour accéder aux données de session
  sessionMiddleware(req, {}, () => {
    // Récupère le nom d'utilisateur depuis la session
    const user = req.session.user && req.session.user.Pseudo ? req.session.user.Pseudo : 'utilisateur inconnu';

    clients.push(ws);

    // Reçoit les messages du client WebSocket
    ws.on('message', function incoming(message) {
      console.log('received: %s', message);

      // Ajoute le nom d'utilisateur au message
      const messageWithUsername = `${user}: ${message}`;

      // Requête SQL pour insérer le message dans la bd
      const sql = 'INSERT INTO messages (username, message) VALUES (?, ?)';
      db.query(sql, [user, message], (err, result) => {
        if (err) {
          console.error('Erreur lors de l\'enregistrement du message :', err);
        } else {
          console.log('Message enregistré dans la base de données');
        }

        // Stocke le message côté client
        clientMessages.push(messageWithUsername);
        localStorage.setItem('clientMessages', JSON.stringify(clientMessages));

        // Diffuse le message à tous les connectés
        broadcast(messageWithUsername);
      });
    });

    // Envoie un message de bienvenue au client WebSocket
    ws.send('Bienvenue sur CoolChat, ' + user + '!');

    // Récupère les messages stockés dans le stockage local
    const storedMessages = localStorage.getItem('clientMessages');
    if (storedMessages) {
      const parsedMessages = JSON.parse(storedMessages);
      parsedMessages.forEach((message) => {
        ws.send(message);
      });
    }
  });
});

// Diffuse un message à tous les clients connectés
function broadcast(message) {
  clients.forEach(function(client) {
    client.send(message);
  });
}
