@extends('layouts.main')

@section('container')
    @auth('customer')
        <!-- <h3 class="balance">Your balance: Rp{{ number_format($customers->customer_balance, 2, ',', '.') }}</h3> -->
    @else

    @endauth
    @if ($products->count())
        
        <div class="background-image">
            <img src="assets/image/all-products-background.png" alt="">
        </div>

        <div class="subtitle">
            <h2>All products</h2>
        </div>

        <div id="product-container" class="products-container">
            @foreach ($products as $product)
                <a class="products-card-link" href="{{ url($product->msbrand->brand_slug . '/' . $product->product_slug) }}">                        
                    <div class="products-cover">
                        <div class="products-card">
                            <img class="product-photos" src="{{ asset('storage/product_photos/' . $product->product_image) }}" class="card-img-top" alt="{{ $product->product_name }}">
                            <div class="card-body">
                                <div class="product-text">
                                    <h3 class="products-card-name">{{ $product->product_name }}</h3>
                                    <p class="products-card-price">Rp{{ number_format($product->product_price, 2, ',', '.') }}</p>
                                </div> 
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

    @else
        <p>No Product found.</p>
    @endif

@endsection