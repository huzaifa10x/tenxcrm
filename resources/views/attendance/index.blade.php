<x-app-layout>
    <x-slot name="header">
        {{ __('Monthly Attendance') }}
    </x-slot>

    @if (auth()->user()->hasRole('ADMIN'))
        <div class="card shadow-sm">
            <div class="card-header">
                <!-- Filters -->
                <form method="GET" action="{{ route('admin.attendance') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Select User</label>
                            <select name="user_id" class="form-control">
                                <option value="">All Users</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>From Date</label>
                            <input type="date" name="from_date" class="form-control"
                                value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label>To Date</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-3 d-flex gap-10 align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                            <a href="{{ route('admin.attendance') }}" class="btn btn-secondary w-100 ml-2">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body p-0">
                <!-- Attendance Records -->
                @if ($attendances->count())
                    <div class="accordion border" id="attendanceAccordion">
                        @php $currentMonth = null; @endphp
                        @foreach ($attendances->groupBy(function ($item) {
        return \Carbon\Carbon::parse($item->date)->format('F Y');
    }) as $month => $records)
                            <div class="card mb-3">
                                <div class="card-header" id="heading-{{ Str::slug($month) }}">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                            data-target="#collapse-{{ Str::slug($month) }}">
                                            📅 {{ $month }} ({{ count($records) }} Records)
                                        </button>
                                    </h5>
                                </div>

                                <div id="collapse-{{ Str::slug($month) }}" class="collapse show">
                                    <div class="card-body">
                                        @foreach ($records->groupBy('date') as $day => $dayRecords)
                                            <div class="p-2 bg-light mb-2">
                                                <strong>🗓
                                                    {{ \Carbon\Carbon::parse($day)->format('l, d M Y') }}</strong>
                                            </div>
                                            <table class="table table-bordered mb-4">
                                                <thead>
                                                    <tr class="bg-dark text-white">
                                                        <th>User</th>
                                                        <th>Punch In</th>
                                                        <th>Punch Out</th>
                                                        <th>Total Hours</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($dayRecords as $attendance)
                                                        <tr>
                                                            <td>{{ $attendance->user->name }}</td>
                                                            <td>{{ $attendance->punch_in }}</td>
                                                            <td>{{ $attendance->punch_out ?? 'N/A' }}</td>
                                                            <td>{{ $attendance->total_hours ?? 'N/A' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-warning">No attendance records found for the selected filters.</div>
                @endif
            </div>
        </div>
    @else
        @include('vendor.not-allowed')
    @endif
</x-app-layout>
