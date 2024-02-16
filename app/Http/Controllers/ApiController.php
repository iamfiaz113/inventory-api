<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Auth;
use Validator;
use Log;
/**
 * @OA\Info(
 *     title="Inventory API",
 *     version="1.0.0",
 *     description="API documentation for the Inventory system",
 *     @OA\Contact(
 *         email="your@email.com"
 *     ),
 *     @OA\License(
 *         name="MIT License",
 *         url="http://opensource.org/licenses/MIT"
 *     )
 * )
 */
class ApiController extends Controller
{

     /**
     * @OA\Post(
     *     path="products/api/add",
     *     summary="Add a new product via API",
     *     description="Add a new product using the provided parameters",
     *     operationId="add",
     *     tags={"Products"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product details",
     *         @OA\JsonContent(
     *             required={"name", "type", "purchase_price", "sale_price", "sku", "upc", "ean", "weight", "brand", "qty", "isbn", "unit", "returnable", "length", "width", "height", "image"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="type", type="string"),
     *             @OA\Property(property="purchase_price", type="number"),
     *             @OA\Property(property="sale_price", type="number"),
     *             @OA\Property(property="sku", type="string"),
     *             @OA\Property(property="upc", type="string"),
     *             @OA\Property(property="ean", type="string"),
     *             @OA\Property(property="weight", type="number"),
     *             @OA\Property(property="brand", type="string"),
     *             @OA\Property(property="qty", type="integer"),
     *             @OA\Property(property="isbn", type="string"),
     *             @OA\Property(property="unit", type="string"),
     *             @OA\Property(property="returnable", type="boolean"),
     *             @OA\Property(property="length", type="number"),
     *             @OA\Property(property="width", type="number"),
     *             @OA\Property(property="height", type="number"),
     *             @OA\Property(property="image", type="string", format="byte", description="Base64-encoded image"),
     *             @OA\Property(property="description", type="string"),
     *         ),
     *     ),
     *     @OA\Response(response="200", description="Product added successfully"),
     *     @OA\Response(response="422", description="Validation error"),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     */
    public function addapiproducts(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string',
            'type' => 'required|string',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'sku' => 'required|string',
            'manufacturer' => 'nullable|string',
            'upc' => 'required|string',
            'ean' => 'required|string',
            'weight' => 'required|numeric',
            'brand' => 'required|string',
            'qty' => 'required|integer',
            'isbn' => 'required|string',
            'unit' => 'required|string',
            'returnable' => 'required',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'image' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            Log::error('Validation failed For adding product in api: ' . json_encode($validator->errors()));
            $errors = $validator->errors()->all();
            return response()->json(['errors' => $errors], 422);
        }
        try {
            $imgname = $this->saveImage($request->input('image'));
    
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
                'image' => $imgname,
                'description' => $request->input('description'),
            ];
    
    
            $productId = DB::table('products')->insert($data);
    
