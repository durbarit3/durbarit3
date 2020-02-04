<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\OrderPlace;
use Carbon\Carbon;
use Session;

class OrderController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth:admin');
  }

  public function index(){
    $allorder=OrderPlace::orderBy('id','DESC')->where('delevary',1)->get();
    return view('admin.ecommerce.order.all',compact('allorder'));
  }

  public function ondelevery(){
    $ondevelery=OrderPlace::orderBy('id','DESC')->where('delevary',4)->get();
    return view('admin.ecommerce.order.ondelevery',compact('ondevelery'));

  }

  public function invoice($id){
    $invoice=OrderPlace::where('id',$id)->first();
    return view('admin.ecommerce.invoice.invoice',compact('invoice'));
  }
}
