<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid" >
      <a class="navbar-brand" href={{ route('index') }}>{{ config('app.name') }}</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
  
      <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href={{ route('index') }}>Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('upload*') ? 'active' : '' }}" href={{ route('uploadFile') }}>Upload file</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ Request::is('verify*') ? 'active' : '' }}" href={{ route('verifyFile') }}>Verify file</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ Request::is('coowner*') ? 'active' : '' }}" href={{ route('coowner') }}>Add Co-owner</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ Request::is('newowner*') ? 'active' : '' }}" href={{ route('newowner') }}>Change Owner</a>
        </li>
        </ul>
        <div class="d-flex">
          <a id="buttonMyFiles" class="btn btn-register" href={{ route('myFiles') }} style="margin-right: 5px; display: none">My Files</a>
          <button id="buttonLogOut" class="btn btn-login" onclick="logOut()" style="display: none">Log Out</button>
          <a id="buttonRegister" class="btn btn-register" href={{ route('register') }} style="margin-right: 5px">Register</a>
          <a id="buttonLogIn" class="btn btn-login" href={{ route('login') }} style="display: block">Log In </a>
        </div>
      </div>
    </div>
  </nav>

  <script>
    if (AUTHENTICATED) {
      document.getElementById('buttonLogIn').style.display='none'
      document.getElementById('buttonRegister').style.display='none'
      document.getElementById('buttonMyFiles').style.display='block'
      document.getElementById('buttonLogOut').style.display='block'
    } else {
      document.getElementById('buttonLogIn').style.display='block'
      document.getElementById('buttonRegister').style.display='block'
      document.getElementById('buttonLogOut').style.display='none'
    }
  </script>