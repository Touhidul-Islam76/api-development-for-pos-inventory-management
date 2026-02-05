<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {

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


    public function show(Product $product)
    {

        if ($product) {
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


    public function update(Product $product, Request $req)
    {

        if (! $product) {
            return response()->json([
                'error'   => true,
                'message' => 'Product not found',
            ], 404);
        }

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
                'error'   => true,
                'message' => 'Validation failed',
                'details' => $validator->errors()->all(),
            ], 422);
        }

        $productData = $req->except('image');

        if ($req->hasFile('image')) {

            $image     = $req->file('image');
            $extension = $image->getClientOriginalExtension();
            $date      = now()->format('Y-m-d');
            $imageName = $date . '_product_image.' . $extension;

            $path = $image->storeAs(
                'product_image',
                $imageName,
                'public'
            );

            $productData['image'] = $path;
        }

        $product->update($productData);

        // 

        return redirect()->route('admin.products.edit', $product->id)->with('success', 'Product updated successfully.');
    }

    public function edit(Product $product)
    {

        $categories = Category::all();

        return view('pages.dashboard.admin.products.edit', compact('product', 'categories'));
    }


    public function productList()
    {
        $products = Product::all();
        return view('pages.dashboard.customer.products.list', compact('products'));
    }


    public function customerOrder(Request $request)
    {
        // dd(Auth::user());
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'details' => $validator->errors()->all(),
            ], 422);
        }

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'error' => true,
                'message' => 'Unauthenticated user',
            ], 401);
        }

        $product = Product::find($request->product_id);

        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total' => $product->price,
        ]);

        $orderDetail = OrderDetail::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Order placed successfully',
            'order' => $order,
            'order_detail' => $orderDetail,
        ], 201);
    }

    public function confirmedOrder()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        return view('pages.dashboard.customer.confirmedOrder', compact('orders'));
    }

    public function adminApprovedOrders()
    {
        // $user = Auth::user();

        // if (!$user) {
        //     abort(401, 'Unauthenticated user');
        // }

        $orders = Order::orderBy('created_at', 'desc')->get();

        // print '<pre>';
        // print_r($orders);
        // print '<pre>';

        return view('pages.dashboard.customer.approvedOrder', compact('orders'));
    }
}
