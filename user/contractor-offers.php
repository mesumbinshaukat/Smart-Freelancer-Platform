<?php
session_start();
include("../connection/connection.php");

require __DIR__ . '/partials/fetch_user_details.php';

$user_details = get_user_info($_COOKIE["email"], $con);

if (!isset($_COOKIE["email"]) || empty($_COOKIE["email"]) || !isset($_COOKIE["user_logged_in_bool"]) || empty($_COOKIE["user_logged_in_bool"])) {
    $_SESSION["error"] = "Please login first";
    header("location:../login.php");
    exit();
}

$projects_query = "
    SELECT p.id as project_id, p.project_title, p.u_id as creator_id, u.name as creator_name, b.id as bid_id, b.bid_letter, b.bid_date, b.bid_price, b.user_id as bidder_id, bu.name as bidder_name
    FROM tbl_projects p
    JOIN tbl_bids b ON p.id = b.project_id
    JOIN tbl_user u ON p.u_id = u.id
    JOIN tbl_user bu ON b.user_id = bu.id
    WHERE p.u_id = ?
";
$stmt = $con->prepare($projects_query);
$stmt->bind_param("i", $user_details['id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!doctype html>
<html lang="en" class="semi-dark">

<head>
    <?php include "./partials/head.php" ?>
    <style>
        .chat_button{

    border: 1px solid transparent;
    border-radius: 15px;
        }
    </style>
    <link rel="stylesheet" href="./assets/css/chat.css">
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet" />
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
    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        <?php include("./partials/sidebar.php"); ?>
        <!--end sidebar wrapper -->
        <!--start header -->
        <header>
            <?php include("./partials/navbar.php"); ?>
        </header>
        <!--end header -->
        <!--start page wrapper -->
        <div class="container-fluid py-2">
    <div class="row">
        <div class="col-lg-9 col-md-9"></div>
        <div class="col-lg-3 col-md-3 col-sm-12">
            <div class="collapse mt-4" id="collapseExample" style="display: none;">
                <!-- Chat Card Content -->
                <div class="card" id="chat4">
                    <div class="card-header d-flex justify-content-between align-items-center p-3" style="border-top: 4px solid #3B71CA;">
                        <a class="d-block" onclick="showChatButton()" href="#">
                            <i class="fa fa-arrow-left fs-5"></i>
                        </a>
                        <h5 class="mb-0">Usernames</h5>
                        <div class="d-flex flex-row align-items-center">
                            <a class="d-block" onclick="showChatButton()" href="#">
                                <i class="fas fa-times text-muted fs-5"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body" style="position:relative; height: 450px; overflow-y: auto; overflow-x: hidden;">
                        <div id="userMessages"></div>
                    </div>
                    <div class="card-footer text-muted d-flex justify-content-start align-items-center p-3">
                        <img src="./chat/ava5-bg.webp" alt="avatar 3" style="width: 40px; height: 100%;">
                        <input type="text" class="form-control form-control-lg" id="message" placeholder="Type message">
                        <a class="ms-1 text-muted" href="#!"><i class="fas fa-paperclip"></i></a>
                        <button type="button" onclick="sendMessage()" class="btn-submit">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="chat_button" id="chatButton" onclick="showCollapseExample()">
                <i class="fas fa-comment fs-4"></i>
            </div>
        </div>
    </div>
</div>

        <div class="page-wrapper">
            <div class="page-content">
            <div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example2" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>#</th>
										<th>Contractor Name</th>
										<th>Proposal</th>
										<th>Offer Date</th>
										<th>Offer Price</th>
										<th>Project Name</th>
                                        <th>Project Created By</th>
                                        <th>Action</th>
									</tr>
								</thead>
								<tbody>

                                <?php
                                    if ($result->num_rows > 0) {
                                        $counter = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $counter++ . "</td>";
                                            echo "<td>" . htmlspecialchars($row['bidder_name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['bid_letter']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['bid_date']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['bid_price']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['project_title']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['creator_name']) . "</td>";
                                            echo "<td><div class='d-flex justify-content-center chat_button' id='chatButton'>
                                            <a class='d-block' onclick='showCollapseExample()' href='#'>
                                              <i class='fas fa-comment fs-4'></i>
                                            </a>
                                            
                                            </div>
                                            <span class='badge bg-primary mx-2'>20</span>
                                          </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='8' class='text-center'>No offers found</td></tr>";
                                    }
                                    ?>


                                <!--  -->
								</tbody>
								<tfoot>
									<tr>
                                    <th>#</th>
										<th>Contractor Name</th>
										<th>Proposal</th>
										<th>Offer Date</th>
										<th>Offer Price</th>
										<th>Project Name</th>
                                        <th>Project Created By</th>
                                        <th>Action</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
            </div>
        </div>

      
  <script>
      function showChatButton() {
    document.getElementById('collapseExample').style.display = 'none';
    document.getElementById('chatButton').style.display = 'flex';
}

      function showCollapseExample() {
    document.getElementById('collapseExample').style.display = 'block';
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
              <img src="./chat/ava5-bg.webp" alt="avatar 1" style="width: 45px; height: 100%;">
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
        <!--end switcher-->
        <?php include "./partials/last_code.php"; ?>
        <?php
        if (isset($_SESSION["success"])) {
            echo "<script>toastr.success('" . $_SESSION["success"] . "');</script>";
            unset($_SESSION["success"]);
        }

        if (isset($_SESSION["error"])) {
            echo "<script>toastr.error('" . $_SESSION["error"] . "');</script>";
            unset($_SESSION["error"]);
        }
        ?>
</body>

</html>