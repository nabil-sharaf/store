@extends('admin.layouts.app')
@section('page-title')
    المنتجات
@endsection
@section('content')
    <!-- /.card -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-start">
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-2 mr-2">
                    <i class="fas fa-plus mr-1"></i> إضافة منتج جديد
                </a>
                @if(auth('admin')->user()->hasAnyRole(['superAdmin']))
                    <button id="delete-selected" class="btn btn-danger mb-2 mr-2">
                        <i class="fas fa-trash-alt mr-1 ml-2 mt-1 float-right"></i> حذف المنتجات المحددة
                    </button>
                    <button id="trend-selected" class="btn btn-primary mb-2 mr-2">
                        <i class="fas fa-fire mr-1"></i> المحدد كترند
                    </button>
                    <button id="best-seller-selected" class="btn btn-success mb-2 mr-2">
                        <i class="fas fa-chart-line mr-1"></i> المحدد كالأفضل
                    </button>
                @endif
            </div>

            <!-- مربع البحث -->
            <form action="{{ route('admin.products.index') }}" method="GET" class="form-inline ml-auto">
                <div class="input-group mb-2">
                    <input type="text" name="search" class="form-control" placeholder="ابحث عن منتج..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.card-header -->
        <!-- .card-body -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center table-responsive-md">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>#</th>
                        <th>اسم المنتج</th>
                        <th>صورة المنتج</th>
                        <th>سعر المنتج</th>
                        <th>الكمية</th>
                        <th>الخصم</th>
                        <th class="d-none d-md-table-cell">بداية الخصم</th>
                        <th class="d-none d-md-table-cell">نهاية الخصم</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td><input type="checkbox" class="product-checkbox" value="{{ $product->id }}"></td>
                            <td>{{ $loop->iteration }}.</td>
                            <td>{{ $product->name }}</td>
                            <td>
                                @if($product->images->first())
                                    <img src="{{ asset('storage/' . $product->images->first()->path) }}" alt="{{ $product->name }}" class="img-fluid" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <span>لا توجد صورة</span>
                                @endif
                            </td>
                            <td>{{ $product->price }} ج</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ $product->discount ? $product->discount->discount . ($product->discount->discount_type == 'percentage' ? '%' : ' ج') : 'لا يوجد' }}</td>
                            <td class="d-none d-md-table-cell">{{ $product->discount?->start_date ? $product->discount->start_date->format('Y-m-d') : 'N/A' }}</td>
                            <td class="d-none d-md-table-cell">{{ $product->discount?->end_date ? $product->discount->end_date->format('Y-m-d') : 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-warning mb-1">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-info mb-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(auth('admin')->user()->hasAnyRole(['superAdmin']))
                                    <button type="button" class="btn btn-sm btn-danger delete-product-btn mb-1" data-id="{{ $product->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">لا يوجد منتجات حاليا</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            {{ $products->appends(request()->input())->links('vendor.pagination.custom') }}
        </div>
    </div>
@endsection

@push('styles')

    <style>
        .table-responsive-md {
            overflow-x: auto;
            /* تحسين التمرير الأفقي على الشاشات الصغيرة */
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
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
    </script>
@endpush
