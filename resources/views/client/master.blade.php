<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pembelian Toko Online</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .store-card,
        .product-card,
        .fabric-card {
            cursor: pointer;
            transition: all 0.3s;
        }

        .store-card:hover,
        .product-card:hover,
        .fabric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .store-card.selected {
            border: 3px solid #198754;
        }

        .product-card,
        .fabric-card {
            overflow: hidden;
        }

        .product-card:hover,
        .fabric-card:hover {
            border-color: #198754;
        }

        .product-card input:checked+label,
        .fabric-card input:checked+label {
            color: #198754;
        }

        .form-check-input:checked {
            background-color: #198754;
            border-color: #198754;
        }

        .form-check {
            padding-left: 0;
            text-align: center;
        }

        .form-check-input {
            float: none;
            margin-right: 5px;
        }

        .form-check-label {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <h1 class="text-center mb-5">Sistem Pembelian Toko Online</h1>
        @yield('content')






    </div>

    <!-- Bootstrap JS & Bootstrap Icons -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>


    @stack('scripts')

</body>

</html>
