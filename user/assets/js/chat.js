function showChatButton() {
    document.getElementById('collapseExample').style.display = 'none';
    document.getElementById('UsersExample').style.display = 'none';
    document.getElementById('chatButton').style.display = 'flex';
  }

  function showUsersExample() {
    var collapseExample = document.getElementById('collapseExample');
  var usersExample = document.getElementById('UsersExample');
  var chatButton = document.getElementById('chatButton');

  // Toggle collapseExample and UsersExample
  collapseExample.style.display = collapseExample.style.display === 'block' ? 'none' : 'none';
  usersExample.style.display = usersExample.style.display === 'none' ? 'block' : 'none';

  // Toggle chatButton visibility
  chatButton.style.display = chatButton.style.display === 'none' ? 'block' : 'none';
  }

  function showCollapseExample() {
    document.getElementById('collapseExample').style.display = 'block';
    document.getElementById('UsersExample').style.display = 'none';
    document.getElementById('chatButton').style.display = 'none';
  }

  var myName = "rafay";
  var ReceiverName = "sad";

  function sendMessage() {
    var message = document.getElementById("message").value;
    const timestamp = firebase.database.ServerValue.TIMESTAMP;

    firebase.database().ref("messages").push().set({
      "sender": myName,
      "message": message,
      "receiver": ReceiverName,
      "timestamp": timestamp
    });

    return false;
  }

  firebase.database().ref("messages")
    .orderByChild("timestamp")
    .limitToLast(10)
    .on("child_added", function (snapshot) {
      const messageData = snapshot.val();
      const messageKey = snapshot.key;

      const isSender = messageData.sender === myName && messageData.receiver === ReceiverName;
      const isReceiver = messageData.sender === ReceiverName && messageData.receiver === myName;

      if (isSender || isReceiver) {
        const displayName = isSender ? myName : ReceiverName;
        const messageHTML = isSender ? `
          <div class="d-flex flex-row justify-content-end mb-4" id="message-${messageKey}">
            <div>
              <p class="small p-2 me-3 mb-1 text-white rounded-3 bg-info">${displayName}: ${messageData.message} &nbsp; <i class="fas fa-trash text-white" data-id="${messageKey}" onclick="deleteMessage(this)"></i></p>     
            </div>
            <img src="ava2-bg.webp" alt="avatar 1" style="width: 45px; height: 100%;">
          </div>
        ` : `
          <div class="d-flex flex-row justify-content-start mb-4" id="message-${messageKey}">
            <img src="ava5-bg.webp" alt="avatar 1" style="width: 45px; height: 100%;">
            <div>
              <p class="small p-2 ms-3 mb-1 rounded-3 bg-body-tertiary">${displayName}: ${messageData.message}</p>
            </div>
          </div>
        `;

        const container = document.getElementById("userMessages");
        container.insertAdjacentHTML('beforeend', messageHTML);
      }
    });

  function deleteMessage(self) {
    var messageId = self.getAttribute("data-id");
    firebase.database().ref("messages").child(messageId).remove();
  }

  firebase.database().ref("messages").on("child_removed", function (snapshot) {
    const messageId = snapshot.key;
    const messageElement = document.getElementById(`message-${messageId}`);

    if (messageElement) {
      messageElement.remove();
    }
  });