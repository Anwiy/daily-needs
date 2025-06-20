<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\MsShipment;  
use App\Models\MsCourier;
use App\Models\TransactionHeader;
use App\Models\MsPaymentMethod;
use Illuminate\Support\Facades\Auth;

class CourierDeliveryOrderController extends Controller
{
    public function show(Request $request){
        $courier = Auth::guard('courier')->user();
        $status = $request->query('status', 'to-ship');
        $paymentMethods = MsPaymentMethod::all();

        $statusLink = [
            'to-ship' => 'To Ship',
            'in-progress' => 'In Progress',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled'
        ];

        if (!$request->has('status')) {
            return redirect()->route('courier.delivery', ['status' => 'to-ship']);
        }

        if ($status === 'to-ship') {
            $data = TransactionHeader::with(['msshipment', 'mscustomer'])
                                        ->where('transaction_status', 'Processing')
                                        ->whereDoesntHave('msshipment')
                                        ->filter([
                                            'status' => 'Processing',
                                            'courier_search' => $request->input('courier_search'),
                                            'payment_method' => $request->input('payment_method')
                                        ])
                                        ->paginate(10)
                                        ->withQueryString();
        }

        else if ($status === 'in-progress') {
            $data = MsShipment::with([
                                        'transactionheader.mscustomer',
                                        'transactionheader.mscustomeraddress',
                                        'transactionheader.transactiondetail',
                                        'transactionheader.mspaymentmethod'
                                        ])
                                        ->whereNotNull('shipment_date_start')
                                        ->whereNull('shipment_date_end')
                                        ->where('courier_id', $courier->courier_id)
                                        ->filter([
                                            'shipment_status' => 'In Progress',
                                            'courier_search' => $request->input('courier_search'),
                                            'payment_method' => $request->input('payment_method')
                                        ])
                                        ->paginate(10)
                                        ->withQueryString();
        }


        else if ($status === 'delivered'){
            $data = MsShipment::with([
                                        'transactionheader.mscustomer',
                                        'transactionheader.mscustomeraddress',
                                        'transactionheader.transactiondetail',
                                        'transactionheader.mspaymentmethod'
                                        ])
                                        ->whereNotNull('shipment_date_end')
                                        ->where('courier_id', $courier->courier_id)
                                        ->filter([
                                            'shipment_status' => 'Delivered',
                                            'courier_search' => $request->input('courier_search'),
                                            'payment_method' => $request->input('payment_method')
                                        ])
                                        ->paginate(10)
                                        ->withQueryString();
        }
        

        else if ($status === 'cancelled'){
            $data = MsShipment::with(['transactionheader.mscustomer',
                                        'transactionheader.mscustomeraddress',
                                        'transactionheader.transactiondetail',
                                        'transactionheader.mspaymentmethod'])
                                        ->whereNotNull('shipment_date_end')
                                        ->where('courier_id', $courier->courier_id)
                                        ->filter([
                                            'shipment_status' => 'Cancelled',
                                            'courier_search' => $request->input('courier_search'),
                                            'payment_method' => $request->input('payment_method')
                                        ])
                                        ->paginate(10)
                                        ->withQueryString();
        }

        else {
            $data = collect();
        }
        
        return view('courier-deliveryorder.index', compact('data', 'status', 'statusLink', 'paymentMethods'));
    }

    public function store(Request $request, $transaction_id)
    {
        $courier = Auth::guard('courier')->user();
        $transaction = TransactionHeader::findOrFail($transaction_id);

        if (!$transaction || $transaction->transaction_status !== 'Processing') {
            return redirect()->back()->with('error', 'Invalid transaction or already processed.');
        }

        MsShipment::create([
            'shipment_date_start' => now(),
            'shipment_status' => 'In Progress',
            'transaction_id' => $transaction->transaction_id,
            'courier_id' => $courier->courier_id,
        ]);

        $transaction->transaction_status = 'Out for Delivery';
        $transaction->save();

        return redirect()->back()->with('success', 'Shipment started successfully.');
    }

    public function update(Request $request, $shipment_id)
    {
        $courier = Auth::guard('courier')->user();
        $shipment = MsShipment::findOrFail($shipment_id);

        if ($shipment->courier_id !== $courier->courier_id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }


        $validateData = $request->validate([
            'shipment_recipient_name' => 'required|max:199',
        ]);

        $shipment->shipment_recipient_name = $validateData['shipment_recipient_name'];
        $shipment->shipment_date_end = now();
        $shipment->shipment_status = 'Delivered';
        $shipment->save();

        $shipment->transactionheader->transaction_status = 'Shipped';
        $shipment->transactionheader->save();

        return redirect()->back()->with('success', 'Shipment marked as delivered.');
    }

    public function cancel($shipment_id)
    {
        $courier = Auth::guard('courier')->user();
        $shipment = MsShipment::findOrFail($shipment_id);

        if ($shipment->courier_id !== $courier->courier_id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $shipment->shipment_status = 'Cancelled';
        $shipment->shipment_date_end = now();
        $shipment->save();

        $shipment->transactionheader->transaction_status = 'Cancelled';
        $shipment->transactionheader->save();

        foreach ($shipment->transactionheader->transactiondetail as $detail) {
            $product = $detail->msproduct;
            $product->product_stock += $detail->quantity;
            $product->save();
        }

        return redirect()->back()->with('success', 'Shipment cancelled and stock returned.');
    }

}
