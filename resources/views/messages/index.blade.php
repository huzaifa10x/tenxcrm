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

            </div>
            <!-- AI Chat User -->
            <div data-id="ai" data-name="AI Assistant" data-img="{{ asset('/images/robot.png') }}"
                class="user chat-sidebar-single">
                <div class="img">
                    <img class="rounded rounded-circle object-fit-cover" src="{{ asset('/images/robot.png') }}"
                        alt="AI Bot">
                </div>
                <div class="info">
                    <h6 class="text-sm mb-1">AI Assistant</h6>
                    <p class="mb-0 text-xs">Ask me anything!</p>
                </div>
                <div class="action text-end">
                    <span
                        class="w-16-px h-16-px text-xs rounded-circle bg-info-main text-white d-inline-flex align-items-center justify-content-center">🤖</span>
                </div>
            </div>

        </div>
        <div class="chat-main card">
            <div class="chat-sidebar-single active">
                <div class="img">
                    <img class="sender-img rounded object-fit-cover rounded-circle"
                        src="{{ auth()->user()->img ? asset('/public/storage/' . auth()->user()->img) : asset('/images/avatar/avatar1.png') }}"
                        alt="image">
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
        const routes = {
            deleteForMe: "{{ route('messages.delete.me', ['id' => '__ID__']) }}",
            deleteForEveryone: "{{ route('messages.delete.everyone', ['id' => '__ID__']) }}",
        };
    </script>

    <script>
        function deleteMessage(id, type) {
            let url = type === 'me' ?
                routes.deleteForMe.replace('__ID__', id) :
                routes.deleteForEveryone.replace('__ID__', id);

            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function() {
                    loadMessages($('#receiver_id').val());
                }
            });
        }

        let selectedUser = null;

        $(document).on('click', '.user', function() {
            selectedUser = $(this).data('id');

            if (selectedUser === 'ai') {
                $('#chatBox').html('');
                $('.sender-name').text('AI Assistant');
                $('.sender-img').attr('src', "{{ asset('/images/robot.png') }}");
            }

            name = $(this).data('name');
            img = $(this).data('img');
            $('#receiver_id').val(selectedUser);
            loadMessages(selectedUser, name, img);
            $(this).find('.unreadCount').text('0 new messages');
            $(this).find('.unreadBadge').hide();
        });

        function loadMessages(userId, name, img) {
            $.get(`{{ route('messages.fetch', ':id') }}`.replace(':id', userId), function(data) {
                $('#chatBox').html('');
                $('.sender-name').text(name);
                $('.sender-img').attr('src', img);
                data.forEach(msg => {
                    $('#chatBox').append(`
                        ${msg.sender_id == "{{ auth()->id() }}" ? 
                            `<div class="chat-single-message position-relative right">
                                                                                                                                        <div class="chat-message-content">
                                                                                                                                            <p class="mb-3">${msg.message}</p>
                                                                                                                                            <p class="chat-time mb-0">
                                                                                                                                                <span>${msg.created_at_formatted}</span>
                                                                                                                                                  <div class="btn-group position-absolute top-0 end-0 m-10 mt-20">
                                                                            <button type="button" class="text-xl text-light" data-bs-toggle="dropdown"
                                                                                data-bs-display="static" aria-expanded="false">
                                                                                <iconify-icon icon="tabler:dots-vertical"></iconify-icon>
                                                                            </button>
                                                                            <ul class="dropdown-menu dropdown-menu-lg-end border">
                                                                                <li>
                                                                                    <button onclick="deleteMessage(${msg.id}, 'me')"
                                                                                        class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2"
                                                                                        type="button">
                                                                                        <iconify-icon icon="mdi:clear-circle-outline"></iconify-icon>
                                                                                        Delete for me
                                                                                    </button>
                                                                                </li>
                                                                                <li>
                                                                                    <button
                                                                                    onclick="deleteMessage(${msg.id}, 'everyone')"
                                                                                        class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2"
                                                                                        type="button">
                                                                                        <iconify-icon icon="ic:baseline-block"></iconify-icon>
                                                                                        Delete for Everyone
                                                                                    </button>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
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
            $.ajax({
                url: "{{ route('messages.markAsRead', ['userId' => '__userId__']) }}".replace('__userId__',
                    userId),
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function() {
                    // After marking as read, remove the highlight
                    $('.chat-sidebar-single[data-id="' + userId + '"]').removeClass('active');
                },
                error: function(xhr) {
                    console.error('Failed to mark messages as read:', xhr.responseText);
                }
            });
        }

        $('#messageForm').on('submit', function(e) {
            e.preventDefault();
            
            const message = $('#messageInput').val();
            const receiver = $('#receiver_id').val();
            if (!message || !receiver) return;
            
            if (receiver === 'ai') {
                $('#chatBox').append(`
                    <div class="chat-single-message position-relative right">
                        <div class="chat-message-content">
                            <p class="mb-3">${message}</p>
                        </div>
                    </div>
                `);

                $.post(`{{ route('groq.chat') }}`, {
                    _token: "{{ csrf_token() }}",
                    prompt: message
                }, function(data) {
                    $('#chatBox').append(`
                        <div class="chat-single-message left">
                            <img src="{{ asset('/images/robot.png') }}" alt="image" class="avatar-lg object-fit-cover rounded-circle">
                            <div class="chat-message-content">
                                <p class="mb-3">${data.choices[0].message.content}</p>
                            </div>
                        </div>
                    `);
                    $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight);
                });

                $('#messageInput').val('');
                return;
            }

            $.post(`{{ route('messages.send') }}`, {
                _token: "{{ csrf_token() }}",
                message: message,
                receiver_id: receiver
            }, function(data) {
                $('#messageInput').val('');
                loadMessages(receiver);
            });
        });

        function loadUsers() {
            $.ajax({
                url: "{{ route('users.getUnreadMessages') }}",
                method: 'GET',
                success: function(users) {
                    let userList = '';
                    users.forEach(user => {
                        let unreadBadge = user.unread_count > 0 ?
                            `<span class="w-16-px h-16-px text-xs rounded-circle bg-warning-main text-white d-inline-flex align-items-center justify-content-center">${user.unread_count}</span>` :
                            '';
                        let activeClass = user.unread_count > 0 ? ' active' :
                            '';

                        let imgPath = user.img ? "{{ asset('/storage/app/public') }}" + "/" + user
                            .img :
                            "{{ asset('/images/avatar/avatar1.png') }}"

                        userList += `
                    <div data-id="${user.id}" data-name="${user.name}" data-img="${imgPath}" class="user chat-sidebar-single${activeClass}">
                        <div class="img">
                            <img class="rounded rounded-circle object-fit-cover" src="${imgPath}" alt="image">
                        </div>
                        <div class="info">
                            <h6 class="text-sm mb-1">${user.name}</h6>
                            ${user.unread_count > 0 ? `
                                                <p class="mb-0 text-xs unreadCount">${user.unread_count} new messages</p>
                                                ` : ''}
                        </div>
                        <div class="action unreadBadge text-end">
                            ${unreadBadge}
                        </div>
                    </div>
                `;
                    });
                    $('.chat-all-list').html(userList); // Populate the user list
                },
                error: function(xhr) {
                    console.error('Failed to load users:', xhr.responseText);
                }
            });
        }

        $(document).ready(function() {
            loadUsers()
        });
    </script>

</x-app-layout>
