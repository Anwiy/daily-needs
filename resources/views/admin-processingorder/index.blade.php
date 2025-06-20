@extends ('admin-sidebar.index')

@section('container')
    <div class="content">
        <h2 class="text-xl font-bold mb-4">Processing Orders</h2>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif
      
        <table class="w-100 mx-auto border-collapse text-center" style="table-layout: fixed;">
            <thead class="text-center">       
                <tr>
                    <th class="p-2">Transaction Number</th>
                    <th class="p-2">Customer Name</th>
                    <th class="p-2">Payment Method</th>
                    <th class="p-2">Total Price</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                    <tr class="text-center table-row-hover pending"
                        data-bs-toggle="modal"
                        data-bs-target="#transactionModal"
                        data-transactiondate="{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('l, d F Y \| H:i:s') }}"
                        data-customer="{{ $transaction->mscustomer->full_name }}"
                        data-address="{{ $transaction->mscustomeraddress->customer_full_address_name }}"
                        data-payment="{{ $transaction->mspaymentmethod->payment_method_name }}"
                        data-products='@json($transaction->transactiondetail)'
                        data-total="Rp {{ number_format($transaction->total_price, 0, ',', '.') }}">
                        <td class="p-2">{{ $transaction->transaction_id }}</td>
                        <td class="p-2">{{ $transaction->mscustomer->full_name }}</td>
                        <td class="p-2">{{ $transaction->mspaymentmethod->payment_method_name }}</td>
                        <td class="p-2">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-4 text-center">No pending orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
      
      <div class="pagination-container">
            <div class="d-flex justify-content-center mt-4">
                {{ $transactions->links('pagination::bootstrap-5') }}
            </div>
      </div>
    </div>
  
    <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" style="max-width: 800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Summary</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Transaction Date:</strong> <span id="modalTransactionDatePending"></span></p>
                    <p><strong>Customer Name:</strong> <span id="modalCustomerPending"></span></p>
                    <p><strong>Address:</strong> <span id="modalAddressPending"></span></p>
                    <p><strong>Payment Method:</strong> <span id="modalPaymentPending"></span></p>
                
                    <table class="table table-bordered" id="modalProductsPending">
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
                                <th id="modalTotalPending"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <form id="confirmForm" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Confirm Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
  
@endsection