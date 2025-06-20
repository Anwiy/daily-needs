<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daily Needs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="/css/navbar.css">  
    <link rel="stylesheet" href="/css/product.css">
    <link rel="stylesheet" href="/css/products.css">
    <link rel="stylesheet" href="/css/cart.css">
    <link rel="stylesheet" href="/css/checkout.css">
    <link rel="stylesheet" href="/css/viewcart.css">
    <link rel="stylesheet" href="/css/footer.css">
</head>
<body>
    <div>
        @include('partials.navbar')
    </div>
    <div>
        @yield('container')
    </div>

    <div>
        @include('footer.index')
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/product.js"></script>
    <script src="/js/cart.js"></script>
    <script src="/js/checkout.js"></script>
    <script src="/js/address.js"></script>
    <script src="/js/paymentmethod.js"></script>
    <script src="/js/insufficientbalance.js"></script>
    <script src="/js/disablebuyandaddtocart.js"></script>
    <script src="/js/footer.js"></script>
    <script src="/js/category-scroll.js"></script>
</body>
</html>