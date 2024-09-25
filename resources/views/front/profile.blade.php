@extends('front.layouts.app')
@section('title', __('profile.my_account'))
@section('content')
<!--== Start Page Title Area ==-->
<section class="page-title-area">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12 m-auto">
                <div class="page-title-content text-center">
                    <h2 class="title">{{ __('profile.my_account') }}</h2>
                    <div class="bread-crumbs">
                        <a href="{{ route('home.index') }}">{{ __('home.title') }}</a>
                        <span class="breadcrumb-sep"> // </span>
                        <span class="active">{{ __('profile.my_account') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End Page Title Area ==-->

<!--== Start My Account Wrapper ==-->
<section class="my-account-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 m-auto">
                <div class="section-title text-center">
                    <h2 class="title">{{ __('profile.info') }}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="myaccount-page-wrapper">
                    <div class="row">
                        <div class="col-lg-3 col-md-4">
                            <nav>
                                <div class="myaccount-tab-menu nav nav-tabs" id="nav-tab" role="tablist">
                                    <button class="nav-link {{ $errors->any()|| $errors->addressErrors->any() ? '' : 'active' }}" id="dashboad-tab" data-bs-toggle="tab" data-bs-target="#dashboad" type="button" role="tab" aria-controls="dashboad" aria-selected="true">{{ __('profile.dashboard') }}</button>
                                    <button class="nav-link {{ $errors->addressErrors->any() ? 'active' : '' }}" id="address-edit-tab" data-bs-toggle="tab" data-bs-target="#address-edit" type="button" role="tab" aria-controls="address-edit" aria-selected="false">{{ __('profile.address') }}</button>
                                    <button class="nav-link {{ $errors->any() ? 'active' : '' }}" id="account-info-tab" data-bs-toggle="tab" data-bs-target="#account-info" type="button" role="tab" aria-controls="account-info" aria-selected="false">{{ __('profile.account_details') }}</button>
                                    <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab" aria-controls="orders" aria-selected="false">{{ __('profile.orders') }}</button>
                                </div>
                            </nav>
                        </div>
                        <div class="col-lg-9 col-md-8">
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade {{ $errors->any() || $errors->addressErrors->any() ? '' : 'show active' }}" id="dashboad" role="tabpanel" aria-labelledby="dashboad-tab">
                                    <div class="myaccount-content">
                                        <h3>{{ __('profile.dashboard') }}</h3>
                                        <div class="welcome">
                                            <p>{{ __('profile.hello') }} <strong>{{ auth()->user()->name }}</strong></p>
                                        </div>
                                        <p class="mb-0">{{ __('profile.dashboard_desc') }}</p>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                                    <div class="myaccount-content">
                                        <h3>{{ __('profile.orders') }}</h3>
                                        <div class="myaccount-table table-responsive text-center">
                                            <table class="table table-bordered">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th>{{ __('profile.order') }}</th>
                                                    <th>{{ __('profile.date') }}</th>
                                                    <th>{{ __('profile.status') }}</th>
                                                    <th>{{ __('profile.total') }}</th>
                                                    <th>{{ __('profile.action') }}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Aug 22, 2018</td>
                                                    <td>Pending</td>
                                                    <td>$3000</td>
                                                    <td><a href="shop-cart.html" class="check-btn sqr-btn">{{ __('profile.view') }}</a></td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>July 22, 2018</td>
                                                    <td>Approved</td>
                                                    <td>$200</td>
                                                    <td><a href="shop-cart.html" class="check-btn sqr-btn">{{ __('profile.view') }}</a></td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>June 12, 2017</td>
                                                    <td>On Hold</td>
                                                    <td>$990</td>
                                                    <td><a href="shop-cart.html" class="check-btn sqr-btn">{{ __('profile.view') }}</a></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="payment-method" role="tabpanel" aria-labelledby="payment-method-tab">
                                    <div class="myaccount-content">
                                        <h3>{{ __('profile.payment_method') }}</h3>
                                        <p class="saved-message">{{ __('profile.saved_payment_message') }}</p>
                                    </div>
                                </div>
                                <div class="tab-pane fade {{ $errors->addressErrors->any() ? 'show active' : '' }}" id="address-edit" role="tabpanel" aria-labelledby="address-edit-tab">
                                    <div class="myaccount-content">
                                        <h3>{{ __('profile.billing_address') }}</h3>
                                        <div class="account-details-form">
                                            <form action="{{ route('profile.updateAddress') }}" method="POST">
                                                @csrf
                                                <div class="single-input-item">
                                                    <label for="full_name" class="required">{{ __('profile.full_name') }}</label>
                                                    <input type="text" id="full_name" name="full_name"
                                                           value="{{ old('full_name', $address?->full_name) }}"
                                                           placeholder="{{ __('profile.full_name_placeholder') }}" />
                                                    @error('full_name','addressErrors')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="single-input-item">
                                                    <label for="phone" class="required">{{ __('profile.phone') }}</label>
                                                    <input type="text" id="phone" name="phone"
                                                           value="{{ old('phone', $address?->phone) }}"
                                                           placeholder="{{ __('profile.phone_placeholder') }}" />
                                                    @error('phone','addressErrors')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="single-input-item">
                                                    <label for="state" class="required">{{ __('profile.state') }}</label>
                                                    <div class="select-style ">
                                                        <select class=" select-active" name="state">
                                                            <option value="" disabled selected>اختر اسم محافظتك</option>
                                                            <option value="القاهرة"{{ $address?->state == 'القاهرة' ? 'selected' : '' }}>القاهرة</option>
                                                            <option value="الجيزة"{{ $address?->state == 'الجيزة' ? 'selected' : '' }}>الجيزة</option>
                                                            <option value="الإسكندرية"{{ $address?->state == 'الإسكندرية' ? 'selected' : '' }}>الإسكندرية</option>
                                                            <option value="الدقهلية"{{ $address?->state == 'الدقهلية' ? 'selected' : '' }}>الدقهلية</option>
                                                            <option value="البحر الأحمر"{{ $address?->state == 'البحر الأحمر' ? 'selected' : '' }}>البحر الأحمر</option>
                                                            <option value="البحيرة"{{ $address?->state == 'البحيرة' ? 'selected' : '' }}>البحيرة</option>
                                                            <option value="الفيوم"{{ $address?->state == 'الفيوم' ? 'selected' : '' }}>الفيوم</option>
                                                            <option value="الغربية"{{ $address?->state == 'الغربية' ? 'selected' : '' }}>الغربية</option>
                                                            <option value="الإسماعيلية"{{ $address?->state == 'الإسماعيلية' ? 'selected' : '' }}>الإسماعيلية</option>
                                                            <option value="المنوفية"{{ $address?->state == 'المنوفية' ? 'selected' : '' }}>المنوفية</option>
                                                            <option value="المنيا"{{ $address?->state == 'المنيا' ? 'selected' : '' }}>المنيا</option>
                                                            <option value="القليوبية"{{ $address?->state == 'القليوبية' ? 'selected' : '' }}>القليوبية</option>
                                                            <option value="الوادي الجديد"{{ $address?->state == 'الوادي الجديد' ? 'selected' : '' }}>الوادي الجديد</option>
                                                            <option value="الشرقية"{{ $address?->state == 'الشرقية' ? 'selected' : '' }}>الشرقية</option>
                                                            <option value="سوهاج"{{ $address?->state == 'سوهاج' ? 'selected' : '' }}>سوهاج</option>
                                                            <option value="أسوان"{{ $address?->state == 'أسوان' ? 'selected' : '' }}>أسوان</option>
                                                            <option value="أسيوط"{{ $address?->state == 'أسيوط' ? 'selected' : '' }}>أسيوط</option>
                                                            <option value="بني سويف"{{ $address?->state == 'بني سويف' ? 'selected' : '' }}>بني سويف</option>
                                                            <option value="بورسعيد"{{ $address?->state == 'بورسعيد' ? 'selected' : '' }}>بورسعيد</option>
                                                            <option value="دمياط"{{ $address?->state == 'دمياط' ? 'selected' : '' }}>دمياط</option>
                                                            <option value="السويس"{{ $address?->state == 'السويس' ? 'selected' : '' }}>السويس</option>
                                                            <option value="الأقصر"{{ $address?->state == 'الأقصر' ? 'selected' : '' }}>الأقصر</option>
                                                            <option value="قنا"{{ $address?->state == 'قنا' ? 'selected' : '' }}>قنا</option>
                                                            <option value="مطروح"{{ $address?->state == 'مطروح' ? 'selected' : '' }}>مطروح</option>
                                                            <option value="شمال سيناء"{{ $address?->state == 'شمال سيناء' ? 'selected' : '' }}>شمال سيناء</option>
                                                            <option value="جنوب سيناء"{{ $address?->state == 'جنوب سيناء' ? 'selected' : '' }}>جنوب سيناء</option>
                                                        </select>
                                                    </div>
                                                    @error('state','addressErrors')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="single-input-item">
                                                    <label for="city" class="required">{{ __('profile.city') }}</label>
                                                    <input type="text" id="city" name="city"
                                                           value="{{ old('city', $address?->city) }}"
                                                           placeholder="{{ __('profile.city_placeholder') }}" />
                                                    @error('city','addressErrors')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="single-input-item">
                                                    <label for="address" class="required">{{ __('profile.address_line') }}</label>
                                                    <input type="text" id="address" name="address"
                                                           value="{{ old('address', $address?->address) }}"
                                                           placeholder="{{ __('profile.address_line_placeholder') }}" />
                                                    @error('address','addressErrors')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="single-input-item">
                                                    <button class="check-btn sqr-btn">{{ __('profile.save_changes') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade  {{ $errors->any() ? 'show active' : '' }}" id="account-info" role="tabpanel" aria-labelledby="account-info-tab">
                                    <div class="myaccount-content">
                                        <h3>{{ __('profile.account_details') }}</h3>
                                        <div class="account-details-form">
                                            @if ($errors->any())
                                                <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; padding: 10px; border-radius: 4px; margin-bottom: 35px;">
                                                    <ul style="list-style-type: none; padding-left: 0; margin: 0;">
                                                        @foreach ($errors->all() as $error)
                                                            <li><i class="fa fa-exclamation-circle" style="margin-right: 5px;"></i>&nbsp; {{ $error }}&nbsp; </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            <form action="{{route('profile.Update')}}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="single-input-item">
                                                            <label for="name" class="required">{{ __('profile.first_name') }}</label>
                                                            <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" placeholder="{{ __('profile.first_name_placeholder') }}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="single-input-item">
                                                            <label for="last-name" class="required">{{ __('profile.last_name') }}</label>
                                                            <input type="text" id="last-name" name="last_name" value="{{ old('last_name', auth()->user()->last_name) }}" placeholder="{{ __('profile.last_name_placeholder') }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="single-input-item">
                                                    <label for="phone" class="required">{{ __('profile.phone') }}</label>
                                                    <input type="text" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" placeholder="{{ __('profile.phone_placeholder') }}" />
                                                </div>
                                                <fieldset>
                                                    <legend>{{ __('profile.password_change') }}:</legend>
                                                    <div class="single-input-item">
                                                        <label for="current-pwd" class="required">{{ __('profile.current_password') }}</label>
                                                        <input type="password" id="current-pwd" name="current_password" placeholder="{{ __('profile.current_password_placeholder') }}" />
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="single-input-item">
                                                                <label for="new-pwd" class="required">{{ __('profile.new_password') }}</label>
                                                                <input type="password" id="new-pwd" name="new_password" placeholder="{{ __('profile.new_password_placeholder') }}" />
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="single-input-item">
                                                                <label for="confirm-pwd" class="required">{{ __('profile.confirm_password') }}</label>
                                                                <input type="password" id="confirm-pwd" name="new_password_confirmation" placeholder="{{ __('profile.confirm_password_placeholder') }}" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <div class="single-input-item">
                                                    <button class="check-btn sqr-btn">{{ __('profile.save_changes') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End My Account Wrapper ==-->

@endsection
@push('styles')
    <style>
        .select-style .select-active {
            padding-inline-start: 36px;
            line-height: 38px;
        }

    </style>
@endpush
