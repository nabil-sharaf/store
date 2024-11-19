@extends('admin.layouts.app')
@section('page-title')
    المنتجات
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div class="d-flex flex-wrap">
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-2 mr-2">
                        <i class="fas fa-plus mr-1"></i> إضافة منتج جديد
                    </a>
{{--                    @if(auth('admin')->user()->hasAnyRole(['superAdmin']))--}}
{{--                        <button id="delete-selected" class="btn btn-danger mb-2 mr-2">--}}
{{--                            <i class="fas fa-trash-alt mr-1"></i> حذف المنتجات المحددة--}}
{{--                        </button>--}}
{{--                    @endif--}}
                    @if(auth('admin')->user()->hasAnyRole(['superAdmin','supervisor']))
                        <button id="trend-selected" class="btn btn-primary mb-2 mr-2">
                            <i class="fas fa-fire mr-1"></i> المحدد كترند
                        </button>
                        <button id="best-seller-selected" class="btn btn-success mb-2 mr-2">
                            <i class="fas fa-chart-line mr-1"></i> المحدد كالأفضل
                        </button>
                    @endif
                </div>

                <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control mr-2" placeholder="ابحث عن منتج..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- Table View for Desktop --}}
        <div class="card-body d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>#</th>
                        <th>اسم المنتج</th>
                        <th>صورة المنتج</th>
                        <th>السعر بعد الخصم</th>
                        <th>الكمية</th>
                        <th>الخصم</th>
                        <th>بداية الخصم</th>
                        <th>نهاية الخصم</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @include('admin.products.partials.table-rows')
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Card View for Mobile --}}
        <div class="card-body d-md-none">
            <div class="row">
                @include('admin.products.partials.card-rows')
            </div>
        </div>

        <div class="card-footer">
            {{ $products->appends(request()->input())->links('vendor.pagination.custom') }}
        </div>
    </div>
@endsection

@push('styles')
    <style>
        @media (max-width: 767px) {
            .card-body .card {
                margin-bottom: 15px;
            }
        }
        .action-icons {
            display: flex;
            justify-content: center; /* تمركز الأيقونات أفقياً */
            align-items: center;    /* محاذاة الأيقونات عمودياً */
            gap: 8px;               /* المسافة بين الأيقونات */
        }

        .action-icons .btn {
            padding: 4px;           /* تصغير الأزرار */
            font-size: 14px;        /* حجم الأيقونات */
            width: 32px;            /* عرض الزر */
            height: 32px;           /* ارتفاع الزر */
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 4px;     /* جعل الحواف دائرية قليلاً */
        }

        .action-icons .btn i {
            margin: 0;              /* إزالة أي مسافات إضافية */
        }

        .table.text-center, .table.text-center td, .table.text-center th {
            padding: 8px;
        }
        td .action-icons {
            display: flex;
            justify-content: center; /* لتوسيط الأيقونات أفقيًا */
            align-items: center;    /* لتوسيط الأيقونات عموديًا */
            gap: 8px;               /* المسافة بين الأيقونات */
            height: 100%;           /* تأكيد ملء ارتفاع الخلية */
        }

        td {
            vertical-align: middle; /* ضمان تمركز المحتوى عموديًا داخل الخلية */
        }

        .action-icons .btn {
            padding: 4px;           /* تصغير حجم الأزرار */
            font-size: 14px;        /* حجم النص والأيقونة */
            width: 32px;            /* عرض الزر */
            height: 32px;           /* ارتفاع الزر */
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 4px;     /* الحواف مستديرة قليلاً */
        }

    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // تحديد كل المنتجات
            $('#select-all').click(function() {
                $('.product-checkbox').prop('checked', this.checked);
            });

            // تنفيذ عملية الحذف الجماعي
            $('#delete-selected').click(function() {

                var selected = [];
                url = "{{ route('admin.products.deleteAll') }}";

                $('.product-checkbox:checked').each(function() {
                    selected.push($(this).val());
                });

                if (selected.length > 0) {
                    if (confirm('هل أنت متأكد من حذف المنتجات المحددة؟')) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "ids": selected
                            },
                            success: function(response) {
                                toastr.error(response.success);
                                $('.product-checkbox:checked').closest('tr').remove();
                            },
                            error: function(response) {
                                console.log(response);
                            }
                        });

                    }
                } else {
                    alert('لم يتم تحديد أي منتجات');
                }
            });
            $('#trend-selected').click(function() {

                var selected = [];
                url = "{{ route('admin.products.trendAll') }}";

                $('.product-checkbox:checked').each(function() {
                    selected.push($(this).val());
                });

                if (selected.length > 0) {
                    if (confirm('هل أنت متأكد من جعل المنتجات المحددة ترند؟')) {
                        $.ajax({
                            url: url,
                            type: 'PUT',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "ids": selected
                            },
                            success: function(response) {
                                toastr.success(response.success);
                                // إزالة التحديد عن جميع checkboxes
                                $('.product-checkbox:checked').prop('checked', false);
                                $('#select-all').prop('checked',false);
                            },
                            error: function(response) {
                                console.log(response);
                            }
                        });

                    }
                } else {
                    alert('لم يتم تحديد أي منتجات');
                }
            });
            $('#best-seller-selected').click(function() {

                var selected = [];
                url = "{{ route('admin.products.bestSellerAll') }}";

                $('.product-checkbox:checked').each(function() {
                    selected.push($(this).val());
                });

                if (selected.length > 0) {
                    if (confirm('هل أنت متأكد من جعل المنتجات المحددة الأفضل')) {
                        $.ajax({
                            url: url,
                            type: 'PUT',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "ids": selected
                            },
                            success: function(response) {
                                toastr.success(response.success);
                                // إزالة التحديد عن جميع checkboxes
                                $('.product-checkbox:checked').prop('checked', false);
                                $('#select-all').prop('checked',false);
                            },
                            error: function(response) {
                                toastr.error(response.error);
                            }
                        });

                    }
                } else {
                    alert('لم يتم تحديد أي منتجات');
                }
            });

            // حذف منتج منفرد
            $('.delete-product-btn').on('click', function() {
                if (confirm('هل أنت متأكد من حذف هذا المنتج؟')) {
                    var productId = $(this).data('id');
                    var url = "{{ route('admin.products.destroy', ':id') }}".replace(':id', productId);
                    var tr = $(this).closest('tr');
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            toastr.error(response.success);
                            // إزالة الصف من الجدول إذا تم الحذف بنجاح
                            tr.remove();
                        },
                        error: function(response) {
                            alert('حدث خطأ أثناء محاولة حذف المنتج');
                        }
                    });
                }
            });
        });
    </script>@endpush
