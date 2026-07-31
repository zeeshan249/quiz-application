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

  .correct-answer-banner {
    text-align: center;
    margin-bottom: 20px;
  }

  .correct-answer-banner .badge {
    font-size: 1rem;
    font-weight: 600;
    padding: 10px 18px;
    border-radius: 999px;
    background-color: #d1f2e2;
    color: #147a4d;
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

  /* Correct option, always shown green once answered */
  .answer-box.correct-answer {
    border: 2px solid #198754;
    background-color: #d1f2e2;
    color: #14532d;
    box-shadow: 0 6px 14px rgba(25, 135, 84, 0.2);
  }

  /* The option the user picked, when it was wrong */
  .answer-box.incorrect-selected {
    border: 2px solid #d1d5db;
    background-color: #f1f2f4;
    color: #6b7280;
  }

  .result-box {
    border-radius: 12px;
    padding: 24px;
    text-align: center;
    margin-top: 30px;
  }

  .answer-row {
    margin-bottom: 25px;
  }

  /* --- Results bar chart (shown after options+result box hide) --- */
  .chart-wrapper {
    max-width: 700px;
    width: 100%;
    margin: 0 auto;
  }

  .bar-row {
    margin-bottom: 18px;
  }

  .bar-label {
    font-weight: 600;
    margin-bottom: 6px;
    display: flex;
    justify-content: space-between;
    color: #374151;
  }

  .bar-track {
    background-color: #e9ecef;
    border-radius: 6px;
    height: 34px;
    overflow: hidden;
  }

  .bar-fill {
    height: 100%;
    border-radius: 6px;
    display: flex;
    align-items: center;
    padding-left: 10px;
    color: #fff;
    font-size: 0.9rem;
    font-weight: 600;
    transition: width 0.6s ease;
    white-space: nowrap;
  }

  .bar-blue {
    background-color: #0d6efd;
  }

  .bar-green {
    background-color: #198754;
  }
    </style>
@endpush

<div class="quiz-wrapper">
  @if ($question)
    @php
      $answered = $isCorrect !== null;
      $correctOption = $question->questionOptions->firstWhere('is_correct', true);
    @endphp

    <div
      class="quiz-question"
      wire:key="question-{{ $question->id }}"
      x-data="{ locked: @js($answerLocked) }"
      x-init="setTimeout(() => { if (!locked) { locked = true; $wire.autoSubmitAnswer() } }, 20000)"
    >
      <div class="question-area">
        <span class="text-primary fw-semibold">{{ $question->text }}</span>
      </div>

      @if (! $answered)
        {{-- Not answered yet: just the picker + submit button --}}
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
        {{-- Answered: options+result box first, then swap to the breakdown after 10s --}}
        <div
          wire:key="answer-reveal-{{ $question->id }}"
          x-data="{ showBreakdown: false }"
          x-init="setTimeout(() => showBreakdown = true, 10000)"
        >
          <div x-show="!showBreakdown">
            <div class="row answer-row g-4">
              @foreach ($question->questionOptions as $option)
                @php
                  $isSelected = $selectedOptionId === $option->id;
                  $showGreen = $option->is_correct;
                  $showGrey = $isSelected && ! $option->is_correct;

                  $classes = 'answer-box';
                  if ($showGreen) {
                      $classes .= ' correct-answer';
                  }
                  if ($showGrey) {
                      $classes .= ' incorrect-selected';
                  }
                @endphp
                <div class="col-md-6" wire:key="question-option-{{ $option->id }}">
                  <button type="button" class="{{ $classes }}" disabled>{{ $option->text }}</button>
                </div>
              @endforeach
            </div>

            <div class="result-box {{ $isCorrect ? 'bg-success-subtle text-success-emphasis' : 'bg-danger-subtle text-danger-emphasis' }}">
              <h2 class="h4 fw-bold mb-2">{{ $isCorrect ? 'Correct!' : 'Incorrect' }}</h2>
              <p class="mb-0">
                {{ $isCorrect ? 'You earned '.$pointsAwarded.' point'.($pointsAwarded === 1 ? '' : 's').'.' : 'You earned 0 points for this question.' }}
              </p>
            </div>
          </div>

          <div x-show="showBreakdown" x-cloak x-transition.opacity.duration.500ms>
            @if ($correctOption)
              <div class="correct-answer-banner">
                <span class="badge">Correct Answer: {{ $correctOption->text }}</span>
              </div>
            @endif

            <div class="chart-wrapper">
              <h5 class="text-center mb-4 text-muted">Answer Breakdown</h5>

              @foreach ($question->questionOptions as $option)
                @php
                  $percentage = $optionResults[$option->id] ?? 0;
                  $barClass = $option->is_correct ? 'bar-green' : 'bar-blue';
                @endphp
                <div class="bar-row" wire:key="result-option-{{ $option->id }}">
                  <div class="bar-label">
                    <span>{{ $option->text }}</span>
                    <span>{{ $percentage }}%</span>
                  </div>
                  <div class="bar-track">
                    <div class="bar-fill {{ $barClass }}" style="width: {{ $percentage }}%;">
                      @if ($percentage > 8)
                        {{ $percentage }}%
                      @endif
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      @endif
    </div>
  @else
    <div class="text-center text-muted">No active question is available for this quiz.</div>
  @endif
</div>