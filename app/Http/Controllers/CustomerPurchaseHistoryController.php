<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionHeader;
use App\Models\MsCustomer;
use App\Models\MsProduct;
use Illuminate\Support\Facades\Auth;

class CustomerPurchaseHistoryController extends Controller
{
    public function index(Request $request)
    {
        $customers = Auth::guard('customer')->user();

        $statusMap = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'out-for-delivery' => 'Out for Delivery',
            'shipped' => 'Shipped',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];

        $urlStatus = $request->query('status', 'Completed');
        $status = $statusMap[$urlStatus] ?? 'Completed';

        $transactions = TransactionHeader::with(['transactiondetail.msproduct', 'mspaymentmethod', 'mscustomeraddress'])
            ->where('customer_id', $customers->customer_id)
            ->filter([
                'status' => $status,
                'search' => $request->query('search')
            ])
            ->orderBy('transaction_date', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('purchasehistory.index', [
            'transactions' => $transactions,
            'status' => $status,
            'statusMap' => $statusMap,
        ]);
    }

    public function cancelOrder($transaction_id)
    {
        $transaction = TransactionHeader::with('transactiondetail.msproduct', 'mspaymentmethod')->findOrFail($transaction_id);

        $customer = Auth::guard('customer')->user();

        if ($transaction->customer_id != $customer->customer_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($transaction->transaction_status != 'Pending') {
            return redirect()->back()->with('error', 'This order cannot be canceled.');
        }

        $transactionDetails = $transaction->transactiondetail;

        $totalRefund = 0;

        foreach ($transactionDetails as $transactiondetail) {
            $product = $transactiondetail->msproduct;
            $quantity = $transactiondetail->quantity;

            $product->product_stock += $quantity;
            $product->save();

            $totalRefund += $transactiondetail->unit_price_at_buy * $quantity;
        }

        if ($transaction->mspaymentmethod->payment_method_name == 'Application Balance'){
            $customer->customer_balance += $totalRefund;
            $customer->save();
        }

        $transaction->transaction_status = 'Cancelled';
        $transaction->save();

        return redirect()->back()->with('success', 'Order successfully cancelled. Your balance has been refunded.');
    }

    public function confirmOrder($transaction_id)
    {
        $transaction = TransactionHeader::with('transactiondetail.msproduct', 'mspaymentmethod')->findOrFail($transaction_id);

        $customer = Auth::guard('customer')->user();

        if ($transaction->customer_id != $customer->customer_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($transaction->transaction_status != 'Shipped') {
            return redirect()->back()->with('error', 'This order cannot be confirm.');
        }

        $transaction->transaction_status = 'Completed';
        $transaction->save();

        return redirect()->back()->with('success', 'Order successfully completed.');
    }
}
