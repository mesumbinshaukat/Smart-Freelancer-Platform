// chat.js

import { db, storage, collection, addDoc, query, where, onSnapshot, orderBy, ref, uploadBytes, getDownloadURL } from './config.js';

let currentSenderId;
let currentReceiverId;

async function sendMessage() {
  const message = document.getElementById('chatMessage').value;
  const attachment = document.getElementById('chatAttachment').files[0];
  const senderId = currentSenderId;
  const receiverId = currentReceiverId;

  let attachmentURL = '';

  if (attachment) {
    const storageRef = ref(storage, `attachments/${attachment.name}`);
    const snapshot = await uploadBytes(storageRef, attachment);
    attachmentURL = await getDownloadURL(snapshot.ref);
  }

  const newMessage = {
    message,
    sender_id: senderId,
    receiver_id: receiverId,
    timestamp: new Date(),
    attachments: attachmentURL ? [attachmentURL] : []
  };

  await addDoc(collection(db, 'messages'), newMessage);

  $.ajax({
    url: 'log_message.php',
    method: 'POST',
    data: {
      message: newMessage.message,
      freelancer_id: receiverId,
      client_id: senderId,
      attachments: attachmentURL ? [attachmentURL] : []
    },
    success: function(response) {
      console.log('Message logged in MySQL');
    },
    error: function(err) {
      console.error('Error logging message:', err);
    }
  });

  document.getElementById('chatMessage').value = '';
  document.getElementById('chatAttachment').value = '';
}

function loadMessages(senderId, receiverId) {
  const senderQuery = query(collection(db, 'messages'), where('sender_id', '==', senderId), where('receiver_id', '==', receiverId), orderBy('timestamp'));
  const receiverQuery = query(collection(db, 'messages'), where('sender_id', '==', receiverId), where('receiver_id', '==', senderId), orderBy('timestamp'));

  const messages = [];

  onSnapshot(senderQuery, (querySnapshot) => {
    querySnapshot.forEach((doc) => {
      messages.push(doc.data());
    });
    displayMessages(messages);
  });

  onSnapshot(receiverQuery, (querySnapshot) => {
    querySnapshot.forEach((doc) => {
      messages.push(doc.data());
    });
    displayMessages(messages);
  });
}

function displayMessages(messages) {
  const chatBody = document.getElementById('chatBody');
  chatBody.innerHTML = '';

  messages.sort((a, b) => a.timestamp - b.timestamp);

  messages.forEach((msg) => {
    const messageElement = document.createElement('div');
    messageElement.classList.add('message');
    messageElement.innerHTML = `<p>${msg.message}</p>`;
    if (msg.attachments && msg.attachments.length > 0) {
      msg.attachments.forEach((url) => {
        const attachmentElement = document.createElement('a');
        attachmentElement.href = url;
        attachmentElement.target = '_blank';
        attachmentElement.textContent = 'Attachment';
        messageElement.appendChild(attachmentElement);
      });
    }
    chatBody.appendChild(messageElement);
  });
}

function initChat(creatorId, bidderId) {
  currentSenderId = userId;  // This is the logged-in user
  currentReceiverId = creatorId === userId ? bidderId : creatorId;

  loadMessages(currentSenderId, currentReceiverId);
}

window.initChat = initChat;
window.sendMessage = sendMessage;
