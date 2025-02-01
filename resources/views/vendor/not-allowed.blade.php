<div class="pt-48 pb-40 text-center">
    <div class="max-w-500-px mx-auto">
        <img src="{{ asset('/images/forbidden/forbidden-img.png') }}" alt="">
    </div>
    <div class="max-w-700-px mx-auto mt-40">
        <h3 class="mb-24 max-w-1000-px">Access Denied</h3>
        <p class="text-neutral-500 max-w-700-px text-lg">You don't have authorization to get to this page. If it's not
            too much trouble, contact your site executive to demand access.</p>
        <a href="{{ route('dashboard') }}"
            class="btn btn-primary-600 px-32 py-16 flex-shrink-0 d-inline-flex align-items-center justify-content-center gap-8 mt-28">
            <i class="ri-home-4-line"></i> Go Back To Home
        </a>
    </div>
</div>
