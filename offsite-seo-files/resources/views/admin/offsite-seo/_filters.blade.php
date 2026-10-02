{{-- Shared date filter. Expects: $action, $range, $ranges, $from, $to. Extra fields go in the slot. --}}
<form method="GET" action="{{ $action }}" class="mb-4 card">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-3 col-6">
                <label class="form-label fw-semibold">Period</label>
                <select name="range" class="form-select js-range">
                    @foreach ($ranges as $key => $label)
                        <option value="{{ $key }}" {{ $range === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 col-6 js-custom-date" style="{{ $range === 'custom' ? '' : 'display:none' }}">
                <label class="form-label fw-semibold">From</label>
                <input type="date" name="start_date" class="form-control" value="{{ $from->toDateString() }}">
            </div>
            <div class="col-md-2 col-6 js-custom-date" style="{{ $range === 'custom' ? '' : 'display:none' }}">
                <label class="form-label fw-semibold">To</label>
                <input type="date" name="end_date" class="form-control" value="{{ $to->toDateString() }}">
            </div>
            {{ $slot ?? '' }}
            <div class="col-md-auto col-12">
                <button type="submit" class="btn btn-primary"><i class="bi bi-funnel-fill me-1"></i>Apply</button>
                <a href="{{ $action }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
        <small class="mt-2 text-muted d-block">Showing {{ $from->format('d M Y') }} – {{ $to->format('d M Y') }}</small>
    </div>
</form>
<script>
    document.querySelectorAll('.js-range').forEach(function(select) {
        select.addEventListener('change', function() {
            document.querySelectorAll('.js-custom-date').forEach(function(el) {
                el.style.display = select.value === 'custom' ? '' : 'none';
            });
        });
    });
</script>
