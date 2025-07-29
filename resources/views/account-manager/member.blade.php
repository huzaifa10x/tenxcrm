<x-app-layout>
    <x-slot name="header">
        {{ __('Account Managers') }}
    </x-slot>
    @if (auth()->user()->hasRole('ADMIN'))
        <div class="card basic-data-table container-fluid">
            <h2>Account Managers & Team Control</h2>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Manager</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($managers as $manager)
                        <tr>
                            <td>{{ $manager->name }}</td>
                            <td>
                                <button class="btn btn-primary btn-sm add-team-btn" data-manager-id="{{ $manager->id }}"
                                    data-toggle="modal" data-target="#amModal">
                                    Add Team Member
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="amModal" tabindex="-1" role="dialog" aria-labelledby="amModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Account Managers to Team</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div id="am-list" class="d-flex flex-wrap gap-3"></div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            let selectedManagerId = null;

            $(document).on('click', '.add-team-btn', function() {
                selectedManagerId = $(this).data('manager-id');
                $('#am-list').empty();

                $.get('{{ route('account-manager.users') }}', function(users) {
                    users.forEach(user => {
                        const btn = $(
                            `<button class="btn btn-outline-success m-1">${user.name}</button>`);
                        btn.click(() => {
                            $.post('{{ route('account-manager.member') }}', {
                                am_id: user.id,
                                manager_id: selectedManagerId,
                                _token: '{{ csrf_token() }}'
                            }, res => {
                                if (res.status === 'success') {
                                    btn.removeClass('btn-outline-success').addClass(
                                        'btn-success').text(user.name + ' ✅');
                                }
                            });
                        });
                        $('#am-list').append(btn);
                    });
                });
            });
        </script>
    @endif
</x-app-layout>
