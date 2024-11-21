@extends('lms::storefront.theme4.user.user')
@section('page-title')
    {{ __('Checkout') }} - {{ $store->tagline ? $store->tagline : config('APP_NAME', ucfirst($store->name)) }}
@endsection
@push('css-page')
@endpush
@push('script-page')
    <!-- APPLY COUPON -->
    <script>
        // Apply Coupon
        $(document).on('click', '.apply-coupon', function(e) {
            e.preventDefault();
            var ele = $(this);
            var coupon = ele.closest('.row').find('.coupon').val();
            var hidden_field = $('.hidden_coupon').val();
            var price = $('#total_value').attr('data-value');

            if (coupon == hidden_field) {
                show_toastr('Error', 'Coupon Already Used', 'error');
            } else {
                if (coupon != '') {
                    $.ajax({
                        url: '{{ route('apply.coursecoupon') }}',
                        datType: 'json',
                        data: {
                            price: price,
                            store_id: {{ $store->id }},
                            coupon: coupon
                        },
                        success: function(data) {
                            $('#stripe_coupon, #paypal_coupon').val(coupon);
                            if (data.is_success) {
                                $('.hidden_coupon').val(coupon);
                                $('.hidden_coupon').attr(data);

                                $('.dicount_price').html(data.discount_price);

                                var html = '';
                                html +=
                                    '<span class="text-sm font-weight-bold total_price" data-value="' +
                                    data.final_price_data_value + '">' + data.final_price + '</span>'
                                $('#total_value').html(html);

                                show_toastr('Success', data.message, 'success');
                            } else {
                                show_toastr('Error', data.message, 'error');
                            }
                        }
                    })
                } else {
                    show_toastr('Error', '{{ __('Invalid Coupon Code.') }}', 'error');
                }
            }

        });
    </script>
@endpush
@php
    $company_setting = getCompanyAllSetting($store->created_by,$store->workspace_id);
@endphp
@section('head-title')
    {{ __('Checkout') }}
