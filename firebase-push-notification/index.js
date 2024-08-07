// index.js

const express = require('express');
const admin = require('firebase-admin');

// Initialize Firebase Admin SDK
const serviceAccount = require('./mkt-oasis-firebase-adminsdk-xuci5-f42393ae75.json'); // Download this file from Firebase Console

admin.initializeApp({
  credential: admin.credential.cert(serviceAccount),
});

const app = express();
const port = 3000;

app.use(express.json()); // For parsing application/json

// Endpoint to send push notifications
app.get('/api/push', (req, res) => {
  const { token, title, body } = req.query;

//   if (!token || !title || !body) {
//     return res.status(400).json({ error: 'Token, title, and body are required.' });
//   }

  const message = {
    notification: {
      title: 'asdads',
      body: 'asdad',
    },
    token: 'cWtDq6d36X2XMlLZ2guaAS:APA91bEYMaGSa59OA4pjvJQsFbAKl6Bc8_bwSgwDvGge0uwq7sOgS7J1_t792Jp9vNf6EX8fPlObGB9OKkOkUBRNglNwzU0xUNMjPG6jIgRadPeS_bBulyGYNugVFgCJDkLfPYTC44YX',
  };

  admin.messaging().send(message)
    .then((response) => {
      res.status(200).json({ success: 'Message sent successfully', response });
    })
    .catch((error) => {
      res.status(500).json({ error: 'Error sending message', details: error });
    });
});

app.listen(port, () => {
  console.log(`Server is running on http://localhost:${port}`);
});
