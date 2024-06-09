<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupons;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderCoupon;
use App\Models\OtherSetting;
use Illuminate\Http\Request;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function checkout(Request $request, $token)
    {
        try {
            $session_cart = Cart::session(Auth::user()->id)->getContent();
            $other_setting = OtherSetting::get()->first();
            $checkToken = Order::where('token',$token)->where('payment_status', 'Paid')->get();
            $service = (Cart::getTotal() ?? '0') * $other_setting->layanan/100;
            $pb01 = (Cart::getTotal()  ?? '0')  * $other_setting->pb01/100;
            $total_price = 0;
            $customer = null;

            if ($request->customer_id) {
                $customer = Customer::whereId($request->customer_id)->get();
            }


            if (count($checkToken) != 0) {
                return redirect()->back()->with(['failed' => 'Tidak dapat mengulang transaksi!']);
            }

            if ($other_setting->layanan != 0) {
                $biaya_layanan = Cart::getTotal() * $other_setting->layanan/100;
                $total_price = (Cart::getTotal()) + $biaya_layanan;
            }else{
                $total_price = (Cart::getTotal() ?? '0');
            }

            if ($other_setting->pb01 != 0) {
                $biaya_pb01 = $total_price * $other_setting->pb01/100;
                $total_price = $total_price + $biaya_pb01;
            }else{
                $total_price = ($total_price ?? '0');
            }

            // ===================By Discount====================
            $getDiscountPrice = ($request->discount_price ? (int) str_replace('.', '', $request->discount_price) : 0);
            $getDiscountPercent = ($request->discount_percent ? (int) $request->discount_percent : 0);

            if ($request->type_discount == 'percent') {
                $discount_amount = Cart::getTotal() * $getDiscountPercent / 100;
            } else {
                $discount_amount = $getDiscountPrice;
            }

            $subtotal = Cart::getTotal();
            $tax_by_discount = ($subtotal - $discount_amount) * $other_setting->pb01 / 100;
            $tax_by_discount = ($subtotal - $discount_amount) * $other_setting->pb01 / 100;
            $total_price_by_discount = ($subtotal - $discount_amount) + $tax_by_discount;
            // ===================By Discount====================

            // =================Create Data Order================
            $order = Order::create([
                'no_invoice'        => $this->generateInvoice(),
                'cashier_name'      => auth()->user()->name,
                'customer_name'     => $customer->name ?? null,
                'customer_email'    => $customer->email ?? null,
                'customer_phone'    => $customer->phone ?? null,
                'status_pembayaran' => 'Paid',
                'payment_method'    => $request->metode_pembayaran,

                'total_qty'         => array_sum($request->qty),
                'sub_total'         => $subtotal,
                'type_discount'     => ($request->type_discount ? $request->type_discount : null) ,
                'discount_price'    => $getDiscountPrice,
                'discount_percent'  => $getDiscountPercent,
                'service'           => $service,
                'pb01'              => ($request->type_discount ? $tax_by_discount : $pb01),
                'total'             => ($request->type_discount ? $total_price_by_discount : $total_price),
                'token'             => $token,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ]);
            // =================Create Data Order================

            // =================Order Coupon=====================
            // Check jika ada coupon yang dipilih
            if ($request->coupon_id) {
                $coupon = Coupons::findOrFail($request->coupon_id);
                $orderCoupon = OrderCoupon::create([
                    'order_id' => $order->id,
                    'name' => $coupon->name,
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'discount_value' => $coupon->discount_value,
                ]);

                $coupon->current_usage += 1;
                $coupon->save();

                // Update tax,total price
                $coupon_type = $coupon->type;
                $subtotal = $order->sub_total;

                if ($coupon_type == 'Percentage Discount') {
                    $coupon_amount = $subtotal * (int)$coupon->discount_value / 100;
                } else {
                    $coupon_amount = (int)$coupon->discount_value;
                }

                $taxPriceByCoupon = ($subtotal - $coupon_amount) * $other_setting->pb01 / 100;
                $totalPriceByCoupon = ($subtotal - $coupon_amount) + $taxPriceByCoupon;

                $order->pb01 = $taxPriceByCoupon;
                $order->total_price = $totalPriceByCoupon;
                $order->save();
            }
            // =================Order Coupon=====================

            // ==================================================================================================
            // Order Detail Product
            // $orderDetails = []; // Array untuk menyimpan detail produk yang telah dimasukkan ke dalam pesanan

            // foreach ($session_cart as $key => $cart) {
            //     foreach ($cart->attributes['product'] as $key2 => $detail) {
            //         $order_product_id = $detail->id;

            //         // Periksa apakah detail produk sudah dimasukkan sebelumnya
            //         if (isset($orderDetails[$order_product_id])) {
            //             // Jika sudah dimasukkan, tambahkan jumlahnya
            //             $orderDetails[$order_product_id]['quantity_product'] += 1;
            //         } else {
            //             // Jika belum dimasukkan, tambahkan detail produk baru ke dalam pesanan
            //             $orderDetails[$order_product_id] = [
            //                 'product_id'                    => $cart->attributes['product']['id'],
            //                 'product_code'                  => $cart->attributes['product']['code'],
            //                 'product_name'                  => $cart->attributes['product']['name'],
            //                 'product_category'              => Category::whereId($cart->attributes['product']['category_id'])->value('name'),
            //                 'product_unit_id'               => $cart->attributes['product_unit']['id'],
            //                 'product_unit_sale_price'       => $cart->attributes['product_unit']['sale_price'],
            //                 'product_unit_cost_price'       => $cart->attributes['product_unit']['cost_price'],
            //                 'product_unit_stock_inventory'  => $cart->attributes['product_unit']['current_stock_inventory'],
            //                 'product_unit_stock_shop'       => $cart->attributes['product_unit']['current_stock_shop'],
            //                 'unit_id'                       => $cart->attributes['unit']['id'],
            //                 'unit_name'                     => $cart->attributes['unit']['name'],
            //                 'unit_value'                    => $cart->attributes['unit']['value'],
            //                 'product_detail_id'             => $detail->id,
            //                 'product_detail_sku'            => $detail->sku,
            //                 'product_detail_barcode'        => $detail->barcode,
            //                 'product_detail_type'           => $detail->type,
            //                 'product_detail_expired_date'   => $detail->expired_date,
            //                 'product_detail_quantity'       => $detail->quantity,
            //                 'quantity_product'              => 1,
            //             ];
            //         }
            //     }
            // }

            // // Save OrderDetail Product
            // foreach ($orderDetails as $detail) {
            //     $orderDetailProduct = new OrderDetailProducts();
            //     $orderDetailProduct->order_id                       = $order->id;
            //     $orderDetailProduct->product_id                     = $detail['product_id'];
            //     $orderDetailProduct->product_code                   = $detail['product_code'];
            //     $orderDetailProduct->product_name                   = $detail['product_name'];
            //     $orderDetailProduct->product_category               = $detail['product_category'];
            //     $orderDetailProduct->product_unit_id                = $detail['product_unit_id'];
            //     $orderDetailProduct->product_unit_sale_price        = $detail['product_unit_sale_price'];
            //     $orderDetailProduct->product_unit_cost_price        = $detail['product_unit_cost_price'];
            //     $orderDetailProduct->product_unit_stock_inventory   = $detail['product_unit_stock_inventory'];
            //     $orderDetailProduct->product_unit_stock_shop        = $detail['product_unit_stock_shop'];
            //     $orderDetailProduct->unit_id                        = $detail['unit_id'];
            //     $orderDetailProduct->unit_name                      = $detail['unit_name'];
            //     $orderDetailProduct->unit_value                     = $detail['unit_value'];
            //     $orderDetailProduct->product_detail_id              = $detail['product_detail_id'];
            //     $orderDetailProduct->product_detail_sku             = $detail['product_detail_sku'];
            //     $orderDetailProduct->product_detail_barcode         = $detail['product_detail_barcode'];
            //     $orderDetailProduct->product_detail_type            = $detail['product_detail_type'];
            //     $orderDetailProduct->product_detail_expired_date    = $detail['product_detail_expired_date'];
            //     $orderDetailProduct->product_detail_quantity        = $detail['product_detail_quantity'];
            //     $orderDetailProduct->quantity_product               = $detail['quantity_product'];

            //     $productDetail = ProductDetail::findOrFail($detail['product_detail_id']);

            //     if ((int)$productDetail->quantity < (int)$detail['quantity_product']) {
            //         return redirect()->back()->with(['failed' => 'Stock product kurang!']);
            //     }

            //     $currentQuantity = (int)$productDetail->quantity - (int)$detail['quantity_product'];
            //     $productDetail->quantity = $currentQuantity;
            //     $productDetail->status = ($currentQuantity > 0) ? $productDetail->status : 'sold';
            //     $productDetail->save();
            //     $orderDetailProduct->save();
            // }
            // ==================================================================================================


        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
