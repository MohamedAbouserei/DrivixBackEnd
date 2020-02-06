<?php

namespace App\Http\Controllers;
use App\Product;
use App\Sparesshop;
use App\User;
Use Exception;
use Illuminate\Http\Request;
use Validator;
use App\Productimg;
use Carbon\Carbon;
use File;
use Yajra\Datatables\Datatables;
use Response;
use Illuminate\Support\Facades\Session;


class productController extends Controller
{
    //
    
    public function searchProduct(Request $request){
        try{
            $validate=Validator::make($request->all(),[       
                'text' =>'required|string'
            ]);
            if ($validate->fails()){
                $errors = $validate->errors();
                return Response()->json($errors, 500);
            }
            $findProducts = Product::where('name' , 'LIKE' , "%{$request->text}%")->limit(10)->get();
            foreach ($findProducts as $product) {
                $product->Productimg;
                foreach($product->Productimg as $img){
                    $img->image = 'http://www.drivixcorp.com/api/storage/'.$img->image.'/ProductsImgs';
                }
            }
            return response()->json($findProducts,200);
        }catch(Exception $ex){
            return response()->json(['msg' => 'failed!Please Try again'], 400);
        }
    }
    
    public function addproduct(Request $request){
        try{
            $validate=Validator::make($request->all(),[       
                'name' =>'required|max:50|min:5|string',
                'description'=>'required|min:5|max:50|string',
                'brand'=>'required|min:5|max:50|string',
                'price'=>'required|numeric|between:0.5,1000000',
                'sparesshop_id'=>'required|integer|exists:sparesshop,id',
            ]);
            if ($validate->fails()){
                $errors = $validate->errors();
                return Response()->json($errors, 500);
            }
            $newProduct=new Product();
            $newProduct->name=$request->name;
            $newProduct->description=$request->description;
            $newProduct->brand=$request->brand;
            $newProduct->price=$request->price;
            $newProduct->sparesshop_id=$request->sparesshop_id;
            $newProduct->save();
            return response()->json(['msg'=>'Product added Successfully'],200);
        }catch(Exception $ex){
            return response()->json(['msg' => 'failed!Please Try again'], 400);
        }
    }

    public function editproduct(Request $request){
        try{
            $validate=Validator::make($request->all(),[       
                'productID'=>'required|integer|exists:product,id',
                'name' =>'required|max:50|min:5|string',
                'description'=>'required|min:5|max:50|string',
                'brand'=>'required|min:5|max:50|string',
                'price'=>'required|numeric|between:0.5,100000',                
            ]);
            if ($validate->fails()) {
                $errors = $validate->errors();
                return Response()->json($errors, 500);
             }
            $Product=Product::find($request->productID);
            $Product->name=$request->name;
            $Product->description=$request->description;
            $Product->brand=$request->brand;
            $Product->price=$request->price;            
            $Product->status=0;
            $Product->save();
            return response()->json(['msg'=>'Product Updated Successfully'],200);
        } catch(Exception $ex){
            return response()->json(['msg' => 'failed!Please Try again'], 400);
        }
    }

    public function deleteproduct(Request $request){
        try{
            $validate=Validator::make($request->all(),[       
                'productID'=>'required|integer|exists:product,id',                
            ]);
            if ($validate->fails()) {
                $errors = $validate->errors();
                return Response()->json($errors, 500);
             }
            $product=Product::find($request->productID);
            $product->delete();
            return response()->json(['msg' => 'Product Successfully deleted'], 200);
        } catch(Exception $ex){
            return response()->json(['msg' => 'failed! product doesn\'t exist'], 400);
        }
    }

    public function myproducts(Request $request){
        try{
            $validate=Validator::make($request->all(),[       
                'spareShopID'=>'required|integer|exists:sparesshop,id',                
            ]);
            if ($validate->fails()) {
                $errors = $validate->errors();
                return Response()->json($errors, 500);
             }
            $shop=Sparesshop::find($request->spareShopID);
            $products=$shop->Product;
            foreach($products as $product){
                $product->Productimg;
                foreach($product->Productimg as $img){
                    $img->image = 'http://www.drivixcorp.com/api/storage/'.$img->image.'/ProductsImgs';
                }
            }
            return response()->json($products,200);
        } catch(Exception $ex){
            return response()->json(['msg'=>'There is no Products in Your shop'],400);
        }
    }
    
    /* Product Images Functionality */
    // add product image
    public function addProductsImages(Request $request){
        $validator = Validator::make($request->all() , [
            'token' => 'required|string|exists:users,token',
            'role_id' => 'required|integer|exists:role,id',
            'type' => 'required|in:0,1|max:1',
            'product_id' => 'required|integer|exists:product,id',
        ]);
        
        // validate Data
        if($validator->fails()){
            $errors = $validator->errors();
            return Response()->json($errors, 400);
        }    
        
        try{ 
            $user = User::where('token', $request->token)->first();

            $targetSparePart = $user->Serviceprovider->Role->where('id',$request->role_id)->where('type',3)->first();
            if(!$targetSparePart ) { return response()->json(['msg'=>'un authorize '],300); }

            // check if isset Image form body
           if($request->images){
                $check_images =  false;
                foreach($request->images as $img){
                    // do spilt check
                    $check_images = $this->is_base64($img);
                    if(!$check_images){break;}
                }

                if(!$check_images){
                    return response()->json(['msg' => 'please enter a valid  image'], 350);
                }
                // continue working product_id
                foreach($request->images as $img){
                    $imageName = $this->storeImageBase64($img , 'ProductsImgs');
                    $productImg = new Productimg ;
                    $productImg->image = $imageName;
                    $productImg->product_id = $request->product_id;
                    $productImg->date = Carbon::now();
                    $productImg->type = $request->type;
                    $productImg->save();
                }
                return response()->json(['msg'=>'Image Uploaded Successfully!'],200);   
           }else{
                return response()->json(['msg' => 'Image is required'], 350);
            }
            
        }catch(Exception $ex){
            return response()->json(['msg' => 'failed!'.$ex->getMessage()], 500);
        }
    }
    
