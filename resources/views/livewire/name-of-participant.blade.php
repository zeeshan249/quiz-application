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
    <div class="code-card text-center" style="max-width: 420px; width: 100%;">

       

        <p class="fs-5 text-muted mb-4">
            Enter your name to join the quiz
        </p>

        @error('participantname')
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @enderror

        <form wire:submit="participantjoin">
            <input
                type="text"
                wire:model="participantname"
                class="form-control form-control-lg text-center"
                placeholder=""
                maxlength="50"
                autofocus
            >

            <button type="submit" class="btn btn-primary btn-lg w-100 mt-4">
                Join Quiz
            </button>
        </form>

    </div>
</div>
