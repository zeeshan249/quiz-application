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
        .quiz-notification{
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    width: 420px;
    background: #fff;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,.15);
    border-left: 5px solid #0d6efd;
    z-index: 9999;
    display: none;
}

.quiz-title{
    font-weight: 600;
    margin-bottom: 8px;
}
.quiz-notification {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);

    width: min(500px, calc(100% - 2rem));

    background: #fff;
    border-radius: 12px;
    border-left: 5px solid #0d6efd;
    padding: 18px;

    box-shadow: 0 10px 30px rgba(0,0,0,.15);

    display: none;
    z-index: 9999;
}
    </style>
@endpush





<div>

    {{-- Floating notification --}}
    <div id="quiz-notification" class="quiz-notification" wire:ignore>
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="quiz-title">🎯 Quiz Starting</div>
                <div id="quiz-message"></div>
            </div>

            <div class="fw-bold fs-5">
                <span id="quiz-countdown">20</span>s
            </div>
        </div>

        <div class="progress mt-3" style="height:6px;">
            <div id="quiz-progress" class="progress-bar bg-success"></div>
        </div>
    </div>

    {{-- Centered waiting card --}}
    <div class="d-flex justify-content-center align-items-center min-vh-100">

        <div class="code-card text-center">

            <div class="spinner-border text-primary mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>

            <h2 class="fw-semibold mb-2">Waiting for Others...</h2>

            <p class="text-muted mb-4">
                You've successfully joined the quiz.<br>
                Please wait while other participants join.
            </p>

            <h4 class="fw-bold">
                <span data-participant-count>{{ $participantCount }}</span>
                Participants Joined
            </h4>

            <small class="text-muted">
                The quiz will begin automatically when the host starts it.
            </small>

        </div>

    </div>

</div>

{{-- @script
    <script>
        window.Echo.channel('quiz.{{ $quizSessionId }}')
            .listen('.participant.joined', (e) => {
                Livewire.dispatch('participant-joined', {
                    count: e.count,
                    quizSessionId: e.quizSessionId,
                });
            });
    </script>
 <script>
window.Echo.channel('quiz.{{ $quizSessionId }}')
    .listen('.quiz.started', (e) => {
        console.log('received', e);

        Livewire.dispatch('quiz-started', {
            startedAt: e.started_at,
            message: e.message,
        });
    });
</script>
@endscript --}}
@script

<script>
const channel = window.Echo.channel('quiz.{{ $quizSessionId }}');

channel.listen('.participant.joined', (e) => {
    Livewire.dispatch('participant-joined', {
        count: e.count,
        quizSessionId: e.quizSessionId,
    });
});

channel.listen('.quiz.started', (e) => {

    const box = document.getElementById('quiz-notification');
    const message = document.getElementById('quiz-message');
    const countdown = document.getElementById('quiz-countdown');
    const progress = document.getElementById('quiz-progress');

    let seconds = 20;

    message.innerText = e.message;
    countdown.innerText = seconds;

    box.style.display = 'block';

    progress.style.width = "100%";

    const timer = setInterval(() => {

        seconds--;

        countdown.innerText = seconds;

        progress.style.width = `${(seconds / 20) * 100}%`;

        if (seconds <= 0) {

            clearInterval(timer);

            box.style.display = 'none';

            // Start quiz / redirect here
            // window.location.href = '/quiz';

        }

    }, 1000);

});
</script>
@endscript
