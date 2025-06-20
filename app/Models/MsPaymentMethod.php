<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MsPaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'ms_payment_methods';

    protected $primaryKey = 'payment_method_id';

    protected $guarded = ['payment_method_id'];

    public function transactionheader(){
        return $this->hasMany(TransactionHeader::class, 'payment_method_id', 'payment_method_id');
    }

    public function mstopup(){
        return $this->hasOne(MsTopUp::class, 'payment_method_id', 'payment_method_id');
    }
}
