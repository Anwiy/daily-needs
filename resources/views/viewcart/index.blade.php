@extends('layouts.main')

@section('container')

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <div class="container" id="container">
        <div class="card p-15 ">
            <div class="cart-title d-flex justify-content-center pt-5 mr-15">
                <h1 class="mb-4">Shopping Cart</h1>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th class="checkbox"></th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                    <tr>
                        <td class="align-middle">
                            <input type="checkbox" id="cart-checkbox-{{ $loop->index }}" class="cart-checkbox"
                                data-product-id="{{ $item->msproduct->product_id }}"
                                data-price="{{ $item->msproduct->product_price * $item->quantity }}">
                            <label for="cart-checkbox-{{ $loop->index }}"></label>
                        </td>
                        <td class="align-middle">
                            <a href="{{ url($item->msproduct->msbrand->brand_slug . '/' . $item->msproduct->product_slug) }}" class="d-flex align-items-center text-decoration-none text-dark">
                                <img src="{{ asset('storage/product_photos/' . ($item->msproduct->product_image ?? 'default.png')) }}" 
                                alt="{{ $item->msproduct->product_name }}" 
                                class="img-thumbnail me-5" width="120" height="120">
                                {{ $item->msproduct->product_name }}
                            </a>
                        </td>
                        <td class="container-td align-middle">
                            <form class="update-cart-form d-flex align-items-center" method="post" action="{{ route('cart.update', ['brand_slug' => $item->msproduct->msbrand->brand_slug, 'product_slug' => $item->msproduct->product_slug]) }}" data-url="{{ route('cart.update', ['brand_slug' => $item->msproduct->msbrand->brand_slug, 'product_slug' => $item->msproduct->product_slug]) }}">
                                @csrf
                                @method('PATCH')

                                <input type="hidden" name="product_id" value="{{ $item->msproduct->product_id }}">

                                <button type="button" name="action" value="decrease" class="btn btn-outline-secondary decrease-btn">-</button>
                                <input type="number" name="quantity" value="{{ $item->quantity }}" class="quantity-input form-control mx-1 text-center" min="1" max="{{ $item->msproduct->product_stock }}" data-max-stock="{{ $item->msproduct->product_stock }}" style="width: 50px">
                                <button type="button" name="action" value="increase" class="btn btn-outline-secondary increase-btn">+</button>
                                <div class="error-message text-danger mt-1"></div>
                            </form>
                        </td>
                        <td class="align-middle total-price-per-item" data-unit-price="{{ $item->msproduct->product_price }}">
                            Rp {{ number_format($item->msproduct->product_price * $item->quantity, 0, ',', '.') }}
                        </td>
                        <td class="align-middle">
                            <form class="delete-cart-form" action="{{ route('cart.delete', ['brand_slug' => $item->msproduct->msbrand->brand_slug, 'product_slug' => $item->msproduct->product_slug]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-cart-item"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="bg-light rounded p-3" id="subtotal">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><strong>Total</strong></h5>
                    <h5 class="mb-0" id="total-price">Rp 0</h5>
                </div>

                <div class="text-center">
                    <form id="cartCheckoutForm" action="{{ route('checkout.process') }}" method="post">
                        @csrf
                        <input type="hidden" name="selected_items" id="selected_items_input">
                        <button id="checkout-button" type="submit" class="btn btn-success fw-bold px-4">Proceed to Checkout</button>
                    </form>
                </div>
            </div> 
            
        </div>
    </div>

@endsection