@extends('admin.layouts.app')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger pt-2 pb-0">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <div class="container-fluid px-4">
        {{-- Header Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 py-3">
                    <h1 class="h3 mb-0 text-primary fw-bold">
                        <i class="fas fa-edit me-2"></i>تعديل الأوبشن
                    </h1>
                    <a href="{{ route('admin.options.index') }}" class="btn btn-secondary px-4">
                        <i class="fas fa-arrow-right me-2"></i>عودة للخيارات
                    </a>
                </div>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="row">
            <div class="col-12 col-lg-8 col-xl-7 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.options.update', $option) }}" method="POST" id="optionForm">
                            @csrf
                            @method('PUT')

                            {{-- Option Names --}}
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="card-title mb-3 text-primary">
                                        <i class="fas fa-font me-2"></i>معلومات الأوبشن
                                    </h5>
                                </div>
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="name_ar" class="form-label">الاسم (عربي)</label>
                                    <input type="text"
                                           class="form-control"
                                           id="name_ar"
                                           name="name[ar]"
                                           value="{{ $option->getTranslation('name', 'ar') }}"
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <label for="name_en" class="form-label">الاسم (إنجليزي)</label>
                                    <input type="text"
                                           class="form-control"
                                           id="name_en"
                                           name="name[en]"
                                           value="{{ $option->getTranslation('name', 'en') }}"
                                           required
                                        {{ in_array(strtolower($option->getTranslation('name', 'en')), ['color', 'dimension']) ? 'readonly' : '' }}
                                    >
                                </div>
                            </div>

                            {{-- Option Values --}}
                            <div class="values-section">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0 text-primary">
                                        <i class="fas fa-list me-2"></i>قيم الأوبشن
                                    </h5>
                                    <button type="button"
                                            id="addValue"
                                            class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus me-2"></i>إضافة قيمة
                                    </button>
                                </div>

                                <div class="value-fields">
                                    @foreach ($option->optionValues as $index => $value)
                                        <div class="value-group">
                                            <input type="hidden" name="values[{{ $index }}][id]" value="{{ $value->id }}">
                                            <div class="card bg-light border-0 mb-3">
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-muted small">القيمة #{{ $index + 1 }}</span>
                                                        <button type="button" class="btn btn-link text-danger p-0 delete-value">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row gx-2">
                                                        <div class="col-md-6 mb-2 mb-md-0">
                                                            <input type="text" class="form-control form-control-sm"
                                                                   name="values[{{ $index }}][ar]"
                                                                   value="{{ $value->getTranslation('value', 'ar') }}"
                                                                   placeholder="القيمة (عربي)" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control form-control-sm"
                                                                   name="values[{{ $index }}][en]"
                                                                   value="{{ $value->getTranslation('value', 'en') }}"
                                                                   placeholder="القيمة (إنجليزي)" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <hr class="my-4">
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="fas fa-save me-2"></i>حفظ التعديلات
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .value-group {
            position: relative;
        }

        .delete-value {
            transition: all 0.2s ease;
        }

        .delete-value:hover {
            transform: scale(1.1);
        }

        .card {
            transition: all 0.2s ease;
        }

        .value-group:hover .delete-value {
            visibility: visible !important;
        }

        @media (max-width: 767px) {
            .delete-value {
                visibility: visible !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let valueIndex = {{ count($option->optionValues) }};
            const valueFields = document.querySelector('.value-fields');

            document.getElementById('addValue').addEventListener('click', function() {
                const newGroup = document.createElement('div');
                newGroup.classList.add('value-group');
                newGroup.innerHTML = `
            <div class="card bg-light border-0 mb-3">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">القيمة #${valueIndex + 1}</span>
                        <button type="button" class="btn btn-link text-danger p-0 delete-value">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="row gx-2">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <input type="text" class="form-control form-control-sm"
                                   name="values[${valueIndex}][ar]"
                                   placeholder="القيمة (عربي)" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control form-control-sm"
                                   name="values[${valueIndex}][en]"
                                   placeholder="القيمة (إنجليزي)" required>
                        </div>
                    </div>
                </div>
            </div>
        `;
                valueFields.appendChild(newGroup);
                valueIndex++;
                addDeleteHandler(newGroup.querySelector('.delete-value'));
            });

            function addDeleteHandler(button) {
                button.addEventListener('click', function() {
                    const valueGroup = this.closest('.value-group');
                    valueGroup.remove();
                });
            }

            document.querySelectorAll('.delete-value').forEach(btn => {
                addDeleteHandler(btn);
            });
        });
    </script>
@endpush