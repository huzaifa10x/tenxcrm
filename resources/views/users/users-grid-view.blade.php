<x-app-layout>
    <x-slot name="header">
        {{ __('Users') }}
    </x-slot>
    <div class="card h-100 p-0 radius-12">
        @if ($userPermissions->contains('can manage users'))
            <div class="card-header">
                <a href="{{ route('users.create') }}" class="btn btn-outline-primary">
                    Add New User
                </a>
            </div>
        @endif
        <div class="card-body p-24">
            <div class="row gy-4">
                @foreach ($users as $user)
                    <div class="col-xxl-3 col-md-6 user-grid-card">
                        <div class="position-relative border radius-16 overflow-hidden">
                            @if ($user->img)
                                <img src="{{ asset('/public/storage/' . $user->img) }}" alt=""
                                    class="w-100 object-fit-cover">
                            @endif

                            @if ($userPermissions->contains('can manage users'))
                                <div class="dropdown position-absolute top-0 end-0 me-16 mt-16">
                                    <button type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                        class="bg-white-gradient-light w-32-px h-32-px radius-8 border border-light-white d-flex justify-content-center align-items-center text-white">
                                        <iconify-icon icon="entypo:dots-three-vertical" class="icon "></iconify-icon>
                                    </button>
                                    <ul class="dropdown-menu p-12 border bg-base shadow">
                                        <li>
                                            <a class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10"
                                                href="{{ route('users.edit', $user->id) }}">
                                                Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                style="display: inline-block;"
                                                onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="delete-btn dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-danger-100 text-hover-danger-600 d-flex align-items-center gap-10">
                                                    Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @endif

                            <div class="ps-16 pb-16 pe-16 text-center mt--50">
                                <img src="https://cdn-icons-png.flaticon.com/512/9131/9131529.png" alt=""
                                    class="border br-white border-width-2-px w-100-px h-100-px rounded-circle object-fit-cover">
                                <h6 class="text-lg mb-0 mt-4">{{ $user->name }}</h6>
                                <span class="text-secondary-light mb-16">{{ $user->email }}</span>

                                <div
                                    class="center-border position-relative bg-danger-gradient-light radius-8 p-12 d-flex align-items-center gap-4">
                                    <div class="text-center w-50">
                                        <h6 class="text-md mb-0">{{ $user->roles->pluck('name')->implode(', ') }}</h6>
                                        <span class="text-secondary-light text-sm mb-0">Department</span>
                                    </div>
                                    <div class="text-center w-50">
                                        <h6 class="text-md mb-0">Phone</h6>
                                        <span class="text-secondary-light text-sm mb-0">{{ $user->phone }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('users.show', $user->id) }}"
                                    class="bg-primary-50 text-primary-600 bg-hover-primary-600 hover-text-white p-10 text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center justify-content-center mt-16 fw-medium gap-2 w-100">
                                    View Profile
                                    <iconify-icon icon="solar:alt-arrow-right-linear"
                                        class="icon text-xl line-height-1"></iconify-icon>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{ $users->links('vendor.pagination.custom') }}

        </div>
    </div>
</x-app-layout>
