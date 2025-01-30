<x-app-layout>
    <x-slot name="header">
        {{ 'USER - ' . $user->name }}
    </x-slot>
    <div class="row gy-4">
        <div class="col-lg-6">
            <div class="user-grid-card position-relative border radius-16 overflow-hidden bg-base h-100">
                <img src="assets/images/user-grid/user-grid-bg1.png" alt="" class="w-100 object-fit-cover">
                <div class="pb-24 ms-16 mb-24 me-16">
                    <div class="mt-10">
                        <h6 class="text-xl mb-16">Personal Info</h6>
                        <ul>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light">Full Name</span>
                                <span class="w-70 text-secondary-light fw-medium">: {{ $user->name }}</span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light"> Email</span>
                                <span class="w-70 text-secondary-light fw-medium">: {{ $user->email }}</span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light"> Phone</span>
                                <span class="w-70 text-secondary-light fw-medium">: {{ $user->phone }}</span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light"> Department</span>
                                <span class="w-70 text-secondary-light fw-medium">: {{ $user->roles->pluck('name')->implode(', ') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
