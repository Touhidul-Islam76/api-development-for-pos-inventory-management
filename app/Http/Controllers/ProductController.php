<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index() {

        $products = Product::all();

    // if($products){
    //     return response()->json([
    //         'error' => false,
    //         'products' => $products,
    //          'status' => 'success'
    //     ], 200);
    // }else{
    //     return response()->json([
    //         'error' => true,
    //         'message' => 'No products found',
    //         'status' => 'error'
    //     ], 404);
    // }
    return view('pages.dashboard.admin.products.list', compact('products'));
}


    public function store(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'category_id' => 'required',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'details' => $validator->errors()->all(),
            ], 422);
        }

        $productData = $req->except('image');

        if ($req->hasFile('image')) {

            $image = $req->file('image');

            // extension (jpg, png etc)
            $extension = $image->getClientOriginalExtension();

            // date
            $date = now()->format('Y-m-d');

            // final image name
            $imageName = $date . '_product_image.' . $extension;

            // store image
            $path = $image->storeAs(
                'product_image',   // storage/app/public/product_image
                $imageName,
                'public'
            );

            // saving image path to database
            $productData['image'] = $path;
        }

        $product = Product::create($productData);
        return response()->json([
            'error' => false,
            'message' => 'Product created successfully',
            'product' => $product,
        ], 201);
    }


    public function show(Product $product) {

        if($product) {
            return response()->json([
                'error' => false,
                'product' => $product,
            ], 200);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Product not found',
            ], 404);
        }

    }


    public function update( Product $product, Request $req ) {

        if ( ! $product ) {
            return response()->json( [
                'error'   => true,
                'message' => 'Product not found',
            ], 404 );
        }

        $validator = Validator::make( $req->all(), [
            'category_id' => 'required',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ] );

        if ( $validator->fails() ) {
            return response()->json( [
                'error'   => true,
                'message' => 'Validation failed',
                'details' => $validator->errors()->all(),
            ], 422 );
        }

        $productData = $req->except( 'image' );

        if ( $req->hasFile( 'image' ) ) {

            $image     = $req->file( 'image' );
            $extension = $image->getClientOriginalExtension();
            $date      = now()->format( 'Y-m-d' );
            $imageName = $date . '_product_image.' . $extension;

            $path = $image->storeAs(
                'product_image',
                $imageName,
                'public'
            );

            $productData['image'] = $path;
        }

        $product->update( $productData );

        return response()->json( [
            'error'   => false,
            'message' => 'Product updated successfully',
            'product' => $product,
        ], 200 );

    }

    public function edit( Product $product ){

        $categories = Category::all();

        return view('pages.dashboard.admin.products.edit', compact('product','categories'));

    }

}
