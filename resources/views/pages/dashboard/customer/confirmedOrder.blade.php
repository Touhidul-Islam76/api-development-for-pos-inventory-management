@extends('layouts.app')
@section('content')

<div class="container-fluid py-4 bg-light">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold text-dark mb-0">All Orders</h4>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light text-uppercase small">
                        <tr>
                            <th class="ps-4">#Order ID</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th class="text-center">Invoice</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td class="ps-4 fw-semibold text-primary">
                                {{ $order->id }}
                            </td>

                            <td class="fw-semibold text-dark">
                                {{ $order->user->name }}
                            </td>

                            <td>
                                <span class="badge rounded-pill px-3
                                    {{ $order->status === 'active' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>

                            <td class="fw-semibold text-success">
                                ${{ $order->total }}
                            </td>

                            <td class="text-muted">
                                {{ $order->created_at->format('Y-m-d') }}
                            </td>

                            <td class="text-center">
                                @if($order->invoice)
                                    <a href="#" class="btn btn-sm btn-primary rounded-pill px-3">
                                        Invoice
                                    </a>
                                @else
                                    <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
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
