@extends('layouts.main')

@section('container')

    <div class="mt-10">
        <h1><strong>Checkout</strong></h1>
    </div>
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif  

    @foreach($products as $product)
        <div class="checkout-card">
            <img src="{{ asset('storage/' . $product->product_image ) }}" alt="{{ $product->product_name }}">
            <div class="checkout-details">
                <div><strong>{{ $product->product_name }}</strong></div>
                <div>Quantity: {{ $product->cart_quantity }}</div>
            </div>
            <div class="checkout-total">
                Rp{{ number_format($product->product_price * $product->cart_quantity, 0, ',', '.') }}
            </div>
        </div>
    @endforeach

    <hr>
    <div class="checkout-total" style="margin-top: 20px;">
        <strong>Total:</strong> Rp{{ number_format($totalPrice, 0, ',', '.') }}
    </div>

    <div class="payment-method-selector mt-3 mb-5">
        <p><strong>Payment Method</strong></p>
        <select id="paymentMethod" class="form-select">
            @foreach ($paymentMethods as $paymentMethod)
                <option value="{{ $paymentMethod->payment_method_id }}"
                    {{ $loop->first ? 'selected' : '' }}>
                    {{ $paymentMethod->payment_method_name }}
                </option>
            @endforeach
        </select>
        <span class="error-message text-danger" id="error_payment_method"></span>
    </div>

    <div class="card p-3 shadow-sm">
        <h3 class="mb-3">Select Address</h3>
        <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#addressModal">
            <i class="fas fa-map-marker-alt"></i> Choose Address
        </button>
        <div class="mt-3">
            <h5>Send To:</h5>
            <p id="selectedAddressText">No address selected</p>
        </div> 
        <span class="error-message text-danger" id="error_customer_address"></span>
    </div>
    
    <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Address List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="notification"></div>
                    <div id="address-list">
                        <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                            Add New Address
                        </button>
                        @foreach ($addresses as $address)
                        <div class="card p-3 mb-2">
                            <button class="btn btn-warning btn-sm edit-address"
                                data-id="{{ $address->customer_address_id }}"
                                data-name="{{ $address->customer_address_name }}"
                                data-street="{{ $address->customer_address_street }}"
                                data-postal="{{ $address->customer_address_postal_code }}"
                                data-district="{{ $address->customer_address_district }}"
                                data-city="{{ $address->customer_address_regency_city }}"
                                data-province="{{ $address->customer_address_province }}"
                                data-country="{{ $address->customer_address_country }}"
                                data-bs-toggle="modal" data-bs-target="#updateAddressModal">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <form action="{{ route('address.destroy', $address->customer_address_id) }}" method="post" class="d-inline delete-address">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                            <strong>{{ $address->customer_address_name }}</strong>
                            <p>{{ $address->customer_address_street }}, {{ $address->customer_address_district }}, {{ $address->customer_address_regency_city }}, {{ $address->customer_address_province }}, {{ $address->customer_address_country }}, {{ $address->customer_address_postal_code }}</p>
                            <button class="btn btn-success btn-sm select-address" data-id="{{ $address->customer_address_id }}">
                                Select
                            </button>
                        </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('address.store') }}" method="post" id="addressForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" id="address_name" name="customer_address_name" class="form-control mb-2" placeholder="Address Name">
                            <span class="error-message text-danger" id="error_customer_address_name"></span>
                        </div>
                        
                        <div class="form-group">
                            <input type="text" id="street" name="customer_address_street" class="form-control mb-2" placeholder="Street">
                            <span class="error-message text-danger" id="error_customer_address_street"></span>
                        </div>
                        
                        <div class="form-group">
                            <input type="text" id="postal_code" name="customer_address_postal_code" class="form-control mb-2" placeholder="Postal Code">
                            <span class="error-message text-danger" id="error_customer_address_postal_code"></span>
                        </div>
                        
                        <div class="form-group">
                            <input type="text" id="district" name="customer_address_district" class="form-control mb-2" placeholder="District">
                            <span class="error-message text-danger" id="error_customer_address_district"></span>
                        </div>
                        
                        <div class="form-group">
                            <input type="text" id="regency_city" name="customer_address_regency_city" class="form-control mb-2" placeholder="Regency / City">
                            <span class="error-message text-danger" id="error_customer_address_regency_city"></span>
                        </div>
                        
                        <div class="form-group">
                            <input type="text" id="province" name="customer_address_province" class="form-control mb-2" placeholder="Province">
                            <span class="error-message text-danger" id="error_customer_address_province"></span>
                        </div>

                        <div class="form-group">
                            <input type="text" id="country" name="customer_address_country" class="form-control mb-2" placeholder="Country">
                            <span class="error-message text-danger" id="error_customer_address_country"></span>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addressModal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveAddress" data-url="{{ route('address.store') }}">Save</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateAddressModal" tabindex="-1" aria-labelledby="updateAddressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('address.update', $address->customer_address_id ?? 0) }}" method="post" id="updateAddressForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" id="update_address_id" name="customer_address_id">

                        <div class="form-group">
                            <input type="text" id="update_address_name" name="customer_address_name" class="form-control mb-2" placeholder="Address Name">
                            <span class="error-message text-danger" id="error_customer_address_name"></span>
                        </div>

                        <div class="form-group">
                            <input type="text" id="update_street" name="customer_address_street" class="form-control mb-2" placeholder="Street">
                            <span class="error-message text-danger" id="error_customer_address_street"></span>
                        </div>

                        <div class="form-group">
                            <input type="text" id="update_postal_code" name="customer_address_postal_code" class="form-control mb-2" placeholder="Postal Code">
                            <span class="error-message text-danger" id="error_customer_address_postal_code"></span>
                        </div>

                        <div class="form-group">
                            <input type="text" id="update_district" name="customer_address_district" class="form-control mb-2" placeholder="District">
                            <span class="error-message text-danger" id="error_customer_address_district"></span>
                        </div>

                        <div class="form-group">
                            <input type="text" id="update_regency_city" name="customer_address_regency_city" class="form-control mb-2" placeholder="Regency / City">
                            <span class="error-message text-danger" id="error_customer_address_regency_city"></span>
                        </div>

                        <div class="form-group">
                            <input type="text" id="update_province" name="customer_address_province" class="form-control mb-2" placeholder="Province">
                            <span class="error-message text-danger" id="error_customer_address_province"></span>
                        </div>

                        <div class="form-group">
                            <input type="text" id="update_country" name="customer_address_country" class="form-control mb-2" placeholder="Country">
                            <span class="error-message text-danger" id="error_customer_address_country"></span>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addressModal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="updateAddress">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form action="{{ route('checkout.payment') }}" method="post" id="checkoutForm">
        @csrf

        @if(isset($quantity) && count($products) === 1)
            <input type="hidden" name="product_id" value="{{ $products[0]->product_id }}">
            <input type="hidden" name="quantity" value="{{ $quantity }}">
            <input type="hidden" id="totalPrice" value="{{ $products[0]->product_price * $quantity }}">
        @else
            @foreach ($products as $product)
                <input type="hidden" name="product_ids[]" value="{{ $product->product_id }}">
                <input type="hidden" name="quantities[]" value="{{ $product->cart_quantity ?? 1 }}">
            @endforeach
            <input type="hidden" id="totalPrice" value="{{ $totalPrice }}">
        @endif

        <input type="hidden" name="payment_method_id" id="selectedPayment" value="">
        <input type="hidden" name="customer_address_id" id="selectedAddress" value="">
        <input type="hidden" id="customerBalance" value="{{ $customers->customer_balance }}">

        <button type="submit" class="btn btn-primary">Place order</button>
    </form>

    <div class="modal fade" id="insufficientBalanceModal" tabindex="-1" aria-labelledby="insufficientBalanceLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header bg-danger text-white">
              <h5 class="modal-title" id="insufficientBalanceLabel">Oops! You don't have enough balance.</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
              <p>Your current balance is:</p> <strong id="currentBalanceText">Rp0</strong></p>
              <p>Looks like your purchase total is higher than your balance. Please top up or select another payment method to continue.</p>
            </div>
            <div class="modal-footer">
              <a href="{{ route('topup.show') }}" class="btn btn-warning">Top Up</a>
            </div>
          </div>
        </div>
    </div>

@endsection