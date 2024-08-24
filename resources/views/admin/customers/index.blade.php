@extends('admin.layouts.app')

@section('page-title')
    المستخدمون
@endsection

@section('content')
    <!-- /.card -->
    <div class="card">
        <div class="card-header">
            <a href="{{ ''}}" class="btn btn-primary float-left mr-2">
                <i class="fas fa-plus mr-1"></i> إضافة مستخدم جديد
            </a>
            <button id="vip-selected" class="btn btn-secondary float-left mr-2">
                <i class="fas fa-star -alt mr-1 ml-2 mt-1 float-right"></i> إضافة vip للمستخدمين المحددين
            </button>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>البريد الإلكتروني</th>
                    <th>نوع المستخدم</th>
                    <th>تاريخ بدء VIP</th>
                    <th>نسبة الخصم</th>
                    <th>الحالة</th>
                    <th>العمليات</th>
                </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    @if($user->customer_type == 'goomla')
                        {{ $customer_type = 'جملة' }}
                    @elseif($user->customer_type == 'vip')
                        {{ $customer_type = 'vip' }}
                   @elseif($user->customer_type == 'normal')
                        {{ $customer_type = 'قطاعي' }}
                    @endif
                    <tr>
                        <td><input type="checkbox" class="user-checkbox" value="{{ $user->id }}"></td>
                        <td>{{ $loop->iteration }}.</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $customer_type}}</td>
                        <td>{{ $user->vip_start_date ? $user->vip_start_date->format('Y-m-d') : 'N/A' }}</td>
                        <td>{{ $user->discount!=0 ? $user->discount . '%' : 'لا يوجد' }}</td>
                        <td><span class="btn btn-sm {{$user->status==1?'btn-success' : 'btn-danger'}}">{{$user->status==1?'مفعل' : 'غير مفعل'}}</span></td>
                        <td>
                            <a href="#" class=" btn btn-sm btn-light mr-1" title="Make Vip" data-toggle="modal" data-target="#modal-default{{$user->id}}" data-id="{{$user->id}}">
                                <i class="fas fa-crown" style="color:darkviolet; background-color:#e2e2e2"></i>
                            </a>

                            <div class="modal fade" id="modal-default{{$user->id}}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">تمييز العميل {{$user->name}}  كـ vip</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">


                                            <div class="form-group row mt-4">
                                                <label for="start_date" class="col-sm-2 control-label">تاريخ البدء</label>
                                                <div class="col-sm-10">
                                                 <input type="date" class="form-control " name="start_date" value="">
                                                 </div>
                                            </div>
                                            <div class="form-group row mt-4">
                                                <label for="end_date" class="col-sm-2 control-label">تاريخ الانتهاء</label>
                                                <div class="col-sm-10">
                                                 <input type="date" class="form-control " name="end_date" value="">
                                                 </div>
                                            </div>

                                            <div class="form-group row mt-4">
                                                <label for="inputDiscount" class="col-sm-2 control-label">نسبة الخصم % </label>
                                                <div class="col-sm-10">
                                                    <input type="number" step="0.01" class="form-control inline-block " id="inputDiscount" placeholder="أدخل قيمة الخصم" name="discount" value="0" min="0">

                                                </div>
                                            </div>

                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">الغاء</button>
                                            <button type="button" class="vip-user-btn btn btn-primary" data-id="{{$user->id}}">تأكيد التغييرات</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>

                            <a href="{{ route('admin.customers.show', $user->id) }}" class="btn btn-sm btn-warning mr-1" title="عرض التفاصيل">
                                <i class="fas fa-eye"></i>
                            </a>

                            @if($user->status==1)
                            <a href="{{''}}" class="btn btn-sm btn-danger mr-1" title="تعطيل المستخدم">
                               <i class="fas fa-user-slash "></i>
                            </a>
                            @else
                                <a href="{{''}}" class="btn btn-sm btn-info mr-1" title="تفعيل المستخدم">
                                    <i class="fas fa-user " ></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">لا يوجد مستخدمين حاليا</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            {{ $users->links('vendor.pagination.custom') }}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // تحديد كل المستخدمين
            $('#select-all').click(function() {
                $('.user-checkbox').prop('checked', this.checked);
            });

            // تنفيذ عملية vip الجماعية
            $('#vip-selected').click(function() {

                var selected = [];
                url = "{{ route('admin.customers.vipAll') }}";

                $('.user-checkbox:checked').each(function() {
                    selected.push($(this).val());
                });

                if (selected.length > 0) {
                    if (confirm('هل أنت متأكد من اضافة vip المستخدمين المحددين؟')) {
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
                    alert('لم يتم تحديد أي مستخدمين');
                }
            });

            // make customer vip
            $('.vip-user-btn').on('click', function() {

                    var userId = $(this).data('id');
                    var url = "{{ route('admin.customers.vip-customer', ':id') }}".replace(':id', userId);
                    // var tr = $(this).closest('tr');
                    $.ajax({
                        url: url,
                        type: 'PUT',
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            // toastr.success(response.success);
                            console.log(response)
                        },

                        error: function(response) {
                            console.log(response)
                            alert('حدث خطأ أثناء التعديل');
                        }
                    });
            });
        });
    </script>
@endpush
