<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MsCustomer;
use App\Models\MsProduct;
use App\Models\MsCategory;
use App\Models\MsBrand;
use App\Models\MsCart;

class CustomerViewCartController extends Controller
{
    public function index()
    {
        $categories = MsCategory::all();
        $customers = auth('customer')->user();

        $cartItems = MsCart::where('customer_id', $customers->customer_id)->with('msproduct.msbrand')->get();

        return view('viewcart.index', compact('categories', 'cartItems', 'customers'));
    }

    public function getSubtotal(Request $request)
    {
        $customer = auth('customer')->user();

        $selectedProducts = $request->input('selected_products', []);

        if (empty($selectedProducts)) {
            return response()->json(['subtotal' => 0]);
        }

        $cartItems = MsCart::filterSubtotal($customer->customer_id, $selectedProducts)->get();

        $subtotal = $cartItems->sum(function ($item) {
            return optional($item->msproduct)->product_price * $item->quantity;
        });
    
        $formattedSubtotal = number_format($subtotal, 0, ',', '.');

        return response()->json(['subtotal' => $formattedSubtotal]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:ms_products,product_id',
            'quantity' => 'required|integer|min:0',
        ]);

        $customer = auth('customer')->user();
        $product = MsProduct::where('product_id', $request->product_id)->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found!'], 404);
        }

        $cartItem = MsCart::CartItem($customer->customer_id, $product->product_id)->first();
        $maxStock = $product->product_stock;
        $inputQuantity = $request->quantity;
    
        if ($inputQuantity < 1) {
            $cartItem->update(['quantity' => 1]);
            return response()->json([
                'error' => "Minimum quantity is 1!",
                'new_quantity' => 1
            ], 400);
        }

        if (!$cartItem) {
            if ($inputQuantity > $maxStock) {
                return response()->json([
                    'error' => "Stock not enough! Maximum stock available is $maxStock.",
                    'max_stock' => $maxStock,
                    'new_quantity' => $maxStock
                ], 400);
            }

            MsCart::create([
                'customer_id' => $customer->customer_id,
                'product_id' => $request->product_id,
                'quantity' => $inputQuantity
            ]);
        }

        else {
            $currentQuantity = $cartItem->quantity;
            $totalQuantity = $currentQuantity + $inputQuantity;

            if ($totalQuantity > $maxStock) {
                return response()->json([
                    'error' => "Maximum stock available is $maxStock and you already have $currentQuantity of this item in your cart.",
                    'current_cart' => $currentQuantity,
                ], 400);
            }

            $cartItem->increment('quantity', $inputQuantity);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart!',
            'new_quantity' => MsCart::CartItem($customer->customer_id, $product->product_id)->first()->quantity
        ]);
    }

    public function update(Request $request, $brand_slug, $product_slug)
    {
        
        $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);
    
        $customer = auth('customer')->user();
        $product = MsProduct::where('product_slug', $product_slug)->first();
    
        if (!$product) {
            return response()->json(['error' => 'Product not found!'], 404);
        }
    
        $cartItem = MsCart::CartItem($customer->customer_id, $product->product_id)->first();
    
        if (!$cartItem) {
            return response()->json(['error' => 'Product not in cart!'], 404);
        }
    
        $maxStock = $product->product_stock;
        $currentQuantity = $cartItem->quantity;
        $newQuantity = $request->quantity;

        if ($maxStock == 0) {
            DB::table('ms_carts')
                        ->where('customer_id', $customers->customer_id)
                        ->where('product_id', $product->product_id)
                        ->delete();
            return response()->json([
                'success' => true,
                'message' => 'Product out of stock, removed from cart',
                'new_quantity' => 0
            ], 200);
        }
    
        if ($newQuantity > $maxStock) {
            $cartItem->update(['quantity' => $maxStock]);
            return response()->json([
                'error' => "Stock not enough! Maximum stock available is $maxStock.",
                'max_stock' => $maxStock,
                'prev_quantity' => $currentQuantity,
                'new_quantity' => $maxStock,
                'total_price' => $product->product_price * $maxStock 
            ], 400);
        }
    
        if ($newQuantity < 1 && $maxStock > 0) {
            $cartItem->update(['quantity' => 1]);
            return response()->json([
                'error' => "Minimum quantity is 1!",
                'prev_quantity' => $currentQuantity,
                'new_quantity' => 1,
                'total_price' => $product->product_price * 1
            ], 400);
        }
    
        $cartItem->update(['quantity' => $newQuantity]);
        $totalPrice = $product->product_price * $newQuantity; 
    
        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!',
            'new_quantity' => $cartItem->quantity,
            'total_price' => $totalPrice
        ], 200);
    }

    public function destroy($brand_slug, $product_slug)
    {
        $customer = auth('customer')->user();

        $product = MsProduct::where('product_slug', $product_slug)->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found!'], 404);
        }

        $cartItem = MsCart::CartItem($customer->customer_id, $product->product_id)->first();

        if (!$cartItem) {
            return response()->json(['error' => 'Product not in cart!'], 404);
        }

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart!',
            'product_slug' => $product_slug
        ]);
    }
}