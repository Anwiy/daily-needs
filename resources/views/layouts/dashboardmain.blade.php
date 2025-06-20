<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Needs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/dashboard.css">
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/topup-balance.css">
    <link rel="stylesheet" href="/css/purchase-history.css">
    <link rel="stylesheet" href="/css/myprofile.css">
    <link rel="stylesheet" href="/css/footer-sidebar.css">
</head>
   
<body>
    <div>
        @include('sidebar.index')
    </div>
    
    <div>
        @yield('container')
    </div>

    <div>
        @include('footer-sidebar.index')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/myprofile.js"></script>
</body>
</html>
