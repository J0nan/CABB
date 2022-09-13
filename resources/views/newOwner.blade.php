@extends('layouts.app')

@push('scripts')
    <script src={{ asset('js/newOwner.js') }}></script>
@endpush

@section('page-content')
    <div>
      <h1>Change owner</h1>
    </div>

    <div class="container-sm" style="max-width: 40em; margin-top: 5%">
      <form id="fileUploadForm" onsubmit="return submitForm(event)">
          <fieldset>
            <legend>Enter details of the new owner</legend>
            <div class="form-group" style="margin-top: 2em">
              <input class="form-control" type="file" id="file">
              <div id="fileErrorInfo" class="invalid-feedback" style="z-index: 11; position: fixed">One file must be selected</div>
            </div>

            <h5 style="margin-top: 7%; margin-bottom: 5px; font-weight: bold">Owner information:</h5>

            <div class="form-floating mb-3" >
              <input type="text" class="form-control" id="name" placeholder="Juan">
              <label for="name">Name</label>
              <div id="nameErrorInfo" class="invalid-feedback" style="z-index: 11; position: fixed"></div>
            </div>

            <div class="form-floating mb-3" style="margin-top: 2em">
              <input type="text" class="form-control" id="surname" placeholder="Gonzalez Sanchez">
              <label for="surname">Surname</label>
              <div id="surnameErrorInfo" class="invalid-feedback" style="z-index: 11; position: fixed"></div>
            </div>

            <div class="form-floating mb-3" style="margin-top: 2em">
              <input type="text" class="form-control" id="legalId" placeholder="12345678X">
              <label for="legalId">Legal ID</label>
              <div id="legalIdErrorInfo" class="invalid-feedback" style="z-index: 11; position: fixed"></div>
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
      <div id = "spinner" style="display: none">
        <div class="d-flex justify-content-center" style="margin-top: 2em">
          <div class="spinner-grow" style="width: 4rem; height: 4rem;" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      </div>
    </div>

    <div id="uploadFileModal" class="modal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 id="uploadFileModalTitle" class="modal-title"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div id="uploadFileModalBody" class="modal-body">
            <p class="text-break">Modal body text goes here.</p>
          </div>
          <div class="modal-footer">
            <button id="uploadFileModalSecondaryBtn" type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="unSetButtonLoading(document.getElementById('uploadFileButton'))">Close</button>
            <button id="uploadFileModalPrimaryBtn" type="button" class="btn btn-primary btn-hover" onclick="uploadFile()"></button>
          </div>
        </div>
      </div>
    </div>

    @stack('scripts')
@endsection