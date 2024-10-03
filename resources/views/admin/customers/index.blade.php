@extends('admin.layouts.app')

@section('page-title')
    المستخدمون
@endsection

@section('content')
    <!-- /.card -->
    <div class="card">
        <div class="card-header">
            <button id="vip-selected-btn" class="btn btn-secondary mb-2" data-toggle="modal" data-target="#modal-default-all">
                <i class="fas fa-star-alt mr-1 ml-2 mt-1"></i> إضافة VIP للمستخدمين المحددين
            </button>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered ">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>رقم الموبايل</th>
                        <th>نوع المستخدم</th>
                        <th>تاريخ انتهاء VIP</th>
                        <th>نسبة الخصم</th>
                        <th>الحالة</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                        @php
                            $customer_type = $user->customer_type == 'goomla' ? 'جملة' : 'قطاعي';
                        @endphp
                        <tr id="customer-data-{{$user->id}}" style="background-color:{{$user->status ? '#fff':'#ccc'}}">
                            <td data-label="اختيار"><input type="checkbox" class="user-checkbox" value="{{ $user->id }}"></td>
                            <td data-label="#">{{ $loop->iteration }}.</td>
                            <td data-label="الاسم" id="user-name-{{$user->id}}">{{ $user->name }}</td>
                            <td data-label="رقم الموبايل">{{ $user->phone }}</td>
                            <td data-label="نوع المستخدم" id="user-type-{{$user->id}}">
                                {{$customer_type}}
                                @if($user->isVip())
                                    <i class="fas fa-crown" style="color:darkviolet;"></i>
                                @endif
                            </td>
                            <td data-label="تاريخ انتهاء VIP" id="vip-end-date-{{$user->id}}" class="{{ $user->isVip() ? 'show-vip' : 'hide-vip' }}">
                                {{ $user->isVip() && $user->vip_end_date ? $user->vip_end_date->format('Y-m-d') : 'N/A' }}
                            </td>
                            <td data-label="نسبة الخصم" id="user-discount-{{$user->id}}" class="{{ $user->isVip() ? 'show-vip' : 'hide-vip' }}">{{ $user->vip_discount != 0 ? $user->discount . '%' : 'لا يوجد' }}</td>
                            <td data-label="الحالة" id="user-status-{{$user->id}}">
                                <span class="btn btn-sm {{$user->status==1 ? 'btn-success' : 'btn-danger'}}">
                                    {{$user->status==1 ? 'مفعل' : 'غير مفعل'}}
                                </span>
                            </td>
                            <td data-label="العمليات" id="vip-td-change-{{$user->id}}">
                                <div class="btn-group" role="group">
                                    @if(!$user->isVip())
                                        <button class="btn btn-sm btn-light mr-1 mb-1" title="Make Vip" data-toggle="modal" data-target="#modal-default{{$user->id}}" id="vip-icon-{{$user->id}}">
                                            <i class="fas fa-crown" style="color:darkviolet;"></i>
                                        </button>
                                    @endif
                                    <a href="{{ route('admin.customers.show', $user->id) }}" class="btn btn-sm btn-warning mr-1 mb-1" title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn-edit-customer btn btn-sm btn-info mr-1 mb-1" data-toggle="modal" data-target="#editCustomerModal-{{ $user->id }}" title="تعديل">
                                        <i class="fas fa-user-edit"></i>
                                    </button>
                                </div>

                                <!-- VIP Modal -->
                                <div class="modal fade" id="modal-default{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="vipModalLabel{{$user->id}}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="vipModalLabel{{$user->id}}">تمييز العميل {{$user->name}} كـ VIP</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- VIP Modal content -->
                                                <div class="form-group row mt-4">
                                                    <label for="start_date{{$user->id}}" class="col-sm-2 control-label">تاريخ البدء</label>
                                                    <div class="col-sm-10">
                                                        <input type="date" class="form-control" id="start_date{{$user->id}}" name="start_date" value="">
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-4">
                                                    <label for="end_date{{$user->id}}" class="col-sm-2 control-label">تاريخ الانتهاء</label>
                                                    <div class="col-sm-10">
                                                        <input type="date" class="form-control" id="end_date{{$user->id}}" name="end_date" value="">
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-4">
                                                    <label for="userDiscount{{$user->id}}" class="col-sm-2 control-label">نسبة الخصم %</label>
                                                    <div class="col-sm-10">
                                                        <input type="number" step="0.01" id="userDiscount{{$user->id}}" class="form-control inline-block" placeholder="أدخل نسبة الخصم" name="userDiscount" value="0" min="0">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                                                <button type="button" class="vip-user-btn btn btn-primary" data-id="{{$user->id}}">تأكيد التغييرات</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Customer Modal -->
                                <div class="modal fade" id="editCustomerModal-{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="editCustomerModalLabel-{{ $user->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header customer-edit-label bg-primary text-white">
                                                <h5 class="modal-title" id="editCustomerModalLabel-{{ $user->id }}">تعديل بيانات العميل</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="editCustomerForm-{{ $user->id }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-4">
                                                        <label for="customerName-{{ $user->id }}" class="form-label mb-3">اسم العميل :</label>
                                                        <input type="text" class="form-control form-control-lg" id="customerName-{{ $user->id }}" name="name" value="{{ $user->name }}">
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="customerType-{{ $user->id }}" class="form-label mb-3">نوع العميل :</label>
                                                        <select class="form-control form-select form-select-lg" id="customerType-{{ $user->id }}" name="type">
                                                            <option value="goomla" {{ $user->customer_type == 'goomla' ? 'selected' : '' }}>جملة</option>
                                                            <option value="regular" {{ $user->customer_type == 'regular' ? 'selected' : '' }}>قطاعي</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="customerStatus-{{ $user->id }}" class="form-label mb-3">حالة الحساب :</label>
                                                        <select class="form-control form-select form-select-lg" id="customerStatus-{{ $user->id }}" name="status">
                                                            <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>مفعل</option>
                                                            <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>غير مفعل</option>
                                                        </select>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="save-customerChanges btn btn-primary" data-id="{{ $user->id }}">حفظ التغييرات</button>
                                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">إغلاق</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            {{ $users->links('vendor.pagination.custom') }}
        </div>
    </div>

    <!-- VIP for Selected Users Modal -->
    <div class="modal fade" id="modal-default-all" tabindex="-1" role="dialog" aria-labelledby="vipAllModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="vipAllModalLabel">تمييز العملاء المحددين كـ VIP</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row mt-4">
                        <label for="all_start_date" class="col-sm-2 control-label">تاريخ البدء</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="all_start_date" name="all_start_date" value="">
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <label for="all_end_date" class="col-sm-2 control-label">تاريخ الانتهاء</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="all_end_date" name="all_end_date" value="">
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <label for="all_userDiscount" class="col-sm-2 control-label">نسبة الخصم %</label>
                        <div class="col-sm-10">
                            <input type="number" step="0.01" id="all_userDiscount" class="form-control inline-block" placeholder="أدخل نسبة الخصم" name="all_userDiscount" value="0" min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="button" id="confirm-vip-btn" class="btn btn-primary">تأكيد التغييرات</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <script>
        $(document).ready(function() {
        // تحديد أو إلغاء تحديد كل المستخدمين
            $('#select-all').click(function() {
                $('.user-checkbox').prop('checked', this.checked);
            });

        // التعامل مع الزر الوحيد لتحديد المستخدمين وفتح المودال وتنفيذ العملية
            $('#vip-selected-btn').click(function(){
                var selected = [];
                $('.user-checkbox:checked').each(function() {
                    selected.push($(this).val());
                });

                if(selected.length < 1){
                    toastr.error('لم يتم تحديد أي مستخدمين');
                } else {
                    var modal = new bootstrap.Modal(document.getElementById('modal-default-all'));
                    modal.show();

                    // تنفيذ العملية بعد تحديد البيانات في المودال
                    $('#confirm-vip-btn').off('click').click(function() {
                        var all_startDate = $('#all_start_date').val();
                        var all_endDate = $('#all_end_date').val();
                        var all_discount = $('#all_userDiscount').val();

                        if(all_startDate === "" || all_endDate === "" || all_userDiscount === ""){
                            toastr.error('يرجى إدخال جميع البيانات المطلوبة');
                            return;
                        }

                        var url = "{{ route('admin.customers.vipAll') }}";

                        if (selected.length > 0) {
                            $.ajax({
                                url: url,
                                type: 'post',
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "ids": selected,
                                    'start_date': all_startDate,
                                    'end_date': all_endDate,
                                    'discount': all_discount,
                                    'make_vip':true,
                                },
                                success: function(response) {
                                    toastr.success(response.success);
                                    $('#modal-default-all').fadeOut(600);
                                    $('.modal-backdrop').remove(); // إزالة الخلفية للمودال

                                    // تحديث حالة الأعضاء في الجدول
                                    $.each(selected, function(index, userId) {
                                        var userType = $('#user-type-' + userId);
                                        // تحقق مما إذا كانت الأيقونة موجودة بالفعل
                                        if (userType.find('i.fas.fa-crown').length === 0) {
                                            userType.append(' <i class="fas fa-crown" style="color:darkviolet;"></i>'); // إضافة أيقونة التاج
                                        }

                                        $('#user-discount-' + userId).text(all_discount + ' %'); // تحديث نسبة الخصم
                                        $('#vip-start-date-' + userId).text(all_startDate); // تحديث تاريخ البدء
                                        $('#vip-end-date-' + userId).text(all_endDate); // تحديث تاريخ الانتهاء

                                    });

                                    // إعادة تعيين القيم في المودال
                                    $('#all_start_date').val('');
                                    $('#all_end_date').val('');
                                    $('#all_userDiscount').val(0);
                                    $('.user-checkbox').prop('checked', false); // إلغاء تحديد كل checkboxes
                                },
                                error: function(response) {
                                    toastr.error('حدث خطأ أثناء التعديل، حاول مرة أخرى.');
                                    console.log(response);
                                }
                            });
                        } else {
                            toastr.error('يجب اختيار عميل واحد على الأقل.');
                        }
                    });
                }
            });

       // make customer vip
            $('.vip-user-btn').on('click', function() {

                var userId = $(this).data('id');
                var url = "{{ route('admin.customers.vip-customer', ':id') }}".replace(':id', userId);

                // تخصيص الحقول بناءً على userId
                var startDate = $( '#start_date' + userId).val();
                var endDate = $('#end_date'+ userId).val();
                var discount = $('#userDiscount' + userId ).val();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'start_date': startDate,
                        'end_date': endDate,
                        'discount': discount,
                        'make_vip':true,
                    },
                    success: function(response) {
                        toastr.success(response.success);
                        $('#modal-default'+userId).fadeOut(600);
                        $('.modal-backdrop').remove(); // إزالة الخلفية للمودال

                        // تحديث حالة العضو في الجدول
                        var userType = $('#user-type-' + userId);

                        userType.append(' <i class="fas fa-crown" style="color:darkviolet;"></i>'); // إضافة أيقونة التاج

                        $('#user-discount-' + userId).text(discount+' %'); // تحديث تاريخ الانتهاء

                        $('#vip-end-date-' + userId).text(endDate); // تحديث تاريخ الانتهاء

                        // تعطيل الأيقونة وتغيير لونها
                        var vipIcon = $('#vip-icon-' + userId );
                        vipIcon.addClass('disabled').attr('aria-disabled', 'true'); // جعل الأيقونة معطلة
                        vipIcon.find('i').css('color', '#5f5f5f'); // تغيير لون الأيقونة للرمادي
                        vipIcon.attr('aria-disabled', 'true');
                        vipIcon.attr('data-target','');
                        vipIcon.attr('data-toggle','');
                    },
                    error: function(response) {
                        toastr.error('حدث خطأ أثناء التعديل تأكد من التواريخ وقيمة ')
                        console.log(userId)
                    }
                });
            });


            // تعديل بيانات المستخدم
            $('.btn-edit-customer').on('click', function(event) {
                event.preventDefault();

                var userId = $(this).data('id');
                var modalId = '#editCustomerModal-' + userId;

                // تعبئة بيانات المستخدم داخل المودال
                $(modalId).find('#customerName-' + userId).val($(this).data('name'));
                $(modalId).find('#customerType-' + userId).val($(this).data('type'));
                $(modalId).find('#customerStatus-' + userId).val($(this).data('status'));

                // ربط الحدث عند الضغط على زر الحفظ
                $(modalId).find('.save-customerChanges').off('click').on('click', function(e) {
                    e.preventDefault();

                    // احصل على بيانات النموذج
                    var formData = {
                        _token: $('input[name="_token"]').val(),
                        name: $(modalId).find('input[name="name"]').val(),
                        customer_type: $(modalId).find('select[name="type"]').val(),
                        status: $(modalId).find('select[name="status"]').val(),
                        _method: 'PUT',
                        'update_user': true,
                    };


                    var url = "{{ route('admin.customers.update', ':id') }}".replace(':id', userId);


                    // إرسال طلب Ajax لتحديث البيانات
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        success: function(response) {
                            toastr.success(response.success);
                            $(modalId).fadeOut(400);
                            $('.modal-backdrop').remove(); // إزالة الخلفية للمودال

                            // تحديث البيانات في الجدول
                            var userRow = $('#customer-data-'+userId);

                            var userType;
                            switch (response.userType) {
                                case 'goomla':
                                    userType='جملة';
                                    break;
                                default:
                                    userType='قطاعي';
                                    break;
                            }

                            var vipIcon = response.is_vip ? '<i class="fas fa-crown" style="color:darkviolet;"></i>' : '';

                            // تحديث البيانات في الجدول
                            userRow.find('#user-status-' + userId).html(response.status == 1 ? '<span class="btn btn-sm btn-success">مفعل</span>' : '<span class="btn btn-sm btn-danger">غير مفعل</span>')
                            userRow.find('#user-type-' + userId).html(userType + ' '+ vipIcon);
                            userRow.find('#user-name-' + userId).text(response.userName);

                        },
                        error: function(response) {
                            toastr.error(response.error);
                        }
                    });

                    return false; // منع أي تحديث إضافي للصفحة
                });
            });

        });



    </script>
@endpush

@push('styles')
    <style>
        .customer-edit-label{
            background-color: #17a2b8 !important;
        }
        .save-customerChanges{
            background-color: #17a2b8 !important;
        }

        @media  (max-width: 767px) {
            .table-responsive {
                border: 0;
            }
            .table-responsive table {
                border: 0;
            }
            .table-responsive table thead {
                display: none;
            }
            .table-responsive table tr {
                margin-bottom: 10px;
                display: block;
                border-bottom: 2px solid #ddd;
            }
            .table-responsive table td {
                display: block;
                text-align: right;
                font-size: 13px;
                border-bottom: 1px dotted #ccc;
            }
            .table-responsive table td:last-child {
                border-bottom: 0;
            }
            .table-responsive table td:before {
                content: attr(data-label);
                float: right;
                text-transform: uppercase;
                font-weight: bold;
                margin-left:10px;
            }
            .table-responsive .btn-group {
                display: flex;
                flex-wrap: wrap;
                justify-content: flex-end;
            }
            .table-responsive table td.hide-vip {
                display: none;
            }
        }

        @media (min-width: 768px){
            .table-responsive{
                text-align: center;
            }
        }
    </style>
@endpush
