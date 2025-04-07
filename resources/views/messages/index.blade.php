<x-app-layout>
    <x-slot name="header">
        {{ __('Messages') }}
    </x-slot>

    <div class="chat-wrapper">
        <div class="chat-sidebar card">
            <div class="chat-search">
                <span class="icon">
                    <iconify-icon icon="iconoir:search"></iconify-icon>
                </span>
                <input type="text" name="#0" autocomplete="off" placeholder="Search...">
            </div>
            <div class="chat-all-list">
                @foreach ($users as $user)
                    <div data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-img="{{ $user->img ? asset('/public/storage/' . $user->img) : asset('/images/avatar/avatar1.png') }}" class="user chat-sidebar-single active">
                        <div class="img">
                            <img class="rounded rounded-circle object-fit-cover"
                                src="{{ $user->img ? asset('/public/storage/' . $user->img) : asset('/images/avatar/avatar1.png') }}"
                                alt="image">
                        </div>
                        <div class="info">
                            <h6 class="text-sm mb-1">{{ $user->name }}</h6>
                            <p class="mb-0 text-xs">hey! there i'm...</p>
                        </div>
                        <div class="action text-end">
                            <p class="mb-0 text-neutral-400 text-xs lh-1">12:30 PM</p>
                            <span
                                class="w-16-px h-16-px text-xs rounded-circle bg-warning-main text-white d-inline-flex align-items-center justify-content-center">8</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="chat-main card">
            <div class="chat-sidebar-single active">
                <div class="img">
                    <img class="sender-img" src="{{ auth()->user()->img ? asset('/public/storage/' . auth()->user()->img) : asset('/images/avatar/avatar1.png') }}" alt="image">
                </div>
                <div class="info">
                    <h6 class="sender-name text-md mb-0">Select User</h6>
                    <p class="mb-0">Available</p>
                </div>
                <div class="action d-inline-flex align-items-center gap-3">
                    <button type="button" class="text-xl text-primary-light">
                        <iconify-icon icon="mi:call"></iconify-icon>
                    </button>
                    <button type="button" class="text-xl text-primary-light">
                        <iconify-icon icon="fluent:video-32-regular"></iconify-icon>
                    </button>
                    <div class="btn-group">
                        <button type="button" class="text-primary-light text-xl" data-bs-toggle="dropdown"
                            data-bs-display="static" aria-expanded="false">
                            <iconify-icon icon="tabler:dots-vertical"></iconify-icon>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-lg-end border">
                            <li>
                                <button
                                    class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2"
                                    type="button">
                                    <iconify-icon icon="mdi:clear-circle-outline"></iconify-icon>
                                    Clear All
                                </button>
                            </li>
                            <li>
                                <button
                                    class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2"
                                    type="button">
                                    <iconify-icon icon="ic:baseline-block"></iconify-icon>
                                    Block
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div><!-- chat-sidebar-single end -->
            <div id="chatBox" class="chat-message-list">
            </div>
            <form id="messageForm" class="chat-message-box">
                <input id="messageInput" type="text" name="chatMessage" placeholder="Write message">
                <div class="chat-message-box-action">
                    <button type="button" class="text-xl">
                        <iconify-icon icon="ph:link"></iconify-icon>
                    </button>
                    <button type="button" class="text-xl">
                        <iconify-icon icon="solar:gallery-linear"></iconify-icon>
                    </button>
                    <input type="hidden" name="receiver_id" id="receiver_id">
                    <button type="submit"
                        class="btn btn-sm btn-primary-600 radius-8 d-inline-flex align-items-center gap-1">
                        Send
                        <iconify-icon icon="f7:paperplane"></iconify-icon>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let selectedUser = null;

        $('.user').on('click', function() {
            selectedUser = $(this).data('id');
            name = $(this).data('name');
            img = $(this).data('img');
            $('#receiver_id').val(selectedUser);
            loadMessages(selectedUser, name, img);
        });

        function loadMessages(userId, name, img) {
            $.get(`{{ route('messages.fetch', ':id') }}`.replace(':id', userId), function(data) {
                $('#chatBox').html('');
                $('.sender-name').text(name);
                $('.sender-img').attr('src', img);
                data.forEach(msg => {
                    $('#chatBox').append(`
                        ${msg.sender_id == "{{ auth()->id() }}" ? 
                            `<div class="chat-single-message right">
                                                        <div class="chat-message-content">
                                                            <p class="mb-3">${msg.message}</p>
                                                            <p class="chat-time mb-0">
                                                                <span>${msg.created_at_formatted}</span>
                                                            </p>
                                                        </div>
                                                    </div>` : 
                            `<div class="chat-single-message left">
                                             <img src="${msg.sender_img}" alt="image" class="avatar-lg object-fit-cover rounded-circle">
                                                    <div class="chat-message-content">
                                                        <p class="mb-3">${msg.message}</p>
                                                        <p class="chat-time mb-0">
                                                            <span>${msg.created_at_formatted}</span>
                                                        </p>
                                                    </div>
                                                </div>`
                        }
                    `);
                });
            });
        }

        $('#messageForm').on('submit', function(e) {
            e.preventDefault();
            const message = $('#messageInput').val();
            const receiver = $('#receiver_id').val();
            if (!message || !receiver) return;

            $.post(`{{ route('messages.send') }}`, {
                _token: "{{ csrf_token() }}",
                message: message,
                receiver_id: receiver
            }, function(data) {
                $('#messageInput').val('');
                loadMessages(receiver);
            });
        });
    </script>

</x-app-layout>
