<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        body {
            background: linear-gradient(to right, #220072ff, #006b1288);
        }
        .sidebar {
            min-height: 100vh;
            width: 280px;
            background-color: rgba(255, 255, 255, 0.36);
        }
        .content-area {
            flex: 1;
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar">
        @include('partials.sidebar') {{-- reuse your sidebar code --}}
    </div>

    <!-- Main content area -->
    <div class="content-area">
        @yield('content')
    </div>
</div>
</body>
</html>
