<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest House - @yield('title')</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/bootstrap.css">

    <link rel="stylesheet" href="{{ asset('assets') }}/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/app.css">
    <link rel="shortcut icon" href="{{ asset('assets') }}/images/favicon.svg" type="image/x-icon">
    @stack('style')
</head>

<body>
    <div id="app">
        @include('layouts.sidebar')
        <div id="main" class='layout-navbar'>
            @include('layouts.topbar')
            <div id="main-content">

                {{-- Content --}}
                @yield('content')
                {{-- End Content --}}

                <footer>
                    <div class="footer clearfix mb-0 text-muted">
                        <div class="float-start">
                            <p>{{ date('Y') }} &copy; Mulia Group Informatika, CV</p>
                        </div>
                        <div class="float-end">
                            <p><a href="http://muliagroupinformatika.my.id/">Mulia Group Informatika</a></p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
    {{-- <script src="{{ asset('assets') }}/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script> --}}
    <script src="{{ asset('assets') }}/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('assets') }}/js/main.js"></script>
    @stack('script')
</body>

</html>
