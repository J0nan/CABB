<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BlockchainFileAuth;
use App\Models\File;
use App\Models\User;
use App\Models\Owner;
use App\Models\FileOwner;
use App\Http\Controllers\Controller;
use App\Http\Resources\FileResource;
use App\Http\Resources\UserFilesResource;
use App\Http\Resources\FileUploadedSuccessfulResource;
use App\Validators\FileValidator;
use Auth;

class FileController extends Controller
{
    public function uploadFile(Request $request)
    {
        $payload = $request->all();
        $validator = FileValidator::store($payload);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $fileName = $payload['file']->getClientOriginalName();
        $fileSize = $payload['file']->getSize();
        $fileHash = hash_file('sha256',$payload['file']);

        $user = Auth::user();

        $response = BlockChainFileAuth::uploadFile($fileHash, $fileName, $fileSize, $user, $payload['name'], $payload['surname'], $payload['legalId']);

        if (!$response[0]){
            $owner = Owner::createIfNotExists($payload['name'], $payload['surname'], $payload['legalId']);

            $file = File::create([
                'hash'      => $fileHash,
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'user_id'   => $user->id
            ]);

            $fileOwner = FileOwner::create([
                'file_id'       => $file->id,
                'owner_id'      => $payload['legalId'],
                'added_by_user' => $user->id,
            ]);
            
            return new FileUploadedSuccessfulResource($file);
        } else {
            return response()->json(['error'=>$response[1]], 500);
        }
    }

    public function estimateGasUpload(Request $request)
    {
        $payload = $request->all();
        $validator = FileValidator::store($payload);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $fileName = $payload['file']->getClientOriginalName();
        $fileSize = $payload['file']->getSize();
        $fileHash = hash_file('sha256',$payload['file']);

        $user = Auth::user();

        $response = BlockChainFileAuth::estimateGasUpload($fileHash, $fileName, $fileSize, $user, $payload['name'], $payload['surname'], $payload['legalId']);

        if (!$response[0]){
            return response()->json(['gas'=>$response[1]], 200);
        } else {
            return response()->json(['error'=>$response[1]], 500);
        }
    }
    
    
    public function verifyFile(Request $request)
    {
        $payload = $request->all();
        
        $validator = FileValidator::verify($payload);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $fileHash = hash_file('sha256',$payload['file']);
        
        $fileInformation = BlockChainFileAuth::verifyFile($fileHash);
        
        if (!$fileInformation[0]){
            return response()->json(['fileInfo'=>$fileInformation[1]], 200);
        } else {
            return response()->json(['error'=>$fileInformation[1]], 500);
        }
    }
    
    public function getCurrentOwners(Request $request)
    {
        $payload = $request->all();
        
        $fileHash = strtolower($payload['fileHash']);
        $numberOwners = $payload['numberOwners'];
        
        $owners = [];
        
        for ($i=0; $i < $numberOwners; $i++) { 
            $ownerInformation = BlockChainFileAuth::getCurrentOwners($fileHash, $i);
            
            if (!$ownerInformation[0] && $ownerInformation[1]['startDate']->toString()) {
                array_push($owners, $ownerInformation[1]);
            } else {
                break;
            }
        }
        
        if (!$ownerInformation[0]){
            return response()->json(['currentOwners'=>$owners], 200);
        } else {
            return response()->json(['error'=>$ownerInformation[1]], 500);
        }
    }
    
    public function getOldOwners(Request $request)
    {
        $payload = $request->all();
        
        $fileHash = strtolower($payload['fileHash']);
        $numberOwners = $payload['numberOwners'];
        
        $owners = [];
        
        for ($i=0; $i < $numberOwners; $i++) { 
            $ownerInformation = BlockChainFileAuth::getOldOwners($fileHash, $i);
            
            if (!$ownerInformation[0] && $ownerInformation[1]['startDate']->toString()) {
                array_push($owners, $ownerInformation[1]);
            } else {
                break;
            }
        }
        
        if (!$ownerInformation[0]){
            return response()->json(['oldOwners'=>$owners], 200);
        } else {
            return response()->json(['error'=>$ownerInformation[1]], 500);
        }
    }
    
    public function setCoOwner(Request $request)
    {
        $payload = $request->all();
        $validator = FileValidator::store($payload);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $fileHash = hash_file('sha256',$payload['file']);
        
        $user = Auth::user();
        
        $response = BlockChainFileAuth::setCoOwner($fileHash, $user, $payload['name'], $payload['surname'], $payload['legalId']);
        
        if (!$response[0]){
            $owner = Owner::createIfNotExists($payload['name'], $payload['surname'], $payload['legalId']);
            
            $file = File::where(['hash' => $fileHash])->first();
            
            $fileOwner = FileOwner::create([
                'file_id'       => $file->id,
                'owner_id'      => $payload['legalId'],
                'added_by_user' => $user->id,
            ]);
            
            return new FileUploadedSuccessfulResource($file);
        } else {
            return response()->json(['error'=>$response[1]], 500);
        }
    }
    
    public function setNewOwner(Request $request)
    {
        $payload = $request->all();
        $validator = FileValidator::store($payload);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $fileHash = hash_file('sha256',$payload['file']);
        
        $user = Auth::user();
        
        $response = BlockChainFileAuth::setNewOwner($fileHash, $user, $payload['name'], $payload['surname'], $payload['legalId']);
        
        if (!$response[0]){
            $owner = Owner::createIfNotExists($payload['name'], $payload['surname'], $payload['legalId']);
            
            $file = File::where(['hash' => $fileHash])->first();
            
            $file->fileOwner->map(function ($fileOwner) {$fileOwner->removeOwner();});
            
            $fileOwner = FileOwner::create([
                'file_id'       => $file->id,
                'owner_id'      => $payload['legalId'],
                'added_by_user' => $user->id,
            ]);
            
            return new FileUploadedSuccessfulResource($file);
        } else {
            return response()->json(['error'=>$response[1]], 500);
        }
    }
    
    public function uploadFileSuccessful(File $file)
    {
    
        return view('uploadFileSuccess', ['file' => $file]);
    }
    
    public function getFile(File $file)
    {
        return new FileResource($file);
    }

    public function getUserFiles()
    {
        $user = Auth::user();

        return new UserFilesResource($user);
    }
}
