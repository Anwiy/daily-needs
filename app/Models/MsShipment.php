<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsShipment extends Model
{
    use HasFactory;

    protected $table = 'ms_shipments';

    protected $primaryKey = 'shipment_id';

    protected $guarded = ['shipment_id'];

    public function transactionheader(){
        return $this->belongsTo(TransactionHeader::class, 'transaction_id', 'transaction_id');
    }

    public function mscourier(){
        return $this->belongsTo(MsCourier::class, 'courier_id', 'courier_id');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['shipment_status'] ?? false, function ($query, $status) {
            $query->where('shipment_status', $status);
        });

        $query->when($filters['payment_method'] ?? false, function ($query, $payment_method) {
            $query->whereHas('transactionheader.mspaymentmethod', function ($q) use ($payment_method) {
                $q->where('payment_method_slug', $payment_method);
            });
        });

        $query->when($filters['courier_search'] ?? false, function ($query, $courier_search) {
            $query->where(function ($query) use ($courier_search) {
                $query->whereHas('transactionheader.mscustomeraddress', function ($q) use ($courier_search) {
                    $q->where('customer_address_street', 'like', '%' . $courier_search . '%')
                        ->orWhere('customer_address_postal_code', 'like', '%' . $courier_search . '%')
                        ->orWhere('customer_address_district', 'like', '%' . $courier_search . '%')    
                        ->orWhere('customer_address_regency_city', 'like', '%' . $courier_search . '%')
                        ->orWhere('customer_address_province', 'like', '%' . $courier_search . '%')  
                        ->orWhere('customer_address_country', 'like', '%' . $courier_search . '%');
                })
                ->orWhereHas('transactionheader.mscustomer', function ($q) use ($courier_search) {
                    $q->whereRaw("CONCAT(customer_first_name, ' ', customer_last_name) LIKE ?", ['%' . $courier_search . '%']);
                })
                ->orWhereHas('transactionheader', function ($q) use ($courier_search) {
                    $q->where('transaction_id', 'like', '%' . $courier_search . '%');
                })
                ->orWhere('shipment_id', 'like', '%' . $courier_search . '%')
                ->orWhere('shipment_recipient_name', 'like', '%' . $courier_search . '%');
            });
        });
    }
}
