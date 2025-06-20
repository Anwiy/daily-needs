<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CustomerService;
use App\Models\TransactionHeader;
use App\Models\TransactionDetail;
use App\Models\MsAdmin;
use App\Models\MsCustomer;
use App\Models\MsCategory;
use App\Models\MsProduct;
use App\Models\MsCustomerAddress;
use App\Models\MsPaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CustomerBuyProductController extends Controller
{
    public function checkout(Request $request)
    { 
        if ($request->has('selected_items')) {
            $selectedItems = json_decode($request->input('selected_items'), true);
    
            if (empty($selectedItems)) {
                return redirect()->back()->with('error', 'No items selected for checkout.');
            }
    
            session(['checkout_cart' => $selectedItems]);
            session()->forget(['checkout_product_id', 'checkout_quantity']);
    
            return redirect()->route('checkout.page');
        }
        else {
            $request->validate([
                'product_id' => 'required|exists:ms_products,product_id',
                'quantity' => 'required|integer|min:1'
            ]);

            session([
                'checkout_product_id' => $request->product_id,
                'checkout_quantity' => $request->quantity
            ]);

            session()->forget('checkout_cart');

            return redirect()->route('checkout.page');
        }
            
    }

    public function index()
    {
        $customers = Auth::guard('customer')->user();

        $cart = session('checkout_cart');
        $productId = session('checkout_product_id');
        $quantity = session('checkout_quantity');

        if ($cart){
            $productIds = collect($cart)->pluck('product_id')->toArray();
            $products = MsProduct::whereIn('product_id', $productIds)->get();

            $products = $products->map(function ($product) use ($cart) {
                $found = collect($cart)->firstWhere('product_id', $product->product_id);
                $product->cart_quantity = $found['quantity'] ?? 1;
                return $product;
            });

            $totalPrice = $products->sum(fn($item) => $item->product_price * $item->cart_quantity);
        }

        else if ($productId && $quantity){
            $product = MsProduct::where('product_id', $productId)->first();
            $product->cart_quantity = $quantity;
            $products = [$product];
            $totalPrice = $product->product_price * $quantity;
        }

        else {
            return redirect()->back()->with('error', 'Invalid checkout!');
        }

        $addresses = CustomerService::getAddresses($customers->customer_id);
        $paymentMethods = MsPaymentMethod::all();
        $categories = MsCategory::all();

        return view('checkout.index', compact('customers', 'categories', 'products', 'quantity', 'totalPrice', 'paymentMethods', 'addresses'));
    }

    public function store(Request $request)
    {
        $customers = Auth::guard('customer')->user();

        if (!$customers) {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }

        $cart = session('checkout_cart');
        $productId = session('checkout_product_id');
        $quantity = session('checkout_quantity');

        $request->validate([
            'payment_method_id' => 'required|exists:ms_payment_methods,payment_method_id',
            'customer_address_id' => 'required|exists:ms_customer_addresses,customer_address_id',
        ]);

        if (!$cart && !$productId) {
            return redirect()->back()->with('error', 'Invalid checkout session.');
        }

        if (!$cart) {
            $request->validate([
                'product_id' => 'required|exists:ms_products,product_id',
                'quantity' => 'required|integer|min:1',
            ]);
        }

        DB::beginTransaction();

        try {
            $transactionHeader = TransactionHeader::create([
                'transaction_date' => now(),
                'transaction_status' => 'Pending',
                'customer_id' => $customers->customer_id,
                'customer_address_id' => $request->customer_address_id,
                'payment_method_id' => $request->payment_method_id,
            ]);

            $totalPrice = 0;

            if ($cart) {
                foreach ($cart as $item) {
                    $product = MsProduct::find($item['product_id']);

                    if (!$product) {
                        throw new \Exception("Product not found.");
                    }

                    if ($product->product_stock < $item['quantity']) {
                        throw new \Exception("Stock not enough for {$product->product_name}");
                    }

                    $unitPrice = $product->product_price;
                    $totalPrice += $product->product_price * $item['quantity'];

                    $product->product_stock -= $item['quantity'];
                    $product->save();

                    TransactionDetail::create([
                        'transaction_id' => $transactionHeader->transaction_id,
                        'product_id' => $product->product_id,
                        'quantity' => $item['quantity'],
                        'unit_price_at_buy' => $unitPrice,
                    ]);

                    DB::table('ms_carts')
                        ->where('customer_id', $customers->customer_id)
                        ->where('product_id', $product->product_id)
                        ->delete();
                }
            }   

            else if ($productId && $quantity) {
                $product = MsProduct::find($productId);

                if (!$product) {
                    throw new \Exception("Product not found.");
                }

                if ($product->product_stock < $quantity) {
                    throw new \Exception("Stock not enough for {$product->product_name}");
                }

                $unitPrice = $product->product_price;
                $totalPrice = $product->product_price * $quantity;

                $product->product_stock -= $quantity;
                $product->save();

                TransactionDetail::create([
                    'transaction_id' => $transactionHeader->transaction_id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price_at_buy' => $unitPrice,
                ]);
            }

            $transactionHeader->load('mspaymentmethod');

            if ($transactionHeader->mspaymentmethod->payment_method_name == 'Application Balance') {
                if ($customers->customer_balance < $totalPrice) {
                    throw new \Exception('Oops! Your current balance isn’t enough. Your current balance is Rp' . number_format($customers->customer_balance, 2));
                }

                $customers->customer_balance -= $totalPrice;
                $customers->save();
            }
            
            DB::commit();
            session()->forget(['checkout_cart', 'checkout_product_id', 'checkout_quantity']);

            return redirect('/')->with('success', 'Your order has been placed successfully!');
        } 
        catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}