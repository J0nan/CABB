<?php

namespace App\Services;

use Web3\Contract;
use Web3\Web3;
use Web3\Utils;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use App\Models\User;
use App\Models\Owner;

class BlockchainFileAuth
{
    public static function uploadFile(string $fileHash, string $fileName, int $fileSize, User $user, string $ownerName, string $ownerSurname, string $ownerId)
    {
        $contractAddress = config('blockchain.contract_address');
        $contract = new Contract(config('blockchain.eth_network'), config('blockchain.abi'));
        $returnData = false;
        $error = false;

        $contract->at($contractAddress)->send('addFile', $fileHash, $fileName, $fileSize, $user->name, $user->email, $ownerName, $ownerSurname, $ownerId, ['from' => $user->wallet, 'gas' => 2000000], function ($err, $result) use (&$returnData, &$error){
            if ($err !== null) {
                $error = true;
                $returnData = $err->getMessage();
            } 
            if ($result){
                $returnData = $result;
            }
        });

        if (!$error) {
            return BlockchainFileAuth::getStatus($contract->getEth(), $returnData, "File already on the Blockchain");
        }
        return array($error, $returnData);
    }

    public static function estimateGasUpload(string $fileHash, string $fileName, int $fileSize, User $user, string $ownerName, string $ownerSurname, string $ownerId)
    {
        $contractAddress = config('blockchain.contract_address');
        $contract = new Contract(config('blockchain.eth_network'), config('blockchain.abi'));
        $returnData = false;
        $error = false;

        $contract->at($contractAddress)->estimateGas('addFile', $fileHash, $fileName, $fileSize, $user->name, $user->email, $ownerName, $ownerSurname, $ownerId, ['from' => $user->wallet], function ($err, $result) use (&$returnData, &$error){
            if ($err !== null) {
                $error = true;
                $returnData = $err->getMessage();
            } 
            if ($result){
                $returnData = $result;
            }
        });
        return array($error, $returnData);
    }

    public static function setCoOwner(string $fileHash, User $user, string $ownerName, string $ownerSurname, string $ownerId)
    {
        $contractAddress = config('blockchain.contract_address');
        $contract = new Contract(config('blockchain.eth_network'), config('blockchain.abi'));
        $returnData = false;
        $error = false;

        $contract->at($contractAddress)->send('setCoOwner', $fileHash, $ownerName, $ownerSurname, $ownerId, $user->name, $user->email, ['from' => $user->wallet, 'gas' => 2000000], function ($err, $result) use (&$returnData, &$error){
            if ($err !== null) {
                $error = true;
                $returnData = $err->getMessage();
            } 
            if ($result){
                $returnData = $result;
            }
        });

        if (!$error) {
            return BlockchainFileAuth::getStatus($contract->getEth(), $returnData, "File not on the Blockchain");
        }
        return array($error, $returnData);
    }
    
    public static function setNewOwner(string $fileHash, User $user, string $ownerName, string $ownerSurname, string $ownerId)
    {
        $contractAddress = config('blockchain.contract_address');
        $contract = new Contract(config('blockchain.eth_network'), config('blockchain.abi'));
        $returnData = false;
        $error = false;

        $contract->at($contractAddress)->send('setNewOwner', $fileHash, $ownerName, $ownerSurname, $ownerId, $user->name, $user->email, ['from' => $user->wallet, 'gas' => 2000000], function ($err, $result) use (&$returnData, &$error){
            if ($err !== null) {
                $error = true;
                $returnData = $err->getMessage();
            } 
            if ($result){
                $returnData = $result;
            }
        });

        if (!$error) {
            return BlockchainFileAuth::getStatus($contract->getEth(), $returnData, "File not on the Blockchain");
        }
        return array($error, $returnData);
    }

    public static function verifyFile($fileHash)
    {
        $returnData = false;
        $error = false;

        $contractAddress = config('blockchain.contract_address');
        $contract = new Contract(config('blockchain.eth_network'), config('blockchain.abi'));
        $returnData = false;

        $contract->at($contractAddress)->call('getFile', $fileHash, function ($err, $result) use (&$returnData, &$error, $fileHash){
            if ($err !== null) {
                $error = true;
                $returnData = $err->getMessage();
            } 
            if ($result){
                $returnData = array($result, ["fileHash" => $fileHash]);
            }
        });

        return array($error, $returnData);
    }

    public static function getCurrentOwners($fileHash, $ownerNumber)
    {
        $returnData = false;
        $error = false;

        $contractAddress = config('blockchain.contract_address');
        $contract = new Contract(config('blockchain.eth_network'), config('blockchain.abi'));
        $returnData = false;

        $contract->at($contractAddress)->call('getCurrentOwners', $fileHash, $ownerNumber, function ($err, $result) use (&$returnData, &$error){
            if ($err !== null) {
                $error = true;
                $returnData = $err->getMessage();
            } 
            if ($result){
                $returnData = $result;
            }
        });

        return array($error, $returnData);
    }

    public static function getOldOwners($fileHash, $ownerNumber)
    {
        $returnData = false;
        $error = false;

        $contractAddress = config('blockchain.contract_address');
        $contract = new Contract(config('blockchain.eth_network'), config('blockchain.abi'));
        $returnData = false;

        $contract->at($contractAddress)->call('getOldOwners', $fileHash, $ownerNumber, function ($err, $result) use (&$returnData, &$error){
            if ($err !== null) {
                $error = true;
                $returnData = $err->getMessage();
            } 
            if ($result){
                $returnData = $result;
            }
        });

        return array($error, $returnData);
    }

    private static function getStatus($eth, $transactionHash, $errorMessage) {
        $returnData = false;
        $error = false;

        $eth->getTransactionReceipt($transactionHash, function ($err, $result) use (&$returnData, &$error, $errorMessage)
        {
            if ($err !== null) {
                $error = true;
                $returnData = $err->getMessage();
            } 
            if ($result){
                if (Utils::jsonToArray($result)['status'] === '0x1') {
                    $error = false;
                } else {
                    $error = true;
                    $returnData = $errorMessage;
                }
            }
        });
        return array($error, $returnData);
    }
}