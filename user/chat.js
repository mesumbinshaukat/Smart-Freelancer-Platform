// import { db, storage, collection, addDoc, query, where, onSnapshot, orderBy, ref, uploadBytes, getDownloadURL, getDocs } from './config.js';


// let currentSenderId;
// let currentReceiverId;
// let messagesLoaded = false;
// let messagesListener;

// async function sendMessage() {
//     const message = document.getElementById('chatMessage').value;
//     const attachment = document.getElementById('chatAttachment').files[0];
//     const senderId = currentSenderId;
//     const receiverId = currentReceiverId;

//     let attachmentURL = '';

//     if (attachment) {
//         const storageRef = ref(storage, `attachments/${attachment.name}`);
//         const snapshot = await uploadBytes(storageRef, attachment);
//         attachmentURL = await getDownloadURL(snapshot.ref);
//     }

//     const newMessage = {
//         message,
//         sender_id: senderId,
//         receiver_id: receiverId,
//         timestamp: new Date(),
//         attachments: attachmentURL ? [attachmentURL] : []
//     };

//     await addDoc(collection(db, 'messages'), newMessage);

//     $.ajax({
//         url: 'log_messages.php',
//         method: 'POST',
//         data: {
//             message: newMessage.message,
//             freelancer_id: senderId,
//             client_id: receiverId,
//             attachments: attachmentURL ? [attachmentURL] : []
//         },
//         success: function(response) {
//             console.log('Message logged in MySQL');
//         },
//         error: function(err) {
//             console.error('Error logging message:', err);
//         }
//     });

//     document.getElementById('chatMessage').value = '';
//     document.getElementById('chatAttachment').value = '';
// }

// function loadMessages(senderId, receiverId) {
//     if (messagesLoaded) return;

//     const senderQuery = query(collection(db, 'messages'), where('sender_id', '==', senderId), where('receiver_id', '==', receiverId), orderBy('timestamp'));
//     const receiverQuery = query(collection(db, 'messages'), where('sender_id', '==', receiverId), where('receiver_id', '==', senderId), orderBy('timestamp'));

//     const messages = [];

//     messagesListener = onSnapshot(senderQuery, (querySnapshot) => {
//         querySnapshot.forEach((doc) => {
//             messages.push(doc.data());
//         });
//         displayMessages(messages);
//     });

//     messagesListener = onSnapshot(receiverQuery, (querySnapshot) => {
//         querySnapshot.forEach((doc) => {
//             messages.push(doc.data());
//         });
//         displayMessages(messages);
//     });

//     messagesLoaded = true;
// }

// function displayMessages(messages) {
//     const chatBody = document.getElementById('chatBody');
//     chatBody.innerHTML = '';

//     messages.sort((a, b) => a.timestamp - b.timestamp);

//     messages.forEach((msg) => {
//         const messageElement = document.createElement('div');
//         messageElement.classList.add('message');
//         messageElement.innerHTML = `<p><strong>${msg.sender_name}:</strong> ${msg.message}</p>`;
//         if (msg.attachments && msg.attachments.length > 0) {
//             msg.attachments.forEach((url) => {
//                 const attachmentElement = document.createElement('a');
//                 attachmentElement.href = url;
//                 attachmentElement.target = '_blank';
//                 attachmentElement.textContent = 'Attachment';
//                 messageElement.appendChild(attachmentElement);
//             });
//         }
//         chatBody.appendChild(messageElement);
//     });
// }

// async function fetchUserInteractions(userId) {
//     const usersQuery = query(collection(db, 'messages'), where('sender_id', '==', userId));
//     const querySnapshot = await getDocs(usersQuery);

//     const userSet = new Set();

//     querySnapshot.forEach((doc) => {
//         const message = doc.data();
//         if (message.receiver_id !== userId) {
//             userSet.add(message.receiver_id);
//         } else {
//             userSet.add(message.sender_id);
//         }
//     });

//     const userListDiv = document.getElementById('userList');
//     userListDiv.innerHTML = '';
    
//     userSet.forEach(user => {
//         const userElement = document.createElement('div');
//         userElement.textContent = `User ID: ${user}`;
//         userElement.addEventListener('click', () => {
//             currentReceiverId = user;
//             loadMessages(currentSenderId, currentReceiverId);
//             $('#userListModal').modal('hide');
//             $('#chatModal').modal('show');
//         });
//         userListDiv.appendChild(userElement);
//     });
// }

// function initChat(senderId, receiverId) {
//     currentSenderId = senderId;
//     currentReceiverId = receiverId;
//     loadMessages(senderId, receiverId);
// }

// function initUserList() {
//     fetchUserInteractions(currentSenderId);
// }

// document.addEventListener('DOMContentLoaded', function() {
//     const sendButton = document.getElementById('sendButton');
//     if (sendButton) {
//         sendButton.addEventListener('click', sendMessage);
//     }

//     const userListModal = document.getElementById('userListModal');
//     if (userListModal) {
//         userListModal.addEventListener('show.bs.modal', initUserList);
//     }

