<x-app-layout>
    <x-slot name="header">
        {{ __('Account Managers') }}
    </x-slot>
    <div class="card shadow-sm border-0 mb-5">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col"><i class="bi bi-person"></i> Manager</th>
                            <th scope="col"><i class="bi bi-people"></i> Team Members</th>
                            <th scope="col" class="text-center"><i class="bi bi-gear"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($managers as $manager)
                            <tr>
                                <td class="fw-semibold text-primary">{{ $manager->name }}</td>
                                <td>
                                    @if ($manager->teams->isEmpty())
                                        <span class="text-muted fst-italic">No team members</span>
                                    @else
                                        <ul class="list-unstyled mb-0 ps-3">
                                            @foreach ($manager->teams as $member)
                                                <li><i
                                                        class="bi bi-person-circle me-1 text-success"></i>{{ $member->name }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-outline-primary btn-sm add-team-btn"
                                        data-manager-id="{{ $manager->id }}" data-bs-toggle="modal"
                                        data-bs-target="#amModal">
                                        <i class="bi bi-plus-circle me-1"></i>Add Member
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div id="am-list" class="d-flex flex-wrap gap-3"></div>


    <script>
        let selectedManagerId = null;

        $(document).on('click', '.add-team-btn', function() {
            selectedManagerId = $(this).data('manager-id');
            $('#am-list').empty();

            $.get('{{ route('account-maanger.users') }}', {
                manager_id: selectedManagerId
            }, function(data) {
                const {
                    all_users,
                    team_users
                } = data;

                all_users.forEach(user => {
                    const alreadyAdded = team_users.some(teamUser => teamUser.id === user.id);

                    const btn = $(`<button class="btn m-1">${user.name}</button>`);

                    if (alreadyAdded) {
                        btn
                            .addClass('btn-success')
                            .text(user.name + ' ✅')
                            .click(() => {
                                if (confirm(`Remove ${user.name} from team?`)) {
                                    $.ajax({
                                        url: '{{ route('account-maanger.remove-member') }}',
                                        method: 'DELETE',
                                        data: {
                                            am_id: user.id,
                                            manager_id: selectedManagerId,
                                            _token: '{{ csrf_token() }}'
                                        },
                                        success: function(res) {
                                            if (res.status === 'success') {
                                                btn.removeClass('btn-success')
                                                    .addClass('btn-outline-success')
                                                    .text(user.name)
                                                    .off(
                                                        'click'
                                                        ) // remove old click handler
                                                    .click(addMemberHandler(user,
                                                        btn));
                                            }
                                        }
                                    });
                                }
                            });
                    } else {
                        btn
                            .addClass('btn-outline-success')
                            .click(addMemberHandler(user, btn));
                    }

                    $('#am-list').append(btn);
                });
            });
        });

        function addMemberHandler(user, btn) {
            return function() {
                $.post('{{ route('account-maanger.member') }}', {
                    am_id: user.id,
                    manager_id: selectedManagerId,
                    _token: '{{ csrf_token() }}'
                }, res => {
                    if (res.status === 'success') {
                        btn.removeClass('btn-outline-success')
                            .addClass('btn-success')
                            .text(user.name + ' ✅')
                            .off('click') // remove old click handler
                            .click(() => {
                                if (confirm(`Remove ${user.name} from team?`)) {
                                    $.ajax({
                                        url: '{{ route('account-maanger.remove-member') }}',
                                        method: 'DELETE',
                                        data: {
                                            am_id: user.id,
                                            manager_id: selectedManagerId,
                                            _token: '{{ csrf_token() }}'
                                        },
                                        success: function(res) {
                                            if (res.status === 'success') {
                                                btn.removeClass('btn-success')
                                                    .addClass('btn-outline-success')
                                                    .text(user.name)
                                                    .off('click')
                                                    .click(addMemberHandler(user, btn));
                                            }
                                        }
                                    });
                                }
                            });
                    }
                });
            }
        }
    </script>

</x-app-layout>
