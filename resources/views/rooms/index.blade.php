<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZmCULaM1uBBroVYBOYSk1knywH/Ev+nYRRuWlolflfl" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body >
    <div class="container-fluid mt-4">
        <div class="card" style="background-color: rgba(255, 255, 255, 0.36);">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>My Chat Rooms</span>
                <div>
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-user me-1"></i> {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <div class="btn-group-vertical col-12">
                                        <a class="btn btn-danger btn-sm" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                    </div>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($rooms->count() > 0)
                    <div class="list-group gap-2">
                        @foreach ($rooms as $room)
                            <div class=" alert alert-primary">
                                <a href="{{ route('rooms.show', $room) }}" class="text-decoration-none text-dark">
                                    <div class="d-flex w-100 justify-content-between ">
                                        <h5>{{ $room->name }} : <small class="mb-1">{{ $room->description }}</small></h5>
                                        <div>
                                            <small>room number: {{ $room->id }}</small>
                                            @if (Auth::id() === $room->user_id)
                                                <span class="badge bg-primary ms-2">Owner</span>
                                            @else
                                                <span class="badge bg-success ms-2">Member</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        @if ($room->latestMessage)
                                            <small>Last message: {{ Str::limit($room->latestMessage->content, 50) }}</small>
                                        @else
                                            <small>No messages yet</small>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-center mt-3  gap-3">
                        <a href="{{ route('rooms.join.form') }}" class="btn btn-primary btn-sm">Join Room</a>
                        <a href="{{ route('rooms.create') }}" class="btn btn-primary btn-sm">Create Room</a>
                    </div>
                @else
                    <p>You haven't joined any rooms yet. <a href="{{ route('rooms.create') }}">Create one!</a></p>
                    <p>Or <a href="{{ route('rooms.join.form') }}">join an existing room</a> with its ID and password.</p>
                @endif
            </div>
        </div>
    </div>
</body>
</html>