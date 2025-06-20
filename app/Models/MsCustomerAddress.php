<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsCustomerAddress extends Model
{
    use HasFactory;

    protected $table = 'ms_customer_addresses';
    protected $primaryKey = 'customer_address_id';

    protected $guarded = ['customer_address_id'];

    public function mscustomer()
    {
        return $this->belongsTo(MsCustomer::class, 'customer_id', 'customer_id');
    }

    public function transactionheader()
    {
        return $this->hasMany(TransactionHeader::class, 'customer_address_id', 'customer_address_id');
    }

    public function getCustomerFullAddressNameAttribute()
    {
        return "{$this->customer_address_street}, {$this->customer_address_district}, {$this->customer_address_regency_city}, {$this->customer_address_province}, {$this->customer_address_country} - {$this->customer_address_postal_code}";
    }

    public function getCustomerShortAddressNameAttribute()
    {
        return "{$this->customer_address_regency_city}";
    }

}