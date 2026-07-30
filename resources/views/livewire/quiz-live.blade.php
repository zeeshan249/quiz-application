@push('styles')
    <style>

          body {
    background:  linear-gradient(#cabeff 0%, #e6deff 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 15px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
  }

       .quiz-wrapper {
    background: #fff;
    border: none;
    border-radius: 20px;
    padding: 35px;
    width: 100%;
    max-width: 1100px;
    box-shadow: 0 20px 40px rgba(99, 102, 241, 0.2);
  }

  .question-area {
    border: 2px solid #c7d2fe;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 40px;
    min-height: 100px;
    background: linear-gradient(135deg, #eef2ff 0%, #f5f3ff 100%);
  }

  .question-area span {
    color: #4f46e5 !important;
    font-size: 1.05rem;
  }

  .answer-box {
    border: 2px solid #c7d2fe;
    border-radius: 12px;
    padding: 20px;
    min-height: 80px;
    display: flex;
    align-items: center;
    cursor: pointer;
    background-color: #fff;
    color: #374151;
    font-weight: 500;
    transition: background-color 0.15s ease, border-color 0.15s ease, transform 0.15s ease, box-shadow 0.15s ease;
    text-align: left;
    width: 100%;
  }

  .answer-box:hover {
    background-color: #eef2ff;
    border-color: #a5b4fc;
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(99, 102, 241, 0.15);
  }

  .answer-box.selected {
    border: 2px solid #4f46e5;
    background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
    color: #4338ca;
    box-shadow: 0 6px 14px rgba(79, 70, 229, 0.25);
  }

  .answer-box:disabled {
    cursor: default;
  }

  .result-box {
    border-radius: 12px;
    padding: 24px;
    text-align: center;
  }

  .answer-row {
    margin-bottom: 25px;
  }
    </style>
@endpush







<div class="quiz-wrapper">
  @if ($question)
    <div
      class="quiz-question"
      wire:key="question-{{ $question->id }}"
      x-data="{ locked: @js($answerLocked) }"
      x-init="setTimeout(() => { if (!locked) { locked = true; $wire.autoSubmitAnswer() } }, 20000)"
    >
      <div class="question-area">
        <span class="text-primary fw-semibold">{{ $question->text }}</span>
      </div>

      @if ($isCorrect === null)
        <form wire:submit="submitAnswer">
          <div class="row answer-row g-4">
            @forelse ($question->questionOptions as $option)
              <div class="col-md-6" wire:key="question-option-{{ $option->id }}">
                <button
                  type="button"
                  class="answer-box {{ $selectedOptionId === $option->id ? 'selected' : '' }}"
                  wire:click="$set('selectedOptionId', {{ $option->id }})"
                  x-bind:disabled="locked"
                >{{ $option->text }}</button>
              </div>
            @empty
              <div class="col-12 text-muted">No options are available for this question.</div>
            @endforelse
          </div>

          @error('selectedOptionId')
            <div class="text-danger mb-3">{{ $message }}</div>
          @enderror

          @if ($question->questionOptions->isNotEmpty())
            <button class="btn w-100 mt-2" type="submit" x-bind:disabled="locked || $wire.selectedOptionId === null" wire:loading.attr="disabled" wire:target="submitAnswer,autoSubmitAnswer" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; border: none; border-radius: 12px; padding: 12px; font-weight: 600; box-shadow: 0 6px 14px rgba(79, 70, 229, 0.3);">
              <span wire:loading.remove wire:target="submitAnswer">Submit Answer</span>
              <span wire:loading wire:target="submitAnswer">Submitting...</span>
            </button>
          @endif
        </form>
      @else
        <div class="result-box {{ $isCorrect ? 'bg-success-subtle text-success-emphasis' : 'bg-danger-subtle text-danger-emphasis' }}">
          <h2 class="h4 fw-bold mb-2">{{ $isCorrect ? 'Correct!' : 'Incorrect' }}</h2>
          <p class="mb-0">
            {{ $isCorrect ? 'You earned '.$pointsAwarded.' point'.($pointsAwarded === 1 ? '' : 's').'.' : 'You earned 0 points for this question.' }}
          </p>
        </div>
      @endif
    </div>
  @else
    <div class="text-center text-muted">No active question is available for this quiz.</div>
  @endif
</div>
