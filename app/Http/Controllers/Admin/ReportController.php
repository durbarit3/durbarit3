<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function productstockreport(){
    	$report=Product::OrderBy('id','DESC')->get();
    	return view('admin.ecommerce.report.stockproduct',compact('report'));
    }

    public function categoryreport(Request $request){
    	$cate_id=$request->cate_id;
    	$report=Product::where('cate_id',$cate_id)->get();
    	return view('admin.ecommerce.report.stockproductresult',compact('report'));
    	
    }
}
