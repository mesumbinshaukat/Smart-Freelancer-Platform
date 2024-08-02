<!-- Add this to a common file included on every page, e.g., partials/footer.php -->
<div id="floatingChatButton" class="floating-chat-button">
    <i class='bx bx-message-dots'></i>
</div>

<div class="modal fade" id="userListModal" tabindex="-1" aria-labelledby="userListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userListModalLabel">Previous Interactions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="container user-list-container">
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <div class="card user-list-card">
                                <div
                                    class="card-header user-list-header d-flex justify-content-between align-items-center">
                                    <i class="fas fa-angle-left"></i>
                                    <p class="mb-0 fw-bold">Users</p>
                                    <i class="fas fa-times"></i>
                                </div>
                                <div class="card-body">
                                    <div id="userList" class="d-flex flex-column">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php # include "./chat_modal.php"; 
?>

<!--end page wrapper -->
<!--start overlay-->
<div class="overlay toggle-icon"></div>
<!--end overlay-->
<!--Start Back To Top Button-->
<a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
<!--End Back To Top Button-->
<footer class="page-footer">
    <p class="mb-0">Copyright © 2024. All right reserved.</p>
</footer>
</div>
<!--end wrapper-->


<?php include "./partials/script.php" ?>

<script>
$(document).ready(function() {
    $('#menu').metisMenu();
});
</script>

<script src="chat.js"></script>