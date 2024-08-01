// SPDX-License-Identifier: MIT

pragma solidity ^0.8.15;

contract ProjectAward {
    address public owner;
    address public escrowWallet = /* YOUR_ESCROW_WALLET */; // Default escrow wallet
    uint public serviceFee = 10; // 10% service fee

    struct Project {
        uint id;
        address creator;
        address contractor;
        uint amount;
        bool isCompleted;
        bool isAwarded;
    }

    mapping(uint => Project) public projects;

    event ProjectAwarded(uint projectId, address contractor, uint amount);
    event ProjectCompleted(uint projectId, address contractor, uint amount);
    event FeeDeducted(uint projectId, uint fee, uint netAmount);

    modifier onlyOwner() {
        require(msg.sender == owner, "Not the contract owner");
        _;
    }

    modifier onlyCreator(uint projectId) {
        require(
            msg.sender == projects[projectId].creator,
            "Not the project creator"
        );
        _;
    }

    constructor() {
        owner = msg.sender;
    }

    function awardProject(uint projectId, address contractor) external payable {
        require(msg.value > 0, "No ETH sent");

        Project storage project = projects[projectId];
        require(!project.isAwarded, "Project already awarded");

        project.id = projectId;
        project.creator = msg.sender;
        project.contractor = contractor;
        project.amount = msg.value;
        project.isCompleted = false;
        project.isAwarded = true;

        payable(escrowWallet).transfer(msg.value);

        emit ProjectAwarded(projectId, contractor, msg.value);
    }

    function completeProject(uint projectId) external onlyCreator(projectId) {
        Project storage project = projects[projectId];
        require(project.isAwarded, "Project not awarded");
        require(!project.isCompleted, "Project already completed");

        uint amount = project.amount;
        uint fee = (amount * serviceFee) / 100;
        uint payment = amount - fee;

        payable(owner).transfer(fee); // Transfer fee to the owner
        payable(project.contractor).transfer(payment); // Transfer payment to the contractor

        project.isCompleted = true;

        emit ProjectCompleted(projectId, project.contractor, payment);
        emit FeeDeducted(projectId, fee, payment);
    }

    function updateServiceFee(uint newFee) external onlyOwner {
        serviceFee = newFee;
    }
}
