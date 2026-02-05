@extends('layouts.app')
@section('content')
<div class="container my-4">
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-header bg-gradient bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">📦 All Orders</h5>
                <span class="badge bg-light text-primary fs-6">
                    Total: {{ count($orders) }}
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>#Order</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th>Action</th>
                            <th>Invoice</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($orders as $order)
                        <tr class="shadow-sm">
                            <td>
                                <span class="badge rounded-pill bg-dark px-3 py-2">
                                    #{{ $order->id }}
                                </span>
                            </td>

                            <td class="fw-semibold text-primary">
                                {{ $order->user->name }}
                            </td>

                            <td>
                                @if($order->status == 'pending')
                                <span class="badge rounded-pill bg-warning text-dark px-3 py-2">
                                    Pending
                                </span>
                                @else
                                <span class="badge rounded-pill bg-success px-3 py-2">
                                    Confirmed
                                </span>
                                @endif
                            </td>

                            <td class="fw-bold text-success">
                                ৳ {{ $order->total }}
                            </td>

                            <td>
                                <span class="badge bg-info-subtle text-info px-3 py-2">
                                    {{ $order->created_at->format('d M Y') }}
                                </span>
                            </td>

                            <td>
                                @if($order->status == 'pending')
                                <button
                                    
                                    class="btn btn-success btn-sm rounded-pill px-3 shadow-sm fw-semibold"
                                    onclick="confirmedOrder(  '{{ $order->id }}', '{{ $order->user->id }}' )">
                                    Confirm
                                </button>

                                @else
                                <span class="badge bg-success-subtle text-success px-3 py-2">
                                    Done
                                </span>
                                @endif
                            </td>

                            <td>
                                @if($order->invoice)
                                <a href="#"
                                    class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm 
                                              fw-semibold">
                                    View
                                </a>
                                @else
                                <span class="badge bg-secondary-subtle text-secondary px-3 py-2">
                                    No Invoice
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection




@push('script')

<script>
     function confirmedOrder(orderID, customerID) {
         
        if (confirm('Are you sure to confirm?')) {

            axios.post('/backend/invoices/store', {
                'order_id': orderID,
                'customer_id': customerID,
            }).then(function(response) {
                console.log(response)
            })

        }
    }
    // window.confirmedOrder = function (){
    //     alert('confirmedOrder');
    // }
</script>

@endpush