    // Storing Base 64 Images
    public function storeImageBase64($base64Image, $type) {
    	$decodedImage = base64_decode($base64Image);
    	$imageName = rand(1, 999) .'_role_' . time() . '.png';
    	$fp = fopen(public_path() . '/imgs/' . $type . '/' . $imageName, 'wb+');
    	fwrite($fp, $decodedImage);
    	fclose($fp);
    	return $imageName;
    }
    
    // Check IF Image Is base64
    public function is_base64($base64Image) {
    	return (bool) preg_match('`^[a-zA-Z0-9+/]+={0,2}$`', $base64Image);
    }
    
    // delete product image
    public function deleteProductsImage(Request $request){
        $validator = Validator::make($request->all() , [
            'token' => 'required|string|exists:users,token',
            'role_id' => 'required|integer|exists:role,id',
            'product_id' => 'required|integer|exists:product,id',
            'productImg_id' => 'required|integer|exists:productimg,id',
        ]);
        
        // validate Data
        if($validator->fails()){
            $errors = $validator->errors();
            return Response()->json($errors, 400);
        }    
        
        try{ 
            $user = User::where('token', $request->token)->first();
            
            $targetRole = $user->Serviceprovider->Role->where('id',$request->role_id)->where('type',3)->first();
            if(!$targetRole ) { return response()->json(['msg'=>'un authorize '],300); }

            $targetProduct = $targetRole->Carservice->Sparesshop->Product->find($request->product_id);
            if(!$targetProduct ) { return response()->json(['msg'=>'un authorize '],300); }
            
            $targetImage = $targetProduct->Productimg->find($request->productImg_id);
            if(!$targetImage ) { return response()->json(['msg'=>'un authorize'],300); }
            
            $this->deleteImageFile($targetImage->image , 'ProductsImgs');

            $targetImage->delete();
            return response()->json(['msg'=>'Deleted Successfully!'],200);

        }
        catch(Exception $ex){
            return response()->json(['msg' => 'failed!'.$ex->getMessage()], 500);
        }
    }
    
    public function deleteImageFile($image, $type) {
    	$imgPath = public_path('/imgs/' . $type . '/' . $image);
    	if (File::exists($imgPath)) {
    		File::delete($imgPath);
    	}
    }
    
    // cms function
    public function ProductsCms () {
        return view('product.index');
    }
    public function getProductAjax () {
        $AllDaTa= Product::all();
        $allProducts = array();
        $x = 0;
        foreach ($AllDaTa as $product) {
            // check date first if set
            $created_at = new Carbon($product->created_at);
            $date = new \DateTime($created_at);
            $created_at = $date->format('m/d/Y');

            $updated_at = new Carbon($product->updated_at);
            $date = new \DateTime($updated_at);
            $updated_at = $date->format('m/d/Y');

            $allProducts[$x]['id'] = $product->id;
            $allProducts[$x]['name'] = $product->name;
            $allProducts[$x]['brand'] = $product->brand;
            $allProducts[$x]['price'] = $product->price;
            $allProducts[$x]['status'] = $product->status;
            $allProducts[$x]['created_at'] = $created_at;
            $allProducts[$x]['updated_at'] = $updated_at;
            $path = 'http://localhost:8000/api/storage/';
            $serverpath = 'http://www.drivixcorp.com/api/storage/';
            if(isset($product->Productimg) && isset($product->Productimg[0])) {
                $response = $serverpath .$product->Productimg[0]->image.'/ProductsImgs';
                $allProducts[$x]['image'] = $response;
            } else {
                $response = $serverpath .'product.png'.'/ProductsImgs';
                $allProducts[$x]['image'] = $response;
            }
            $x++;
        }
        $data = collect($allProducts);
        return Datatables::of($data)->setRowClass(function($p) {
            return $p['status'] == 0 ? 'locked-row' : 'unlocked-row';
        })->make(true);
    }
    public function lockAunlockProduct (Request $request) {
        $p = Product::find($request->id);
        if(isset($p)) {
            if($p->status ==1) {
                $p->status = 0;
            } else { $p->status = 1 ;}
            $p->save();
            return 'true';
        }
        return 'false';
    }
    public function getProductCms($id) {
        $p = Product::find($id);
        if(isset($p)) {
            $p->images = $p->Productimg;
            return view('product.show' , compact('p'));
        }
        else {
            Session::flash('warning','this Product is not exists any more !!');
            return redirect()->route('products');
        }
    }
    
}
