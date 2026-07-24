<div>
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <div class="page-pretitle">Data</div>
                <h1 class="page-title">Question</h1>
            </div>
            <div class="page-actions">

                <a class="btn btn-primary" href="{{ route('admin.questions.create') }}" wire:navigate>
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M4 8h8M8 4v8" />
                    </svg>
                    Add Question
                </a>
            </div>
        </div>
    </div>
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <div class="card-title">All Questions</div>
                <div class="card-subtitle">Sortable, searchable, paginated.</div>
            </div>

            <div style="width:300px">
                <input type="text" class="form-control" placeholder="Search..."
                    wire:model.live.debounce.300ms="search">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table" data-page-length="10" data-selectable data-export="customers">
                <thead>
                    <tr>
                        <th wire:click="sortBy('id')" style="cursor:pointer">SL. No</th>
                        <th wire:click="sortBy('title')" style="cursor:pointer">Question Set</th>
                        <th>Title</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($questions as $value)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $value->questionSet?->title??'' }}</td>
                            <td>{{ $value->text }}</td>

                            <td class="text-end">
                                <a href="{{ route('admin.questions.edit', $value) }}" wire:navigate
                                    class="btn btn-sm btn-outline-primary">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- <div class="card-footer d-flex justify-content-end">
                {{ $quizzes->links() }}
            </div> --}}
            <div class="card-footer">
                <x-pagination :paginator="$questions" id="quiz-sessions-info" />
            </div>
        </div>
    </div>

    {{-- Live as if you were to die tomorrow. Learn as if you were to live forever. - Mahatma Gandhi --}}
</div>
