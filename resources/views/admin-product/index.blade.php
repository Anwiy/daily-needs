@extends('admin-sidebar.index')

<link rel="stylesheet" href="/css/admin-product.css">

@section('container')

<div class="content">
    <a href="/admin/productlist"><i class="bi bi-arrow-left-circle"></i></a><h2> Edit Product</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ url('admin/productlist/' . $products->product_slug . '/update') }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="main-container">
            <div class="kiri-container">
                <div class="form-group-kiri">
                    <label>Category Name: </label>
                    <select name="category_id" class="form-control1 @error('category_id') is-invalid @enderror">
                        @foreach ($categories as $category)
                            <option value="{{ $category->category_id }}" 
                                {{ $products->category_id == $category->category_id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group-kiri" id="productimg">
                    @if($products->product_image)
                        <img id="preview" src="{{ asset('storage/product_photos/' . $products->product_image) }}" alt="Product Image">
                    @else
                        <img id="preview" src="#" alt="Product Image Preview" style="display: none;" width="100" />
                    @endif

                    <label for="inputimg" class="btn btn-success btn-sm">Edit Picture</label>
                    <input type="file" name="product_image" id="inputimg" onchange="previewImage(event)" style="display: none;">
                </div>

            </div>
               
            <div class="kanan-container">
                <div class="form-group-kanan">
                    <label>Product Name</label>
                    <div class="input-with-icon">
                        <i class="bi bi-pen edit-icon" onclick="enableEdit('product_name')"></i>
                        <input type="text" id="product_name" name="product_name" class="form-control1 @error('product_name') is-invalid @enderror" value="{{ old('product_name', $products->product_name) }}" readonly>
                    </div>
                    <hr>
                    @error('product_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
        
                <div class="form-group-kanan">
                    <label>Product Price</label>
                    <div class="input-with-icon">
                        <input type="number" id="product_price" name="product_price" class="form-control1 @error('product_price') is-invalid @enderror" value="{{ old('product_price', $products->product_price) }}" step="1" min="1" readonly>
                        <i class="bi bi-pen edit-icon" onclick="enableEdit('product_price')"></i>
                    </div>
                    <hr>
                    @error('product_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
        
                <div class="form-group-kanan">
                    <label>Brand Name: </label>
                    <select name="brand_id" class="form-control1 @error('brand_id') is-invalid @enderror">
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->brand_id }}" 
                                {{ $products->brand_id == $brand->brand_id ? 'selected' : '' }}>
                                {{ $brand->brand_name }}
                            </option>
                        @endforeach
                    </select>
                    <hr>
                    @error('brand_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>            
                        
                <div class="form-group-kanan">
                    <div id="prodesc">
                        <label>Product Description</label>
                        <i class="bi bi-pen edit-icon" onclick="enableEdit('product_description')"></i>
                    </div>
                    <div class="input-with-icon textarea-icon">
                        <textarea name="product_description" id="product_description" class="form-control1" readonly>{{ old('product_description', $products->product_description) }}</textarea>
                    </div>
                    <hr>
                    @error('product_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-kanan">
                    <!-- <label>Stock:</label> -->
                    <div class="input-group">
                        <div class="input-group-kiri">
                            <span>Stock: {{ $products->product_stock }}</span>
                        </div>
                        <div class="input-group-kanan">
                            <button class="btn btn-outline-secondary" id="decrease-btn" type="button" onclick="decreaseQuantity()">-</button>
                            <input type="number" name="product_stock" id="product_stock" class="form-control1 text-center" value="{{ old('product_stock', $products->product_stock) }}">
                            <button class="btn btn-outline-secondary" id="increase-btn" type="button" onclick="increaseQuantity()">+</button>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success">Save Changes</button>  
            </div>    
        </div>
    </form> 
</div>

<script src="/js/admin-product.js"></script>

@endsection
