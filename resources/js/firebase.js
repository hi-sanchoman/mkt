
import { initializeApp } from "firebase/app";
import { getMessaging, getToken, onMessage } from "firebase/messaging";

// Initialize the Firebase app in the service worker by passing in
// your app's Firebase config object.
// https://firebase.google.com/docs/web/setup#config-object
const firebaseApp = initializeApp({
    apiKey: 'AIzaSyCmW3Jh_AkTt2peUfZEO-kvR7BT2cOtBPY',
    authDomain: 'mkt-oasis.firebaseapp.com',
    projectId: 'mkt-oasis',
    storageBucket: 'mkt-oasis.appspot.com',
    messagingSenderId: '834050578891',
    appId: '1:834050578891:web:a9e546e2ff706c5574be4e',
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = getMessaging(firebaseApp);

export { messaging, getToken, onMessage };