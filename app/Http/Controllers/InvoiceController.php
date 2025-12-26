<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request){
        $customer_id = $request->customer_id;
        $user_id = $request->user_id;
        $invoice_number = 'INV-'.date('Ymd').'-'.uniqid();
        $invoice_date = $request->invoice_date;
        $notes = $request->notes;

        $gross_total_amount = 0;

        $invoice = Invoice::create([
            'customer_id' => $customer_id,
            'user_id' => $user_id,
            'invoice_number' => $invoice_number,
            'invoice_date' => $invoice_date,
            'notes' => $notes,
            'total_amount' => 0,
            'status' => 'pending',
        ]);

        $products = $request->products; // Expecting an array of products with 'product_id' and 'quantity'
        $quantities = $request->quantity;
        $amounts = $request->amount;

        $total_amount = 0;
        foreach( $products as $key=>$value ){
            $product_id = $products[$key];
            $quantity = $quantities[$key];
            $amount = $amounts[$key];

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

        }
        $invoice->total_amount = $gross_total_amount;
        $invoice->save();


        return response()->json([
            'message' => 'Invoice created successfully',
            'invoice_id' => $invoice->id,
        ], 201);
    }
}
