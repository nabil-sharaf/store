@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">سجلات النظام</h1>
            <button id="delete-all-logs" class="btn btn-danger">
                <i class="fas fa-trash-alt me-2"></i> حذف جميع السجلات
            </button>
        </div>
        <div class="row">
            @foreach($logs as $log)
                <div class="col-12 col-lg-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <!-- رأس الكارد -->
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="d-block mb-1">{{ $log->user->email ?? 'نظام' }}</strong>
                                    <small class="text-muted">{{ $log->created_at->format('Y-m-d H:i:s') }}</small>
                                </div>
                                <div>
                                    @if($log->event == 'created')
                                        <span class="badge bg-success"><i class="fas fa-plus-circle me-1"></i> إنشاء</span>
                                    @elseif($log->event == 'updated')
                                        <span class="badge bg-warning"><i class="fas fa-edit me-1"></i> تعديل</span>
                                    @elseif($log->event == 'deleted')
                                        <span class="badge bg-danger"><i class="fas fa-trash-alt me-1"></i> حذف</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                <strong class="text-muted">{{ class_basename($log->auditable_type) }}</strong>
                            </div>
                        </div>

                        <!-- محتوى الكارد -->
                        <div class="card-body p-0">
                            <!-- البيانات القديمة -->
                            @if(count($log->old_values))
                                <div class="p-3">
                                    <p class="text-danger text-center mb-3"><strong>البيانات القديمة</strong></p>
                                    <div class="bg-light rounded-3 border">
                                        @foreach($log->old_values as $key => $oldValue)
                                            <div class="row mx-0 py-2 align-items-center {{ !$loop->last ? 'border-bottom' : '' }}">
                                                <div class="col-6 text-end">
                                                    <span class="text-muted">{{ __('fields.' . $key) }}</span>
                                                </div>
                                                <div class="col-6 text-center">
                                                    <strong>{{ $oldValue ?: '--' }}</strong>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- البيانات الجديدة -->
                            @if(count($log->new_values))
                                <div class="p-3 {{ count($log->old_values) ? 'border-top' : '' }}">
                                    <p class="text-success text-center mb-3"><strong>البيانات الجديدة</strong></p>
                                    <div class="bg-light rounded-3 border">
                                        @foreach($log->new_values as $key => $newValue)
                                            <div class="row mx-0 py-2 align-items-center {{ !$loop->last ? 'border-bottom' : '' }}">
                                                <div class="col-6 text-end">
                                                    <span class="text-muted">{{ __('fields.' . $key) }}</span>
                                                </div>
                                                <div class="col-6 text-center">
                                                    <strong>{{ $newValue ?: '--' }}</strong>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center mt-2 mb-4">
            {{ $logs->appends(request()->input())->links('vendor.pagination.custom') }}
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* تنسيقات عامة */
        .card {
            border: none;
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1rem;
        }

        .badge {
            padding: 0.5em 0.7em;
        }

        /* تنسيقات المحتوى */
        .bg-light {
            background-color: #f8f9fa !important;
        }

        .border-bottom {
            border-bottom: 1px solid rgba(0,0,0,0.05) !important;
        }

        .border-top {
            border-top: 1px solid rgba(0,0,0,0.05) !important;
        }

        /* تنسيقات النصوص */
        .text-muted {
            color: #6c757d !important;
        }

        .text-success {
            color: #198754 !important;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        /* تحسينات للموبايل */
        @media (max-width: 767.98px) {
            .card-header {
                padding: 0.75rem;
            }

            .py-2 {
                padding-top: 0.4rem !important;
                padding-bottom: 0.4rem !important;
            }

            .p-3 {
                padding: 0.75rem !important;
            }
        }

        /* تنسيقات إضافية */
        .rounded-3 {
            border-radius: 0.5rem !important;
        }

        .border {
            border: 1px solid rgba(0,0,0,0.1) !important;
        }

        /* تحسين عرض القيم */
        .col-6 strong {
            display: inline-block;
            width: 100%;
            text-align: center;
        }

        /* تحسين المسافات بين الكروت */
        .mb-4 {
            margin-bottom: 1.5rem !important;
        }

        /* تحسين شكل البادج */
        .badge i {
            margin-right: 0.25rem;
        }

        /* تحسين الظل */
        .shadow-sm {
            box-shadow: 0 .125rem .25rem rgba(0,0,0,.075) !important;
        }
    </style>
@endpush
@push('scripts')
    <script>
        document.getElementById('delete-all-logs').addEventListener('click', function () {
            if (confirm('هل أنت متأكد من حذف جميع السجلات؟')) {
                fetch('{{ route("admin.logs.deleteAll") }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('تم حذف جميع السجلات بنجاح.');
                            location.reload();
                        } else {
                            alert('حدث خطأ أثناء عملية الحذف.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('حدث خطأ غير متوقع.');
                    });
            }
        });
    </script>
@endpush
