<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="semibox" data-sidebar-visibility="show"
    data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | Velzon - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico') }}">
    @include('layouts.head-css')
    <!-- trix editor -->
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    <style>
        trix-editor {
            background-color: white;
            /* Mengatur background editor */
            color: black;
            /* Mengatur warna teks */
            border: 1px solid #ccc;
            /* Mengatur border */
            padding: 10px;
            /* Mengatur padding */
            width: 100%;
            /* Mengatur lebar editor mengikuti container */
            min-height: 200px;
            /* Mengatur tinggi minimum editor */
            box-sizing: border-box;
            /* Termasuk padding dan border dalam perhitungan width */
            overflow: visible;
            /* Menampilkan konten yang melebihi batas */
        }

        .trix-editor [data-trix-attachment],
        .trix-editor figcaption {
            background-color: white;
            /* Mengatur background untuk elemen attachment */
        }

        .trix-content {
            background-color: white !important;
            /* Mengatur background konten editor */
            color: black !important;
            /* Mengatur warna teks konten */
            overflow: visible !important;
            /* Menampilkan konten yang melebihi batas */
        }

        trix-toolbar [data-trix-button-group="file-tools"] {
            display: none;
        }

        .button-container {
            gap: 10px;
            /* Jarak antara tombol */
        }

        .btn-custom {
            flex: 1;
            /* Agar kedua tombol memiliki lebar yang sama */
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            /* Memastikan teks berada di tengah */
            width: 100%;
            /* Pastikan tombol mengambil seluruh lebar yang tersedia */
            max-width: 200px;
            /* Atur batas lebar tombol jika diperlukan */
        }

        /* Media query untuk merubah flex direction di layar lebih besar dari mobile */
        @media (min-width: 768px) {
            .button-container {
                flex-direction: row;
                /* Flex direction berubah jadi row di layar yang lebih besar */
            }
        }
    </style>
</head>

@section('body')
    @include('layouts.body')
@show
<!-- Begin page -->
<div id="layout-wrapper">
    @include('layouts.topbar')
    @include('layouts.sidebar')
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @yield('content')
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
        @include('layouts.footer')
    </div>
    <!-- end main content-->
</div>
<!-- END layout-wrapper -->

@include('layouts.customizer')

<!-- JAVASCRIPT -->
@include('layouts.vendor-scripts')
<script>
    function confirmLogout(event) {
        event.preventDefault(); // Mencegah link melakukan aksi default

        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, logout!',
            cancelButtonText: 'No, stay logged in'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit(); // Melakukan logout jika dikonfirmasi
            }
        });
    }
</script>
</body>

</html>
