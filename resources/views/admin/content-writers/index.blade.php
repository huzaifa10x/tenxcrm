<x-app-layout>
    <x-slot name="header">
        {{ __('Content Writers Performance') }}
    </x-slot>

    <div class="mb-4">
        <div class="card">
            <div class="text-white card-header bg-primary">
                <h5 class="mb-0">
                    <i class="bi bi-people-fill me-2"></i>Content Writers Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    @foreach ($contentWriters as $writer)
                        <div class="col-md-6 col-lg-4">
                            <div class="border-0 shadow-sm card h-100 hover-shadow">
                                <div class="card-body">
                                    <div class="mb-3 d-flex align-items-center">
                                        <div class="text-white avatar-circle bg-primary me-3">
                                            {{ strtoupper(substr($writer->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $writer->name }}</h6>
                                            <small class="text-muted">{{ $writer->email }}</small>
                                        </div>
                                    </div>

                                    <div class="mb-3 row g-2">
                                        <div class="col-4">
                                            <div class="p-2 text-center rounded bg-light">
                                                <div class="fs-5 fw-bold text-primary">{{ $writer->total_tasks }}</div>
                                                <small class="text-muted">Total</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="p-2 text-center rounded bg-light">
                                                <div class="fs-5 fw-bold text-success">{{ $writer->completed_tasks }}
                                                </div>
                                                <small class="text-muted">Done</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="p-2 text-center rounded bg-light">
                                                <div class="fs-5 fw-bold text-warning">{{ $writer->pending_tasks }}
                                                </div>
                                                <small class="text-muted">Pending</small>
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                        $completionRate =
                                            $writer->total_tasks > 0
                                                ? round(($writer->completed_tasks / $writer->total_tasks) * 100)
                                                : 0;
                                    @endphp

                                    <div class="mb-3">
                                        <div class="mb-1 d-flex justify-content-between">
                                            <small class="text-muted">Completion Rate</small>
                                            <small class="fw-bold">{{ $completionRate }}%</small>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: {{ $completionRate }}%"></div>
                                        </div>
                                    </div>

                                    <a href="{{ route('admin.content-writers.show', $writer->id) }}"
                                        class="btn btn-primary btn-sm w-100">
                                        <i class="bi bi-bar-chart-line me-1"></i>View Detailed Analytics
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <style>
        .avatar-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: bold;
        }

        .hover-shadow {
            transition: all 0.3s ease;
        }

        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }
    </style>
</x-app-layout>
