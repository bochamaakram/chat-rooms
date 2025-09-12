<html>
  <head><html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        .chat-container {
            max-height: 85dvh;
            min-height: 85dvh;
            overflow-y: auto;
            background-image: url('https://img.freepik.com/vecteurs-premium/motif-geometrique-sans-couture-symboles-ligne-combinaisons-cartes-jouer-fond-casino_104589-1197.jpg');
            background-size:20%;
            background-position: center;
        }
        @media (max-width: 768px) {
            .chat-container {
                background-size: 80%;
            }
        }
        .message {
            border-radius: 18px;
            padding: 10px 16px;
            width: fit-content;
            margin-bottom: 12px;
            max-width: 80%;
            word-wrap: break-word;
        }
        .own-message {
            background-color: #0d6efd;
            color: white;
            margin-left: auto;
        }
        .other-message {
            background-color: #e9ecef;
            color: #212529;
        }
        .message-time {
            font-size: 0.75rem;
            opacity: 0.8;
            margin-top: 4px;
            text-align: right;
        }
        .typing-indicator {
            display: none;
            font-style: italic;
            color: #6c757d;
            padding: 8px 0;
        }
    </style>
  </head>
  <body>
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <a class="navbar-brand" href="{{ url('/') }}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/></svg></a>
                <h5 class="mb-0">{{ $room->name }}</h5>
                @if (Auth::id() === $room->user_id)
                <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="mb-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete Room</button>
                </form>
                @endif
            </div>

            <div class="card-body chat-container" id="chat-messages">
                @foreach ($messages as $message)
                    <div class="message {{ Auth::id() === $message->user_id ? 'own-message' : 'other-message' }}">
                        @if (Auth::id() !== $message->user_id)
                            <strong>{{ $message->user->name }}</strong><br>
                        @endif
                        {{ $message->content }}
                    </div>
                @endforeach
            </div>

            <div class="card-footer">
                <div class="typing-indicator" id="typing-indicator">
                    Someone is typing...
                </div>

                <form id="message-form">
                    @csrf
                    <div class="input-group">
                        <input type="text" id="message-input" class="form-control" placeholder="Type your message..." autocomplete="off">
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Disable logging in production
        Pusher.logToConsole = false;

        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true
        });

        const channel = pusher.subscribe('room.{{ $room->id }}');

        // Handle incoming messages
        channel.bind('App\\Events\\MessageSent', function(data) {
            // Refresh the page when a new message is received
            location.reload();
        });

        // Handle message submission
        document.getElementById('message-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const messageInput = document.getElementById('message-input');
            const message = messageInput.value.trim();

            if (message) {
                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = 'Sending...';
                submitBtn.disabled = true;

                axios.post('{{ route('messages.store', $room) }}', {
                    content: message
                })
                .then(response => {
                    // Refresh the page after successful message submission
                    location.reload();
                })
                .catch(error => {
                    console.error('Error sending message:', error);
                    alert('Failed to send message. Please try again.');
                    submitBtn.innerHTML = 'Send';
                    submitBtn.disabled = false;
                });
            }
        });

        // Auto-scroll to bottom of chat
        function scrollToBottom() {
            const chatMessages = document.getElementById('chat-messages');
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Scroll to bottom on initial load
        window.addEventListener('load', function() {
            scrollToBottom();

            // Focus on message input
            document.getElementById('message-input').focus();
        });
    </script>
  </body>
</html>