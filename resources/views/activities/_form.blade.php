{{--
  _form.blade.php
  Used two ways:
  1. Full page: wrapped by edit.blade.php inside layouts.crm
  2. Modal AJAX: returned as raw HTML via ActivityController::edit() when request is XHR
--}}

@php
    $isAjax = request()->headers->get('X-Requested-With') === 'XMLHttpRequest'
              || request()->expectsJson();
    $action = route('crm.activities.update', $activity);
@endphp

<div class="modal-header border-bottom py-3 px-4">
    <h5 class="modal-title fw-semibold">Edit Activity</h5>
    @if($isAjax)
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    @endif
</div>

<form method="POST" action="{{ $action }}" id="editActivityForm">
    @csrf @method('PUT')

    <div class="modal-body p-0">
        <div class="row g-0">

            {{-- ── Left pane ── --}}
            <div class="col-md-8 p-4">

                <div class="mb-3">
                    <input type="text" name="subject"
                           class="form-control form-control-lg fw-semibold border-0 border-bottom rounded-0 px-0"
                           value="{{ old('subject', $activity->subject) }}" required
                           style="font-size:1.1rem;box-shadow:none">
                </div>

                {{-- Type buttons --}}
                <div class="d-flex gap-2 mb-4">
                    @foreach(\App\Models\Activity::TYPES as $t)
                        <button type="button"
                                class="type-btn {{ old('type', $activity->type) === $t ? 'active' : '' }}"
                                data-type="{{ $t }}"
                                title="{{ $t }}"
                                onclick="selectTypeEdit(this)">
                            <i class="bi {{ \App\Models\Activity::TYPE_ICONS[$t] }}"></i>
                        </button>
                    @endforeach
                    <input type="hidden" name="type" id="editModalType" value="{{ old('type', $activity->type) }}">
                </div>

                <style>
                .type-btn{width:40px;height:40px;border-radius:8px;border:1.5px solid #dee2e6;
                background:#f8f9fa;display:flex;align-items:center;justify-content:center;
                cursor:pointer;font-size:1.1rem;transition:all .15s}
                .type-btn:hover,.type-btn.active{background:#0d6efd;border-color:#0d6efd;color:#fff}
                .notes-textarea{background:#fefce8;border:1px solid #fef08a}
                .notes-textarea:focus{background:#fefce8;border-color:#eab308;box-shadow:none}
                </style>

                {{-- Date/time --}}
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-clock text-muted"></i>
                    <input type="date" name="due_date"
                           class="form-control form-control-sm"
                           value="{{ old('due_date', $activity->due_date?->format('Y-m-d')) }}"
                           style="width:150px">
                    <input type="time" name="due_time"
                           class="form-control form-control-sm"
                           value="{{ old('due_time', $activity->due_time ? substr($activity->due_time,0,5) : '') }}"
                           style="width:120px">
                    <span class="text-muted">–</span>
                    <input type="time" name="end_time"
                           class="form-control form-control-sm"
                           value="{{ old('end_time', $activity->end_time ? substr($activity->end_time,0,5) : '') }}"
                           style="width:120px">
                </div>

                {{-- Priority --}}
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-flag text-muted"></i>
                    <select name="priority" class="form-select form-select-sm w-auto">
                        <option value="Normal" @selected(old('priority',$activity->priority)==='Normal')>Normal</option>
                        <option value="High"   @selected(old('priority',$activity->priority)==='High')>High</option>
                    </select>
                </div>

                {{-- Location --}}
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-geo-alt text-muted"></i>
                    <input type="text" name="location" class="form-control form-control-sm"
                           placeholder="Location"
                           value="{{ old('location', $activity->location) }}">
                </div>

                {{-- Video call --}}
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-camera-video text-muted"></i>
                    <input type="url" name="video_call_url" class="form-control form-control-sm"
                           placeholder="Video call URL"
                           value="{{ old('video_call_url', $activity->video_call_url) }}">
                </div>

                {{-- Description --}}
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-card-text text-muted"></i>
                    <input type="text" name="description" class="form-control form-control-sm"
                           placeholder="Description"
                           value="{{ old('description', $activity->description) }}">
                </div>

                {{-- Notes --}}
                <div class="d-flex gap-2 mb-3">
                    <i class="bi bi-sticky text-muted mt-1"></i>
                    <textarea name="note" rows="4"
                              class="form-control form-control-sm notes-textarea flex-grow-1"
                              placeholder="Notes…">{{ old('note', $activity->note) }}</textarea>
                </div>

                {{-- Assigned to --}}
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-person-circle text-muted"></i>
                    <input type="text" name="assigned_to" class="form-control form-control-sm"
                           style="max-width:260px"
                           placeholder="Assigned to"
                           value="{{ old('assigned_to', $activity->assigned_to) }}">
                </div>

                {{-- Links --}}
                <div class="d-flex gap-2 mb-3">
                    <i class="bi bi-link-45deg text-muted mt-1"></i>
                    <div class="flex-grow-1 d-flex flex-column gap-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-currency-dollar text-muted"></i>
                            </span>
                            <select name="deal_id" class="form-select form-select-sm border-start-0">
                                <option value="">Deal or Lead</option>
                                @foreach(\App\Models\Deal::orderBy('title')->get(['id','title']) as $d)
                                    <option value="{{ $d->id }}"
                                        @selected(old('deal_id',$activity->deal_id)==$d->id)>
                                        {{ $d->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-person text-muted"></i>
                            </span>
                            <input type="text" name="contact_person"
                                   class="form-control form-control-sm border-start-0"
                                   placeholder="People"
                                   value="{{ old('contact_person', $activity->contact_person) }}">
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-building text-muted"></i>
                            </span>
                            <input type="text" name="organization"
                                   class="form-control form-control-sm border-start-0"
                                   placeholder="Organization"
                                   value="{{ old('organization', $activity->organization) }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Right pane: status ── --}}
            <div class="col-md-4 p-4 border-start">
                <h6 class="fw-semibold mb-3">Status</h6>
                <select name="done" class="form-select form-select-sm mb-3">
                    <option value="To do" @selected(old('done',$activity->done)==='To do')>To do</option>
                    <option value="Done"  @selected(old('done',$activity->done)==='Done')>Done</option>
                </select>

                <h6 class="fw-semibold mb-2 mt-4">Phone numbers</h6>
                <div class="d-flex flex-column gap-2">
                    <input type="text" name="phone_work" class="form-control form-control-sm"
                           placeholder="Work" value="{{ old('phone_work',$activity->phone_work) }}">
                    <input type="text" name="phone_mobile" class="form-control form-control-sm"
                           placeholder="Mobile" value="{{ old('phone_mobile',$activity->phone_mobile) }}">
                    <input type="text" name="phone_home" class="form-control form-control-sm"
                           placeholder="Home" value="{{ old('phone_home',$activity->phone_home) }}">
                    <input type="text" name="phone_other" class="form-control form-control-sm"
                           placeholder="Other" value="{{ old('phone_other',$activity->phone_other) }}">
                </div>

                <h6 class="fw-semibold mb-2 mt-4">Email addresses</h6>
                <div class="d-flex flex-column gap-2">
                    <input type="email" name="email_work" class="form-control form-control-sm"
                           placeholder="Work" value="{{ old('email_work',$activity->email_work) }}">
                    <input type="email" name="email_home" class="form-control form-control-sm"
                           placeholder="Home" value="{{ old('email_home',$activity->email_home) }}">
                    <input type="email" name="email_other" class="form-control form-control-sm"
                           placeholder="Other" value="{{ old('email_other',$activity->email_other) }}">
                </div>
            </div>

        </div>
    </div>

    <div class="modal-footer border-top px-4 py-3 d-flex justify-content-end gap-2">
        @if($isAjax)
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        @else
            <a href="{{ route('crm.activities.index') }}" class="btn btn-outline-secondary">Cancel</a>
        @endif
        <button type="submit" class="btn btn-success px-4">Save Changes</button>
    </div>
</form>

<script>
function selectTypeEdit(btn) {
    document.querySelectorAll('#editActivityForm .type-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('editModalType').value = btn.dataset.type;
}
</script>
