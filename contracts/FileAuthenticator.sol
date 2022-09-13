// SPDX-License-Identifier: MIT
pragma solidity >=0.8 <0.9.0;

contract FileAuthenticator {

    struct Authenticator {
        string name;
        string email;
        address wallet;
    }

    struct Owner {
        string name;
        string surname;
        string legalId;
        uint startDate;
        uint endDate;
        Authenticator authenticator;
    }

    struct FileInfo {
        string name;
        uint size;
        Authenticator authenticator;
        uint createdAt;
        mapping(uint => Owner) currentOwners;
        uint numberCurrentOwners;
        mapping(uint => Owner) oldOwners;
        uint numberOldOwners;
    }

    mapping(string => FileInfo) files;

    function addFile(string memory _fileHash, string memory _fileName, uint256 _fileSize,  
                     string memory _authName, string memory _authEmail,
                     string memory _ownerName, string memory _ownerSurname, string memory _ownerLegalId) public {
        if (files[_fileHash].createdAt > 0) {
            revert("File already in blockchain");
        } else {
            uint currentTimestamp = block.timestamp;
            Authenticator memory authenticator = Authenticator(_authName, _authEmail, msg.sender);
            files[_fileHash].name = _fileName;
            files[_fileHash].size = _fileSize;
            files[_fileHash].authenticator = authenticator;
            files[_fileHash].createdAt = currentTimestamp;
            files[_fileHash].currentOwners[0] = Owner(_ownerName, _ownerSurname, _ownerLegalId, currentTimestamp, 0, authenticator);
            files[_fileHash].numberCurrentOwners = 1;
            files[_fileHash].numberOldOwners = 0;
        }
    }

    function getFile(string memory _fileHash) public view returns (string memory fileName, uint256 fileSize, 
                                                                   string memory authName, string memory authEmail, address authWallet,
                                                                   uint fileCreatedAt,
                                                                   uint numberCurrentOwners, uint numberOldOwners){
        if (files[_fileHash].createdAt > 0) {
            fileName = files[_fileHash].name;
            fileSize = files[_fileHash].size;
            authName = files[_fileHash].authenticator.name;
            authEmail = files[_fileHash].authenticator.email;
            authWallet = files[_fileHash].authenticator.wallet;
            fileCreatedAt = files[_fileHash].createdAt;
            numberCurrentOwners = files[_fileHash].numberCurrentOwners;
            numberOldOwners = files[_fileHash].numberOldOwners;
        } else {
            revert("File not found");
        }
    }

    function setCoOwner(string memory _fileHash, 
                        string memory _ownerName, string memory _ownerSurname, string memory _ownerLegalId, 
                        string memory _authName, string memory _authEmail) public {
        if (files[_fileHash].createdAt > 0) {
            uint currentTimestamp = block.timestamp;
            Authenticator memory authenticator = Authenticator(_authName, _authEmail, msg.sender);
            uint numberCurrentOwners = files[_fileHash].numberCurrentOwners;
            files[_fileHash].currentOwners[numberCurrentOwners] = Owner(_ownerName, _ownerSurname, _ownerLegalId, currentTimestamp, 0, authenticator);
            files[_fileHash].numberCurrentOwners = numberCurrentOwners + 1;
        } else {
            revert("File not found");
        }
    }

    function setNewOwner(string memory _fileHash, 
                        string memory _ownerName, string memory _ownerSurname, string memory _ownerLegalId, 
                        string memory _authName, string memory _authEmail) public {
        if (files[_fileHash].createdAt > 0) {
            uint currentTimestamp = block.timestamp;
            Authenticator memory authenticator = Authenticator(_authName, _authEmail, msg.sender);
            uint numberCurrentOwners = files[_fileHash].numberCurrentOwners;
            uint numberOldOwners = files[_fileHash].numberOldOwners;
            for (uint i = 0; i < numberCurrentOwners; i++) {
                uint j = numberOldOwners+i;
                files[_fileHash].oldOwners[j] = files[_fileHash].currentOwners[i];
                files[_fileHash].oldOwners[j].endDate = currentTimestamp;
                files[_fileHash].oldOwners[j].authenticator = authenticator;
                delete files[_fileHash].currentOwners[i];
            }
            files[_fileHash].currentOwners[0] = Owner(_ownerName, _ownerSurname, _ownerLegalId, currentTimestamp, 0, authenticator);
            files[_fileHash].numberCurrentOwners = 1;
            files[_fileHash].numberOldOwners = numberOldOwners + numberCurrentOwners;
        } else {
            revert("File not found");
        }
    }

    function getAllCurrentOwners(string memory _fileHash) private view returns (Owner[] memory){
        uint numberOwners = files[_fileHash].numberCurrentOwners;
        Owner[] memory owners = new Owner[](numberOwners);
        for(uint i = 0; i < numberOwners; i++) {
            owners[i] = files[_fileHash].currentOwners[i];
        }
        return (owners);
    }

    function getCurrentOwners(string memory _fileHash, uint _ownerNumber) public view returns (string memory name, string memory surname, string memory legalId,
                                                                                                uint startDate, uint endDate,
                                                                                                string memory authName, string memory authEmail, address authWallet){
        if (files[_fileHash].createdAt > 0) {
            name = files[_fileHash].currentOwners[_ownerNumber].name;
            surname = files[_fileHash].currentOwners[_ownerNumber].surname;
            legalId = files[_fileHash].currentOwners[_ownerNumber].legalId;
            startDate = files[_fileHash].currentOwners[_ownerNumber].startDate;
            endDate = files[_fileHash].currentOwners[_ownerNumber].endDate;
            authName = files[_fileHash].currentOwners[_ownerNumber].authenticator.name;
            authEmail = files[_fileHash].currentOwners[_ownerNumber].authenticator.email;
            authWallet = files[_fileHash].currentOwners[_ownerNumber].authenticator.wallet;
        } else {
            revert("File not found");
        }
    }

    function getOldOwners(string memory _fileHash, uint _ownerNumber) public view returns (string memory name, string memory surname, string memory legalId,
                                                                                                uint startDate, uint endDate,
                                                                                                string memory authName, string memory authEmail, address authWallet){
        if (files[_fileHash].createdAt > 0) {
            name = files[_fileHash].oldOwners[_ownerNumber].name;
            surname = files[_fileHash].oldOwners[_ownerNumber].surname;
            legalId = files[_fileHash].oldOwners[_ownerNumber].legalId;
            startDate = files[_fileHash].oldOwners[_ownerNumber].startDate;
            endDate = files[_fileHash].oldOwners[_ownerNumber].endDate;
            authName = files[_fileHash].oldOwners[_ownerNumber].authenticator.name;
            authEmail = files[_fileHash].oldOwners[_ownerNumber].authenticator.email;
            authWallet = files[_fileHash].oldOwners[_ownerNumber].authenticator.wallet;
        } else {
            revert("File not found");
        }
    }

    function getAllOldOwners(string memory _fileHash) private view returns (Owner[] memory){
        uint numberOwners = files[_fileHash].numberOldOwners;
        Owner[] memory owners = new Owner[](numberOwners);
        for(uint i = 0; i < numberOwners; i++) {
            owners[i] = files[_fileHash].oldOwners[i];
        }
        return (owners);
    }
}
