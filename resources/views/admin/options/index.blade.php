@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid px-4">
        {{-- Header Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 py-3">
                    <h1 class="h3 mb-0 text-primary fw-bold">
                        <i class="fas fa-cogs me-2 ml-1"></i>&nbsp; إدارة الخيارات
                    </h1>
                    <a href="{{ route('admin.options.create') }}" class="btn btn-success px-4 add-new-option">
                        <i class="fas fa-plus-circle me-2 "></i>&nbsp;&nbsp; إضافة أوبشن جديد
                    </a>
                </div>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-2 fs-5"></i>
                    <span class="fs-6">{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Options Cards --}}
        <div class="row g-4">
            @foreach($options as $option)
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-1">{{ $option->getTranslation('name', 'ar') }}</h5>
                                    <small class="text-muted ml-1">{{ $option->getTranslation('name', 'en') }}</small>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.options.edit', $option) }}"
                                       class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(!in_array(strtolower($option->getTranslation('name', 'en')), ['color', 'dimension']))
                                        <form action="{{ route('admin.options.destroy', $option) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-danger btn-sm mr-1"
                                                    onclick="return confirm('هل أنت متأكد من حذف هذا الخيار وجميع قيمه؟')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="values-list">
                               @foreach($option->optionValues as $value)
                                    <div class="value-item">
                                        <div class="value-content">
                                            <i class="fas fa-tag text-info me-2"></i>
                                            <span class="arabic-value">{{ $value->getTranslation('value', 'ar') }}</span>
                                            <span class="separator mx-2">/</span>
                                            <span class="english-value">{{ $value->getTranslation('value', 'en') }}</span>
                                        </div>
                                        <div class="value-actions">
                                            <button type="button"
                                                    class="btn btn-link text-primary p-0 me-2 edit-value-btn"
                                                    data-toggle="modal"
                                                    data-target="#editValueModal_{{ $value->id }}"
                                                    title="تعديل القيمة">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.option-values.destroy', $value->id) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-link text-danger p-0 mr-1 delete-value-btn"
                                                        title="حذف القيمة"
                                                        onclick="return confirm('هل أنت متأكد من حذف هذه القيمة؟')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    {{-- Edit Value Modal --}}
                                    <div class="modal fade"
                                         id="editValueModal_{{ $value->id }}"
                                         tabindex="-1"
                                         aria-labelledby="editValueModalLabel_{{ $value->id }}"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.option-values.update', $value->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editValueModalLabel_{{ $value->id }}">تعديل القيمة</h5>
                                                        <button type="button" class="btn-close ms-0 me-2" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">القيمة (عربي)</label>
                                                            <input type="text"
                                                                   name="value[ar]"
                                                                   class="form-control"
                                                                   value="{{ $value->getTranslation('value', 'ar') }}"
                                                                   required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">القيمة (إنجليزي)</label>
                                                            <input type="text"
                                                                   name="value[en]"
                                                                   class="form-control"
                                                                   value="{{ $value->getTranslation('value', 'en') }}"
                                                                   required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Custom Styles */
        .values-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .value-item {
            background-color: #f8f9fa;
            border-radius: 0.375rem;
            padding: 0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s ease;
        }

        .value-item:hover {
            background-color: #e9ecef;
        }

        .value-content {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .value-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .edit-value-btn:hover,
        .delete-value-btn:hover {
            transform: scale(1.1);
        }

        @media (max-width: 576px) {
            .value-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .value-content {
                width: 100%;
            }

            .value-actions {
                width: 100%;
                justify-content: flex-end;
            }

            .separator {
                display: none;
            }

            .english-value {
                width: 100%;
                color: #6c757d;
            }

        }

        /* RTL Modal Fixes */
        .modal-header .btn-close {
            margin: 0;
        }

        .modal-footer {
            justify-content: flex-start;
        }
        @media (max-width: 767px) {
            .add-new-option{
                margin-top: 10px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
@endpush
