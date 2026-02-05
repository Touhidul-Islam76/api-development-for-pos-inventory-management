<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request){

    // print '<pre>';
    // print_r($request->all());
    // print '<pre>';
    // echo 'Invoices';
    // exit;

        $order_id = $request->order_id;
        $customer_id = $request->customer_id;
        $user = Auth::user();
        $invoice_number = 'INV-'.date('Ymd').'-'.uniqid();
        $invoice_date = date('Y-m-d H:i:s');
        $notes = '';

        $gross_total_amount = 0;

        $invoice = Invoice::create([
            'order_id' => $order_id,
            'user_id' => 1,
            'customer_id' => $customer_id,
            'invoice_number' => $invoice_number,
            'invoice_date' => $invoice_date,
            'notes' => $notes,
            'total_amount' => 0,
            'status' => 'pending',
        ]);

        // $products = $request->products; // Expecting an array of products with 'product_id' and 'quantity'
        // $quantities = $request->quantity;
        // $amounts = $request->amount;

        $orderDetail = OrderDetail::where('order_id', $order_id)->get();


        $total_amount = 0;
        foreach( $orderDetail as $order ){
            $product_id = $order->product_id;
            $quantity = $order->quantity;
            $amount = $order->price;

            $total_amount = $quantity * $amount;
            $gross_total_amount += $total_amount;

            $invoiceDetails = [
                'invoice_id' => $invoice->id,
                'product_id' => $product_id,
                'quantity' => $quantity,
                'amount' => $amount,
                'total_amount' => $total_amount,
            ];

            InvoiceDetail::create($invoiceDetails);

            $product = Product::find($product_id);
            $product->quantity = $product->quantity - $quantity;
            $product->save();

        }
        $invoice->total_amount = $gross_total_amount;
        $invoice->save();

        $order = Order::find($order_id);
        $order->status = 'confirmed';
        $order->save();


        return response()->json([
            'message' => 'Invoice created successfully',
            'invoice_data' => $invoice,
            'status' => 'success'
        ], 201);
    }
}
