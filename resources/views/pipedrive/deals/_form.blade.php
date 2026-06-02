{{-- Shared add/edit deal form. Mirrors Pipedrive's "Add deal" dialog. --}}
@php $isEdit = isset($deal); @endphp

@if ($errors->any())
    <div class="alert alert-danger py-2" style="font-size:.82rem;">
        <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="row">
    {{-- Left column --}}
    <div class="col-lg-7">
        <div class="mb-3">
            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" required
                   value="{{ old('title', $deal->title ?? '') }}" placeholder="e.g. Biryani Walla — SEO + Social">
        </div>

        <div class="row g-2 mb-3">
            <div class="col-7">
                <label class="form-label fw-semibold">Value</label>
                <input type="number" step="0.01" min="0" name="value" class="form-control"
                       value="{{ old('value', $deal->value ?? '') }}" placeholder="0.00">
            </div>
            <div class="col-5">
                <label class="form-label fw-semibold">Currency</label>
                <select name="currency" class="form-select">
                    @foreach (['AED','USD','PKR','SAR','EUR','GBP'] as $cur)
                        <option value="{{ $cur }}" @selected(old('currency', $deal->currency ?? 'AED') === $cur)>{{ $cur }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Pipeline stage</label>
            <select name="stage_id" class="form-select">
                @foreach ($stages as $stage)
                    <option value="{{ $stage->id }}" @selected(old('stage_id', $deal->stage_id ?? $stages->first()->id) == $stage->id)>
                        {{ $stage->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-6">
                <label class="form-label fw-semibold">Label</label>
                <input type="text" name="label" class="form-control" list="labelOptions"
                       value="{{ old('label', $deal->label ?? '') }}" placeholder="Hot / Warm / Cold">
                <datalist id="labelOptions">
                    <option value="Hot"><option value="Warm"><option value="Cold"><option value="Upsell">
                </datalist>
            </div>
            <div class="col-6">
                <label class="form-label fw-semibold">Expected close date</label>
                <input type="date" name="expected_close_date" class="form-control"
                       value="{{ old('expected_close_date', optional($deal->expected_close_date ?? null)->format('Y-m-d')) }}">
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-6">
                <label class="form-label fw-semibold">Source channel</label>
                <input type="text" name="source_channel" class="form-control"
                       value="{{ old('source_channel', $deal->source_channel ?? '') }}" placeholder="Website, Referral…">
            </div>
            <div class="col-6">
                <label class="form-label fw-semibold">Source channel ID</label>
                <input type="text" name="source_channel_id" class="form-control"
                       value="{{ old('source_channel_id', $deal->source_channel_id ?? '') }}">
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-6">
                <label class="form-label fw-semibold">Visible to</label>
                <select name="visible_to" class="form-select">
                    <option value="owner" @selected(old('visible_to', $deal->visible_to ?? 'owner') === 'owner')>Owner only</option>
                    <option value="everyone" @selected(old('visible_to', $deal->visible_to ?? '') === 'everyone')>Everyone</option>
                </select>
            </div>
            @if (auth()->user()->hasRole('ADMIN'))
                <div class="col-6">
                    <label class="form-label fw-semibold">Owner</label>
                    <select name="owner_id" class="form-select">
                        @foreach ($owners as $o)
                            <option value="{{ $o->id }}" @selected(old('owner_id', $deal->owner_id ?? auth()->id()) == $o->id)>{{ $o->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
    </div>

    {{-- Right column: contact & organization --}}
    <div class="col-lg-5">
        <div class="crm-panel mb-3">
            <div class="crm-panel-head"><i class="bi bi-person me-1"></i>Contact person</div>
            <div class="crm-panel-body">
                <input type="hidden" name="person_id" id="person_id" value="{{ old('person_id', $deal->person_id ?? '') }}">
                <div class="mb-2">
                    <label class="form-label">Name</label>
                    <input type="text" name="person_name" id="person_name" class="form-control" list="personList"
                           value="{{ old('person_name', $deal->person->name ?? '') }}" placeholder="Search or add new…" autocomplete="off">
                    <datalist id="personList"></datalist>
                </div>
                <div class="mb-2">
                    <label class="form-label">Phone</label>
                    <input type="text" name="person_phone" class="form-control"
                           value="{{ old('person_phone', $deal->person->phone ?? '') }}">
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="person_email" class="form-control"
                           value="{{ old('person_email', $deal->person->email ?? '') }}">
                </div>
            </div>
        </div>

        <div class="crm-panel">
            <div class="crm-panel-head"><i class="bi bi-building me-1"></i>Organization</div>
            <div class="crm-panel-body">
                <input type="hidden" name="organization_id" id="organization_id" value="{{ old('organization_id', $deal->organization_id ?? '') }}">
                <label class="form-label">Name</label>
                <input type="text" name="organization_name" id="organization_name" class="form-control" list="orgList"
                       value="{{ old('organization_name', $deal->organization->name ?? '') }}" placeholder="Search or add new…" autocomplete="off">
                <datalist id="orgList"></datalist>
            </div>
        </div>
    </div>
</div>

<script>
    // Lightweight typeahead: fill datalists + capture an existing record's id
    function wireTypeahead(inputId, listId, hiddenId, url) {
        const input = document.getElementById(inputId);
        const list  = document.getElementById(listId);
        const hidden = document.getElementById(hiddenId);
        let cache = [];
        input.addEventListener('input', function () {
            hidden.value = ''; // typing means "maybe new"
            if (this.value.length < 2) return;
            fetch(`${url}?q=${encodeURIComponent(this.value)}`, { headers: { 'Accept': 'application/json' }})
                .then(r => r.json())
                .then(rows => {
                    cache = rows;
                    list.innerHTML = rows.map(r => `<option data-id="${r.id}" value="${r.name}">`).join('');
                    const match = rows.find(r => r.name === input.value);
                    if (match) hidden.value = match.id;
                });
        });
        input.addEventListener('change', function () {
            const match = cache.find(r => r.name === input.value);
            hidden.value = match ? match.id : '';
        });
    }
    document.addEventListener('DOMContentLoaded', function () {
        wireTypeahead('person_name', 'personList', 'person_id', '{{ route('pipedrive.persons.search') }}');
        wireTypeahead('organization_name', 'orgList', 'organization_id', '{{ route('pipedrive.organizations.search') }}');
    });
</script>
