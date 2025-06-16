@extends('courier-sidebar.index')

@section('container')

    <div class="content">

        <div class="container-header-delivery">
            <a href="{{ url()->previous() }}" class="back-delivery">
                <i class="bi bi-arrow-left-circle"></i>
            </a>
            <h2 class="delivery-order">Delivery Order</h2>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <ul class="nav nav-tabs">
            @foreach($statusLink as $key => $label)
                <li class="nav-item">
                    <a class="nav-link delivery-status {{ $status == $key ? 'active' : '' }}" href="{{ route('courier.delivery', ['status' => $key]) }}">
                        {{ $label }}
                    </a>
                </li>
            @endforeach
        </ul>
    
        <div class="tab-content mt-3">
            @if($status === 'to-ship')
                <form method="get" action="{{ route('courier.delivery') }}" id="paymentMethodForm" class="mb-3" style="max-width: 250px;">
                    <input type="hidden" name="status" value="{{ request('status', 'to-ship') }}">
                    <label for="payment_method">Payment Method</label>
                    <select name="payment_method" class="form-select" onchange="document.getElementById('paymentMethodForm').submit();">
                        <option value="">All</option>
                        @foreach ($paymentMethods as $method)
                            <option value="{{ $method->payment_method_slug }}"
                                {{ request('payment_method') === $method->payment_method_slug ? 'selected' : '' }}>
                                {{ $method->payment_method_name }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <form method="get" action="{{ route('courier.delivery') }}" class="mb-4" style="max-width: 350px;">
                    <input type="hidden" name="status" value="{{ request('status', 'to-ship') }}">
                    @if(request('payment_method'))
                        <input type="hidden" name="payment_method" value="{{ request('payment_method') }}">
                    @endif
                    <div class="input-group">
                        <input type="text" name="courier_search" value="{{ request('courier_search') }}" class="form-control" placeholder="Type here to search">
                        <button type="submit" class="btn btn-success">Search</button>
                    </div>
                </form>

                <table class="w-100 border-collapse text-center pb-3" style="table-layout: fixed;">
                    <thead class="text-center">
                        <tr>
                            <th class="p-2">Transaction Number</th>
                            <th class="p-2">Customer Name</th>
                            <th class="p-2">Payment Method</th>
                            <th class="p-2">City</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $transaction)
                            <tr class="text-center table-row-hover to-ship"
                                data-bs-toggle="modal"
                                data-bs-target="#toShipStatusModal"
                                data-transaction-id="{{ $transaction->transaction_id }}"
                                data-transaction-date="{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('l, d F Y') }}"
                                data-customer-name="{{ $transaction->mscustomer->full_name }}"
                                data-customer-address="{{ $transaction->mscustomeraddress->customer_full_address_name }}"
                                data-payment-method="{{ $transaction->mspaymentmethod->payment_method_name }}"
                                data-total-amount="Rp {{ number_format($transaction->total_price, 0, ',', '.') }}">
                                <td class="p-2">{{ $transaction->transaction_id }}</td>
                                <td class="p-2">{{ $transaction->mscustomer->full_name }}</td>
                                <td class="p-2">{{ $transaction->mspaymentmethod->payment_method_name }}</td>
                                <td class="p-2">{{ $transaction->mscustomeraddress->customer_short_address_name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination-container">
                    <div class="d-flex justify-content-center mt-4">
                        {{ $data->links('pagination::bootstrap-5') }}
                    </div>
                </div>

                @elseif($status === 'in-progress')
                    <form method="get" action="{{ route('courier.delivery') }}" id="paymentMethodForm" class="mb-3" style="max-width: 250px;">
                        <input type="hidden" name="status" value="{{ request('status', 'in-progress') }}">
                        @if(request('courier_in_progress_search'))
                            <input type="hidden" name="courier_search" value="{{ request('courier_search') }}">
                        @endif
                    
                        <label for="payment_method">Payment Method</label>
                        <select name="payment_method" class="form-select" onchange="document.getElementById('paymentMethodForm').submit();">
                            <option value="">All</option>
                            @foreach ($paymentMethods as $method)
                                <option value="{{ $method->payment_method_slug }}"
                                    {{ request('payment_method') === $method->payment_method_slug ? 'selected' : '' }}>
                                    {{ $method->payment_method_name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                
                    <form method="get" action="{{ route('courier.delivery') }}" class="mb-4" style="max-width: 350px;">
                        <input type="hidden" name="status" value="{{ request('status', 'in-progress') }}">
                        @if(request('payment_method'))
                            <input type="hidden" name="payment_method" value="{{ request('payment_method') }}">
                        @endif
                    
                        <div class="input-group">
                            <input type="text" name="courier_search" value="{{ request('courier_search') }}" class="form-control" placeholder="Type here to search">
                            <button type="submit" class="btn btn-success">Search</button>
                        </div>
                    </form>
            
                <table class="w-100 border-collapse text-center" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <th class="p-2">Shipment Number</th>
                            <th class="p-2">Customer Name</th>
                            <th class="p-2">Payment Method</th>
                            <th class="p-2">City</th>
                        </tr>
                    </thead>
                    <tbody> 
                        @foreach ($data as $shipment)
                        <tr class="text-center table-row-hover in-progress"
                            data-payment="{{ $shipment->transactionheader->mspaymentmethod->payment_method_name }}"
                            data-shipment-id="{{ $shipment->shipment_id }}"
                            data-transaction-id="{{ $shipment->transactionheader->transaction_id }}"
                            data-shipment-date="{{ \Carbon\Carbon::parse($shipment->shipment_date_start)->format('l, d F Y \| H:i:s') }}"
                            data-address="{{ $shipment->transactionheader->mscustomeraddress->customer_full_address_name }}"
                            data-payment-method="{{ $shipment->transactionheader->mspaymentmethod->payment_method_name }}"
                            data-status="{{ $shipment->shipment_status }}"
                            data-total-price="{{ $shipment->transactionheader->total_price }}">
                            <td class="p-2">{{ $shipment->shipment_id }}</td>
                            <td class="p-2">{{ $shipment->transactionheader->mscustomer->full_name }}</td>
                            <td class="p-2">{{ $shipment->transactionheader->mspaymentmethod->payment_method_name }}</td>
                            <td class="p-2">{{ $shipment->transactionheader->mscustomeraddress->customer_short_address_name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination-container">
                    <div class="d-flex justify-content-center mt-4">
                        {{ $data->links('pagination::bootstrap-5') }}
                    </div>
                </div>
                
            @elseif($status === 'delivered')
                <form method="get" action="{{ route('courier.delivery') }}" id="paymentMethodForm" class="mb-3" style="max-width: 250px;">
                    <input type="hidden" name="status" value="{{ request('status', 'delivered') }}">
                    @if(request('courier_search'))
                        <input type="hidden" name="courier_search" value="{{ request('courier_search') }}">
                    @endif
                
                    <label for="payment_method">Payment Method</label>
                    <select name="payment_method" class="form-select" onchange="document.getElementById('paymentMethodForm').submit();">
                        <option value="">All</option>
                        @foreach ($paymentMethods as $method)
                            <option value="{{ $method->payment_method_slug }}"
                                {{ request('payment_method') === $method->payment_method_slug ? 'selected' : '' }}>
                                {{ $method->payment_method_name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            
                <form method="get" action="{{ route('courier.delivery') }}" class="mb-4" style="max-width: 350px;">
                    <input type="hidden" name="status" value="{{ request('status', 'delivered') }}">
                    @if(request('payment_method'))
                        <input type="hidden" name="payment_method" value="{{ request('payment_method') }}">
                    @endif
                
                    <div class="input-group">
                        <input type="text" name="courier_search" value="{{ request('courier_search') }}" class="form-control" placeholder="Type here to search">
                        <button type="submit" class="btn btn-success">Search</button>
                    </div>
                </form>
                <table class="w-100 border-collapse text-center">
                    <thead>
                        <tr>
                            <th class="p-2">Shipment Number</th>
                            <th class="p-2">Customer Name</th>
                            <th class="p-2">Payment Method</th>
                            <th class="p-2">City</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $shipment)
                        <tr class="text-center table-row-hover delivered"
                            data-bs-toggle="modal"
                            data-bs-target="#deliveredStatusModal"
                            data-shipment-id-delivered="{{ $shipment->shipment_id }}"
                            data-transaction-id-delivered="{{ $shipment->transactionheader->transaction_id }}"
                            data-customer-name-delivered="{{ $shipment->transactionheader->mscustomer->full_name }}"
                            data-shipment-start-date-delivered="{{ \Carbon\Carbon::parse($shipment->shipment_date_start)->format('l, d F Y \| H:i:s') }}"
                            data-shipment-end-date-delivered="{{ \Carbon\Carbon::parse($shipment->shipment_date_end)->format('l, d F Y \| H:i:s') }}"
                            data-address-delivered="{{ $shipment->transactionheader->mscustomeraddress->customer_full_address_name }}"
                            data-payment-method-delivered="{{ $shipment->transactionheader->mspaymentmethod->payment_method_name }}"
                            data-shipment-recipient-name-delivered="{{ $shipment->shipment_recipient_name }}"
                            data-total-amount-delivered="Rp {{ number_format($shipment->transactionheader->total_price, 2, ',', '.') }}">
                            <td class="p-2">{{ $shipment->shipment_id }}</td>
                            <td class="p-2">{{ $shipment->transactionheader->mscustomer->full_name }}</td>
                            <td class="p-2">{{ $shipment->transactionheader->mspaymentmethod->payment_method_name }}</td>
                            <td class="p-2">{{ $shipment->transactionheader->mscustomeraddress->customer_short_address_name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination-container">
                    <div class="d-flex justify-content-center mt-4">
                        {{ $data->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            
            @elseif($status === 'cancelled')
            <form method="get" action="{{ route('courier.delivery') }}" id="paymentMethodForm" class="mb-3" style="max-width: 250px;">
                <input type="hidden" name="status" value="{{ request('status', 'in-progress') }}">
                @if(request('courier_search'))
                    <input type="hidden" name="courier_search" value="{{ request('courier_search') }}">
                @endif
            
                <label for="payment_method">Payment Method</label>
                <select name="payment_method" class="form-select" onchange="document.getElementById('paymentMethodForm').submit();">
                    <option value="">All</option>
                    @foreach ($paymentMethods as $method)
                        <option value="{{ $method->payment_method_slug }}"
                            {{ request('payment_method') === $method->payment_method_slug ? 'selected' : '' }}>
                            {{ $method->payment_method_name }}
                        </option>
                    @endforeach
                </select>
            </form>
        
            <form method="get" action="{{ route('courier.delivery') }}" class="mb-4" style="max-width: 350px;">
                <input type="hidden" name="status" value="{{ request('status', 'in-progress') }}">
                @if(request('payment_method'))
                    <input type="hidden" name="payment_method" value="{{ request('payment_method') }}">
                @endif
            
                <div class="input-group">
                    <input type="text" name="courier_search" value="{{ request('courier_search') }}" class="form-control" placeholder="Type here to search">
                    <button type="submit" class="btn btn-success">Search</button>
                </div>
            </form>
                <table class="w-100 border-collapse text-center">
                    <thead>
                        <tr>
                            <th class="p-2">Shipment Number</th>
                            <th class="p-2">Customer Name</th>
                            <th class="p-2">Payment Method</th>
                            <th class="p-2">City</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $shipment)
                        <tr class="text-center table-row-hover cancelled"
                            data-bs-toggle="modal"
                            data-bs-target="#cancelledStatusModal"
                            data-shipment-id-cancelled="{{ $shipment->shipment_id }}"
                            data-transaction-id-cancelled="{{ $shipment->transactionheader->transaction_id }}"
                            data-customer-name-cancelled="{{ $shipment->transactionheader->mscustomer->full_name }}"
                            data-shipment-start-date-cancelled="{{ \Carbon\Carbon::parse($shipment->shipment_date_start)->format('l, d F Y \| H:i:s') }}"
                            data-shipment-end-date-cancelled="{{ \Carbon\Carbon::parse($shipment->shipment_date_end)->format('l, d F Y \| H:i:s') }}"
                            data-address-cancelled="{{ $shipment->transactionheader->mscustomeraddress->customer_full_address_name }}"
                            data-payment-method-cancelled="{{ $shipment->transactionheader->mspaymentmethod->payment_method_name }}"
                            data-total-amount-cancelled="Rp {{ number_format($shipment->transactionheader->total_price, 2, ',', '.') }}">
                            <td class="p-2">{{ $shipment->shipment_id }}</td>
                            <td class="p-2">{{ $shipment->transactionheader->mscustomer->full_name }}</td>
                            <td class="p-2">{{ $shipment->transactionheader->mspaymentmethod->payment_method_name }}</td>
                            <td class="p-2">{{ $shipment->transactionheader->mscustomeraddress->customer_short_address_name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination-container">
                    <div class="d-flex justify-content-center mt-4">
                        {{ $data->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="toShipStatusModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" style="max-width: 800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Shipment Summary</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Transaction Date:</strong> <span id="modalTransactionDate"></span></p>
                    <p><strong>Customer Name:</strong> <span id="modalCustomer"></span></p>
                    <p><strong>Address:</strong> <span id="modalAddressToShip"></span></p>
                    <p><strong>Payment Method:</strong> <span id="modalPaymentToShip"></span></p>
                </div>
                <div class="modal-footer">
                    <form id="confirmForm" method="post">
                        @csrf
                        <button type="submit" class="btn btn-success">Start Delivery</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="codConfirmModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">COD Confirmation Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Total:</strong> <span id="cod-total-price"></span></p>
                    <p>Has the item been paid for and does it match the total?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="cod-no" class="btn btn-danger">No</button>
                    <button type="button" id="cod-yes" class="btn btn-success">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="inProgressStatusModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" style="max-width: 700px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Shipment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Shipment Number:</strong> <span id="modalShipmentNumber"></span></p>
                    <p><strong>Transaction Number:</strong> <span id="modalTransactionNumber"></span></p>
                    <p><strong>Shipment Date Start:</strong> <span id="modalShipmentDateStart"></span></p>
                    <p><strong>Address:</strong> <span id="modalAddressInProgress"></span></p>
                    <p><strong>Payment Method:</strong> <span id="modalPaymentInProgress"></span></p>
                    <form id="inProgressConfirmForm" method="POST">
                        @csrf
                        <input type="hidden" name="shipment_id" id="hiddenShipmentId">
                        <input type="hidden" name="transaction_id" id="hiddenTransactionId">
                        <input type="hidden" name="shipment_date_end" value="{{ now() }}">
                        
                        <div class="mb-3">
                            <label for="recipientName" class="form-label"><strong>Recipient Name:</strong></label>
                            <input 
                                type="text" 
                                class="form-control @error('shipment_recipient_name') is-invalid @enderror" 
                                id="recipientName" 
                                name="shipment_recipient_name" 
                                value="{{ old('shipment_recipient_name') }}">
                            <div class="invalid-feedback" id="recipientError"></div>
                        </div> 
                    
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Mark as Delivered</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deliveredStatusModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" style="max-width: 700px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Shipment History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Shipment Number:</strong> <span id="modalShipmentNumberDelivered"></span></p>
                    <p><strong>Transaction Number:</strong> <span id="modalTransactionNumberDelivered"></span></p>
                    <p><strong>Customer Name:</strong> <span id="modalCustomerNameDelivered"></span></p>
                    <p><strong>Shipment Start Date:</strong> <span id="modalShipmentDateStartDelivered"></span></p>
                    <p><strong>Shipment End Date:</strong> <span id="modalShipmentDateEndDelivered"></span></p>
                    <p><strong>Address:</strong> <span id="modalAddressDelivered"></span></p>
                    <p><strong>Payment Method:</strong> <span id="modalPaymentDelivered"></span></p>
                    <p><strong>Recipient Name:</strong> <span id="modalRecipientNameDelivered"></span></p>
                    <p><strong>Total Price:</strong> <span id="modalTotalPriceDelivered"></span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancelledStatusModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" style="max-width: 700px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Shipment History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Shipment Number:</strong> <span id="modalShipmentNumberCancelled"></span></p>
                    <p><strong>Transaction Number:</strong> <span id="modalTransactionNumberCancelled"></span></p>
                    <p><strong>Customer Name:</strong> <span id="modalCustomerNameCancelled"></span></p>
                    <p><strong>Shipment Start Date:</strong> <span id="modalShipmentDateStartCancelled"></span></p>
                    <p><strong>Shipment End Date:</strong> <span id="modalShipmentDateEndCancelled"></span></p>
                    <p><strong>Address:</strong> <span id="modalAddressCancelled"></span></p>
                    <p><strong>Payment Method:</strong> <span id="modalPaymentCancelled"></span></p>
                    <p><strong>Total Price:</strong> <span id="modalTotalPriceCancelled"></span></p>
                </div>
            </div>
        </div>
    </div>

@endsection