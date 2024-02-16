<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Auth;
use Validator;
use Log;
class AdminController extends Controller
{
    public function products()
    {
        $products=DB::table('products')->where('is_deleted',0)->orderby('id','Desc')->get();
        return view('admin.products.view',compact('products'));
    }
    public function addproducts()
    {
        return view('admin.products.add');
    }
    public function storeproduct(Request $request)
    {
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }
        $data = [
            'name' => $request->input('name'),
            'type' => $request->input('type'),
            'sku' => $request->input('sku'),
            'manufacturer' => $request->input('manufacturer'),
            'upc' => $request->input('upc'),
            'ean' => $request->input('ean'),
            'weight' => $request->input('weight'),
            'brand' => $request->input('brand'),
            'qty' => $request->input('qty'),
            'isbn' => $request->input('isbn'),
            'unit' => $request->input('unit'),
            'returnable' => $request->input('returnable'),
            'length' => $request->input('length'),
            'width' => $request->input('width'),
            'height' => $request->input('height'),
            'purchase_price' => $request->input('purchase_price'),
            'sale_price' => $request->input('sale_price'),
            'description' => $request->input('description'),
        ];
        if ($imagePath) {
            $data['image'] = $imagePath;
        }
        DB::table('products')->insert($data);
        $productId = DB::table('products')->insertGetId($data);
        if ($productId) {
            return redirect('products')->with('success', 'Product added Successfully!');
        } else {
            \Log::channel('mylog')->info('Product addition failed. Data: ' . json_encode($data) . ' User ID: ' . 1 . ' User IP: ' . request()->ip());
            return back();
        }
    }


    public function editproducts($id)
    {
        $product = DB::table('products')->where('id', $id)->first();
    
        if (!$product) {
            \Log::channel('mylog')->info('Edit product id not found id:'.$id.' User ID: ' . 1 . ' User IP: ' . request()->ip());
            return redirect('products');
        }
        return view('admin.products.edit', compact('product'));
    }

    public function updateproduct(Request $request)
    {
        $id=$request->input('id');
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }
        $data = [
            'name' => $request->input('name'),
            'type' => $request->input('type'),
            'sku' => $request->input('sku'),
            'manufacturer' => $request->input('manufacturer'),
            'upc' => $request->input('upc'),
            'ean' => $request->input('ean'),
            'weight' => $request->input('weight'),
            'brand' => $request->input('brand'),
            'qty' => $request->input('qty'),
            'isbn' => $request->input('isbn'),
            'unit' => $request->input('unit'),
            'returnable' => $request->input('returnable'),
            'length' => $request->input('length'),
            'width' => $request->input('width'),
            'height' => $request->input('height'),
            'purchase_price' => $request->input('purchase_price'),
            'sale_price' => $request->input('sale_price'),
            'description' => $request->input('description'),
        ];
        if ($imagePath) {
            $data['image'] = $imagePath;
        }
        $result = DB::table('products')->where('id', $id)->update($data);
        if ($result === 0) {
            return redirect('products')->with('success', 'Product Updated Successfully!');
        }else{
            \Log::channel('mylog')->info('Failed to update product. ID: ' . $id. ' Data:' .json_encode($data));
            return redirect('products');
        }
    }
    public function deleteproducts($id)
    {
        $product = DB::table('products')->find($id);
        if ($product) {
            DB::table('products')->where('id', $id)->update(['is_deleted' => 1]);
            return back()->with('success', 'Product Deleted Successfully!');
        }
        \Log::channel('mylog')->info('Failed to Delete product. ID: '.$id);
        return redirect('products');
    }
    public function showProduct($id)
    {
        $product = DB::table('products')->find($id);
        if (!$product) {
            \Log::channel('mylog')->info('Product not found for show with this ID: '.$id);
            return redirect('products');
        }
        return view('admin.products.show', compact('product'));
    }
}
