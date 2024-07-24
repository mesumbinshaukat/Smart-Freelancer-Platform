// config.js

import { initializeApp } from "https://www.gstatic.com/firebasejs/9.1.1/firebase-app.js";
import { getFirestore, collection, addDoc, query, where, onSnapshot, orderBy } from "https://www.gstatic.com/firebasejs/9.1.1/firebase-firestore.js";
import { getStorage, ref, uploadBytes, getDownloadURL } from "https://www.gstatic.com/firebasejs/9.1.1/firebase-storage.js";

const firebaseConfig = {
  apiKey: "AIzaSyDkHTx2y0099kf9li1teVjyviqLjRzYOpw",
  authDomain: "smart-contractor-b265c.firebaseapp.com",
  projectId: "smart-contractor-b265c",
  storageBucket: "smart-contractor-b265c.appspot.com",
  messagingSenderId: "218149105779",
  appId: "1:218149105779:web:495f0ec9beedb455f87a06"
};

const app = initializeApp(firebaseConfig);
const db = getFirestore(app);
const storage = getStorage(app);

export { db, storage, collection, addDoc, query, where, onSnapshot, orderBy, ref, uploadBytes, getDownloadURL };
