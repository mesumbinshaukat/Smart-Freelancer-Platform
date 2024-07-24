<?php 
$loggedin_email = $_COOKIE['email'];
$query = "SELECT * FROM `tbl_user` WHERE `email` = '$loggedin_email'";
$runs = mysqli_query($con, $query);
$user_detail = mysqli_fetch_array($runs);

$projects_select = "
    SELECT p.id as project_id, p.project_title, p.u_id as creator_id, u.name as creator_name, b.id as bid_id, b.bid_letter, b.bid_date, b.bid_price, b.user_id as bidder_id, bu.name as bidder_name
    FROM tbl_projects p
    JOIN tbl_bids b ON p.id = b.project_id
    JOIN tbl_user u ON p.u_id = u.id
    JOIN tbl_user bu ON b.user_id = bu.id
    WHERE p.u_id = ?
";
$stmt1 = $con->prepare($projects_select);
$stmt1->bind_param("i", $user_detail['id']);
$stmt1->execute();
$result1 = $stmt1->get_result();

?>
<!DOCTYPE html>
<html lang="en">
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
                  <!-- <span class="badge bg-primary me-3">20</span> -->
                  <a class="d-block" onclick="showChatButton()" href="#">
                    <i class="fas fa-times text-muted fs-5"></i>
                  </a>
                </div>
              </div>
              <div class="card-body" style="position:relative; height: 450px; overflow-y: auto; overflow-x: hidden;">
                <div id="userMessages"></div>
              </div>
              <div class="card-footer text-muted d-flex justify-content-start align-items-center p-3">
                <img src="./chat/user.png" alt="avatar 3" style="width: 40px; height: 100%;">
                <input type="text" class="form-control form-control-lg" id="message" placeholder="Type message">
                <a class="ms-1 text-muted" href="#!"><i class="fas fa-paperclip"></i></a>
                <button type="button" onclick="sendMessage()" class="btn-submit">
                  <i class="fas fa-paper-plane"></i>
                </button>
              </div>
            </div>
          </div>

          <div class="collapse mt-4" id="UsersExample" style="display: none;">
            <div class="card" id="chat4">
              <div class="card-header d-flex justify-content-between align-items-center p-3" style="border-top: 4px solid #3B71CA;">
                
                <h5 class="mb-0">Chats</h5>
                <div class="d-flex flex-row align-items-center">
                  <a class="d-block" onclick="showChatButton()" href="#">
                    <i class="fas fa-times text-muted fs-5"></i>
                  </a>
                </div>
              </div>
              <div class="card-body" style="position:relative; height: 450px; overflow-y: auto; overflow-x: hidden;">
                <?php 
              if ($result1->num_rows > 0) {
                 while ($rows = $result1->fetch_assoc()) {
                ?>
                <div class="row user_list" onclick="showCollapseExample()">
                  <div class="d-flex justify-content-between align-items-center w-100">
                      <div class="d-flex align-items-center">
                          <img src="./chat/user.png" alt="avatar 3" style="width: 40px; height: 40px;">
                          <p class="px-3 mb-0"><?php echo $rows['bidder_name'];?></p>
                      </div>
                      <div class="d-flex align-items-center">
                          <!-- <span class="badge bg-primary mx-2">20</span> -->
                          <i class="fas fa-chevron-right"></i>
                      </div>
                  </div>
              </div>
              <?php }} else{
                echo "No users found";
              }
              
              ?>
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
  
  <script src="../assets/js/chat.js"></script>      
</body>
</html>
