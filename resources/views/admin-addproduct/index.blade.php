@extends ('admin-sidebar.index')

@section('container')
    <div class="content">
        <div class="header-container">
            <i class="bi bi-arrow-left-circle" onclick="window.location.href='/admin/productlist';"></i>
            <h2>Add New Product</h2>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="/admin/productlist/addnewproduct" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-container">
                <div class="upper-container">
                    <div class="image-container">
                        <label for="product_image" class="image-label">
                        <div class="image-content">
                            <img id="image-preview" src="" alt="Product Image" style="display: none;">
                            <div class="image-placeholder">
                                <i class="bi bi-plus-lg"></i>
                                <p>Add Image</p>
                            </div>
                        </div>
                        <input type="file" name="product_image" id="product_image" class="form-control @error('product_image') is-invalid @enderror">
                        </label>
                        @error('product_image')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="text-container">
                        <div class="form-group">
                            <label>Category Name</label>
                            <select name="category_id" class="form-control @error('category_id') is-invalid @enderror select-add-product">
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->category_id }}" 
                                        {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Brand Name</label>
                            <select name="brand_id" class="form-control @error('brand_id') is-invalid @enderror select-add-product">
                                <option value="">Select Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->brand_id }}" 
                                        {{ old('brand_id') == $brand->brand_id ? 'selected' : '' }}>
                                        {{ $brand->brand_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" name="product_name" class="form-control @error('product_name') is-invalid @enderror" value="{{ old('product_name') }}">
                            @error('product_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        
                    </div>
                </div>

                <hr class="separator-line">

                <h3>Detail</h3>

                <div class="detail-container">
                    <div class="form-group">
                        <label>Stock</label>
                        <div class="stock-input">
                            <button class="btn btn-outline-secondary" id="decrease-btn" type="button" onclick="decreaseQuantity()">-</button>
                            <input type="number" name="product_stock" class="form-control @error('product_stock') is-invalid @enderror" value="{{ old('product_stock') }}">
                            <button class="btn btn-outline-secondary" id="increase-btn" type="button" onclick="increaseQuantity()">+</button>
                        </div>
                        @error('product_stock')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Product Price</label>
                        <input type="number" name="product_price" class="form-control @error('product_price') is-invalid @enderror" value="{{ old('product_price') }}">
                        @error('product_price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
            
                    <div class="form-group">
                        <label>Product Description</label>
                        <input type="text" name="product_description" class="form-control @error('product_description') is-invalid @enderror" value="{{ old('product_description') }}">
                        @error('product_description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    
                </div>
            </div>
            <button type="submit" class="btn btn-success">Add</button>
        </form>
    </div>
    
    
@endsection