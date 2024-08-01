<!-- Bootstrap JS -->
<script src="assets/js/bootstrap.bundle.min.js"></script>
<!--plugins-->
<!-- <script src="assets/js/jquery.min.js"></script> -->
<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
<script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
<script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="assets/plugins/chartjs/js/chart.js"></script>
<script src="assets/plugins/sparkline-charts/jquery.sparkline.min.js"></script>
<script src="assets/plugins/jquery.easy-pie-chart/jquery.easypiechart.min.js"></script>
<script src="assets/plugins/jquery-knob/excanvas.js"></script>
<script src="assets/plugins/jquery-knob/jquery.knob.js"></script>
<script src="assets/plugins/Drag-And-Drop/dist/imageuploadify.min.js"></script>
<script src="../node_modules/web3/dist/web3.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->

<link rel="stylesheet" href="https://cdn.datatables.net/2.1.2/css/dataTables.dataTables.css" />

<script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>

<script>
$(function() {
    $(".knob").knob();
});
</script>
<script src="assets/js/index.js"></script>
<!--app JS-->
<script src="assets/js/app.js"></script>
<script>
new PerfectScrollbar(".app-container")
</script>

<script>
$(document).ready(function() {
    $('#image-uploadify').imageuploadify();
});
</script>

<!-- Add this script to your common JavaScript file or directly in the common layout file -->
<!-- <script>
    document.getElementById('floatingChatButton').addEventListener('click', function() {
        // Fetch past chats for the logged-in user
        loadPastChats(userId);
        // Show the chat modal
        var chatModal = new bootstrap.Modal(document.getElementById('chatModal'));
        chatModal.show();
    });

    async function loadPastChats(userId) {
        const q = query(
            collection(db, 'messages'),
            where('sender_id', '==', userId),
            orderBy('timestamp', 'asc')
        );

        onSnapshot(q, (querySnapshot) => {
            const messages = [];
            querySnapshot.forEach((doc) => {
                messages.push(doc.data());
            });
            displayMessages(messages);
        });
    }

    function displayMessages(messages) {
        const chatBody = document.getElementById('chatBody');
        chatBody.innerHTML = '';

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
</script> -->

<script>
let table = new DataTable('#contractor-offers', {

    reponsive: true,
    scrollY: true,
    scroller: {
        displayBuffer: 20
    },
    ordering: true,
    lengthChange: true,
    columnDefs: [{
        responsivePriority: 1,
        targets: 0
    }, ],

});
</script>