<!DOCTYPE html>
<html lang="en">
<head>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css">
  <script src="https://www.gstatic.com/firebasejs/6.6.1/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/6.6.1/firebase-database.js"></script>
  <script>
    // Your web app's Firebase configuration
    const firebaseConfig = {
      apiKey: "AIzaSyANlOE7pgmTKDaBcvbrydbaA3UN4xVl49U",
      authDomain: "chatsystem-2bd16.firebaseapp.com",
      databaseURL: "https://chatsystem-2bd16-default-rtdb.firebaseio.com",
      projectId: "chatsystem-2bd16",
      storageBucket: "chatsystem-2bd16.appspot.com",
      messagingSenderId: "522712873535",
      appId: "1:522712873535:web:c835c34d4114acac22568a"
    };

    // Initialize Firebase
    const app = firebase.initializeApp(firebaseConfig);
  </script>
</head>
<body>
  <section>
    <div class="container-fluid py-2">
      <div class="row">
        <div class="col-lg-9 col-md-9"></div>
        <div class="col-lg-3 col-md-3 col-sm-12">

          <!-- Collapsed content -->
          <div class="collapse mt-4" id="collapseExample" style="display: none;">
            <div class="card" id="chat4">
              <div class="card-header d-flex justify-content-between align-items-center p-3" style="border-top: 4px solid #3B71CA;">
                <a class="d-block" onclick="showUsersExample()" href="#">
                  <i class="fa fa-arrow-left fs-5"></i>
                </a>
                <h5 class="mb-0">Usernames</h5>
                <div class="d-flex flex-row align-items-center">
                  <span class="badge bg-primary me-3">20</span>
                  <a class="d-block" onclick="showChatButton()" href="#">
                    <i class="fas fa-times text-muted fs-5"></i>
                  </a>
                </div>
              </div>
              <div class="card-body" style="position:relative; height: 420px; overflow-y: auto; overflow-x: hidden;">
                <div id="userMessages"></div>
              </div>
              <div class="card-footer text-muted d-flex justify-content-start align-items-center p-3">
                <img src="ava5-bg.webp" alt="avatar 3" style="width: 40px; height: 100%;">
                <input type="text" class="form-control form-control-lg" id="message" placeholder="Type message">
                <a class="ms-1 text-muted" href="#!"><i class="fas fa-paperclip"></i></a>
                <a class="ms-3 text-muted" href="#!"><i class="fas fa-smile"></i></a>
                <button type="button" onclick="sendMessage()" class="btn-submit">
                  <i class="fas fa-paper-plane"></i>
                </button>
              </div>
            </div>
          </div>

          <div class="collapse mt-4" id="UsersExample" style="display: none;">
            <div class="card" id="chat4">
              <div class="card-header d-flex justify-content-between align-items-center p-3" style="border-top: 4px solid #3B71CA;">
                
                <h5 class="mb-0">Users</h5>
                <div class="d-flex flex-row align-items-center">
                  <a class="d-block" onclick="showChatButton()" href="#">
                    <i class="fas fa-times text-muted fs-5"></i>
                  </a>
                </div>
              </div>
              <div class="card-body" style="position:relative; height: 500px; overflow-y: auto; overflow-x: hidden;">
                <div class="row user_list" onclick="showCollapseExample()">
                  <div class="d-flex justify-content-between align-items-center w-100">
                      <div class="d-flex align-items-center">
                          <img src="ava5-bg.webp" alt="avatar 3" style="width: 40px; height: 40px;">
                          <p class="px-3 mb-0">hello</p>
                      </div>
                      <div class="d-flex align-items-center">
                          <span class="badge bg-primary mx-2">20</span>
                          <i class="fas fa-chevron-right"></i>
                      </div>
                  </div>
              </div>
              </div>
            </div>
          </div>

          <!-- Button to trigger UsersExample -->
          <div class="d-flex justify-content-center chat_button" id="chatButton">
            <a class="d-block" onclick="showUsersExample()" href="#">
              <i class="fas fa-comment fs-4"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script>
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
  </script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
</body>
</html>
