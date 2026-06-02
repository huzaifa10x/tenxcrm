{{-- A single draggable deal card on the kanban board --}}
<div class="crm-card {{ $deal->is_rotting ? 'is-rotting' : '' }}" data-deal-id="{{ $deal->id }}"
     onclick="if(!window.__dragging) location.href='{{ route('pipedrive.deals.show', $deal->id) }}'">
    <div class="card-title">{{ $deal->title }}</div>
    <div class="card-sub">
        @if ($deal->organization)
            <i class="bi bi-building me-1"></i>{{ Str::limit($deal->organization->name, 26) }}
        @elseif ($deal->person)
            <i class="bi bi-person me-1"></i>{{ Str::limit($deal->person->name, 26) }}
        @else
            <span class="text-muted">No contact</span>
        @endif
    </div>
    <div class="card-foot">
        <span class="card-value">{{ $deal->currency }} {{ number_format((float) $deal->value, 0) }}</span>
        <span class="d-flex align-items-center gap-2">
            @if ($deal->is_rotting)
                <span class="crm-rot-flag" title="Idle — needs attention"><i class="bi bi-exclamation-triangle-fill"></i></span>
            @endif
            @if ($deal->label)
                <span class="card-label" style="background:var(--ds-accent-soft);color:var(--ds-accent);">{{ $deal->label }}</span>
            @endif
            <span class="card-avatar" title="{{ $deal->owner?->name }}">
                {{ strtoupper(Str::substr($deal->owner?->name ?? '?', 0, 1)) }}
            </span>
        </span>
    </div>
</div>
