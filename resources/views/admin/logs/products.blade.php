@extends('admin.layouts.app')

@section('content')
    <h1>سجلات تعديلات المنتج/الفاريانت</h1>

    <div class="row">
        @foreach($logs as $log)
            <div class="col-12 col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        @php
                            $modelName = class_basename($log->auditable_type);
                            $skuCode = 'غير متوفر';

                            if ($log->auditable_type == 'App\Models\Admin\Product') {
                                $product = \App\Models\Admin\Product::find($log->auditable_id)?->first();
                                if ($product) {
                                    $skuCode = $product->sku_code;
                                }
                            } elseif ($log->auditable_type == 'App\Models\Admin\Variant') {
                                $variant = \App\Models\Admin\Variant::find($log->auditable_id)?->first();
                                if ($variant) {
                                    $skuCode = $variant->sku_code;
                                }
                            }
                        @endphp
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">{{ $modelName }}</h5>
                                <div class="text-muted small">{{ $skuCode }}</div>
                            </div>
                            <div>
                                @if($log->event == 'created')
                                    <span class="badge bg-success"><i class="fas fa-plus-circle"></i> إنشاء</span>
                                @elseif($log->event == 'updated')
                                    <span class="badge bg-warning"><i class="fas fa-edit"></i> تعديل</span>
                                @elseif($log->event == 'deleted')
                                    <span class="badge bg-danger"><i class="fas fa-trash-alt"></i> حذف</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>المستخدم:</strong> {{ $log->user->email ?? 'نظام' }}
                        </div>

                        <div class="changes-container">
                            @foreach($log->old_values as $key => $oldValue)
                                @php
                                    $newValue = $log->new_values[$key] ?? '—';
                                    $isChanged = $oldValue != $newValue;
                                @endphp
                                <div class="change-item mb-3">
                                    <div class="fw-bold mb-2">{{ __('fields.' . $key) }}</div>
                                    <div class="change-values">
                                        <div class="d-flex justify-content-between p-2 bg-light rounded mb-1">
                                            <span class="text-muted">القديم:</span>
                                            <span class="{{ $isChanged ? 'text-danger' : '' }}">{{ $oldValue }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between p-2 bg-light rounded">
                                            <span class="text-muted">الجديد:</span>
                                            <span class="{{ $isChanged ? 'text-success' : '' }}">{{ $newValue }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-muted small mt-3">
                            <i class="far fa-clock"></i> {{ $log->created_at->format('Y-m-d H:i:s') }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center mt-4">
        {{ $logs->appends(request()->input())->links('vendor.pagination.custom') }}
    </div>
@endsection

@push('styles')
    <style>
        .card {
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            padding: 1rem;
        }

        .card-body {
            padding: 1.25rem;
        }

        .change-item {
            border-bottom: 1px solid #eee;
            padding-bottom: 1rem;
        }

        .change-item:last-child {
            border-bottom: none;
        }

        .change-values {
            background: white;
            border-radius: 6px;
        }

        .badge {
            padding: 0.5em 0.8em;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge i {
            margin-left: 0.3rem;
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .text-success {
            color: #198754 !important;
        }

        .text-muted {
            color: #6c757d !important;
        }

        /* تحسينات التجاوب */
        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }

            .change-item {
                margin-bottom: 0.75rem;
                padding-bottom: 0.75rem;
            }
        }
    </style>
@endpush
