<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function reportGross(){
        $data ['page_title'] = 'Report Sales Gross Profit';
        return view('admin.report.sales.gross-profit',$data);
    }

    public function getReportGross(Request $request)
    {
        if ($request->ajax()) {
            $query = Order::select('orders.id', 'created_at', 'cashier_name', 'payment_method', 'customer_name', 'total');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    return '<a href="#" class="btn btn-sm btn-primary view-details" data-id="' . $row->id . '">View</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getOrderDetails($id)
    {
        $order = Order::with(['orderProducts.orderProductAddons'])->findOrFail($id);
        return view('admin.report.sales.order-details', compact('order'))->render();
    }

    public function paymentMethod(){
        $data ['page_title'] = 'Report Sales Payment Method';
        return view('admin.report.sales.payment-method',$data);
    }

    public function getPaymentMethodsReport(Request $request)
    {
        if ($request->ajax()) {
            $query = Order::select('payment_method')
                ->selectRaw('COUNT(*) as quantity')
                ->selectRaw('SUM(total) as total_collected')
                ->groupBy('payment_method');

            return DataTables::of($query)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
