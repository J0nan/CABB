@extends('layouts.app')

@push('scripts')
    <script src={{ asset('js/verifyFile.js') }}></script>
@endpush

@section('page-content')
    <div>
      <h1>Verify File</h1>
    </div>

    <div id="fileVerification" class="container-sm" style="max-width: 40em; margin-top: 5%">
      <form id="fileUploadForm" onsubmit="return submitForm(event)">
          <fieldset>
            <legend>Verify a file from the Blockchain</legend>
            <div class="form-group" style="margin-top: 2em">
              <input class="form-control" type="file" id="file">
              <div id="fileErrorInfo" class="invalid-feedback" style="z-index: 11; position: fixed">One file must be selected</div>
            </div>
            <div class="d-flex justify-content-center" style="margin-top: 2em">
              <button type="submit" class="btn btn-primary btn-hover" id="uploadFileButton" style="min-width: 150px">Submit</button>
            </div>
            <div id="serverError" class="alert alert-dismissible alert-danger" style="margin-top: 2em; display: none">
              <button type="button" class="btn-close" onclick="hideServerError()"></button>
              <h4 id="statusError" class="alert-heading">Error</h4>
              <div id="textError">There was an error</div>
            </div>
          </fieldset>
      </form>
    </div>

    <div id="fileInformation" class="container-xl" style="max-width: 70rem; margin-top: 5%; display:none">
      <div class="text-center">
        <h1 class="text"> <b>File Information From The Blockchain</b></h1>
      </div>
      <div>
        <table class="table table-hover">
          <tbody>
            <tr class="table-light">
              <th scope="row">File Name:</th>
              <td id="fileName" class="text-break"></td>
            </tr>
            <tr class="table-light">
              <th scope="row">File Size(Bytes):</th>
              <td id="fileSize" class="text-break"></td>
            </tr>
            <tr class="table-light">
              <th scope="row">File Hash:</th>
              <td id="fileHash" class="text-break"></td>
            </tr>
            <tr class="table-light">
              <th scope="row">Uploaded Date:</th>
              <td id="uploadedDate" class="text-break"></td>
            </tr>
            <tr class="table-primary">
              <th scope="row">Authenticator Name:</th>
              <td id="authName" class="text-break"></td>
            </tr>
            <tr class="table-primary">
              <th scope="row">Authenticator Email:</th>
              <td id="authEmail" class="text-break"></td>
            </tr>
            <tr class="table-primary">
              <th scope="row">Authenticator Wallet:</th>
              <td id="authWallet" class="text-break"></td>
            </tr>
            <tr class="table-light">
              <th scope="row">Current Owners:</th>
              <td id="currentOwners" class="text-break">
                <div class="spinner-grow" role="status">
                  <span class="sr-only"></span>
                </div>
              </td>
            </tr>
            <tr class="table-light">
              <th scope="row">Previous Owners:</th>
              <td id="oldOwners" class="text-break">
                <div class="spinner-grow" role="status">
                  <span class="sr-only"></span>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-center" style="margin-top: 2em">
        <button type="button" class="btn btn-primary btn-hover" style="min-width: 150px" onclick="verifyNewFile()">Verify more files</button>
      </div>
    </div>
    
    @stack('scripts')
@endsection