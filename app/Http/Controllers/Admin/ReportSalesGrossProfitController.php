<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportSalesGrossProfitController extends Controller
{
    public function index(){
        $data ['page_title'] = 'Report Sales Gross Profit';
        return view('admin.report.gross_profit',$data);
    }

    public function paymentMethod(){
        $data ['page_title'] = 'Report Sales Gross Profit';
        return view('admin.report.payment_method',$data);
    }
}
