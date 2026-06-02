@if ($errors->any())
    <div class="alert alert-danger py-2" style="font-size:.82rem;"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
<div class="row g-3">
    <div class="col-md-6"><label class="form-label fw-semibold">First name</label>
        <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $person->first_name ?? '') }}"></div>
    <div class="col-md-6"><label class="form-label fw-semibold">Last name</label>
        <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $person->last_name ?? '') }}"></div>
    <div class="col-12"><label class="form-label fw-semibold">Display name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $person->name ?? '') }}" placeholder="Leave blank to auto-build from first + last"></div>
    <div class="col-md-6"><label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $person->email ?? '') }}"></div>
    <div class="col-md-6"><label class="form-label fw-semibold">Phone</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $person->phone ?? '') }}"></div>
    <div class="col-md-6"><label class="form-label fw-semibold">Label</label>
        <input type="text" name="label" class="form-control" value="{{ old('label', $person->label ?? '') }}"></div>
    <div class="col-md-6"><label class="form-label fw-semibold">Organization</label>
        <select name="organization_id" class="form-select">
            <option value="">— None —</option>
            @foreach ($organizations as $org)
                <option value="{{ $org->id }}" @selected(old('organization_id', $person->organization_id ?? '') == $org->id)>{{ $org->name }}</option>
            @endforeach
        </select></div>
</div>
