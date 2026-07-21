@push('styles')
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(#cabeff 0%, #e6deff 100%);
            font-family: 'Inter', sans-serif;
        }

        .code-card {
            max-width: 450px;
            width: 100%;
            background: #fff;
            border: 1px solid #eae7ef;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 10px 30px -10px rgba(94, 59, 219, .15);
            transition: .3s;
        }

        .title {
            font-family: 'Manrope', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #1b1b21;
            margin-bottom: 2rem;
        }

        .code-input {
            font-family: 'Manrope', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            letter-spacing: 1em;
            color: #5e3bdb;
            border: 1px solid #c9c4d7;
            border-radius: .75rem;
            padding: 1rem 2rem;
            transition: .3s;
        }

        .code-input:focus {
            border-color: #5e3bdb;
            box-shadow: 0 0 0 .25rem rgba(94, 59, 219, .15);
        }

        .code-input.valid {
            border-color: #5e3bdb;
        }

        .code-input::placeholder {
            color: #dbd9e1;
        }
    </style>
@endpush

<div class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="code-card text-center">

        <div class="mb-4">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>

            <h2 class="fw-semibold mb-2">Waiting for Others...</h2>

            <p class="text-muted mb-4">
                You've successfully joined the quiz.<br>
                Please wait while other participants join.
            </p>

            <h4 class="fw-bold">
                <span data-participant-count>{{ $participantCount??0 }}</span> Participants Joined
            </h4>

            <small class="text-muted">
                The quiz will begin automatically when the host starts it.
            </small>
        </div>

    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const quizSessionId = @json($quizSessionId);
    const countElement = document.querySelector('[data-participant-count]');

    console.log('🚀 Waiting Lobby Script Loaded');
    console.log('Quiz Session ID:', quizSessionId);
    console.log('Count Element:', countElement);

    if (!countElement) {
        console.error('❌ Count element not found!');
        return;
    }

    // Listen to Echo broadcasts
    if (window.Echo) {
        console.log('📡 Echo available, listening to broadcasts...');
        const channel = window.Echo.channel(`quiz.${quizSessionId}`);

        channel.subscribed(() => {
            console.log('✅ Subscribed to quiz channel:', `quiz.${quizSessionId}`);
        });

        channel.listen('.participant.joined', (data) => {
            console.log('📢 Broadcast received:', data);
            if (data.count !== undefined) {
                countElement.textContent = data.count;
                console.log('✅ Count updated via broadcast:', data.count);
            }
        });
    } else {
        console.warn('⚠️ Echo not available, will use polling only');
    }

    // Polling - check every 2 seconds
    console.log('🔄 Starting polling...');
    const pollInterval = setInterval(async () => {
        try {
            const response = await fetch(`/api/quiz-session/${quizSessionId}/participant-count`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });
            if (response.ok) {
                const data = await response.json();
                if (data.count !== undefined) {
                    const currentCount = parseInt(countElement.textContent);
                    if (data.count !== currentCount) {
                        console.log('✅ Count updated via polling:', data.count);
                        countElement.textContent = data.count;
                    }
                }
            } else {
                console.error('❌ API error:', response.status);
            }
        } catch (error) {
            console.error('❌ Polling error:', error);
        }
    }, 2000);
});
</script>
@endpush

