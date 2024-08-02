<?php

function chatModal($bid_id, $bidder_id, $client_id)
{
    echo '
    <div class="modal fade" id="chatModal_' . $bid_id . '" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title" id="chatModalLabel">Live Chat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container chat-container">
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <div class="card chat-card">
                                    <div class="card-body bg-dark" id="chatBody_' . $bid_id . '">
                                    </div>
                                    <div class="card-footer bg-dark">
                                        <form class="chat-form" data-bid-id="' . $bid_id . '" data-contractor-id="' . $bidder_id . '">
                                            <input type="hidden" name="client_id" value="' . $client_id . '">
                                            <input type="hidden" name="contractor_id" value="' . $bidder_id . '">
                                            <div class="form-outline chat-textarea">
                                                <textarea class="form-control bg-body-tertiary" name="chatMessage" rows="3" placeholder="Type your message"></textarea>
                                                <label class="form-label">Type your message</label>
                                            </div>
                                            <div class="d-flex justify-content-end mt-2">
                                                <input type="file" class="form-control" name="chatAttachment">
                                            </div>
                                            <div class="d-flex justify-content-end mt-2">
                                                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary send-button" data-bid-id="' . $bid_id . '" data-contractor-id="' . $bidder_id . '">Send</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>';
}