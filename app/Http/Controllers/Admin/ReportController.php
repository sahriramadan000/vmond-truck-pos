<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

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

    public function reportAbsensi(){
        $data ['page_title'] = 'Report Absensi';
        return view('admin.report.absensi.index',$data);
    }

    public function getAttendanceReport(Request $request)
    {
        if ($request->ajax()) {
            // Check if the user is an admin
            if (Auth::user()->hasAnyRole(['super-admin', 'admin'])) {
                $query = Attendance::with('user:id,fullname') // Assuming you have a relationship defined in Attendance model for user
                    ->select('attendances.id', 'user_id', 'check_in', 'check_out', 'date', 'status');
            } else {
                $query = Attendance::with('user:id,fullname')
                    ->where('user_id', Auth::user()->id)
                    ->select('attendances.id', 'user_id', 'check_in', 'check_out', 'date', 'status');
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('check_in', function($row) {
                    return $row->check_in ? \Carbon\Carbon::parse($row->check_in)->format('H:i:s') : '-';
                })
                ->editColumn('check_out', function($row) {
                    return $row->check_out ? \Carbon\Carbon::parse($row->check_out)->format('H:i:s') : '-';
                })
                ->editColumn('date', function($row) {
                    return \Carbon\Carbon::parse($row->date)->format('d-m-Y');
                })
                ->addColumn('user_name', function($row) {
                    return $row->user->fullname;
                })
                ->make(true);
        }
    }
}
