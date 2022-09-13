<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        {{-- https://bootswatch.com/lux/ --}}
        <link rel="stylesheet" href="https://bootswatch.com/5/lux/bootstrap.min.css">

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        @push('styles')
            <link rel="stylesheet" href={{ asset('css/style.css') }}>
        @endpush
        <!-- Script -->
        <script src={{ asset('js/auth.js') }}></script>
        <script>
            var URI_LOGIN = '{{ route('login') }}';
            var AUTH_COOKIE = '{{ config('auth.cookie_name') }}';
            checkAuthentication({{ Request::is('upload*')||Request::is('coowner*')||Request::is('newowner*')||Request::is('user*') ? true : false }});
        </script>
    </head>
    <body>
        <header>
            @include('layouts.navbar')
        </header>
        <main>
            <div class="container-xl" style="margin-top: 1em; margin-bottom: 5%">
                @yield('page-content')
            </div>
        </main>
        <footer>
            CERTIFICATES OF AUTHENTICITY BASED ON BLOCKCHAIN
        </footer>
    </body>
</html>

@stack('styles')
