@extends('layouts.sidenav-layout')
@section('content')

<div class="card">
    <div class="card-body">

        @if($products->isNotEmpty())

        <div class="row">

            @foreach($products as $product)

            <div class="col-md-3 mb-4">

                <div class="card">
                    <img src="{{ $product->product_image_url }}" alt="{{ $product->name }}" class="card-img-top" height="150">

                    <div class="card-body">

                        <h4 class="card-title">{{ $product->name }}</h4>
                        <h3>{{ $product->price }}</h3>
                        <h3>{{ $product->descriptions }}</h3>

                        <button type="button" onclick="orderNow('{{ $product->id }}')">Order Now</button>
                    </div>

                </div>

            </div>


            @endforeach

        </div>



        @else

        <div>No Product found</div>

        @endif

    </div>
</div>


@push('script')
<script>
    function orderNow(productID) {
        if (confirm('Are you confirm?')) {
            axios.post('/backend/products/customer/order', {
                    product_id: productID
                }, {
                    withCredentials: true
                })
                .then(res => {
                    console.log(res.data);
                })
                .catch(err => {
                    console.error(err.response?.data || err.message);
                });

            if (res.status === 200) {
                successToast(res.data.message);
            } else {
                errorToast(res.data.message);
            }

        }
    }
</script>
@endpush

@endsection