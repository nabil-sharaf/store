@if($productPrice > $discountedPrice)
    <span class="price-old" style=" color: #999; ">
        {{ number_format($productPrice, 0) }}{{ trim(__('home.currency')) }}
    </span>
<span class="price" style="color: #e74c3c;">
    {{ number_format($discountedPrice, 0) }} {{ __('home.currency') }}
</span>
    @else
    <span class="price" style="color: #e74c3c;">
    {{ number_format($discountedPrice, 2) }} {{ __('home.currency') }}
    </span>
@endif


@push('styles')
    <style>
        .price-old {
            position: relative;
            color: #999;
            font-size: 13px ;
            text-decoration: unset !important;
        }

        .price-old::after {
            content: '';
            position: absolute;
            left: 6%;
            top: 50%;
            width: 94%;
            height: .5px;
            background-color: black;
            transform: translateY(-50%);

        }
    </style>
@endpush

