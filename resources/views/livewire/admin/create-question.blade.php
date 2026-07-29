<div>
    <div class="row col-8-4">
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Create Question</div>
                    <div class="card-subtitle">Tell us about the project you're starting.</div>
                </div>
            </div>
            <div class="card-body">
                <form wire:submit="{{ $question ? 'update' : 'save' }}">
                    <div class="form-group">
                        <label class="form-label" for="questionText">Question Text<span class="required">*</span></label>
                        <input type="text" wire:model.blur="text" name="text" id="questionText"
                            class="form-control" placeholder="">
                        @error('text')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-help">A short, recognizable Question Text</div>
                    </div>







                    <div class="form-group">
                        <label class="form-label" for="question_set_id">
                            Question Set
                            <span class="required">*</span>
                        </label>

                        <div wire:ignore x-data x-init="const control = new TomSelect($refs.questionSet, {
                            create: false,
                            placeholder: 'Select Question Set',
                            allowEmptyOption: true,
                        });
                        
                        control.setValue(@js($question_set_id));
                        
                        control.on('change', value => {
                            $wire.set('question_set_id', value);
                        });">
                            <select x-ref="questionSet" id="question_set_id">
                                <option value="">Select Question Set</option>

                                @foreach ($questionSets as $questionSet)
                                    <option value="{{ $questionSet->id }}">
                                        {{ $questionSet->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @error('question_set_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>




                    <div x-data="questionOptions(@entangle('options'))" class="form-group">
                        <label class="form-label">
                            Question Options
                            <span class="required">*</span>
                        </label>

                        <template x-for="(option,index) in options" :key="option.id">

                            <div class="row align-items-center mb-2">

                                <div class="col-auto">

                                    <input type="checkbox" x-model="option.is_correct" :id="'correct-option-' + index"
                                        class="form-check-input"
                                        :aria-label="'Mark option ' + (index + 1) + ' as correct'">

                                </div>

                                <div class="col">

                                    <input type="text" class="form-control" x-model.blur="option.text"
                                        :placeholder="'Option ' + (index + 1)">

                                </div>

                                <div class="col-auto">

                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                        x-show="options.length > 2" @click="remove(index)">
                                        Remove
                                    </button>

                                </div>

                            </div>

                        </template>

                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" x-show="options.length < 6"
                            @click="add()">
                            + Add Option
                        </button>
                    </div>

                    @error('options')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    @error('options.*.text')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror


                    <div class="form-actions right">
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>{{ $question ? 'Update Question' : 'Create Question' }}</span>
                            <span wire:loading>{{ $question ? 'Updating...' : 'Creating...' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>


    </div>



























    {{-- <div
    x-data="{
        options: @entangle('options'),

        add() {
            if (this.options.length >= 6) return;

            this.options.push({
                id: Date.now() + Math.random(),
                text: '',
                is_correct: false
            });
        },

        remove(index) {
            if (this.options.length <= 2) return;

            this.options.splice(index, 1);
        }
    }"
    class="form-group"
>
    <label class="form-label">
        Question Options
        <span class="required">*</span>
    </label>

    <template x-for="(option, index) in options" :key="option.id">
        <div class="row align-items-center mb-2">
            <div class="col-auto">
                <input
                    type="checkbox"
                    class="form-check-input"
                    x-model="option.is_correct"
                    :id="'correct-option-' + index"
                    :aria-label="'Mark option ' + (index + 1) + ' as correct"
                >
            </div>

            <div class="col">
                <input
                    type="text"
                    class="form-control"
                    x-model.blur="option.text"
                    :placeholder="'Option ' + (index + 1)"
                >
            </div>

            <div class="col-auto">
                <button
                    type="button"
                    class="btn btn-outline-danger btn-sm"
                    x-show="options.length > 2"
                    @click="remove(index)"
                >
                    Remove
                </button>
            </div>
        </div>
    </template>

    <button
        type="button"
        class="btn btn-outline-primary btn-sm mt-2"
        x-show="options.length < 6"
        @click="add()"
    >
        + Add Option
    </button>
</div> --}}

    {{-- The whole future lies in uncertainty: live immediately. - Seneca --}}
</div>
@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {

            Alpine.data('questionOptions', (entangledOptions) => ({

                options: entangledOptions,

                add() {

                    if (this.options.length >= 6) {
                        return;
                    }

                    this.options.push({
                        id: Date.now() + Math.random(),
                        text: '',
                        is_correct: false
                    });

                },

                remove(index) {

                    if (this.options.length <= 2) {
                        return;
                    }

                    this.options.splice(index, 1);

                }

            }));

        });
    </script>
@endpush