//     const chatModal = document.getElementById('chatModal');
//     if (chatModal) {
//         chatModal.addEventListener('show.bs.modal', () => {
//             loadMessages(currentSenderId, currentReceiverId);
//         });
//     }
// });

// export { initChat, initUserList, sendMessage, loadMessages };



// APPWRITE
// import { databases, storage, Query } from './config.js';

// let currentSenderId;
// let currentReceiverId;
// let messagesLoaded = false;
// let messagesListener;

// async function sendMessage() {
//     const message = document.getElementById('chatMessage').value;
//     const attachment = document.getElementById('chatAttachment').files[0];
//     const senderId = currentSenderId;
//     const receiverId = currentReceiverId;

//     let attachmentURL = '';

//     if (attachment) {
//         const file = new File([attachment], attachment.name, { type: attachment.type });
//         const response = await storage.createFile('66a31d9c000cdbc060d5', 'unique()', file);
//         attachmentURL = storage.getFileView(response['$id']);
//     }

//     const newMessage = {
//         message,
//         sender_id: senderId,
//         receiver_id: receiverId,
//         timestamp: new Date().toISOString(),
//         attachments: attachmentURL ? [attachmentURL] : []
//     };

//     await databases.createDocument('66a3113700066de4ba03', '66a3115a0032675f11eb', newMessage);

//     document.getElementById('chatMessage').value = '';
//     document.getElementById('chatAttachment').value = '';
// }

// function loadMessages(senderId, receiverId) {
//     if (messagesLoaded) return;

//     const senderQuery = databases.listDocuments('66a3113700066de4ba03', '66a3115a0032675f11eb', [
//         Query.equal('sender_id', senderId),
//         Query.equal('receiver_id', receiverId),
//         Query.orderAsc('timestamp')
//     ]);

//     const receiverQuery = databases.listDocuments('66a3113700066de4ba03', '66a3115a0032675f11eb', [
//         Query.equal('sender_id', receiverId),
//         Query.equal('receiver_id', senderId),
//         Query.orderAsc('timestamp')
//     ]);

//     Promise.all([senderQuery, receiverQuery]).then(([senderMessages, receiverMessages]) => {
//         const messages = [...senderMessages.documents, ...receiverMessages.documents];
//         displayMessages(messages);
//     });

//     messagesLoaded = true;
// }

// function displayMessages(messages) {
//     const chatBody = document.getElementById('chatBody');
//     chatBody.innerHTML = '';

//     messages.sort((a, b) => new Date(a.timestamp) - new Date(b.timestamp));

//     messages.forEach((msg) => {
//         const messageElement = document.createElement('div');
//         messageElement.classList.add('message');
//         messageElement.innerHTML = `<p><strong>${msg.sender_name}:</strong> ${msg.message}</p>`;
//         if (msg.attachments && msg.attachments.length > 0) {
//             msg.attachments.forEach((url) => {
//                 const attachmentElement = document.createElement('a');
//                 attachmentElement.href = url;
//                 attachmentElement.target = '_blank';
//                 attachmentElement.textContent = 'Attachment';
//                 messageElement.appendChild(attachmentElement);
//             });
//         }
//         chatBody.appendChild(messageElement);
//     });
// }

// async function fetchUserInteractions(userId) {
//     const usersQuery = databases.listDocuments('66a3113700066de4ba03', '66a3115a0032675f11eb', [
//         Query.equal('sender_id', userId)
//     ]);

//     const querySnapshot = await usersQuery;

//     const userSet = new Set();

//     querySnapshot.documents.forEach((doc) => {
//         const message = doc;
//         if (message.receiver_id !== userId) {
//             userSet.add(message.receiver_id);
//         } else {
//             userSet.add(message.sender_id);
//         }
//     });

//     const userListDiv = document.getElementById('userList');
//     userListDiv.innerHTML = '';

//     userSet.forEach(user => {
//         const userElement = document.createElement('div');
//         userElement.textContent = `User ID: ${user}`;
//         userElement.addEventListener('click', () => {
//             currentReceiverId = user;
//             loadMessages(currentSenderId, currentReceiverId);
//             $('#userListModal').modal('hide');
//             $('#chatModal').modal('show');
//         });
//         userListDiv.appendChild(userElement);
//     });
// }

// function initChat(senderId, receiverId) {
//     currentSenderId = senderId;
//     currentReceiverId = receiverId;
//     loadMessages(senderId, receiverId);
// }

// function initUserList() {
//     fetchUserInteractions(currentSenderId);
// }

// document.addEventListener('DOMContentLoaded', function() {
//     const sendButton = document.getElementById('sendButton');
//     if (sendButton) {
//         sendButton.addEventListener('click', sendMessage);
//     }

//     const userListModal = document.getElementById('userListModal');
//     if (userListModal) {
//         userListModal.addEventListener('show.bs.modal', initUserList);
//     }

//     const chatModal = document.getElementById('chatModal');
//     if (chatModal) {
//         chatModal.addEventListener('show.bs.modal', () => {
//             loadMessages(currentSenderId, currentReceiverId);
//         });
//     }
// });

// export { initChat, initUserList, sendMessage, loadMessages };
