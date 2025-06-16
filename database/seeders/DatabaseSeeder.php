<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\MsAdmin;
use App\Models\MsBrand;
use App\Models\MsCart;
use App\Models\MsCategory;
use App\Models\MsCompany;
use App\Models\MsCourier;
use App\Models\MsCustomer;
use App\Models\MsCustomerAddress;
use App\Models\MsPaymentMethod;
use App\Models\MsProduct;
use App\Models\MsShipment;
use App\Models\MsTopUp;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        MsCategory::factory(6)->create();
        MsPaymentMethod::factory(3)->create();
        MsAdmin::factory(10)->create();
        MsCustomer::factory(20)->create();
        MsCourier::factory(10)->create();
        MsCompany::factory(5)->create();
        MsBrand::factory(15)->create();
        MsTopUp::factory(10)->create();
        MsCustomerAddress::factory(30)->create();
        TransactionHeader::factory(30)->create();
        MsProduct::factory(20)->create();
        TransactionDetail::factory(50)->create();
        MsShipment::factory(30)->create();
        MsCart::factory(20)->create();
    }
}
