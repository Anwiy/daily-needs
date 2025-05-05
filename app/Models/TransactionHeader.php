<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionHeader extends Model
{
    use HasFactory;

    protected $table = 'transaction_headers';

    protected $primaryKey = 'transaction_id';

    protected $guarded = ['transaction_id'];

    public function mscustomer(){
        return $this->belongsTo(MsCustomer::class, 'customer_id', 'customer_id');
    }

    public function msadmin(){
        return $this->belongsTo(MsAdmin::class, 'admin_id', 'admin_id');
    }

    public function mspaymentmethod(){
        return $this->belongsTo(MsPaymentMethod::class, 'payment_method_id', 'payment_method_id');
    }

    public function transactiondetail(){
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'transaction_id');
    }

    public function msshipment(){
        return $this->hasOne(MsShipment::class, 'transaction_id', 'transaction_id');
    }

    public function mscustomeraddress(){
        return $this->belongsTo(MsCustomerAddress::class, 'customer_address_id', 'customer_address_id');
    }

    public function getTotalPriceAttribute()
    {
        return $this->transactiondetail->sum(function ($detail) {
            return $detail->unit_price_at_buy * $detail->quantity;
        });
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['status'] ?? false, function ($query, $status) {
            $query->where('transaction_status', $status);
        });

        $query->when($filters['search'] ?? false, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('transactiondetail.msproduct', function ($q) use ($search) {
                    $q->where('product_name', 'like', '%' . $search . '%');
                });
            });
        });

        $query->when($filters['payment_method'] ?? false, function ($query, $payment_method) {
            $query->whereHas('mspaymentmethod', function ($q) use ($payment_method) {
                $q->where('payment_method_slug', $payment_method);
            });
        });

        $query->when($filters['courier_to_ship_search'] ?? false, function ($query, $courier_to_ship_search) {
            $query->where(function ($query) use ($courier_to_ship_search) {
                $query->whereHas('mscustomeraddress', function ($q) use ($courier_to_ship_search) {
                    $q->where('customer_address_street', 'like', '%' . $courier_to_ship_search . '%')
                        ->orWhere('customer_address_postal_code', 'like', '%' . $courier_to_ship_search . '%')
                        ->orWhere('customer_address_district', 'like', '%' . $courier_to_ship_search . '%')    
                        ->orWhere('customer_address_regency_city', 'like', '%' . $courier_to_ship_search . '%')
                        ->orWhere('customer_address_province', 'like', '%' . $courier_to_ship_search . '%')  
                        ->orWhere('customer_address_country', 'like', '%' . $courier_to_ship_search . '%');
                })
                ->orWhereHas('mscustomer', function ($q) use ($courier_to_ship_search) {
                    $q->whereRaw("CONCAT(customer_first_name, ' ', customer_last_name) LIKE ?", ['%' . $courier_to_ship_search . '%']);
                })
                ->orWhere('transaction_id', 'like', '%' . $courier_to_ship_search . '%');
            });
        });
    }
}



