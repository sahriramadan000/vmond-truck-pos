<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupons;
use App\Models\OtherSetting;
use App\Models\Product;
use App\Models\ProductTag;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class TransactionController extends Controller
{
    public function index(){
        $data['page_title'] = 'Transaction';
        $data['products'] = Product::orderby('id', 'asc')->get();
        $data ['other_setting'] = OtherSetting::get()->first();
        $data['data_items'] = Cart::session(Auth::user()->id)->getContent();

        $data['subtotal'] = (Cart::getTotal() ?? '0');
        $data['tax'] = ((Cart::getTotal() ?? '0') * $data['other_setting']->pb01/100);
        $data['total'] = (Cart::getTotal() ?? '0') + $data['tax'];

        return view('admin.pos.index', $data);
    }

    // ========================================================================================
    // Modal View
    // ========================================================================================

    // Modal add Discount
    public function modalAddDiscount()
    {
        return View::make('admin.pos.modal.modal-add-discount');
    }

    // Modal add Coupon
    public function modalAddCoupon()
    {
        Cart::session(Auth::user()->id)->getContent();
        $subtotal = Cart::getTotal();

        $coupons = $coupons = Coupons::where('minimum_cart', '<=', $subtotal)
                    ->where('expired_at', '<=', now())
                    ->whereRaw('current_usage < limit_usage')
                    ->get();
        return View::make('admin.pos.modal.modal-add-coupon')->with([
            'coupons'      => $coupons,
        ]);
    }

     // Modal add Customer
     public function modalAddCustomer()
     {
         return View::make('admin.pos.modal.modal-add-customer');
     }

     // Modal Search
    public function modalSearchProduct()
    {
        return View::make('admin.pos.modal.modal-search-product');
    }

    // Modal My Order
    public function modalMyOrder()
    {
        $today = Carbon::today();
        // $getOrderPaid = Order::whereStatusPembayaran('Paid')->whereDate('created_at', $today)->orderBy('id', 'desc')->get();
        $getOrderPaid = [];
        // $getCacheOnhold = CacheOnholdControl::select(['key','name'])->whereDate('created_at', $today)->orderBy('id', 'desc')->get();
        $getCacheOnhold = [];

        return View::make('admin.pos.modal.modal-my-order')->with([
            'order_paids'      => $getOrderPaid,
            'onhold_orders'    => $getCacheOnhold,
        ]);
    }

    // Modal Add Cart
    public function modalAddCart($productId)
    {
        $productById = Product::findOrFail($productId);

        return View::make('admin.pos.modal.modal-add-cart')->with([
            'product'      => $productById,
        ]);
    }

    // Add Ongkir
    // public function modalAddOngkir()
    // {
    //     return View::make('pos.modal-add-ongkir');
    // }

    // ========================================================================================
    // End Modal View
    // ========================================================================================


    // ========================================================================================
    // Other Function
    // ========================================================================================

    // Get Data Tag
    public function getTag()
    {
        $allTag = Tag::has('products')->get();
        return response()->json($allTag, 200);
    }

    public function getProduct($idTag)
    {
        $getProductByTags = Product::whereHas('productTag', function ($query) use ($idTag) {
            $query->where('tag_id', $idTag);
        })->get();
        return response()->json($getProductByTags, 200);
    }

    public function deleteItem($id){
        if (Auth::check()) {
            Cart::session(Auth::user()->id)->remove($id);
        }
        $user = 'guest';
        Cart::session($user)->remove($id);
        return redirect()->back()->with('success', 'Item deleted successfully!');
    }

    // Add to cart with js
    public function addToCart(Request $request){
        try {
            if ($request->product_id == null) {
                return redirect()->back()->with('failed', 'Please Select The Product!');
            }

            $product = Product::findOrFail($request->product_id);
            $productUnit = $product->productUnit()->where('unit_id', $request->unit_id)->get()->first();
            $productDetail = $product->productDetail()->findOrFail($request->product_detail_id);
            $unit = Unit::findOrFail($request->unit_id);

            $cartContent = Cart::session(Auth::user()->id)->getContent();

            $productDetailAttributes = array(
                'product' => $product,
                'product_unit' => $productUnit,
                'product_detail' => $productDetail,
                'unit' => $unit,
            );

            $itemIdentifier = md5(json_encode($productDetail));

            // Cek apakah item yang akan ditambahkan sudah ada di keranjang
            $existingItem = $cartContent->first(function ($item, $key) use ($productDetail, $product, $request) {
                $attributes = $item->attributes;

                // Periksa apakah produk sama dengan produk yang ada dalam keranjang
                if ($attributes['product']['id'] === $product->id && (int)$attributes['product_unit']['unit_id'] === (int)$request->unit_id && (int)$attributes['product_unit']['product_id'] === (int)$request->product_id) {
                    // Jika produk sama, tambahkan detail produk baru
                    $existingProductDetail = $attributes['product_detail'];
                    $existingProductDetail[] = $productDetail;

                    // Update atribut produk dengan produk yang telah diperbarui
                    $item->attributes->put('product_detail', $existingProductDetail);
                    return true;
                }

                return false;
            });

            if ($existingItem !== null) {
                // Jika item sudah ada, tambahkan jumlahnya
                Cart::session(Auth::user()->id)->update($existingItem->id, [
                    'quantity' => $request->quantity,
                    'attributes' => $existingItem->attributes->toArray(),
                ]);
            } else {
                $productDetailAttributes['product_detail'] = [$productDetailAttributes['product_detail']];
                Cart::session(Auth::user()->id)->add(array(
                    'id' => $itemIdentifier,
                    'name' => $product->name,
                    'price' => $productUnit->sale_price,
                    'quantity' => $request->quantity,
                    'attributes' => $productDetailAttributes,
                    'conditions' => $unit->name,
                    'associatedModel' => Product::class
                ));
            }

            $other_setting = OtherSetting::select('pb01')->get()->first();
            $subtotal = (Cart::getTotal() ?? '0');
            $tax = ((Cart::getTotal() ?? '0') * $other_setting->pb01/100);
            $totalPayment = (Cart::getTotal() ?? '0') + $tax;

            return response()->json([
                'success'   => 'Product '.$product->name.' Berhasil masuk cart!',
                'data'      => Cart::session(Auth::user()->id)->getContent()->toArray(),
                'tax'       => $tax,
                'subtotal'  => $subtotal,
                'total'     => $totalPayment,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['failed' => 'Product '.$product->name.' gagal masuk cart!'. $th->getMessage()], 500);
        }
    }

    // Void Cart
    public function voidCart()
    {
        Cart::session(Auth::user()->id)->clear();
        return redirect()->back()->with('success', 'Cart berhasil dibersihkan!');
    }

    public function addToCartBarcode(Request $request){
        try {
            if ($request->barcode == null) {
                return redirect()->back()->with('failed', 'Please Select The Product!');
            }

            // $product = Product::where('barcode', $request->barcode)
            //       ->orWhere('sku', $request->sku)
            //       ->first();
            $productDetail = ProductDetail::where('barcode', $request->barcode)
                  ->orWhere('sku', $request->sku)
                  ->first();

            $product = Product::findOrFail($productDetail->product_id);
            $unit = Unit::findOrFail($productDetail->unit_id);
            $productUnit = $product->productUnit()->where('unit_id', $unit->id)->get()->first();

            $cartContent = Cart::session(Auth::user()->id)->getContent();

            $productDetailAttributes = array(
                'product' => $product,
                'product_unit' => $productUnit,
                'product_detail' => $productDetail,
                'unit' => $unit,
            );

            $itemIdentifier = md5(json_encode($productDetail));

            // Cek apakah item yang akan ditambahkan sudah ada di keranjang
            $existingItem = $cartContent->first(function ($item, $key) use ($productDetail, $product, $request) {
                $attributes = $item->attributes;

                // Periksa apakah produk sama dengan produk yang ada dalam keranjang
                if ($attributes['product']['id'] === $product->id && (int)$attributes['product_unit']['unit_id'] === (int)$request->unit_id && (int)$attributes['product_unit']['product_id'] === (int)$request->product_id) {
                    // Jika produk sama, tambahkan detail produk baru
                    $existingProductDetail = $attributes['product_detail'];
                    $existingProductDetail[] = $productDetail;

                    // Update atribut produk dengan produk yang telah diperbarui
                    $item->attributes->put('product_detail', $existingProductDetail);
                    return true;
                }

                return false;
            });

            if ($existingItem !== null) {
                // Jika item sudah ada, tambahkan jumlahnya
                Cart::session(Auth::user()->id)->update($existingItem->id, [
                    'quantity' => $request->quantity,
                    'attributes' => $existingItem->attributes->toArray(),
                ]);
            } else {
                $productDetailAttributes['product_detail'] = [$productDetailAttributes['product_detail']];
                Cart::session(Auth::user()->id)->add(array(
                    'id' => $itemIdentifier,
                    'name' => $product->name,
                    'price' => $productUnit->sale_price,
                    'quantity' => $request->quantity,
                    'attributes' => $productDetailAttributes,
                    'conditions' => $unit->name,
                    'associatedModel' => Product::class
                ));
            }

            $other_setting = OtherSetting::select('pb01')->get()->first();
            $subtotal = (Cart::getTotal() ?? '0');
            $tax = ((Cart::getTotal() ?? '0') * $other_setting->pb01/100);
            $totalPayment = (Cart::getTotal() ?? '0') + $tax;

            return response()->json([
                'success'   => 'Product '.$product->name.' Berhasil masuk cart!',
                'data'      => Cart::session(Auth::user()->id)->getContent()->toArray(),
                'tax'       => $tax,
                'subtotal'  => $subtotal,
                'total'     => $totalPayment,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['failed' => 'Product '.$product->name.' gagal masuk cart!'. $th->getMessage()], 500);
        }
    }

    public function getDataCustomers(Request $request)
    {
        $customers = Customer::select(['id', 'name'])->get();
        return response()->json($customers);
    }

    // ====================================================

    // Update Cart By Coupon
    public function updateCartByCoupon(Request $request)
    {
        $coupon = Coupon::findOrFail($request->coupon_id);
        $coupon_type = $coupon->type;
        $ongkir_price = (int) str_replace('.', '', $request->ongkir_price);
        Cart::session(Auth::user()->id)->getContent();
        $tax = OtherSetting::get()->first();

        if ($coupon_type == 'Percentage Discount') {
            $coupon_amount = Cart::getTotal() * (int)$coupon->discount_value / 100;
        } else {
            $coupon_amount = (int)$coupon->discount_value;
        }

        $subtotal = Cart::getTotal();
        $tax = ($subtotal - $coupon_amount) * $tax->pb01 / 100;
        $total = ($subtotal - $coupon_amount) + $tax + $ongkir_price;
        $info = $coupon->name;

        return response()->json([
            'success'   => 'Coupon '.$coupon->name.' berhasil ditambahkan!',
            'coupon_type'  => $coupon_type,
            'ongkir_price'  => $ongkir_price,
            'coupon_amount'  => $coupon_amount,
            'subtotal'  => $subtotal,
            'tax'       => $tax,
            'total'     => $total,
            'info'     => $info,
        ], 200);
    }

    // ====================================================
    //
    // ====================================================
    // Update Cart By Discount
    public function updateCartByDiscount(Request $request)
    {
        $discount_price = (int) str_replace('.', '', $request->discount_price);
        $discount_percent = (int) $request->discount_percent;
        $discount_type = $request->discount_type;
        $ongkir_price = (int) str_replace('.', '', $request->ongkir_price);

        Cart::session(Auth::user()->id)->getContent();
        $tax = OtherSetting::get()->first();

        if ($discount_type == 'percent') {
            $discount_amount = Cart::getTotal() * $discount_percent / 100;
        } else {
            $discount_amount = $discount_price;
        }

        $subtotal = Cart::getTotal();
        $tax = ($subtotal - $discount_amount) * $tax->pb01 / 100;
        $total = ($subtotal - $discount_amount) + $tax + $ongkir_price;

        return response()->json([
            'success'   => 'Discount berhasil ditambahkan!',
            'discount_price'  => $discount_price,
            'discount_percent'  => $discount_percent,
            'discount_type'  => $discount_type,
            'discount_amount'  => $discount_amount,
            'ongkir_price'  => $ongkir_price,
            'subtotal'  => $subtotal,
            'tax'       => $tax,
            'total'     => $total,
        ], 200);
    }

    // Update Cart By Discount
    public function updateCartOngkir(Request $request)
    {
        $discount_price = (int) str_replace('.', '', $request->discount_price);
        $discount_percent = (int) $request->discount_percent;
        $discount_type = $request->discount_type;
        $ongkir_price = (int) str_replace('.', '', $request->ongkir_price);

        Cart::session(Auth::user()->id)->getContent();
        $tax = OtherSetting::get()->first();

        if ($discount_type == 'percent') {
            $discount_amount = Cart::getTotal() * $discount_percent / 100;
        } else {
            $discount_amount = $discount_price;
        }

        $subtotal = Cart::getTotal();
        $tax = ($subtotal - $discount_amount) * $tax->pb01 / 100;
        $total = ($subtotal - $discount_amount) + $tax + $ongkir_price;

        return response()->json([
            'success'   => 'Ongkir berhasil ditambahkan!',
            'type_discount'  => $discount_type,
            'discount_percent'  => $discount_percent,
            'discount_price'  => $discount_price,
            'ongkir_price'  => $ongkir_price,
            'subtotal'  => $subtotal,
            'tax'       => $tax,
            'total'     => $total,
        ], 200);
    }

    public function searchProduct(Request $request)
    {
        $products = Product::select(['id', 'name'])->get();
        // $products = Product::pluck('name');

        return response()->json($products);
    }
}
