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
    <style>
    body {
    background: #121212;
    min-height: 100vh;
    padding: 20px;
    font-family: 'Nunito', sans-serif;
    color: #e0e0e0;
}

.main-card {
    border-radius: 12px;
    overflow: hidden;
    background-color: #1e1e1e;
    box-shadow: 0 6px 16px rgba(0,0,0,0.4);
}

.card-header {
    background: #1f1f1f;
    color: #f0f0f0;
    padding: 15px 20px;
    border-bottom: 1px solid #2c2c2c;
}

.navbar-brand,
.nav-link {
    color: #bbb !important;
    transition: color 0.3s;
}

.navbar-brand:hover,
.nav-link:hover {
    color: #fff !important;
}

.form-control {
    border-radius: 8px;
    padding: 10px 15px;
    background: #2a2a2a;
    border: 1px solid #333;
    color: #eee;
}

.form-control:focus {
    outline: none;
    background: #2a2a2a;
    color: #eee;
    border-color: #4facfe;
    box-shadow: 0 0 0 2px rgba(79,172,254,0.2);
}

.btn {
    border-radius: 5px;
    padding: 8px 20px;
    border: none;
    transition: all 0.3s;
    font-weight: 500;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}

.card {
    border-radius: 10px;
    background: #1f1f1f;
    color: #ddd;
    border: 1px solid #2c2c2c;
}

.card-title {
    color: #f5f5f5;
    font-weight: 600;
}

.card-text {
    color: #aaa;
}

.text-box{
    background: #2a2a2a;
    color: #eee;
}

.chat-container {
    max-height: 80vh;
    overflow-y: auto;
    background: #181818;
    padding: 15px;
    border-radius: 10px;
}

.message {
    border-radius: 14px;
    padding: 10px 14px;
    margin-bottom: 12px;
    max-width: 80%;
    word-wrap: break-word;
}

.own-message {
    background: linear-gradient(to right, #4facfe, #00f2fe);
    color: #fff;
    margin-left: auto;
}

.li-it{
    background: #6851beff;
    color: #ddd;
    border:none;
}

.other-message {
    background: #2a2a2a;
    color: #ddd;
}

.message-time {
    font-size: 0.75rem;
    opacity: 0.7;
    margin-top: 3px;
    text-align: right;
    color: #aaa;
}

.typing-indicator {
    font-style: italic;
    color: #888;
    padding: 8px 0;
}
    </style>
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
                            <div class="list-group-item li-it">
                                <a href="{{ route('rooms.show', $room) }}" class="text-decoration-none text-dark">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $room->name }} : <small class="mb-1">{{ $room->description }}</small></h5>
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
                        <a href="{{ route('rooms.join.form') }}" class="btn btn-secondary btn-sm">Join Room</a>
                        <a href="{{ route('rooms.create') }}" class="btn btn-secondary btn-sm">Create Room</a>
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