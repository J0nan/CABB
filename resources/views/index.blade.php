@extends('layouts.app')

@section('page-content')
        <div>
            <h1>CERTIFICATES OF AUTHENTICITY BASED ON BLOCKCHAIN</h1>
        </div>
        <div style="margin-left: 1em; margin-top: 2em">
            <p>
                <b>CERTIFICATES OF AUTHENTICITY BASED ON BLOCKCHAIN (CABB)</b> seeks a decentralized system to improve security, transparency and global acceptance of digital certificates of authenticity. For this, a smart contract was developed, and deployed in Ethereum. This smart contract allows users to store information to determine if the file has been modified and extra information to facilitate verification of the authenticity of the art. A REST API server and web page also where developed, in order to facilitate its use to the greatest number of users.
            </p>
            <p>
                The developed system is composed of two large blocks, the Blockchain network, in this case Ethereum, and Docker, where there are three containers, a proxy server, the web server and API and the database.
            </p>
            <div class="d-flex justify-content-center" style="margin-top: 2em">
                <a type="button" class="btn btn-primary btn-hover btn-lg" style="min-width: 150px; margin-right: 30px" href={{ route('verifyFile') }}>Start Verifying Files</a>
                <a type="button" class="btn btn-primary btn-hover btn-lg" style="min-width: 150px" href={{ route('uploadFile') }}>Start Uploading Files</a>
            </div>
        </div>
@endsection