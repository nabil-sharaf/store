@extends('admin.layouts.app')
@section('page-title')
المنتجات
@endsection
@section('content')
            <!-- /.card -->
            <div class="card">
              <div class="card-header">
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary float-left mr-2">
                    <i class="fas fa-plus mr-1"></i> إضافة منتج جديد
                </a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered text-center">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>اسم المنتج</th>
                      <th>الوصف</th>
                      <th>سعر المنتج</th>
                      <th>الكمية</th>
                      <th>صورة المنتج</th>
                      <th>القسم</th>
                      <th>العمليات</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($products as $product)
                    <tr>
                      <td>{{ $loop->iteration }}.</td>
                      <td>{{ $product->name }}</td>
                      <td>
                        {{ Str::limit($product->description, 10, ' ....') }}
                      </td>
                      <td>{{ $product->price }}</td>
                      <td>{{ $product->quantity }}</td>
                      <td>
                        @if($product->images->first())
                            <img src="{{ asset('storage/' . $product->images->first()->path) }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            <span>لا توجد صورة</span>
                        @endif
                      </td>
                      <td>
                        {{ STR::limit($product->categories->pluck('name')->implode(', '),14,'.....') }}
                      </td>
                      <td>
                        <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-warning mr-1" title="عرض التفاصيل">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-info mr-1" title="تعديل">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                {{ $products->links('vendor.pagination.custom') }}
              </div>
            </div>
@endsection
