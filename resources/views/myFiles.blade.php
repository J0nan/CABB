@extends('layouts.app')

@push('scripts')
    <script src={{ asset('js/myFiles.js') }}></script>
    <script>getInfo()</script>
@endpush

@section('page-content')

  <div class="container-xl" style="max-width: 70rem; margin-top: 5%">
    <div class="text-center">
      <h1 class="text"> <b>My Uploaded Files</b></h1>
    </div>
    <div id="myFiles" style="margin-top: 5em">
      <div class="d-flex justify-content-center">
        <div class="spinner-grow" role="status" style="width: 5rem; height: 5rem; margin-top: 5em">
        </div>
      </div>
    </div>
  </div>

  @stack('scripts')
@endsection