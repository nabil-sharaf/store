@extends('admin.layouts.app')
@section('page-title')
الاقسام
@endsection
@section('content')
            <!-- /.card -->
            <div class="card">
              <div class="card-header">
<!--                <h3 class="card-title float-left">الأقسام</h3>-->
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary float-left mr-2">
                        <i class="fas fa-plus mr-1"></i> إضافة قسم جديد
                    </a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered text-center">
                  <thead>
                    <tr>
                      <th >#</th>
                      <th >اسم القسم</th>
                      <th>الوصف</th>
                      <th>نوع الكاتيجوري</th>
                      <th >العمليات</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($categories as $category)
                    <tr>
                      <td>{{ $loop->iteration }}.</td>
                      <td>{{ $category->name }}</td>
                      <td>
                        {{ Str::limit($category->description, 30, ' ....') }}
                      </td>
                      <td>
                        @if($category->parent_id)
                            <span class="badge bg-gradient-gray-dark">فرعي</span>
                        @else
                            <span class="badge bg-gradient-blue">رئيسي</span>
                        @endif
                      </td>
<td>
    <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-sm btn-warning mr-1" title="عرض التفاصيل" >
        <i class="fas fa-eye "></i>
    </a>
    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-info mr-1" title="تعديل">
        <i class="fas fa-edit"></i>
    </a>
    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذا القسم؟')">
            <i class="fas fa-trash-alt"></i>
        </button>
    </form>
</td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="5">لا يوجد اقسام حاليا</td>
                        </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                {{ $categories->links('vendor.pagination.custom') }}
              </div>
            </div>
@endsection