@endsection
@section('content')
<div class="wrapper">
    <section class="common-banner-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-6">
                    <div class="common-banner-content">
                        <a href="{{ route('store.slug', $store->slug) }}" class="back-btn">
                            <span class="svg-ic">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="5" viewBox="0 0 11 5" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.5791 2.28954C10.5791 2.53299 10.3818 2.73035 10.1383 2.73035L1.52698 2.73048L2.5628 3.73673C2.73742 3.90636 2.74146 4.18544 2.57183 4.36005C2.40219 4.53467 2.12312 4.53871 1.9485 4.36908L0.133482 2.60587C0.0480403 2.52287 -0.000171489 2.40882 -0.000171488 2.2897C-0.000171486 2.17058 0.0480403 2.05653 0.133482 1.97353L1.9485 0.210321C2.12312 0.0406877 2.40219 0.044729 2.57183 0.219347C2.74146 0.393966 2.73742 0.673036 2.5628 0.842669L1.52702 1.84888L10.1383 1.84875C10.3817 1.84874 10.5791 2.04609 10.5791 2.28954Z" fill="white"></path>
                                </svg>
                            </span>
                            {{ __('Back to Home') }}
                        </a>
                        <div class="section-title">
                            <h2>{{ __('Payment') }}</h2>
                        </div>
                        <p>{{ __('Lorem Ipsum is simply dummy text of the printing and typesetting industry.Lorem Ipsum is simply dummy text of the printing and typesetting industry.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="padding-top padding-bottom checkout-section">
        <div class="container">
             <div class="row">
                 <div class="col-lg-8 col-12">
                    @if ($company_setting['bank_transfer_payment_is_on'] == 'on' &&
                        !empty($company_setting['bank_number']) )
                        <div class="payment-method">
                            <div class="payment-title d-flex align-items-center justify-content-between">
                                <h4>{{ __('Bank Transfer') }}</h4>
                                <div class="payment-image d-flex align-items-center">
                                    <img src="{{ asset('Modules/LMS/Resources/assets/imgs/bank.png') }}" alt="Image placeholder">
                                </div>
                            </div>
                            <p>{!! $company_setting['bank_number'] !!}</p>
                            <form action="{{ route('course.pay.with.bank', $store->slug) }}" method="POST"
                                class="payment-method-form" id="bank_transfer_form" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="upload-btn-wrapper">
                                            <label for="bank_transfer_invoice" class="file-upload btn btn-round">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17"
                                                    viewBox="0 0 17 17" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M6.67952 7.2448C6.69833 7.59772 6.42748 7.89908 6.07456 7.91789C5.59289 7.94357 5.21139 7.97498 4.91327 8.00642C4.51291 8.04864 4.26965 8.29456 4.22921 8.64831C4.17115 9.15619 4.12069 9.92477 4.12069 11.0589C4.12069 12.193 4.17115 12.9616 4.22921 13.4695C4.26972 13.8238 4.51237 14.0691 4.91213 14.1112C5.61223 14.1851 6.76953 14.2586 8.60022 14.2586C10.4309 14.2586 11.5882 14.1851 12.2883 14.1112C12.6881 14.0691 12.9307 13.8238 12.9712 13.4695C13.0293 12.9616 13.0798 12.193 13.0798 11.0589C13.0798 9.92477 13.0293 9.15619 12.9712 8.64831C12.9308 8.29456 12.6875 8.04864 12.2872 8.00642C11.9891 7.97498 11.6076 7.94357 11.1259 7.91789C10.773 7.89908 10.5021 7.59772 10.5209 7.2448C10.5397 6.89187 10.8411 6.62103 11.194 6.63984C11.695 6.66655 12.0987 6.69958 12.4214 6.73361C13.3713 6.8338 14.1291 7.50771 14.2428 8.50295C14.3077 9.07016 14.3596 9.88879 14.3596 11.0589C14.3596 12.229 14.3077 13.0476 14.2428 13.6148C14.1291 14.6095 13.3732 15.2837 12.4227 15.384C11.6667 15.4638 10.4629 15.5384 8.60022 15.5384C6.73752 15.5384 5.5337 15.4638 4.77779 15.384C3.82728 15.2837 3.07133 14.6095 2.95763 13.6148C2.89279 13.0476 2.84082 12.229 2.84082 11.0589C2.84082 9.88879 2.89279 9.07016 2.95763 8.50295C3.0714 7.50771 3.82911 6.8338 4.77903 6.73361C5.10175 6.69958 5.50546 6.66655 6.00642 6.63984C6.35935 6.62103 6.6607 6.89187 6.67952 7.2448Z"
                                                        fill="white"></path>
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M6.81509 4.79241C6.56518 5.04232 6.16 5.04232 5.91009 4.79241C5.66018 4.5425 5.66018 4.13732 5.91009 3.88741L8.14986 1.64764C8.39977 1.39773 8.80495 1.39773 9.05486 1.64764L11.2946 3.88741C11.5445 4.13732 11.5445 4.5425 11.2946 4.79241C11.0447 5.04232 10.6395 5.04232 10.3896 4.79241L9.24229 3.64508V9.77934C9.24229 10.1328 8.95578 10.4193 8.60236 10.4193C8.24893 10.4193 7.96242 10.1328 7.96242 9.77934L7.96242 3.64508L6.81509 4.79241Z"
                                                        fill="white"></path>
                                                </svg>
                                                {{ __('Upload invoice reciept') }}
                                            </label>
                                            <input type="file" name="bank_transfer_invoice" id="bank_transfer_invoice"
                                                class="file-input" data-filename="invoice_logo_update">
                                            <input type="hidden" name="product_id">
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group text-right">
                                        <button type="submit" class="btn">{{ __('Pay Now') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif

                    @stack('course_payment')
                 </div>
                 <div class="col-lg-4 col-12">
                    @php
                        $cart = session()->get($store->slug);
                    @endphp
                    <div class="checkout-right-side">
                        <div class="coupon-form">
                            <div class="coupon-header">
                                <h4>{{ __('Coupon') }}</h4>
                            </div>
                            <div class="coupon-body">
                                <form action="">
                                    <div class="input-wrapper">
                                        <input type="text" class="coupon" placeholder="Enter Coupon Code">
                                    </div>
                                    <div class="btn-wrapper">
                                        <button type="submit" class="btn btn-secondary apply-coupon">{{ __('Apply') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                       <div class="mini-cart">
                            @php
                                $total = 0;
                                $sub_total = 0;

                                $cart = session()->get($store->slug);
                                $total_item = 0;
                                if (isset($cart['products'])) {
                                    foreach ($cart['products'] as $item) {
                                        if (isset($cart) && !empty($cart['products'])) {
                                            $total_item = count($cart['products']);
                                        }
                                    }
                                }
                            @endphp
                           <div class="mini-cart-header">
                               <h4>{{ __('My Cart') }}:<span class="checkout-cartcount">[{{ $total_item }}]</span></h4>
                           </div>
                           <div id="cart-body" class="mini-cart-has-item">
                               <div class="mini-cart-body">
                                    @foreach ($cart['products'] as $k => $value)
                                        @php
                                            $total += $value['price'];
                                            $sub_total += $value['price'];
                                        @endphp
                                        <div class="mini-cart-item">
                                            <div class="mini-cart-image">
                                                <a href="{{ route('store.viewcourse', [$store->slug, \Illuminate\Support\Facades\Crypt::encrypt($value['id'])]) }}">
                                                    @if(!empty($value['image']))
                                                        <img src="{{asset($value['image'])}}" alt="img" width="260px" height="160px">
                                                    @else
                                                        <img src="{{ asset('Modules/LMS/Resources/assets/themes/theme4/images/product-3.jpg')}}" alt="img">
                                                    @endif
                                                </a>
                                            </div>
                                            <div class="mini-cart-details">
                                                <p class="mini-cart-title"><a href="{{ route('store.viewcourse', [$store->slug, \Illuminate\Support\Facades\Crypt::encrypt($value['id'])]) }}">{{ $value['product_name'] }}</a></p>
                                                <div class="pvarprice d-flex align-items-center justify-content-between">
                                                    <div class="price">
                                                        <ins>{{ currency_format_with_sym($value['price'],$store->created_by,$store->workspace_id) }}</ins>
                                                    </div>
                                                    <a class="remove_item" title="Remove item" href="#" data-confirm="{{ __('Are You Sure?') . ' | ' . __('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="document.getElementById('delete-product-cart-{{ $k }}').submit();">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                            <path d="M12.7589 7.24609C12.5002 7.24609 12.2905 7.45577 12.2905 7.71448V16.5669C12.2905 16.8255 12.5002 17.0353 12.7589 17.0353C13.0176 17.0353 13.2273 16.8255 13.2273 16.5669V7.71448C13.2273 7.45577 13.0176 7.24609 12.7589 7.24609Z" fill="#61AFB3"></path>
                                                            <path d="M7.23157 7.24609C6.97286 7.24609 6.76318 7.45577 6.76318 7.71448V16.5669C6.76318 16.8255 6.97286 17.0353 7.23157 17.0353C7.49028 17.0353 7.69995 16.8255 7.69995 16.5669V7.71448C7.69995 7.45577 7.49028 7.24609 7.23157 7.24609Z" fill="#61AFB3"></path>
                                                            <path d="M3.20333 5.95419V17.4942C3.20333 18.1762 3.45344 18.8168 3.89035 19.2764C4.32525 19.7373 4.93049 19.9989 5.56391 20H14.4259C15.0594 19.9989 15.6647 19.7373 16.0994 19.2764C16.5363 18.8168 16.7864 18.1762 16.7864 17.4942V5.95419C17.6549 5.72366 18.2177 4.8846 18.1016 3.99339C17.9852 3.10236 17.2261 2.43583 16.3274 2.43565H13.9293V1.85017C13.932 1.35782 13.7374 0.885049 13.3888 0.537238C13.0403 0.18961 12.5668 -0.00396362 12.0744 6.15416e-05H7.91533C7.42298 -0.00396362 6.94948 0.18961 6.60093 0.537238C6.25239 0.885049 6.05772 1.35782 6.06046 1.85017V2.43565H3.66238C2.76367 2.43583 2.00456 3.10236 1.8882 3.99339C1.77202 4.8846 2.33481 5.72366 3.20333 5.95419ZM14.4259 19.0632H5.56391C4.76308 19.0632 4.14009 18.3753 4.14009 17.4942V5.99536H15.8497V17.4942C15.8497 18.3753 15.2267 19.0632 14.4259 19.0632ZM6.99723 1.85017C6.99412 1.60628 7.08999 1.37154 7.26307 1.19938C7.43597 1.02721 7.67126 0.932619 7.91533 0.936827H12.0744C12.3185 0.932619 12.5538 1.02721 12.7267 1.19938C12.8998 1.37136 12.9956 1.60628 12.9925 1.85017V2.43565H6.99723V1.85017ZM3.66238 3.37242H16.3274C16.793 3.37242 17.1705 3.74987 17.1705 4.21551C17.1705 4.68114 16.793 5.05859 16.3274 5.05859H3.66238C3.19674 5.05859 2.81929 4.68114 2.81929 4.21551C2.81929 3.74987 3.19674 3.37242 3.66238 3.37242Z" fill="#61AFB3"></path>
                                                            <path d="M9.99523 7.24609C9.73653 7.24609 9.52686 7.45577 9.52686 7.71448V16.5669C9.52686 16.8255 9.73653 17.0353 9.99523 17.0353C10.2539 17.0353 10.4636 16.8255 10.4636 16.5669V7.71448C10.4636 7.45577 10.2539 7.24609 9.99523 7.24609Z" fill="#61AFB3"></path>
                                                            <defs>
                                                                <clipPath>
                                                                    <rect width="20" height="20" fill="white"></rect>
                                                                </clipPath>
                                                            </defs>
                                                        </svg>
                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['delete.cart_item', [$store->slug, $value['product_id']]],
                                                            'id' => 'delete-product-cart-' . $k,
                                                        ]) !!}
                                                        {!! Form::close() !!}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                               </div>
                               <div class="mini-cart-footer">
                                   <div class="u-save d-flex justify-content-between">
                                       <div class="cpn-lbl"> {{ __('Coupon') }}</div>
                                       <div class="cpn-price dicount_price">0.00</div>
                                    </div>
                                   <div class="mini-cart-footer-total-row d-flex align-items-center justify-content-between">
                                       <div class="mini-total-lbl">
                                        {{ __('Total') }} :
                                       </div>
                                       <div class="mini-total-price" id="total_value" data-value="{{ $total }}">
                                            <span class="total_price" data-value="{{ $total }}"> {{ currency_format_with_sym($total,$store->created_by,$store->workspace_id) }}</span>
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
</div>
@endsection
