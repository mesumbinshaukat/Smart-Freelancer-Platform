document.addEventListener('DOMContentLoaded', (event) => {
    const floatingChatButton = document.getElementById('floatingChatButton');
    if (floatingChatButton) {
        floatingChatButton.addEventListener('click', function() {
            $('#userListModal').modal('show');
        });
    }
});

$(document).ready(function() {
    $('.send-button').click(function() {
        var contractorId = $(this).data('contractor-id');
        var chatForm = $('form[data-contractor-id="' + contractorId + '"]');
        var formData = new FormData(chatForm[0]);

        $.ajax({
            type: 'POST',
            url: 'send_messages.php',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response);
                try {
                    var data = typeof response === "string" ? JSON.parse(response) : response;
                    if (data.success) {
                        chatForm[0].reset();
                        fetchOldMessages(contractorId, chatForm.data('bid-id'));
                    } else {
                        toastr.error(data.message);
                    }
                } catch (err) {
                    console.log("Catch Error:" + err.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error S:" + textStatus + " | " + errorThrown);
            }
        });
    });

    function fetchOldMessages(contractorId, bidId) {
        $.ajax({
            type: 'GET',
            url: 'fetch_old_messages.php',
            data: {
                contractor_id: contractorId
            },
            success: function(response) {
                $('#chatBody_' + bidId).html(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error F:" + textStatus + " | " + errorThrown);
            }
        });
    }

    $('body').on('show.bs.modal', '.modal', function() {
        var contractorId = $(this).find('.send-button').data('contractor-id');
        var bidId = $(this).find('.send-button').data('bid-id');
        fetchOldMessages(contractorId, bidId);
        setInterval(function() {
            fetchOldMessages(contractorId, bidId);
        }, 500);
    });

    $('body').on('click', '.user-item', function() {
        var contractorId = $(this).data('contractor-id');
        var bidId = $(this).data('bid-id');
        var modalId = '#chatModal_' + bidId;
        $(modalId).modal('show');
        fetchOldMessages(contractorId, bidId);
    });

    function fetchUsers() {
        $.ajax({
            type: 'GET',
            url: 'fetch_users.php',
            success: function(response) {
                $("#userList").html(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error FU:" + textStatus + " | " + errorThrown);
            }
        });
    }

    fetchUsers();
});
