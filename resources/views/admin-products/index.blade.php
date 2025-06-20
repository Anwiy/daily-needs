@extends ('admin-sidebar.index')

@section('container')

    <div class="content">
        <h2>Product List</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div>
            <a href="/admin/productlist/addnewproduct" class="" 
               style="position: fixed; bottom: 20px; right: 20px; z-index: 1000; font-size: 40px;">
               <i class="bi bi-plus-circle-fill"></i>
            </a>
        </div>

        <ul class="nav-link">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="categoryDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Category
                </a>
                <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
                    <li><a class="dropdown-item" href="/admin/productlist">All</a></li>
                    @foreach ($categories as $category)
                        <li><a class="dropdown-item" href="{{ url('admin/productlist/?category=' . $category->category_slug) }}">{{ $category->category_name }}</a></li>
                    @endforeach
                </ul>
            </li>
            
            <li>
                <div class="row">
                    <div class="col-md-20">
                        <form action="/admin/productlist" method="get">
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Type here to search" name="search" value="{{ request('search') }}">
                                <button class="btn btn-danger" type="submit">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </li>
        </ul>

        @if ($products->count())
            @if (request('category'))
                <h2 class="category-title">
                    Category: {{ $categories->firstWhere('category_slug', request('category'))->category_name }}
                </h2>
            @endif
    
            <div class="products-container">
                @foreach ($products as $product)
                    <div class="products-cover">
                        <div class="products-card">
                            <img src="{{ asset('storage/product_photos/' . $product->product_image) }}" class="card-img-top image-per-product" alt="{{ $product->product_name }}">
                            <div class="card-body info-row">
                                <div class="product-text">
                                    <h3 class="products-card-name">{{ $product->product_name }}</h3>
                                    <p class="products-card-price">Rp{{ number_format($product->product_price, 2, ',', '.') }}</p>
                                </div>
                                <a href="{{ url('/admin/productlist/' . $product->product_slug) }}">
                                    <i class="bi bi-pencil-square"></i>
                                </a>  
                            </div> 
                        </div>
                    </div>
                @endforeach
            </div>
    
        @else
            <p>No Products found.</p>
        @endif
    </div>

@endsection