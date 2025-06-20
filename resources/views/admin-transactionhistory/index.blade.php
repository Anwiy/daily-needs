@extends ('admin-sidebar.index')

@section('container')
    <div class="content">
        @if(session('success'))
            <div class="bg-green-200 text-green-800 p-2 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif
        <h2 class="mb-4">Transaction History</h2>

        <ul class="nav nav-tabs">
            @foreach($statusLink as $key => $label)
                <li class="nav-item">
                    <a class="nav-link {{ $status == $key ? 'active' : '' }}"
                       href="{{ route('admin.transaction-history', ['status' => $key]) }}">
                        {{ $label }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="tab-content mt-3">
            @if($status === 'processing')
                <form method="get" action="{{ route('admin.transaction-history') }}" id="paymentMethodForm" class="mb-3" style="max-width: 250px;">
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

                <form method="get" action="{{ route('admin.transaction-history') }}" class="mb-4" style="max-width: 350px;">
                    <input type="hidden" name="status" value="{{ request('status', 'to-ship') }}">
                    @if(request('payment_method'))
                        <input type="hidden" name="payment_method" value="{{ request('payment_method') }}">
                    @endif
                    <div class="input-group">
                        <input type="text" name="admin_search" value="{{ request('admin_search') }}" class="form-control" placeholder="Type here to search">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </form>
                <table class="w-100 border-collapse text-center" style="table-layout: fixed;">
                    <thead class="text-center">
                        <tr>
                            <th class="p-2">Transaction Number</th>
                            <th class="p-2">Customer Name</th>
                            <th class="p-2">Payment Method</th>
                            <th class="p-2">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $transaction)
                            <tr class="text-center table-row-hover processing"
                                data-bs-toggle="modal"
                                data-bs-target="#adminProcessingModal"
                                data-transactiondate-processing="{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('l, d F Y \| H:i:s') }}"
                                data-customer-processing="{{ $transaction->mscustomer->full_name }}"
                                data-address-processing="{{ $transaction->mscustomeraddress->customer_full_address_name }}"
                                data-payment-processing="{{ $transaction->mspaymentmethod->payment_method_name }}"
                                data-products-processing='@json($transaction->transactiondetail)'
                                data-total-processing="Rp {{ number_format($transaction->total_price, 0, ',', '.') }}">
                                <td class="p-2">{{ $transaction->transaction_id }}</td>
                                <td class="p-2">{{ $transaction->mscustomer->full_name }}</td>
                                <td class="p-2">{{ $transaction->mspaymentmethod->payment_method_name }}</td>
                                <td class="p-2">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center">No processing orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="pagination-container">
                    <div class="d-flex justify-content-center mt-4">
                        {{ $data->links('pagination::bootstrap-5') }}
                    </div>
                </div>

            @elseif($status === 'out-for-delivery')
                <form method="get" action="{{ route('admin.transaction-history') }}" id="paymentMethodForm" class="mb-3" style="max-width: 250px;">
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

                <form method="get" action="{{ route('admin.transaction-history') }}" class="mb-4" style="max-width: 350px;">
                    <input type="hidden" name="status" value="{{ request('status', 'to-ship') }}">
                    @if(request('payment_method'))
                        <input type="hidden" name="payment_method" value="{{ request('payment_method') }}">
                    @endif
                    <div class="input-group">
                        <input type="text" name="admin_search" value="{{ request('admin_search') }}" class="form-control" placeholder="Type here to search">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </form>
                <table class="w-100 border-collapse text-center" style="table-layout: fixed;">
                    <thead class="text-center">
                        <tr>
                            <th class="p-2">Transaction Number</th>
                            <th class="p-2">Customer Name</th>
                            <th class="p-2">Payment Method</th>
                            <th class="p-2">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $transaction)
                            <tr class="text-center border-b out-for-delivery"
                                data-bs-toggle="modal"
                                data-bs-target="#adminOutfordeliveryModal"
                                data-shipmentnumber-outfordelivery="{{ $transaction->msshipment->shipment_id }}"
                                data-transactiondate-outfordelivery="{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('l, d F Y \| H:i:s') }}"
                                data-shipmentdatestart-outfordelivery="{{ \Carbon\Carbon::parse($transaction->msshipment->shipment_date_start)->format('l, d F Y \| H:i:s') }}"
                                data-customer-outfordelivery="{{ $transaction->mscustomer->full_name }}"
                                data-address-outfordelivery="{{ $transaction->mscustomeraddress->customer_full_address_name }}"
                                data-payment-outfordelivery="{{ $transaction->mspaymentmethod->payment_method_name }}"
                                data-products-outfordelivery='@json($transaction->transactiondetail)'
                                data-total-outfordelivery="Rp {{ number_format($transaction->total_price, 0, ',', '.') }}">
                                <td class="p-2">{{ $transaction->transaction_id }}</td>
                                <td class="p-2">{{ $transaction->mscustomer->full_name }}</td>
                                <td class="p-2">{{ $transaction->mspaymentmethod->payment_method_name }}</td>
                                <td class="p-2">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center">No out for delivery orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="pagination-container">
                    <div class="d-flex justify-content-center mt-4">
                        {{ $data->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            
            @elseif($status === 'shipped')
                <form method="get" action="{{ route('admin.transaction-history') }}" id="paymentMethodForm" class="mb-3" style="max-width: 250px;">
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

                <form method="get" action="{{ route('admin.transaction-history') }}" class="mb-4" style="max-width: 350px;">
                    <input type="hidden" name="status" value="{{ request('status', 'to-ship') }}">
                    @if(request('payment_method'))
                        <input type="hidden" name="payment_method" value="{{ request('payment_method') }}">
                    @endif
                    <div class="input-group">
                        <input type="text" name="admin_search" value="{{ request('admin_search') }}" class="form-control" placeholder="Type here to search">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </form>
                <table class="w-100 border-collapse text-center" style="table-layout: fixed;">
                    <thead class="text-center">
                        <tr>
                            <th class="p-2">Transaction Number</th>
                            <th class="p-2">Customer Name</th>
                            <th class="p-2">Payment Method</th>
                            <th class="p-2">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $transaction)
                            <tr class="text-center border-b shipped"
                                data-bs-toggle="modal"
                                data-bs-target="#adminShippedModal"
                                data-shipmentnumber-shipped="{{ $transaction->msshipment->shipment_id }}"
                                data-transactiondate-shipped="{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('l, d F Y \| H:i:s') }}"
                                data-shipmentdatestart-shipped="{{ \Carbon\Carbon::parse($transaction->msshipment->shipment_date_start)->format('l, d F Y \| H:i:s') }}"
                                data-shipmentdateend-shipped="{{ \Carbon\Carbon::parse($transaction->msshipment->shipment_date_end)->format('l, d F Y \| H:i:s') }}"
                                data-customer-shipped="{{ $transaction->mscustomer->full_name }}"
                                data-address-shipped="{{ $transaction->mscustomeraddress->customer_full_address_name }}"
                                data-shipmentrecipient-shipped="{{ $transaction->msshipment->shipment_recipient_name }}"
                                data-payment-shipped="{{ $transaction->mspaymentmethod->payment_method_name }}"
                                data-products-shipped='@json($transaction->transactiondetail)'
                                data-total-shipped="Rp {{ number_format($transaction->total_price, 0, ',', '.') }}">
                                <td class="p-2">{{ $transaction->transaction_id }}</td>
                                <td class="p-2">{{ $transaction->mscustomer->full_name }}</td>
                                <td class="p-2">{{ $transaction->mspaymentmethod->payment_method_name }}</td>
                                <td class="p-2">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center">No shipped orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="pagination-container">
                    <div class="d-flex justify-content-center mt-4">
                        {{ $data->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            
            @elseif($status === 'completed')
                <form method="get" action="{{ route('admin.transaction-history') }}" id="paymentMethodForm" class="mb-3" style="max-width: 250px;">
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
            
                <form method="get" action="{{ route('admin.transaction-history') }}" class="mb-4" style="max-width: 350px;">
                    <input type="hidden" name="status" value="{{ request('status', 'to-ship') }}">
                    @if(request('payment_method'))
                        <input type="hidden" name="payment_method" value="{{ request('payment_method') }}">
                    @endif
                    <div class="input-group">
                        <input type="text" name="admin_search" value="{{ request('admin_search') }}" class="form-control" placeholder="Type here to search">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </form>
                <table class="w-100 border-collapse text-center" style="table-layout: fixed;">
                    <thead class="text-center">
                        <tr>
                            <th class="p-2">Transaction Number</th>
                            <th class="p-2">Customer Name</th>
                            <th class="p-2">Payment Method</th>
                            <th class="p-2">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $transaction)
                            <tr class="text-center border-b completed"
                                data-bs-toggle="modal"
                                data-bs-target="#adminCompletedModal"
                                data-shipmentnumber-completed="{{ $transaction->msshipment->shipment_id }}"
                                data-transactiondate-completed="{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('l, d F Y \| H:i:s') }}"
                                data-shipmentdatestart-completed="{{ \Carbon\Carbon::parse($transaction->msshipment->shipment_date_start)->format('l, d F Y \| H:i:s') }}"
                                data-shipmentdateend-completed="{{ \Carbon\Carbon::parse($transaction->msshipment->shipment_date_end)->format('l, d F Y \| H:i:s') }}"
                                data-customer-completed="{{ $transaction->mscustomer->full_name }}"
                                data-address-completed="{{ $transaction->mscustomeraddress->customer_full_address_name }}"
                                data-shipmentrecipient-completed="{{ $transaction->msshipment->shipment_recipient_name }}"
                                data-payment-completed="{{ $transaction->mspaymentmethod->payment_method_name }}"
                                data-products-completed='@json($transaction->transactiondetail)'
                                data-total-completed="Rp {{ number_format($transaction->total_price, 0, ',', '.') }}">
                                <td class="p-2">{{ $transaction->transaction_id }}</td>
                                <td class="p-2">{{ $transaction->mscustomer->full_name }}</td>
                                <td class="p-2">{{ $transaction->mspaymentmethod->payment_method_name }}</td>
                                <td class="p-2">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center">No completed orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="pagination-container">
                    <div class="d-flex justify-content-center mt-4">
                        {{ $data->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            
            @elseif($status === 'cancelled')
                <table class="w-100 border-collapse text-center" style="table-layout: fixed;">
                    <thead class="text-center">
                        <tr>
                            <th class="p-2">Transaction Number</th>
                            <th class="p-2">Customer Name</th>
                            <th class="p-2">Payment Method</th>
                            <th class="p-2">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $transaction)
                            <tr class="text-center border-b cancelled"
                                data-bs-toggle="modal"
                                data-bs-target="#adminCancelledModal"
                                data-shipmentnumber-cancelled="{{ $transaction->msshipment->shipment_id }}"
                                data-transactiondate-cancelled="{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('l, d F Y \| H:i:s') }}"
                                data-shipmentdatestart-cancelled="{{ \Carbon\Carbon::parse($transaction->msshipment->shipment_date_start)->format('l, d F Y \| H:i:s') }}"
                                data-shipmentdateend-cancelled="{{ \Carbon\Carbon::parse($transaction->msshipment->shipment_date_end)->format('l, d F Y \| H:i:s') }}"
                                data-customer-cancelled="{{ $transaction->mscustomer->full_name }}"
                                data-address-cancelled="{{ $transaction->mscustomeraddress->customer_full_address_name }}"
                                data-payment-cancelled="{{ $transaction->mspaymentmethod->payment_method_name }}"
                                data-products-cancelled='@json($transaction->transactiondetail)'
                                data-total-cancelled="Rp {{ number_format($transaction->total_price, 0, ',', '.') }}">
                                <td class="p-2">{{ $transaction->transaction_id }}</td>
                                <td class="p-2">{{ $transaction->mscustomer->full_name }}</td>
                                <td class="p-2">{{ $transaction->mspaymentmethod->payment_method_name }}</td>
                                <td class="p-2">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center">No cancelled orders found.</td>
                            </tr>
                        @endforelse
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

    <div class="modal fade" id="adminProcessingModal" tabindex="-1" aria-labelledby="adminProcessingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" style="max-width: 800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Transaction Date:</strong> <span id="modalTransactionDateProcessing"></span></p>
                    <p><strong>Customer Name:</strong> <span id="modalCustomerProcessing"></span></p>
                    <p><strong>Address:</strong> <span id="modalAddressProcessing"></span></p>
                    <p><strong>Payment Method:</strong> <span id="modalPaymentProcessing"></span></p>
                
                    <table class="table table-bordered" id="modalProductsProcessing">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Price Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total:</th>
                                <th id="modalTotalProcessing"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="adminOutfordeliveryModal" tabindex="-1" aria-labelledby="adminOutfordeliveryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" style="max-width: 800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Shipment Number:</strong> <span id="modalShipmentNumberOutfordelivery"></span></p>
                    <p><strong>Transaction Date:</strong> <span id="modalTransactionDateOutfordelivery"></span></p>
                    <p><strong>Shipment Start Date:</strong> <span id="modalShipmentStartDateOutfordelivery"></span></p>
                    <p><strong>Customer Name:</strong> <span id="modalCustomerOutfordelivery"></span></p>
                    <p><strong>Address:</strong> <span id="modalAddressOutfordelivery"></span></p>
                    <p><strong>Payment Method:</strong> <span id="modalPaymentOutfordelivery"></span></p>
                
                    <table class="table table-bordered" id="modalProductsOutfordelivery">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Price Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total:</th>
                                <th id="modalTotalOutfordelivery"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="adminShippedModal" tabindex="-1" aria-labelledby="adminShippedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" style="max-width: 800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Shipment Number:</strong> <span id="modalShipmentNumberShipped"></span></p>
                    <p><strong>Transaction Date:</strong> <span id="modalTransactionDateShipped"></span></p>
                    <p><strong>Shipment Start Date:</strong> <span id="modalShipmentStartDateShipped"></span></p>
                    <p><strong>Shipment End Date:</strong> <span id="modalShipmentEndDateShipped"></span></p>
                    <p><strong>Customer Name:</strong> <span id="modalCustomerShipped"></span></p>
                    <p><strong>Address:</strong> <span id="modalAddressShipped"></span></p>
                    <p><strong>Payment Method:</strong> <span id="modalPaymentShipped"></span></p>
                    <p><strong>Recipient Name:</strong> <span id="modalRecipientNameShipped"></span></p>
                
                    <table class="table table-bordered" id="modalProductsShipped">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Price Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total:</th>
                                <th id="modalTotalShipped"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="adminCompletedModal" tabindex="-1" aria-labelledby="adminCompletedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" style="max-width: 800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Shipment Number:</strong> <span id="modalShipmentNumberCompleted"></span></p>
                    <p><strong>Transaction Date:</strong> <span id="modalTransactionDateCompleted"></span></p>
                    <p><strong>Shipment Start Date:</strong> <span id="modalShipmentStartDateCompleted"></span></p>
                    <p><strong>Shipment End Date:</strong> <span id="modalShipmentEndDateCompleted"></span></p>
                    <p><strong>Customer Name:</strong> <span id="modalCustomerCompleted"></span></p>
                    <p><strong>Address:</strong> <span id="modalAddressCompleted"></span></p>
                    <p><strong>Payment Method:</strong> <span id="modalPaymentCompleted"></span></p>
                    <p><strong>Recipient Name:</strong> <span id="modalRecipientNameCompleted"></span></p>
                
                    <table class="table table-bordered" id="modalProductsCompleted">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Price Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total:</th>
                                <th id="modalTotalCompleted"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="adminCancelledModal" tabindex="-1" aria-labelledby="adminCancelledModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" style="max-width: 800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Shipment Number:</strong> <span id="modalShipmentNumberCancelled"></span></p>
                    <p><strong>Transaction Date:</strong> <span id="modalTransactionDateCancelled"></span></p>
                    <p><strong>Shipment Start Date:</strong> <span id="modalShipmentStartDateCancelled"></span></p>
                    <p><strong>Shipment End Date:</strong> <span id="modalShipmentEndDateCancelled"></span></p>
                    <p><strong>Customer Name:</strong> <span id="modalCustomerCancelled"></span></p>
                    <p><strong>Address:</strong> <span id="modalAddressCancelled"></span></p>
                    <p><strong>Payment Method:</strong> <span id="modalPaymentCancelled"></span></p>
                
                    <table class="table table-bordered" id="modalProductsCancelled">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Price Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total:</th>
                                <th id="modalTotalCancelled"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection