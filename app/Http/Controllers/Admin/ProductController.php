<?php

namespace App\Http\Controllers\Admin;

use DB;
use Image;

use Session;
use Response;
use App\Color;
use App\Product;
use Carbon\Carbon;
use App\ProductImage;
use App\ReSubCategory;
use App\ProductLicense;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function producttype(){
        return view('admin.ecommerce.product.producttype');
    }
    public function digital(){
        return view('admin.ecommerce.product.digital');
    }
    public function license(){
        return view('admin.ecommerce.product.license');
    }
    public function affiliate(){
        return view('admin.ecommerce.product.affiliate');
    }
    public function index(){
         $allproduct=Product::where('is_deleted',0)->where('status',1)->get();
        // $allproduct = DB::table('products')
        //     ->join('categories', 'products.cate_id', '=', 'categories.id')
        //     ->join('sub_categories', 'products.subcate_id', '=', 'sub_categories.id')
        //     ->join('re_sub_categories', 'products.resubcate_id', '=', 're_sub_categories.id')
        //     ->select('products.*', 'categories.cate_name', 'sub_categories.subcate_name', 're_sub_categories.resubcate_name')
        //     ->where('products.is_deleted',0)
        //     ->OrderBy('products.id','DESC')
        //     ->get();
        return view('admin.ecommerce.product.all',compact('allproduct'));
    }

    public function add(){
    	return view('admin.ecommerce.product.add');
    }

    public function resub($subcate_id){
            $resub=ReSubCategory::where('subcate_id',$subcate_id)->get();
            return json_encode($resub);
    }

      public function sku_combination(Request $request)
    {
        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }
        else {
            $colors_active = 0;
        }

        $unit_price = $request->unit_price;
        $product_name = $request->product_name;

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }
        if($request->has('colors_active') && $request->has('choice_no')){
                $choice_count=count($request->choice_no);
                   if($choice_count == 0){
                        $combinations = Arr::crossJoin($options[0]);
                        return view('admin.ecommerce.partials.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
                    }
                    elseif($choice_count==1){
                        $combinations = Arr::crossJoin($options[0],$options[1]);
                        return view('admin.ecommerce.partials.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
                    }elseif($choice_count==2){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2]);
                        return view('admin.ecommerce.partials.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
                    }elseif($choice_count==3){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2],$options[3]);
                        return view('admin.ecommerce.partials.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
                    }elseif($choice_count==4){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2],$options[3],$options[4]);
                        return view('admin.ecommerce.partials.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
                    }elseif($choice_count==5){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2],$options[3],$options[4],$options[5]);
                        return view('admin.ecommerce.partials.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
                    }
        }elseif($request->has('colors_active')){

            $combinations = Arr::crossJoin($options[0]);
            return view('admin.ecommerce.partials.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));

        }elseif($request->has('choice_no')){
            $choice_count=count($request->choice_no);
                  if($choice_count==1){
                        $combinations = Arr::crossJoin($options[0]);
                        return view('admin.ecommerce.partials.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
                    }
                   elseif($choice_count==2){
                        $combinations = Arr::crossJoin($options[0],$options[1]);
                        return view('admin.ecommerce.partials.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
                    }elseif($choice_count==3){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2]);
                        return view('admin.ecommerce.partials.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
                    }elseif($choice_count==4){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2],$options[3]);
                        return view('admin.ecommerce.partials.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
                    }
                    elseif($choice_count==5){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2],$options[3],$options[4]);
                        return view('admin.ecommerce.partials.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
                    }
        }


    }

    // product insert
    public function insert(Request $request){
       //return $request;
        $product = new Product;
        $product->product_name = $request->product_name;
        $product->product_type = $request->product_type;
        $product->product_sku = $request->product_sku;
        $product->product_price = $request->unit_price;
        $product->cate_id = $request->cate_id;
        $product->subcate_id = $request->subcate_id;
        $product->resubcate_id = $request->resubcate_id;
        $product->brand = $request->brand;
        $product->product_qty = $request->product_qty;
        $product->allow_product_condition = $request->allow_product_condition;
        $product->product_condition = $request->product_condition;
        $product->allow_product_measurement = $request->allow_product_measurement;
        $product->product_measurement = $request->product_measurement;
        $product->allow_flash_deal = $request->allow_flash_deal;
        $product->flash_deal_start_date = $request->flash_deal_start_date;
        $product->flash_deal_end_date = $request->flash_deal_end_date;
        $product->flash_deal_type = $request->flash_deal_type;
        $product->flash_deal_price = $request->flash_deal_price;
        $product->product_description = $request->product_description;
        $product->buy_and_return_policy = $request->buy_and_return_policy;
        $product->shipping_time = $request->shipping_time;
        $product->meta_tag = $request->m_tag;
        $product->meta_description = $request->meta_description;
        $product->select_upload_type = $request->upload_type;
        $product->upload_link = $request->upload_link;
        $product->license_type = $request->license_type;
        $product->affiliate_link = $request->affiliate_link;
        // upload file
        if($request->hasFile('pdf')){

            $product->upload_file = $request->file('pdf')->store('public/uploads/products/file/');

        }
        // main image
        $photos = array();

        if($request->hasFile('photos')){
            foreach ($request->photos as $key => $photo) {
                $photoName = uniqid() . "." . $photo->getClientOriginalExtension();
                $resizedPhoto = Image::make($photo)->resize(600, 600)->save($photoName);
                Storage::disk('public')->put($photoName, $resizedPhoto);
                unlink($photoName);
                array_push($photos, $photoName);
                // $path = $photo->store('/');
                // array_push($photos,$path);
                //ImageOptimizer::optimize(base_path('public/').$path);
            }
            $product->photos = json_encode($photos);
        }
        // thumb image
        if($request->hasFile('thumbnail_img')){
                $image=$request->file('thumbnail_img');
                $ImageName='th'.'_'.time().'.'.$image->getClientOriginalExtension();
                Image::make($image)->resize(270,270)->save('public/uploads/products/thumbnail/'.$ImageName);
                Image::make($image)->resize(120,120)->save('public/uploads/products/thumbnail/smallthum/'.$ImageName);
                Image::make($image)->resize(600,600)->save('public/uploads/products/thumbnail/productdetails/'.$ImageName);
                $product->thumbnail_img =$ImageName;

                Image::make($image)->resize(64,64)->save('public/uploads/products/thumbnail/cartthum/'.$ImageName);
                $product->thumbnail_img =$ImageName;
        }

        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $product->colors = json_encode($request->colors);
        }
        else {
            $colors = array();
            $product->colors = json_encode($colors);
        }
        $choice_options = array();
        if($request->has('choice')){
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_'.$no;
                $item['name'] = 'choice_'.$no;
                $item['title'] = $request->choice[$key];
                $item['options'] = explode(',', implode('|', $request[$str]));
                array_push($choice_options, $item);
            }
        }
        $product->choice_options = json_encode($choice_options);
// combination start
        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('|',$request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }
        // if
        if($request->has('colors_active') && $request->has('choice_no')){
                $choice_count=count($request->choice_no);
                   if($choice_count == 0){
                            $combinations = Arr::crossJoin($options[0]);
                            if(count($combinations[0]) > 0){
                            foreach ($combinations as $key => $combination){
                                $str = '';
                                foreach ($combination as $key => $item){
                                    if($key > 0 ){
                                        $str .= '-'.str_replace(' ', '', $item);
                                    }
                                    else{
                                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                                            $color_name = \App\Color::where('color_code', $item)->first()->color_name;
                                            $str .= $color_name;
                                        }
                                        else{
                                            $str .= str_replace(' ', '', $item);
                                        }
                                    }
                                }
                                $item = array();
                                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                                $variations[$str] = $item;
                            }
                        }

                    }
                    elseif($choice_count==1){
                        $combinations = Arr::crossJoin($options[0],$options[1]);
                       if(count($combinations[0]) > 0){
                            foreach ($combinations as $key => $combination){
                                $str = '';
                                foreach ($combination as $key => $item){
                                    if($key > 0 ){
                                        $str .= '-'.str_replace(' ', '', $item);
                                    }
                                    else{
                                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                                            $color_name = \App\Color::where('color_code', $item)->first()->color_name;
                                            $str .= $color_name;
                                        }
                                        else{
                                            $str .= str_replace(' ', '', $item);
                                        }
                                    }
                                }
                                $item = array();
                                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                                $variations[$str] = $item;
                            }
                        }
                    }elseif($choice_count==2){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2]);
                         if(count($combinations[0]) > 0){
                            foreach ($combinations as $key => $combination){
                                $str = '';
                                foreach ($combination as $key => $item){
                                    if($key > 0 ){
                                        $str .= '-'.str_replace(' ', '', $item);
                                    }
                                    else{
                                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                                            $color_name = \App\Color::where('color_code', $item)->first()->color_name;
                                            $str .= $color_name;
                                        }
                                        else{
                                            $str .= str_replace(' ', '', $item);
                                        }
                                    }
                                }
                                $item = array();
                                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                                $variations[$str] = $item;
                            }
                        }
                    }elseif($choice_count==3){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2],$options[3]);
                         if(count($combinations[0]) > 0){
                            foreach ($combinations as $key => $combination){
                                $str = '';
                                foreach ($combination as $key => $item){
                                    if($key > 0 ){
                                        $str .= '-'.str_replace(' ', '', $item);
                                    }
                                    else{
                                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                                            $color_name = \App\Color::where('color_code', $item)->first()->color_name;
                                            $str .= $color_name;
                                        }
                                        else{
                                            $str .= str_replace(' ', '', $item);
                                        }
                                    }
                                }
                                $item = array();
                                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                                $variations[$str] = $item;
                            }
                        }
                    }elseif($choice_count==4){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2],$options[3],$options[4]);
                        if(count($combinations[0]) > 0){
                            foreach ($combinations as $key => $combination){
                                $str = '';
                                foreach ($combination as $key => $item){
                                    if($key > 0 ){
                                        $str .= '-'.str_replace(' ', '', $item);
                                    }
                                    else{
                                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                                            $color_name = \App\Color::where('color_code', $item)->first()->color_name;
                                            $str .= $color_name;
                                        }
                                        else{
                                            $str .= str_replace(' ', '', $item);
                                        }
                                    }
                                }
                                $item = array();
                                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                                $variations[$str] = $item;
                            }
                        }
                    }elseif($choice_count==5){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2],$options[3],$options[4],$options[5]);
                         if(count($combinations[0]) > 0){
                            foreach ($combinations as $key => $combination){
                                $str = '';
                                foreach ($combination as $key => $item){
                                    if($key > 0 ){
                                        $str .= '-'.str_replace(' ', '', $item);
                                    }
                                    else{
                                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                                            $color_name = \App\Color::where('color_code', $item)->first()->color_name;
                                            $str .= $color_name;
                                        }
                                        else{
                                            $str .= str_replace(' ', '', $item);
                                        }
                                    }
                                }
                                $item = array();
                                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                                $variations[$str] = $item;
                            }
                        }
                    }
            $product->variations = json_encode($variations);
        }elseif($request->has('colors_active')){

            $combinations = Arr::crossJoin($options[0]);
             if(count($combinations[0]) > 0){
                            foreach ($combinations as $key => $combination){
                                $str = '';
                                foreach ($combination as $key => $item){
                                    if($key > 0 ){
                                        $str .= '-'.str_replace(' ', '', $item);
                                    }
                                    else{
                                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                                            $color_name = \App\Color::where('color_code', $item)->first()->color_name;
                                            $str .= $color_name;
                                        }
                                        else{
                                            $str .= str_replace(' ', '', $item);
                                        }
                                    }
                                }
                                $item = array();
                                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                                $variations[$str] = $item;
                            }
                        }

        }elseif($request->has('choice_no')){
            $choice_count=count($request->choice_no);
                  if($choice_count==1){
                        $combinations = Arr::crossJoin($options[0]);
                         if(count($combinations[0]) > 0){
                            foreach ($combinations as $key => $combination){
                                $str = '';
                                foreach ($combination as $key => $item){
                                    if($key > 0 ){
                                        $str .= '-'.str_replace(' ', '', $item);
                                    }
                                    else{
                                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                                            $color_name = \App\Color::where('color_code', $item)->first()->color_name;
                                            $str .= $color_name;
                                        }
                                        else{
                                            $str .= str_replace(' ', '', $item);
                                        }
                                    }
                                }
                                $item = array();
                                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                                $variations[$str] = $item;
                            }
                        }
                    }
                   elseif($choice_count==2){
                        $combinations = Arr::crossJoin($options[0],$options[1]);
                         if(count($combinations[0]) > 0){
                            foreach ($combinations as $key => $combination){
                                $str = '';
                                foreach ($combination as $key => $item){
                                    if($key > 0 ){
                                        $str .= '-'.str_replace(' ', '', $item);
                                    }
                                    else{
                                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                                            $color_name = \App\Color::where('color_code', $item)->first()->color_name;
                                            $str .= $color_name;
                                        }
                                        else{
                                            $str .= str_replace(' ', '', $item);
                                        }
                                    }
                                }
                                $item = array();
                                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                                $variations[$str] = $item;
                            }
                        }
                    }elseif($choice_count==3){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2]);
                         if(count($combinations[0]) > 0){
                            foreach ($combinations as $key => $combination){
                                $str = '';
                                foreach ($combination as $key => $item){
                                    if($key > 0 ){
                                        $str .= '-'.str_replace(' ', '', $item);
                                    }
                                    else{
                                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                                            $color_name = \App\Color::where('color_code', $item)->first()->color_name;
                                            $str .= $color_name;
                                        }
                                        else{
                                            $str .= str_replace(' ', '', $item);
                                        }
                                    }
                                }
                                $item = array();
                                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                                $variations[$str] = $item;
                            }
                        }
                    }elseif($choice_count==4){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2],$options[3]);
                         if(count($combinations[0]) > 0){
                            foreach ($combinations as $key => $combination){
                                $str = '';
                                foreach ($combination as $key => $item){
                                    if($key > 0 ){
                                        $str .= '-'.str_replace(' ', '', $item);
                                    }
                                    else{
                                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                                            $color_name = \App\Color::where('color_code', $item)->first()->color_name;
                                            $str .= $color_name;
                                        }
                                        else{
                                            $str .= str_replace(' ', '', $item);
                                        }
                                    }
                                }
                                $item = array();
                                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                                $variations[$str] = $item;
                            }
                        }
                    }
                    elseif($choice_count==5){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2],$options[3],$options[4]);
                         if(count($combinations[0]) > 0){
                            foreach ($combinations as $key => $combination){
                                $str = '';
                                foreach ($combination as $key => $item){
                                    if($key > 0 ){
                                        $str .= '-'.str_replace(' ', '', $item);
                                    }
                                    else{
                                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                                            $color_name = \App\Color::where('color_code', $item)->first()->color_name;
                                            $str .= $color_name;
                                        }
                                        else{
                                            $str .= str_replace(' ', '', $item);
                                        }
                                    }
                                }
                                $item = array();
                                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                                $variations[$str] = $item;
                            }
                        }
                    }
                      $product->variations = json_encode($variations);
        }

     $product->save();

     // Product License:
    if($request->license_key){
        if(count($request->license_key) > 0){
            foreach ($request->license_key as $item => $v) {
               $data3=array(
                'product_id'=>$product->id,
                'license_key'=>$request->license_key[$item],
                'license_quantity'=>$request->license_quantity[$item],
                'license_duration'=>$request->license_duration[$item],
                'created_at'=>Carbon::now()->toDateTimeString(),

               );
               ProductLicense::Insert($data3);
            }

        }
    }

    // end product licence

       if($product->save()){
         $notification=array(
                'messege'=>'Product Insert Successfully',
                'alert-type'=>'success'
                 );
               return Redirect()->back()->with($notification);
       }
       else{
        $notification=array(
                'messege'=>'Product Insert Faild',
                'alert-type'=>'error'
                 );
               return Redirect()->back()->with($notification);
       }

    }

    // end insert
  public function updatetodaydeal(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->today_deal = $request->status;
        if($product->save()){
            return 1;
        }
        return 0;
    }


    public function updatepublished(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->status = $request->status;
        if($product->save()){
            return 1;
        }
        return 0;
    }

    public function view($id){
        $view=Product::where('id',$id)->first();
        return view('admin.ecommerce.product.view',compact('view'));
    }


    // multisoft delete
    public function productmultisoftdelete(Request $request){

        $deleteid=$request->Input('delid');
         if($deleteid){
         $delet=Product::whereIn('id',$deleteid)->update([
                'is_deleted'=>'1',
                'updated_at'=>Carbon::now()->toDateTimeString(),
         ]);
         if($delet){
             $notification=array(
                'messege'=>'success',
                'alert-type'=>'success'
                 );
             return redirect()->back()->with($notification);
         }else{
             $notification=array(
                'messege'=>'error',
                'alert-type'=>'error'
                 );
             return redirect()->back()->with($notification);
            }
         }else{
            $notification=array(
                'messege'=>'Nothing To Delete',
                'alert-type'=>'info'
                 );
             return redirect()->back()->with($notification);
         }

    }
    // restore
    public function prorecover($id){
        $recover=Product::where('id',$id)->update([
            'is_deleted'=>'0',
            'updated_at'=>Carbon::now()->toDateTimeString(),
        ]);
        if($recover){
                $notification=array(
                'messege'=>'Restore Success',
                'alert-type'=>'success'
                 );
             return redirect()->back()->with($notification);
        }else{
            $notification=array(
                'messege'=>'Faild',
                'alert-type'=>'error'
                 );
             return redirect()->back()->with($notification);
        }
    }
    // product physical type one edit
    public function producteditone($id){
        $data=Product::where('id',$id)->first();
        $tag= json_decode($data->meta_tag);
        return view('admin.ecommerce.product.editphysical',compact('data','tag'));
    }
    //product digital two edit
    public function productedittwo($id){
        $data=Product::where('id',$id)->first();
        $tag= json_decode($data->meta_tag);
        return view('admin.ecommerce.product.editdigital',compact('data','tag'));
    }
    // product lincence three edit
    public function producteditthree($id){
         $data=Product::where('id',$id)->first();
         $tag= json_decode($data->meta_tag);
         return view('admin.ecommerce.product.editlicense',compact('data','tag'));
    }
    // affiliate
   public function producteditfour($id){

        $data=Product::where('id',$id)->first();
        $tag= json_decode($data->meta_tag);
        return view('admin.ecommerce.product.editaffiliate',compact('data','tag'));
   }
   // edit combination start
   public function sku_combination_edit(Request $request){

        $product = Product::findOrFail($request->id);
        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }
        else {
            $colors_active = 0;
        }

        $product_name = $request->product_name;
        $unit_price = $request->unit_price;

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }


        if($request->has('colors_active') && $request->has('choice_no')){
                $choice_count=count($request->choice_no);
                   if($choice_count == 0){
                        $combinations = Arr::crossJoin($options[0]);
                        return view('admin.ecommerce.partials.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
                    }
                    elseif($choice_count==1){
                        $combinations = Arr::crossJoin($options[0],$options[1]);
                        return view('admin.ecommerce.partials.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
                    }elseif($choice_count==2){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2]);
                        return view('admin.ecommerce.partials.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
                    }elseif($choice_count==3){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2],$options[3]);
                        return view('admin.ecommerce.partials.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
                    }elseif($choice_count==4){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2],$options[3],$options[4]);
                        return view('admin.ecommerce.partials.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
                    }elseif($choice_count==5){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2],$options[3],$options[4],$options[5]);
                        return view('admin.ecommerce.partials.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
                    }
        }elseif($request->has('colors_active')){

            $combinations = Arr::crossJoin($options[0]);
            return view('admin.ecommerce.partials.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));

        }elseif($request->has('choice_no')){
            $choice_count=count($request->choice_no);
                  if($choice_count==1){
                        $combinations = Arr::crossJoin($options[0]);
                        return view('admin.ecommerce.partials.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
                    }
                   elseif($choice_count==2){
                        $combinations = Arr::crossJoin($options[0],$options[1]);
                        return view('admin.ecommerce.partials.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
                    }elseif($choice_count==3){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2]);
                        return view('admin.ecommerce.partials.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
                    }elseif($choice_count==4){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2],$options[3]);
                        return view('admin.ecommerce.partials.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
                    }
                    elseif($choice_count==5){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2],$options[3],$options[4]);
                       return view('admin.ecommerce.partials.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
                    }
        }
   }

   // update product
        public function update(Request $request,$id){
            // return $request;
        $product = Product::findOrFail($id);
        $product->product_name = $request->product_name;
        $product->product_sku = $request->product_sku;
        $product->product_price = $request->unit_price;
        $product->cate_id = $request->cate_id;
        $product->subcate_id = $request->subcate_id;
        $product->resubcate_id = $request->resubcate_id;
        $product->brand = $request->brand;
        $product->product_qty = $request->product_qty;
        $product->allow_product_condition = $request->allow_product_condition;
        $product->product_condition = $request->product_condition;
        $product->allow_product_measurement = $request->allow_product_measurement;
        $product->product_measurement = $request->product_measurement;
        $product->allow_flash_deal = $request->allow_flash_deal;
        $product->flash_deal_start_date = $request->flash_deal_start_date;
        $product->flash_deal_end_date = $request->flash_deal_end_date;
        $product->flash_deal_type = $request->flash_deal_type;
        $product->flash_deal_price = $request->flash_deal_price;
        $product->product_description = $request->product_description;
        $product->buy_and_return_policy = $request->buy_and_return_policy;
        $product->shipping_time = $request->shipping_time;
        $product->meta_tag = $request->m_tag;
        $product->meta_description = $request->meta_description;
        // digital product edit field
            $product->select_upload_type = $request->upload_type;
            $product->upload_link = $request->upload_link;
            if($request->hasFile('pdf')){
            $product->upload_file = $request->pdf->store('public/uploads/products/file');
           }
        // end digital product edit

        // affiliate prodact edit field
           $product->affiliate_link = $request->affiliate_link;
        // affiliate prodact edit field

        // license product edit field
            $product->license_type = $request->license_type;
        //lincense product edit end

        if($request->has('previous_photos')){
            $photos = $request->previous_photos;
        }
        else{
            $photos = array();
        }

        if($request->hasFile('photos')){
            foreach ($request->photos as $key => $photo) {
                
                $photoName = uniqid() . "." . $photo->getClientOriginalExtension();
                $resizedPhoto = Image::make($photo)->resize(600, 600)->save($photoName);
                Storage::disk('public')->put($photoName, $resizedPhoto);
                unlink($photoName);
                array_push($photos, $photoName);
                // $path = $photo->store('/');
                // array_push($photos, $path);

            }
        }
        $product->photos = json_encode($photos);
        $product->thumbnail_img = $request->previous_thumbnail_img;
        if($request->hasFile('thumbnail_img')){
                $image=$request->file('thumbnail_img');
                $ImageName='th'.'_'.time().'.'.$image->getClientOriginalExtension();
                Image::make($image)->resize(270,270)->save('public/uploads/products/thumbnail/'.$ImageName);
                Image::make($image)->resize(120,120)->save('public/uploads/products/thumbnail/smallthum/'.$ImageName);
                Image::make($image)->resize(600,600)->save('public/uploads/products/thumbnail/productdetails/'.$ImageName);
                $product->thumbnail_img =$ImageName;

                Image::make($image)->resize(64,64)->save('public/uploads/products/thumbnail/cartthum/'.$ImageName);
                $product->thumbnail_img =$ImageName;
        }

        //combinations start
if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $product->colors = json_encode($request->colors);
        }
        else {
            $colors = array();
            $product->colors = json_encode($colors);
        }
        $choice_options = array();
        if($request->has('choice')){
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_'.$no;
                $item['name'] = 'choice_'.$no;
                $item['title'] = $request->choice[$key];
                $item['options'] = explode(',', implode('|', $request[$str]));
                array_push($choice_options, $item);
            }
        }
        $product->choice_options = json_encode($choice_options);
// combination start
        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('|',$request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }
        // if
        if($request->has('colors_active') && $request->has('choice_no')){
                $choice_count=count($request->choice_no);
                   if($choice_count == 0){
                            $combinations = Arr::crossJoin($options[0]);
                            if(count($combinations[0]) > 0){
                            foreach ($combinations as $key => $combination){
                                $str = '';
                                foreach ($combination as $key => $item){
                                    if($key > 0 ){
                                        $str .= '-'.str_replace(' ', '', $item);
                                    }
                                    else{
                                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                                            $color_name = \App\Color::where('color_code', $item)->first()->color_name;
                                            $str .= $color_name;
                                        }
                                        else{
                                            $str .= str_replace(' ', '', $item);
                                        }
                                    }
                                }
                                $item = array();
                                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                                $variations[$str] = $item;
                            }
                        }

                    }
                    elseif($choice_count==1){
                        $combinations = Arr::crossJoin($options[0],$options[1]);
                       if(count($combinations[0]) > 0){
                            foreach ($combinations as $key => $combination){
                                $str = '';
                                foreach ($combination as $key => $item){
                                    if($key > 0 ){
                                        $str .= '-'.str_replace(' ', '', $item);
                                    }
                                    else{
                                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                                            $color_name = \App\Color::where('color_code', $item)->first()->color_name;
                                            $str .= $color_name;
                                        }
                                        else{
                                            $str .= str_replace(' ', '', $item);
                                        }
                                    }
                                }
                                $item = array();
                                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                                $variations[$str] = $item;
                            }
                        }
                    }elseif($choice_count==2){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2]);
                         if(count($combinations[0]) > 0){
                            foreach ($combinations as $key => $combination){
                                $str = '';
                                foreach ($combination as $key => $item){
                                    if($key > 0 ){
                                        $str .= '-'.str_replace(' ', '', $item);
                                    }
                                    else{
                                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                                            $color_name = \App\Color::where('color_code', $item)->first()->color_name;
                                            $str .= $color_name;
                                        }
                                        else{
                                            $str .= str_replace(' ', '', $item);
                                        }
                                    }
                                }
                                $item = array();
                                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                                $variations[$str] = $item;
                            }
                        }
                    }elseif($choice_count==3){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2],$options[3]);
                         if(count($combinations[0]) > 0){
                            foreach ($combinations as $key => $combination){
                                $str = '';
                                foreach ($combination as $key => $item){
                                    if($key > 0 ){
                                        $str .= '-'.str_replace(' ', '', $item);
                                    }
                                    else{
                                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                                            $color_name = \App\Color::where('color_code', $item)->first()->color_name;
                                            $str .= $color_name;
                                        }
                                        else{
                                            $str .= str_replace(' ', '', $item);
                                        }
                                    }
                                }
                                $item = array();
                                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                                $variations[$str] = $item;
                            }
                        }
                    }elseif($choice_count==4){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2],$options[3],$options[4]);
                        if(count($combinations[0]) > 0){
                            foreach ($combinations as $key => $combination){
                                $str = '';
                                foreach ($combination as $key => $item){
                                    if($key > 0 ){
                                        $str .= '-'.str_replace(' ', '', $item);
                                    }
                                    else{
                                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                                            $color_name = \App\Color::where('color_code', $item)->first()->color_name;
                                            $str .= $color_name;
                                        }
                                        else{
                                            $str .= str_replace(' ', '', $item);
                                        }
                                    }
                                }
                                $item = array();
                                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                                $variations[$str] = $item;
                            }
                        }
                    }elseif($choice_count==5){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2],$options[3],$options[4],$options[5]);
                         if(count($combinations[0]) > 0){
                            foreach ($combinations as $key => $combination){
                                $str = '';
                                foreach ($combination as $key => $item){
                                    if($key > 0 ){
                                        $str .= '-'.str_replace(' ', '', $item);
                                    }
                                    else{
                                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                                            $color_name = \App\Color::where('color_code', $item)->first()->color_name;
                                            $str .= $color_name;
                                        }
                                        else{
                                            $str .= str_replace(' ', '', $item);
                                        }
                                    }
                                }
                                $item = array();
                                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                                $variations[$str] = $item;
                            }
                        }
                    }
            $product->variations = json_encode($variations);
        }elseif($request->has('colors_active')){

            $combinations = Arr::crossJoin($options[0]);
             if(count($combinations[0]) > 0){
                            foreach ($combinations as $key => $combination){
                                $str = '';
                                foreach ($combination as $key => $item){
                                    if($key > 0 ){
                                        $str .= '-'.str_replace(' ', '', $item);
                                    }
                                    else{
                                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                                            $color_name = \App\Color::where('color_code', $item)->first()->color_name;
                                            $str .= $color_name;
                                        }
                                        else{
                                            $str .= str_replace(' ', '', $item);
                                        }
                                    }
                                }
                                $item = array();
                                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                                $variations[$str] = $item;
                            }
                        }

        }elseif($request->has('choice_no')){
            $choice_count=count($request->choice_no);
                  if($choice_count==1){
                        $combinations = Arr::crossJoin($options[0]);
                         if(count($combinations[0]) > 0){
                            foreach ($combinations as $key => $combination){
                                $str = '';
                                foreach ($combination as $key => $item){
                                    if($key > 0 ){
                                        $str .= '-'.str_replace(' ', '', $item);
                                    }
                                    else{
                                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                                            $color_name = \App\Color::where('color_code', $item)->first()->color_name;
                                            $str .= $color_name;
                                        }
                                        else{
                                            $str .= str_replace(' ', '', $item);
                                        }
                                    }
                                }
                                $item = array();
                                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                                $variations[$str] = $item;
                            }
                        }
                    }
                   elseif($choice_count==2){
                        $combinations = Arr::crossJoin($options[0],$options[1]);
                         if(count($combinations[0]) > 0){
                            foreach ($combinations as $key => $combination){
                                $str = '';
                                foreach ($combination as $key => $item){
                                    if($key > 0 ){
                                        $str .= '-'.str_replace(' ', '', $item);
                                    }
                                    else{
                                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                                            $color_name = \App\Color::where('color_code', $item)->first()->color_name;
                                            $str .= $color_name;
                                        }
                                        else{
                                            $str .= str_replace(' ', '', $item);
                                        }
                                    }
                                }
                                $item = array();
                                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                                $variations[$str] = $item;
                            }
                        }
                    }elseif($choice_count==3){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2]);
                         if(count($combinations[0]) > 0){
                            foreach ($combinations as $key => $combination){
                                $str = '';
                                foreach ($combination as $key => $item){
                                    if($key > 0 ){
                                        $str .= '-'.str_replace(' ', '', $item);
                                    }
                                    else{
                                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                                            $color_name = \App\Color::where('color_code', $item)->first()->color_name;
                                            $str .= $color_name;
                                        }
                                        else{
                                            $str .= str_replace(' ', '', $item);
                                        }
                                    }
                                }
                                $item = array();
                                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                                $variations[$str] = $item;
                            }
                        }
                    }elseif($choice_count==4){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2],$options[3]);
                         if(count($combinations[0]) > 0){
                            foreach ($combinations as $key => $combination){
                                $str = '';
                                foreach ($combination as $key => $item){
                                    if($key > 0 ){
                                        $str .= '-'.str_replace(' ', '', $item);
                                    }
                                    else{
                                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                                            $color_name = \App\Color::where('color_code', $item)->first()->color_name;
                                            $str .= $color_name;
                                        }
                                        else{
                                            $str .= str_replace(' ', '', $item);
                                        }
                                    }
                                }
                                $item = array();
                                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                                $variations[$str] = $item;
                            }
                        }
                    }
                    elseif($choice_count==5){
                        $combinations = Arr::crossJoin($options[0],$options[1],$options[2],$options[3],$options[4]);
                         if(count($combinations[0]) > 0){
                            foreach ($combinations as $key => $combination){
                                $str = '';
                                foreach ($combination as $key => $item){
                                    if($key > 0 ){
                                        $str .= '-'.str_replace(' ', '', $item);
                                    }
                                    else{
                                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                                            $color_name = \App\Color::where('color_code', $item)->first()->color_name;
                                            $str .= $color_name;
                                        }
                                        else{
                                            $str .= str_replace(' ', '', $item);
                                        }
                                    }
                                }
                                $item = array();
                                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                                $variations[$str] = $item;
                            }
                        }
                    }
                      $product->variations = json_encode($variations);
        }
        //combination end







        //dd($request->license_key);

        // foreach($request->license_key as $key=>$license){
            // $updatelis=ProductLicense::where('product_id',$id)->where('id',$key)->first();
            // dd($updatelis);
            // $data3=array(
            //     'product_id'=>$updatelis,
            //     'license_key'=>$request->license_key[$key],
            //     'license_quantity'=>$request->license_quantity[$key],
            //     'license_duration'=>$request->license_duration[$key],
            //     'created_at'=>Carbon::now()->toDateTimeString(),
            //    );
            //    ProductLicense::Insert($data3);
            // $updatelis=array(
            //     'license_key'=>$request->license_key[$license],
            //     'license_quantity'=>$request->license_quantity[$license],
            //     'license_duration'=>$request->license_duration[$license],
            //     'created_at'=>Carbon::now()->toDateTimeString(),

            //    );
            //    ProductLicense::Insert($updatelis);
             // $updatelis->license_key=$license->license_key;
             // $updatelis->save();
            // $license->license_key=$request->license_key;
            // $license->license_quantity=$request->license_quantity;
            // $license->license_quantity=$request->license_quantity;
            // $license->save();
        // }

         // $updateli->save();
       $product->save();
        if($request->license_key){
           $prlicenseid=$request['licenseid'];
        if($prlicenseid){
        $ProductLicense=ProductLicense::whereIn('id',$prlicenseid)->delete();

        if(count($request->license_key) > 0){
            foreach ($request->license_key as $item => $v) {
               $data4=array(
                'product_id'=>$request->proid,
                'license_key'=>$request->license_key[$item],
                'license_quantity'=>$request->license_quantity[$item],
                'license_duration'=>$request->license_duration[$item],
                'created_at'=>Carbon::now()->toDateTimeString(),
               );
               ProductLicense::insert($data4);

            }

        }
      }else{
        if(count($request->license_key) > 0){
            foreach ($request->license_key as $item => $v) {
               $data4=array(
                'product_id'=>$product->id,
                'license_key'=>$request->license_key[$item],
                'license_quantity'=>$request->license_quantity[$item],
                'license_duration'=>$request->license_duration[$item],
                'created_at'=>Carbon::now()->toDateTimeString(),
               );
               ProductLicense::insert($data4);

            }

        }
      }
    }





        if($product->save()){
          $notification=array(
                'messege'=>'Update Success',
                'alert-type'=>'success'
                 );
               return Redirect()->back()->with($notification);
        }else{
            $notification=array(
                'messege'=>'Update Faild',
                'alert-type'=>'error'
                 );
               return Redirect()->back()->with($notification);
        }







   }

   // license pro
   public function licencepro($id){
        $data=ProductLicense::where('id',$id)->delete();
         return redirect()->back();
   }

   // single soft delete
   public function softdelete($id){
        $softdelete=Product::where('id',$id)->update([
            'is_deleted'=>'1',
            'updated_at'=>Carbon::now()->toDateTimeString(),

        ]);
        if($softdelete){
            $notification=array(
                'messege'=>'SoftDelete Success',
                'alert-type'=>'success'
                 );
               return Redirect()->back()->with($notification);
        }else{
            $notification=array(
                'messege'=>'softdelete Faild',
                'alert-type'=>'error'
                 );
               return Redirect()->back()->with($notification);
        }
   }
   // heard delete single product
   public function hearddelete($id){
        $product_id=Product::where('id',$id)->first();
        $thum_image=$product_id->thumbnail_img;

         if($thum_image){
            unlink(''.$thum_image);

             $delet=Product::where('id',$id)->delete();
             if($delet){
                $notification=array(
                'messege'=>'Product Delete Success',
                'alert-type'=>'success'
                 );
               return Redirect()->back()->with($notification);
             }else{
                $notification=array(
                'messege'=>'softdelete Faild',
                'alert-type'=>'error'
                 );
               return Redirect()->back()->with($notification);
             }
        }else{
            $delet=Product::where('id',$id)->delete();
             if($delet){
                $notification=array(
                'messege'=>'Product Delete Success',
                'alert-type'=>'success'
                 );
               return Redirect()->back()->with($notification);
             }else{
                $notification=array(
                'messege'=>'softdelete Fail',
                'alert-type'=>'error'
                 );
               return Redirect()->back()->with($notification);
             }
        }


   }


}
