// import { getMessaging } from 'firebase/messaging/sw'
// import { onBackgroundMessage } from 'firebase/messaging/sw'

// const messaging = getMessaging()
// onBackgroundMessage(messaging, (payload) => {
//   console.log('[firebase-messaging-sw.js] Received background message ', payload)
//   // Customize notification here
//   const notificationTitle = 'Background Message Title'
//   const notificationOptions = {
//     body: 'Background Message body.',
//     icon: '/firebase-logo.png',
//   }

//   self.registration.showNotification(notificationTitle, notificationOptions)
// })


// Import Firebase scripts
importScripts('https://www.gstatic.com/firebasejs/10.1.0/firebase-app-compat.js')
importScripts('https://www.gstatic.com/firebasejs/10.1.0/firebase-messaging-compat.js')


// Initialize Firebase with your configuration
const firebaseConfig = {
  apiKey: 'AIzaSyCmW3Jh_AkTt2peUfZEO-kvR7BT2cOtBPY',
  authDomain: 'mkt-oasis.firebaseapp.com',
  projectId: 'mkt-oasis',
  storageBucket: 'mkt-oasis.appspot.com',
  messagingSenderId: '834050578891',
  appId: '1:834050578891:web:a9e546e2ff706c5574be4e',
};

// Initialize Firebase in the service worker
firebase.initializeApp(firebaseConfig);

// Retrieve Firebase Messaging object
const messaging = firebase.messaging();

// Handle background messages
messaging.onBackgroundMessage((payload) => {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);

  // Customize notification here
  const notificationTitle = payload.notification.title || 'Default Title';
  const notificationOptions = {
    body: payload.notification.body || 'Default body.',
    icon: payload.notification.icon || '/firebase-logo.png',
    click_action: payload.notification.click_action || '/',
  };


  self.registration.showNotification(notificationTitle, notificationOptions);
});