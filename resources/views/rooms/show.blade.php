<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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

.alert {
    border-radius: 8px;
    background: #2a2a2a;
    border: 1px solid #444;
    color: #ddd;
}

.chat-container {
    max-height: 90dvh;
    overflow-y: auto;
    background: radial-gradient(circle, #9900ff71, #121212ff);
    padding: 15px;
    border-radius: 10px;
}

.message {
    border-radius: 14px;
    padding: 10px 14px;
    margin-bottom: 12px;
    max-width: 80%;
    width: fit-content;
    word-wrap: break-word;
}

.own-message {
    background: linear-gradient(to right, #6f4ffeff, #003bfeff);
    color: #fff;
    margin-left: auto;
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
    display: none; /* Hidden by default */
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

        // Typing indicator functionality
        const typingIndicator = document.getElementById('typing-indicator');
        const messageInput = document.getElementById('message-input');
        let typingTimer;
        const TYPING_TIMEOUT = 1000; // 1 second of inactivity

        // Function to show typing indicator
        function showTypingIndicator() {
            typingIndicator.style.display = 'block';
        }

        // Function to hide typing indicator
        function hideTypingIndicator() {
            typingIndicator.style.display = 'none';
        }

        // Listen for typing events
        messageInput.addEventListener('input', function() {
            // Show indicator when user starts typing
            showTypingIndicator();

            // Clear existing timer
            clearTimeout(typingTimer);

            // Set a new timer to hide the indicator after inactivity
            typingTimer = setTimeout(hideTypingIndicator, TYPING_TIMEOUT);
        });

        // Also hide indicator when input loses focus
        messageInput.addEventListener('blur', function() {
            hideTypingIndicator();
            clearTimeout(typingTimer);
        });

        // Hide indicator when form is submitted
        document.getElementById('message-form').addEventListener('submit', function() {
            hideTypingIndicator();
            clearTimeout(typingTimer);
        });
    </script>
  </body>
</html>