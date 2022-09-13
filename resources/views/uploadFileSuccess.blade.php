@extends('layouts.app')

@push('scripts')
    <script src={{ asset('js/uploadFile.js') }}></script>
@endpush

@section('page-content')

  <div class="container-xl" style="max-width: 70rem; margin-top: 5%">
    <div class="text-center">
      <h1 class="text-success"> <b>{{ count($file->getCurrentOwners())<>1 ? 'Co-owner added' : 'File Uploaded' }}</b></h1>
    </div>
    <div>
      <table class="table table-hover">
        <tbody>
          <tr class="table-light">
            <th scope="row">File Name:</th>
            <td class="text-break">{{ $file->file_name }}</td>
          </tr>
          <tr class="table-light">
            <th scope="row">File Size(bytes):</th>
            <td class="text-break">{{ $file->file_size }}</td>
          </tr>
          <tr class="table-light">
            <th scope="row">File Hash:</th>
            <td class="text-break">{{ $file->hash }}</td>
          </tr>
          <tr class="table-light">
            <th scope="row">Uploaded by:</th>
            <td class="text-break">{{ $file->user->name }}</td>
          </tr>
          <tr class="table-light">
            <th scope="row">Number of owners:</th>
            <td class="text-break">{{ count($file->getCurrentOwners()) }}</td>
          </tr>
          <tr class="table-light">
            <th scope="row">Uploaded date:</th>
            <td class="text-break">{{ $file->created_at }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="d-flex justify-content-center" style="margin-top: 2em">
      <a type="button" class="btn btn-primary btn-hover" style="min-width: 150px" href={{ count($file->getCurrentOwners())>1 ? route('coowner')  : route('uploadFile') }}> {{ count($file->getCurrentOwners())>1 ? 'Add more co-owners' : 'Upload more files' }}</a>
    </div>
  </div>


  @stack('scripts')
@endsection