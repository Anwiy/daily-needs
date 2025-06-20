<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionHeader;
use App\Models\TransactionDetail;
use App\Models\MsAdmin;
use App\Models\MsPaymentMethod;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminTransactionHistoryController extends Controller
{
    public function index(Request $request)
    {
        $admins = Auth::guard('admin')->user();

        $paymentMethods = MsPaymentMethod::all();

        $statusLink = [
            'processing' => 'Processing',
            'out-for-delivery' => 'Out for Delivery',
            'shipped' => 'Shipped',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];

        $status = $request->query('status', 'completed');

        if ($status === 'processing')
        {
            $data = TransactionHeader::with(['mscustomer', 'msshipment', 'mscustomeraddress', 'mspaymentmethod', 'transactiondetail.msproduct'])
                                    ->where('admin_id', $admins->admin_id)
                                    ->whereDoesntHave('msshipment')
                                    ->filter([
                                        'status' => 'Processing',
                                        'admin_search' => $request->input('admin_search'),
                                        'payment_method' => $request->input('payment_method')
                                    ])
                                    ->orderBy('transaction_date', 'desc')
                                    ->paginate(10)
                                    ->withQueryString();
        }

        else if ($status === 'out-for-delivery')
        {
            $data = TransactionHeader::with(['mscustomer', 'msshipment', 'mscustomeraddress', 'mspaymentmethod', 'transactiondetail.msproduct'])
                                    ->where('admin_id', $admins->admin_id)
                                    ->whereHas('msshipment', function ($query) use ($status) {
                                        $query->filter(['shipment_status' => 'In Progress']);
                                    })
                                    ->filter([
                                        'status' => 'Out for Delivery',
                                        'admin_search' => $request->input('admin_search'),
                                        'payment_method' => $request->input('payment_method')
                                    ])
                                    ->orderBy('transaction_date', 'desc')
                                    ->paginate(10)
                                    ->withQueryString();
        }

        else if ($status === 'shipped')
        {
            $data = TransactionHeader::with(['mscustomer', 'msshipment', 'mscustomeraddress', 'mspaymentmethod', 'transactiondetail.msproduct'])
                                    ->where('admin_id', $admins->admin_id)
                                    ->whereHas('msshipment', function ($query) use ($status) {
                                        $query->filter(['shipment_status' => 'Delivered']);
                                    })
                                    ->filter([
                                        'status' => 'Shipped',
                                        'admin_search' => $request->input('admin_search'),
                                        'payment_method' => $request->input('payment_method')
                                    ])
                                    ->orderBy('transaction_date', 'desc')
                                    ->paginate(10)
                                    ->withQueryString();
        }

        else if ($status === 'completed')
        {
            $data = TransactionHeader::with(['mscustomer', 'msshipment', 'mscustomeraddress', 'mspaymentmethod', 'transactiondetail.msproduct'])
                                    ->where('admin_id', $admins->admin_id)
                                    ->whereHas('msshipment', function ($query) use ($status) {
                                        $query->filter(['shipment_status' => 'Delivered']);
                                    })
                                    ->filter([
                                        'status' => 'Completed',
                                        'admin_search' => $request->input('admin_search'),
                                        'payment_method' => $request->input('payment_method')
                                    ])
                                    ->orderBy('transaction_date', 'desc')
                                    ->paginate(10)
                                    ->withQueryString();
        }

        else if ($status === 'cancelled')
        {
            $data = TransactionHeader::with(['mscustomer', 'msshipment', 'mscustomeraddress', 'mspaymentmethod', 'transactiondetail.msproduct'])
                                    ->where('admin_id', $admins->admin_id)
                                    ->whereHas('msshipment', function ($query) use ($status) {
                                        $query->filter(['shipment_status' => 'Cancelled']);
                                    })
                                    ->filter([
                                        'status' => 'Cancelled',
                                        'admin_search' => $request->input('admin_search'),
                                        'payment_method' => $request->input('payment_method')
                                    ])
                                    ->orderBy('transaction_date', 'desc')
                                    ->paginate(10)
                                    ->withQueryString(); 
        }

        else {
            $data = collect();
        }

        $data->each(function ($data) {
            $data->transactiondetail->each->append('subtotal');
        });

        return view('admin-transactionhistory.index', compact('data', 'statusLink', 'status', 'paymentMethods'));
    }
}
