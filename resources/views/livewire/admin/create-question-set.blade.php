<div>
      <div class="row col-8-4">
    <div class="card">
      <div class="card-header">
        <div>
          <div class="card-title">Create Question Sessions</div>
          <div class="card-subtitle">Tell us about the project you're starting.</div>
        </div>
      </div>
      <div class="card-body">
        <form wire:submit="{{ $questionSet ? 'update' : 'save' }}">
          <div class="form-group">
            <label class="form-label" for="">Name<span class="required">*</span></label>
            <input type="text" wire:model.blur="title" name="title" id="" class="form-control" placeholder="">
              @error('title')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            <div class="form-help"></div>
          </div>
      


          <div class="form-group">
            <label class="form-label" for="">Description<span class="required">*</span></label>
            <input type="text" wire:model="description" name="description" id="" class="form-control" placeholder="">
              @error('description')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            <div class="form-help"></div>
          </div>

       

   
      

          <div class="form-actions right">
            <button type="submit" class="btn btn-primary" wire:loading.attr="diabled">
            <span wire:loading.remove>{{ $questionSet ? 'Update Question Set' : 'Create Question Set' }}</span>
            <span wire:loading>{{ $questionSet ? 'Updating...' : 'Creating...' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

  
  </div>


 
    {{-- The whole future lies in uncertainty: live immediately. - Seneca --}}
</div>
