<x-app-layout>
    @if (auth()->user()->hasRole('ADMIN'))
        <x-slot name="header">
            {{ __('System Setting') }}
        </x-slot>
        @include('attendance.partials.assets')

        @php
            $t = fn ($v, $d) => $v ? substr($v, 0, 5) : $d;
            $workingDays = $settings->working_days ?? [1, 2, 3, 4, 5];
            $holidays = collect($settings->holidays ?? [])->map(fn ($h) => trim((string) $h, " \"'[]"))->filter()->sort()->values();
        @endphp

        <div class="xa">
            @include('attendance.partials.tabs', ['active' => 'settings'])

            <div class="xa-head">
                <div>
                    <h1 class="xa-title">Attendance settings</h1>
                    <div class="xa-sub">Used for punch-in rules, late / half-day / early-out status and all attendance reports. Times are Asia/Dubai.</div>
                </div>
            </div>

            <form id="settingsForm" novalidate>
                @csrf
                <div class="xa-grid xa-grid-2 mb-3">
                    <div class="xa-card">
                        <div class="xa-card-head"><h2 class="xa-card-title">Working week &amp; hours</h2></div>
                        <div class="xa-card-body xa-stack">
                            <div class="xa-field">
                                <span class="xa-label">Working days</span>
                                <div class="xa-days" role="group" aria-label="Working days">
                                    @foreach ([1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 7 => 'Sun'] as $d => $label)
                                        <label><input type="checkbox" name="working_days[]" value="{{ $d }}" @checked(in_array($d, $workingDays))><span>{{ $label }}</span></label>
                                    @endforeach
                                </div>
                                <div class="xa-help">Other days are weekends: no punch-in, not counted as absences or leave.</div>
                            </div>
                            <div class="xa-form-row">
                                <div class="xa-field">
                                    <label for="sOpen">Office opens</label>
                                    <input type="time" id="sOpen" name="office_open_time" value="{{ $t($settings->office_open_time ?? null, '09:00') }}" class="xa-input" required>
                                </div>
                                <div class="xa-field">
                                    <label for="sClose">Office closes</label>
                                    <input type="time" id="sClose" name="office_close_time" value="{{ $t($settings->office_close_time ?? null, '18:00') }}" class="xa-input" required>
                                    <div class="xa-help">Missed punch-outs are closed at this time.</div>
                                </div>
                            </div>
                            <div class="xa-form-row">
                                <div class="xa-field">
                                    <label for="sEarly">Early punch-in window (min)</label>
                                    <input type="number" id="sEarly" name="early_punch_in_minutes" min="0" max="240" value="{{ $settings->early_punch_in_minutes ?? 15 }}" class="xa-input">
                                    <div class="xa-help">Early punches are recorded at opening time.</div>
                                </div>
                                <div class="xa-field">
                                    <label for="sBreak">Break ends</label>
                                    <input type="time" id="sBreak" name="break_end_time" value="{{ $t($settings->break_end_time ?? null, '14:00') }}" class="xa-input" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="xa-card">
                        <div class="xa-card-head"><h2 class="xa-card-title">Late, half-day &amp; early-out rules</h2></div>
                        <div class="xa-card-body xa-stack">
                            <div class="xa-form-row">
                                <div class="xa-field">
                                    <label for="sGrace">Grace period (min)</label>
                                    <input type="number" id="sGrace" name="late_threshold" min="0" max="240" value="{{ $settings->late_threshold ?? 15 }}" class="xa-input" required>
                                    <div class="xa-help">Late = after opening + this. Late minutes count from opening time.</div>
                                </div>
                                <div class="xa-field">
                                    <label for="sHalf">Half day if in after</label>
                                    <input type="time" id="sHalf" name="half_day_threshold" value="{{ $t($settings->half_day_threshold ?? null, '12:00') }}" class="xa-input" required>
                                </div>
                            </div>
                            <div class="xa-form-row">
                                <div class="xa-field">
                                    <label for="sOut">Early out if out before</label>
                                    <input type="time" id="sOut" name="early_out_threshold" value="{{ $t($settings->early_out_threshold ?? ($settings->office_close_time ?? null), '18:00') }}" class="xa-input" required>
                                </div>
                                <div></div>
                            </div>
                            <div class="xa-form-row">
                                <div class="xa-field">
                                    <label for="sLateDed">Late arrivals per deduction</label>
                                    <input type="number" id="sLateDed" name="late_deduction_threshold" min="1" max="31" value="{{ $settings->late_deduction_threshold ?? 3 }}" class="xa-input" required>
                                </div>
                                <div class="xa-field">
                                    <label for="sHalfDed">Half days per deduction</label>
                                    <input type="number" id="sHalfDed" name="half_day_deduction_threshold" min="1" max="31" value="{{ $settings->half_day_deduction_threshold ?? 3 }}" class="xa-input" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="xa-grid xa-grid-2 mb-3">
                    <div class="xa-card">
                        <div class="xa-card-head"><h2 class="xa-card-title">Public holidays</h2><span class="xa-card-note">{{ $holidays->count() }} saved</span></div>
                        <div class="xa-card-body">
                            <div class="d-flex gap-2">
                                <input type="date" id="holidayPick" class="xa-input" aria-label="Holiday date">
                                <button type="button" class="xa-btn xa-btn-dark" id="holidayAdd">Add</button>
                            </div>
                            <input type="hidden" name="holidays" id="holidaysField" value="{{ $holidays->implode(',') }}">
                            <div class="xa-tags" id="holidayTags"></div>
                            <div class="xa-help mt-2">Holidays aren't working days: no punch-in, no absence, and not deducted from leave.</div>
                        </div>
                    </div>
                    <div class="xa-card">
                        <div class="xa-card-head"><h2 class="xa-card-title">Leave</h2></div>
                        <div class="xa-card-body xa-stack">
                            <div>Annual allowance: <strong>{{ $settings->annual_leave_allowance ?? 12 }} days</strong> per employee, leave year starts <strong>1 {{ \Carbon\Carbon::create(2000, $settings->leave_year_start_month ?? 1, 1)->format('F') }}</strong>.</div>
                            <div class="xa-help">Leave types, allowances, carry-forward and balances are managed on the Leave policies page. Approvers need the <em>can approve leaves</em> permission (Roles).</div>
                            <div><a class="xa-btn" href="{{ route('leaves.policies') }}">Open leave policies</a></div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="xa-btn xa-btn-primary">Save settings</button>
                </div>
            </form>
            <div id="responseMessage" class="mt-2"></div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.getElementById('settingsForm');
                const field = document.getElementById('holidaysField');
                const tags = document.getElementById('holidayTags');
                const today = new Date().toISOString().slice(0, 10);
                let list = field.value ? field.value.split(',').filter(Boolean) : [];

                function render() {
                    list = Array.from(new Set(list)).sort();
                    field.value = list.join(',');
                    tags.innerHTML = list.length ? list.map(d => {
                        const label = new Date(d + 'T00:00:00').toLocaleDateString('en-GB', { weekday: 'short', day: '2-digit', month: 'short', year: 'numeric' });
                        return '<span class="xa-tag ' + (d < today ? 'is-past' : '') + '">' + XA.esc(label) + '<button type="button" data-del="' + d + '" aria-label="Remove ' + XA.esc(label) + '">×</button></span>';
                    }).join('') : '<span class="xa-muted" style="font-size:.82rem">No holidays added.</span>';
                }
                render();
                document.getElementById('holidayAdd').addEventListener('click', () => {
                    const v = document.getElementById('holidayPick').value;
                    if (v) { list.push(v); render(); document.getElementById('holidayPick').value = ''; }
                });
                tags.addEventListener('click', (e) => {
                    const b = e.target.closest('[data-del]');
                    if (b) { list = list.filter(d => d !== b.dataset.del); render(); }
                });

                form.addEventListener('submit', async function (event) {
                    event.preventDefault();
                    if (!form.querySelector('[name="working_days[]"]:checked')) {
                        XA.toast('error', 'Pick at least one working day.');
                        return;
                    }
                    const btn = form.querySelector('[type="submit"]');
                    XA.showErrors(form, {});
                    XA.busy(btn, true);
                    try {
                        const data = await XA.request(@json(route('settings.update')), { body: new FormData(form) });
                        XA.toast('success', data.success);
                    } catch (err) {
                        XA.showErrors(form, err.errors);
                    } finally {
                        XA.busy(btn, false);
                    }
                });
            });
        </script>
    @else
        @include('vendor.not-allowed')
    @endif
</x-app-layout>
