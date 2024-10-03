<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/styles.css" />
    <style>
        .btn2 {
            background-color: rgb(161, 8, 14); /* Warna latar belakang default */
            color: white; /* Warna teks default */
            transition: background-color 0.3s, color 0.3s; /* Transisi halus untuk perubahan warna */
        }

        .explore__card:hover .btn2 {
            background-color: #fea82b; /* Warna latar belakang saat hover */
            color: white; /* Warna teks saat hover */
        }
    </style>

    <title>Tech Radar | <?php echo e($company->name); ?></title>
</head>

<body>
    
    <a href="#home" style="position: fixed;right:20px;bottom:35px;background:#fea82b;padding-block:5px;padding-inline:10px;border-radius:5px;z-index:9999;">
        <i class="ri-arrow-up-line" style="font-size:30px;color:white;"></i>
    </a>
    <nav id="navbar">
        <div class="nav__header">
            <div class="nav__logo">
                <a href="#" class="logo">Tech<span>Radar</span></a>
            </div>
            <div class="nav__menu__btn" id="menu-btn">
                <i class="ri-menu-line"></i>
            </div>
        </div>
        <ul class="nav__links" id="nav-links">
            <li><a href="/">Halaman Utama</a></li>
            <?php if(Auth::check()): ?>
            <li>
                <form action="/logout" method="POST" class="btn" style="display: flex; align-items:center; gap:10px;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" style="background: transparent; border: none; color: white; cursor: pointer;">
                        Logout
                    </button>
                </form>
            </li> 
        <?php else: ?>
            <li>
                <a href="/loginAccount" class="btn" style="display: flex;align-items:center;gap:10px;">
                    <img src="/assets/google.png" style="width: 20px;">
                    <span style="color: white">Login</span>
                </a>
            </li>              
        <?php endif; ?>
        </ul>
    </nav>


    <section class="section__container" style="margin-bottom: 120px;">
        <h2 class="section__header">Perusahaan <span><?php echo e($company->name); ?></span></h2>
        <div class="section__description__company">
            <?php echo $company->description; ?>

        </div>
        <div class="row justify-content-between align-items-center" style="display: flex;align-items:center;margin-top:100px;">
            <!-- Form Pencarian -->
            <form action="" method="GET" class="col-md-5 mb-3 mb-md-0">
                <div class="input-group">
                    <input type="text" class="form-control" value="<?php echo e(request('search')); ?>" placeholder="Cari nama atau deskripsi..." name="search">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </form>
        
            <!-- Form Filter -->
            <form action="" method="GET" class="col-md-3">
                <select class="form-select custom-select" name="filter" aria-label="Pilih urutan" style="padding: 1rem 2rem;border-radius: 5px;" onchange="this.form.submit()">
                    <option value="" disabled selected>Urutkan berdasarkan</option>
                    <option value="terbaru" <?php echo e(request('filter') == 'terbaru' ? 'selected' : ''); ?>>Terbaru</option>
                    <option value="terlama" <?php echo e(request('filter') == 'terlama' ? 'selected' : ''); ?>>Terlama</option>
                    <option value="az" <?php echo e(request('filter') == 'az' ? 'selected' : ''); ?>>A-Z</option>
                    <option value="za" <?php echo e(request('filter') == 'za' ? 'selected' : ''); ?>>Z-A</option>
                </select>
            </form>
        </div>
        
        <div class="job__grid row justify-content-center">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-12 col-lg-6 col-xl-4 mt-3"> <!-- Tempatkan class grid di sini -->
                    <div class="explore__card">
                        <span><i class="ri-layout-fill"></i></span>
                        <h4><?php echo e($category->name); ?></h4>
                        <?php
                        $totalTechnologies = $category->technologies->count();
                        ?>
                        <p><?php echo e($totalTechnologies); ?> teknologi</p>
                        <a href="https://viz.tech-radar.gci.my.id/?documentId=https://viz.tech-radar.gci.my.id/files/<?php echo e(strtoupper($category->name)); ?> - <?php echo e($company->name); ?>.json" class="btn2 d-block text-center">Lihat Radar</a>
                    </div>
                    
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <div class="d-flex justify-content-end mt-5">
                <div class="col-sm-6">
                    <div class="pagination-block pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
                        <?php if($categories->onFirstPage()): ?>
                            <div class="page-item disabled" style="margin-right: 10px;">
                                <span class="page-link" style="background-color: #fea82b; color: rgb(161, 8, 14); border-color: rgb(161, 8, 14);">Sebelumnya</span>
                            </div>
                        <?php else: ?>
                            <div class="page-item" style="margin-right: 10px;">
                                <a href="<?php echo e($categories->appends(request()->all())->previousPageUrl()); ?>" class="page-link" id="page-prev" style="background-color: #fea82b; color: rgb(161, 8, 14); border-color: rgb(161, 8, 14);">Sebelumnya</a>
                            </div>
                        <?php endif; ?>
                    
                        <!-- Page Numbers -->
                        <span id="page-num" class="pagination">
                            <?php $__currentLoopData = $categories->links()->elements[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($page == $categories->currentPage()): ?>
                                    <span class="page-item active" style="margin-right: 10px;">
                                        <span class="page-link" style="background-color: rgb(161, 8, 14); color: #fff; border-color: rgb(161, 8, 14);"><?php echo e($page); ?></span>
                                    </span>
                                <?php else: ?>
                                    <a href="<?php echo e($categories->appends(request()->all())->url($page)); ?>" class="page-item" style="margin-right: 10px;">
                                        <span class="page-link" style="background-color: #fea82b; color: rgb(161, 8, 14); border-color: rgb(161, 8, 14);"><?php echo e($page); ?></span>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </span>
                    
                        <?php if($categories->hasMorePages()): ?>
                            <div class="page-item">
                                <a href="<?php echo e($categories->appends(request()->all())->nextPageUrl()); ?>" class="page-link" id="page-next" style="background-color: #fea82b; color: rgb(161, 8, 14); border-color: rgb(161, 8, 14);">Berikutnya</a>
                            </div>
                        <?php else: ?>
                            <div class="page-item disabled">
                                <span class="page-link" style="background-color: #fea82b; color: rgb(161, 8, 14); border-color: rgb(161, 8, 14);">Berikutnya</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    

                </div>
            </div>

        </div>
    </section>

    <footer class="footer" style="background: rgb(161, 8, 14);" style="">
        <div class="section__container footer__container">
            <div class="footer__col">
                <div class="footer__logo">
                    <a href="#" class="logo text-white">Tech<span>Radar</span></a>

                </div>
                <p class="text-white">
                    Tech Radar dirancang untuk membantu Anda menjelajahi dan memvisualisasikan tren teknologi, sehingga memungkinkan pengambilan keputusan yang tepat untuk proyek Anda.
                </p>
                
            </div>
            <div class="footer__col">
                <h4 class="text-white">Quick Links</h4>
                <ul class="footer__links">
                    <li><a href="#navbar" class="text-white">Home</a></li>
                    <li><a href="#about" class="text-white">About</a></li>
                    <li><a href="#categories" class="text-white">Categories</a></li>
                    <li><a href="#job" class="text-white">Companies</a></li>
                    <li><a href="#service" class="text-white">Services</a></li>
                    <li><a href="#client" class="text-white">Teams</a></li>
                </ul>
            </div>
            <div class="footer__col">
                <h4 class="text-white">Follow Us</h4>
                <ul class="footer__links">
                    <li><a href="#" class="text-white">Facebook</a></li>
                    <li><a href="#" class="text-white">Instagram</a></li>
                    <li><a href="#" class="text-white">LinkedIn</a></li>
                    <li><a href="#" class="text-white">Twitter</a></li>
                    <li><a href="#" class="text-white">Youtube</a></li>
                </ul>
            </div>
            <div class="footer__col">
                <h4 class="text-white">Contact Us</h4>
                <ul class="footer__links">
                    <li>
                        <a href="#" class="text-white">
                            <span><i class="ri-phone-fill"></i></span> +91 234 56788
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-white">
                            <span><i class="ri-map-pin-2-fill"></i></span> 123 Panam,
                            Riau, Indonesia
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="footer__bar text-white">
            Copyright © 2024 Tech Radar Project. All rights reserved.
        </div>
    </footer>

    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
    <script src="/js/main.js"></script>
</body>

</html>
<?php /**PATH E:\magang\test ui adminDashboard\Laravel\corporate\resources\views/company.blade.php ENDPATH**/ ?>