            if ($productId) {
                return response()->json(['success' => 'Product added successfully'], 200);
            } else {
                $this->logProductAdditionFailure($data);
                return response()->json(['success' => false, 'message' => 'Product addition failed.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /**
     * @param string $imageData
     * @return string
     * @throws \Exception
     */
    private function saveImage($imageData)
    {
        try {
            $decodedImage = base64_decode(explode(',', $imageData)[1]); 
            $imageName = 'image_' . time() . '.png';
            $imgname = "products/" . $imageName;
            if (file_put_contents(storage_path('app/public/products/' . $imageName), $decodedImage)) {
                return $imgname;
            } else {
                throw new \Exception('Unable to save the image');
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500); 
        }
        
    }
     /**
     * @param array $data
     */
    private function logProductAdditionFailure($data)
    {
        \Log::channel('mylog')->info('Product addition failed using Api. Data: ' . json_encode($data) . ' User ID: ' . 1 . ' User IP: ' . request()->ip());
    }
    /**
 * @OA\Get(
 *     path="products/api/view",
 *     summary="View all products via API",
 *     description="Get a list of all products",
 *     operationId="view",
 *     tags={"Products"},
 *     @OA\Response(response="200", description="List of products"),
 *     @OA\Response(response="500", description="Internal server error"),
 * )
 */
    public function getApiProducts()
    {
        $baseURL = 'http://localhost/inventory/storage/app/public';
        $products = DB::table('products')->where('is_deleted', 0)->orderBy('created_at', 'desc')->get();
        $products->map(function ($product) use ($baseURL) {
            $product->image = $baseURL . '/' . $product->image;
            return $product;
        });
        return response()->json($products);
    }

    /**
 * @OA\get(
 *     path="products/api/delete",
 *     summary="Delete a product via API",
 *     description="Delete a product using the provided ID",
 *     operationId="delete",
 *     tags={"Products"},
 *     @OA\Parameter(
 *         name="id",
 *         in="query",
 *         description="ID of the product to be deleted",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="Product deleted successfully"),
 *     @OA\Response(response="404", description="Product with that ID not found"),
 *     @OA\Response(response="500", description="Internal server error"),
 * )
 */
    public function deleteapiproducts($id)
    {
        $product = DB::table('products')->find($id);
        if ($product) {
            DB::table('products')->where('id', $id)->update(['is_deleted' => 1]);
            return response()->json(['success' => 'Product deleted successfully'], 200);
        } else {
            return response()->json(['error' => 'Product with that ID not found'], 404);
        }
    }

    public function showeapiproducts($id)
    {
        $product = DB::table('products')->find($id);
        if ($product) {
            $baseURL = 'http://localhost/inventory/storage/app/public';
            $product->image = $baseURL . '/' . $product->image;
            return response()->json(['show' => $product], 200);
        } else {
            return response()->json(['error' => 'Product with that ID not found'], 404);
        }
    }

/**
 * @OA\put(
 *     path="products/api/update",
 *     summary="Update a product via API",
 *     description="Update a product using the provided parameters",
 *     operationId="update",
 *     tags={"Products"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Product details to be updated",
 *         @OA\JsonContent(
 *             required={"productid", "name", "type", "purchase_price", "sale_price", "sku", "upc", "ean", "weight", "brand", "qty", "isbn", "unit", "returnable", "length", "width", "height", "image", "description"},
 *             @OA\Property(property="productid", type="integer"),
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="type", type="string"),
 *             @OA\Property(property="purchase_price", type="number"),
 *             @OA\Property(property="sale_price", type="number"),
 *             @OA\Property(property="sku", type="string"),
 *             @OA\Property(property="upc", type="string"),
 *             @OA\Property(property="ean", type="string"),
 *             @OA\Property(property="weight", type="number"),
 *             @OA\Property(property="brand", type="string"),
 *             @OA\Property(property="qty", type="integer"),
 *             @OA\Property(property="isbn", type="string"),
 *             @OA\Property(property="unit", type="string"),
 *             @OA\Property(property="returnable", type="boolean"),
 *             @OA\Property(property="length", type="number"),
 *             @OA\Property(property="width", type="number"),
 *             @OA\Property(property="height", type="number"),
 *             @OA\Property(property="image", type="string", format="byte", description="Base64-encoded image"),
 *             @OA\Property(property="description", type="string"),
 *         ),
 *     ),
 *     @OA\Response(response="200", description="Product updated successfully"),
 *     @OA\Response(response="404", description="Product with that ID not found"),
 *     @OA\Response(response="422", description="Validation error"),
 *     @OA\Response(response="500", description="Internal server error"),
 * )
 */

    public function updateeapiproducts(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string',
            'type' => 'required|string',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'sku' => 'required|string',
            'manufacturer' => 'nullable|string',
            'upc' => 'required|string',
            'ean' => 'required|string',
            'weight' => 'required|numeric',
            'brand' => 'required|string',
            'qty' => 'required|integer',
            'isbn' => 'required|string',
            'unit' => 'required|string',
            'returnable' => 'required',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
        ]);
        
        if ($validator->fails()) {
            Log::error('Validation failed For adding product in api: ' . json_encode($validator->errors()));
            $errors = $validator->errors()->all();
            return response()->json(['errors' => $errors], 422);
        }
        $data = [];
        $id=$request->input('productid');
        if ($request->has('image')) {
            $imgname = $this->saveImage($request->input('image'));
            $data['image'] = $imgname;
        }
        try {
            $data += [
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

            $result = DB::table('products')->where('id', $id)->update($data);
            if ($result !== 1) {
                return response()->json(['success' => 'Product Updation Successfull'], 200);
            } else {
                $this->logProductUpdationFailure($data);
                return response()->json(['error' => 'Product Updation Failed'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}
