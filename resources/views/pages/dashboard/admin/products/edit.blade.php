@extends('layouts.sidenav-layout')
@section('content')

<div class="card animated fadeIn w-100 p-3">
                <div class="card-body">
                    <h4>Edit Product</h4>
                    <hr/>
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="container-fluid m-0 p-0">
                            <div class="row m-0 p-0">
                                <!-- Product Image Section START -->
                                <div class="col-md-4 p-2 text-center">
                                    <label>Product Image</label>
                                    <div>
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" style="width:120px;height:120px;border-radius:10px;object-fit:cover;border:1px solid #ddd;" />
                                        @else
                                            <img src="{{ asset('assets/images/no-image.png') }}" alt="Product Image" style="width:120px;height:120px;border-radius:10px;object-fit:cover;border:1px solid #ddd;" />
                                        @endif
                                    </div>
                                    <input name="image" type="file" accept="image/*" class="form-control mt-2"/>
                                    @error('image')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <!-- Product Image Section END -->
                                <div class="col-md-4 p-2">
                                    <label>Product Name</label>
                                    <input name="name" value="{{ old('name', $product->name) }}" class="form-control" type="text" required/>
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-4 p-2">
                                    <label>Category</label>
                                    <select name="category_id" class="form-control" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $category->id==$product->category_id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6 p-2">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-3 p-2">
                                    <label>Quantity</label>
                                    <input name="quantity" value="{{ old('quantity', $product->quantity) }}" class="form-control" type="number" min="0" required/>
                                    @error('quantity')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-3 p-2">
                                    <label>Price</label>
                                    <input name="price" value="{{ old('price', $product->price) }}" class="form-control" type="number" min="0" step="0.01" required/>
                                    @error('price')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row m-0 p-0">
                                <div class="col-md-4 p-2">
                                    <button type="submit" class="btn mt-3 w-100 bg-gradient-primary">Update Product</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

@endsection