// SPDX-License-Identifier: MIT

pragma solidity ^0.8.15;

contract ProjectAward {
    address public owner;
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
        require(!projects[projectId].isAwarded, "Project already awarded");
        require(msg.value > 0, "No ETH sent");
        projects[projectId] = Project({
            id: projectId,
            creator: msg.sender,
            contractor: contractor,
            amount: msg.value,
            isCompleted: false,
            isAwarded: true
        });

        emit ProjectAwarded(projectId, contractor, msg.value);
    }

    function completeProject(uint projectId) external onlyCreator(projectId) {
        require(projects[projectId].isAwarded, "Project not awarded");
        require(!projects[projectId].isCompleted, "Project already completed");

        uint amount = projects[projectId].amount;
        uint fee = (amount * serviceFee) / 100;
        uint payment = amount - fee;

        payable(owner).transfer(fee);
        payable(projects[projectId].contractor).transfer(payment);

        projects[projectId].isCompleted = true;

        emit ProjectCompleted(
            projectId,
            projects[projectId].contractor,
            payment
        );
    }

    function updateServiceFee(uint newFee) external onlyOwner {
        serviceFee = newFee;
    }
}
