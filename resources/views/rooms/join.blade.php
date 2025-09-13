@extends('layouts.app')

@section('content')
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

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="main-card card">
                <div class="card-header navbar">Join a Room
                    <a class="navbar-brand" href="{{ url('/') }}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/></svg></a>
                </div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('rooms.join') }}">
                        @csrf

                        <div class="form-group row mb-4">
                            <label for="room_id" class="col-md-4 col-form-label text-md-right">Room ID</label>

                            <div class="col-md-6">
                                <input id="room_id" type="text" class="form-control @error('room_id') is-invalid @enderror" name="room_id" value="{{ old('room_id') }}" required autocomplete="room_id" autofocus>

                                @error('room_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Room Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required><br>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Join Room
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection