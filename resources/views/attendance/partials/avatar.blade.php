@php
    $__name = $user->name ?? '?';
    $__initials = collect(preg_split('/\s+/', trim((string) $__name)))->filter()->take(2)->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->implode('') ?: '?';
@endphp
<span class="xa-avatar {{ $class ?? '' }}" style="--hue: {{ crc32((string) $__name) % 360 }}" aria-hidden="true">
    @if (!empty($user->img))
        <img src="{{ asset('/public/storage/' . $user->img) }}" alt="" loading="lazy" onerror="this.remove()">
    @endif
    {{ $__initials }}
</span>
