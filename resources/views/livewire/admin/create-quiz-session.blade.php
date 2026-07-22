<div>
    <div class="row col-8-4">
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Create Quiz Sessions</div>
                    <div class="card-subtitle">Tell us about the project you're starting.</div>
                </div>
            </div>
            <div class="card-body">
                <form wire:submit="{{ $quizSession ? 'update' : 'save' }}">
                    <div class="form-group">
                        <label class="form-label" for="projectName">Name<span class="required">*</span></label>
                        <input type="text" wire:model.blur="title" name="title" id="projectName"
                            class="form-control" placeholder="">
                        @error('title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-help">A short, recognizable name. You can change it later.</div>
                    </div>



                    <div class="form-group">
                        <label class="form-label" for="projectName">Join Code<span class="required">*</span></label>
                        <input type="text" wire:model="join_code" name="join_code" id="projectName"
                            class="form-control" placeholder="">
                        @error('join_code')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-help">A short, recognizable name. You can change it later.</div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="client">Status</label>
                            <select id="client" wire:model="status" name="status" class="form-control">
                                <option value="draft">Draft</option>
                                <option value="lobby">Lobby</option>
                                <option value="live">Live</option>
                                <option value="ended">Ended</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- <div class="form-group">
                        <label class="form-label" for="question_set_id">Question Set</label>
                        <select id="question_set_id" wire:model="question_set_id" class="form-control">
                            <option value="">Select Question Set</option>

                            @foreach ($questionSets as $questionSet)
                                <option value="{{ $questionSet->id }}">
                                    {{ $questionSet->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('question_set_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div> --}}

                    <div class="form-group">
                        <label class="form-label" for="question_set_id">
                            Question Set
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
                            <select x-ref="questionSet" id="question_set_id" >
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


                    <div class="form-actions right">
                        <button type="submit" class="btn btn-primary" wire:loading.attr="diabled">
                            <span wire:loading.remove>{{ $quizSession ? 'Update Session' : 'Create Session' }}</span>
                            <span wire:loading>{{ $quizSession ? 'Updating...' : 'Creating...' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>


    </div>



    {{-- The whole future lies in uncertainty: live immediately. - Seneca --}}
</div>
