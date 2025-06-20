@extends('layouts.dashboardmain')

@section('container')
<div class="purchase-history-container">
    <div class="history-header">
        <a href="{{ url()->previous() }}" class="back-button">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="history-header-purchase-history">Purchase History</h2>
        <div class="status-filter">
            @foreach($statusMap as $key => $value)
                <a href="?status={{ $key }}" class="{{ $status == $value ? 'active' : '' }}">{{ $value }}</a>
            @endforeach
        </div>
    </div>

    <div class="search-box">
        <form action="" method="GET">
            <input type="hidden" name="status" value="{{ request('status', 'completed') }}">
            <input type="text" name="search" placeholder="Search transactions..." value="{{ request('search') }}">
            <button type="submit"><i class="bi bi-search"></i></button>
        </form>
    </div>

    @if($transactions->isEmpty())
        <div class="empty-state">
            <p>No transactions found</p>
        </div>
    @else
        <div class="transactions-list">
            @foreach($transactions as $transaction)
            <div class="transaction-card">
                <div class="transaction-header">
                    <span class="order-date">
                        {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y | H:i') }}
                    </span>
                    <span class="order-status {{ strtolower($transaction->transaction_status) }}">{{ $transaction->transaction_status }}</span>
                </div>

                <div class="transaction-body">
                    <div class="products-list">
                        @foreach($transaction->transactiondetail as $detail)
                        <div class="product-item">
                            <img src="{{ asset('storage/product_photos/' . $detail->msproduct->product_image) }}" alt="{{ $detail->msproduct->product_name }}" class="product-image">
                            <div class="product-info">
                                <h4>{{ $detail->msproduct->product_name }}</h4>
                                <p>{{ $detail->quantity }} x Rp {{ number_format($detail->unit_price_at_buy, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="transaction-summary">
                        <div class="summary-row">
                            <span>Payment Method:</span>
                            <span>{{ $transaction->mspaymentmethod->payment_method_name }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping Address:</span>
                            <span>{{ $transaction->mscustomeraddress->customer_full_address_name }}</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total:</span>
                            <span>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="transaction-footer">
                    @if($transaction->transaction_status == 'Pending')
                        <button type="button" class="cancel-btn" data-bs-toggle="modal" data-bs-target="#cancelOrderModal-{{ $transaction->transaction_id }}">
                            Cancel Order
                        </button>
                    @elseif($transaction->transaction_status == 'Shipped')
                        <button type="button" class="confirm-btn" data-bs-toggle="modal" data-bs-target="#completeOrderModal-{{ $transaction->transaction_id }}">
                            Complete Order
                        </button>
                    @endif
                </div>
            </div>

            <div class="modal fade" id="cancelOrderModal-{{ $transaction->transaction_id }}" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('purchasehistory.cancel', $transaction->transaction_id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="modal-header">
                                <h5 class="modal-title" id="cancelOrderModalLabel">Cancellation Confirmation</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to cancel your order?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                <button type="submit" class="btn btn-danger">Yes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        
            <div class="modal fade" id="completeOrderModal-{{ $transaction->transaction_id }}" tabindex="-1" aria-labelledby="confirmOrderModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('purchasehistory.confirm', $transaction->transaction_id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="modal-header">
                                <h5 class="modal-title" id="cancelOrderModalLabel">Order Confirmation Complete</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to complete your order?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
                                <button type="submit" class="btn btn-success">Yes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="pagination-container">
            <div class="d-flex justify-content-center mt-4">
                {{ $transactions->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection