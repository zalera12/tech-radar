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


    <title>Tech Radar | Home</title>
</head>

<body>
    <a href="#navbar" style="position: fixed;right:20px;bottom:35px;background:#fea82b;padding-block:5px;padding-inline:10px;border-radius:5px;z-index:9999;">
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
            <li>
                <a href="/loginAccount" class="btn" style="display: flex;align-items:center;gap:10px;">
                    <img src="/assets/google.png" style="width: 20px;">
                    <span style="color: white">Sign in</span>
                </a>
            </li>
        </ul>
    </nav>


    <section class="section__container" style="margin-bottom: 120px;">
        <h2 class="section__header">Semua <span>Perusahaan Mitra Kami</span></h2>
        <p class="section__description">
            Jelajahi semua perusahaan terkemuka yang bermitra dengan kami dalam memantau dan mengelola teknologi serta
            inovasi mereka.
        </p>
        <div class="row justify-content-between align-items-center"
            style="display: flex;align-items:center;margin-top:100px;">
            <!-- Form Pencarian -->
            <form action="" class="col-md-5 mb-3 mb-md-0">
                <div class="input-group">
                    <input type="text" class="form-control" value="{{ request('search') }}"
                        placeholder="Cari nama atau deskripsi..." name="search">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </form>

            <!-- Form Filter -->
            <form action="" class="col-md-3">
                <select class="form-select custom-select" name="filter" aria-label="Pilih urutan"
                    style="padding: 1rem 2rem;border-radius: 5px;" onchange="this.form.submit()">
                    <option value="" disabled selected>Urutkan berdasarkan</option>
                    <option value="terbaru" {{ request('filter') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                    <option value="terlama" {{ request('filter') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                    <option value="az" {{ request('filter') == 'az' ? 'selected' : '' }}>A-Z</option>
                    <option value="za" {{ request('filter') == 'za' ? 'selected' : '' }}>Z-A</option>
                </select>
            </form>
        </div>
        <div class="job__grid row justify-content-center">
            @foreach ($companies as $company)
                <div class="col-12 col-lg-6 col-xl-4"> <!-- Tempatkan class grid di sini -->
                    <div class="job__card" style="margin-top: 1rem;">
                        <div class="job__card__header">
                            <img src="{{ asset($company->image ? '/storage/' . $company->image : '/build/images/users/multi-user.jpg') }}"
                                style="width: 50px;height:50px;">

                            <div>
                                <h5>{{ $company->name }}</h5>
                                <?php
                                $dataCompany = App\Models\company_users::where('company_id', $company->id)->get();
                                $roleId = App\Models\Role::where('name', 'OWNER')->first()->id;
                                $dataId;
                                foreach ($dataCompany as $data) {
                                    if ($data->role_id == $roleId) {
                                        $dataId = $data->user_id;
                                        break;
                                    }
                                }
                                $name = App\Models\User::where('id', $dataId)->first()->name;
                                ?>
                                <h6>{{ $name }}</h6>
                            </div>
                        </div>
                        <p style="margin-top: 20px;height:70px;">
                            {{ Str::limit(strip_tags(str_replace('&nbsp;', ' ', $company->description)), 135) }}
                        </p>

                        <div class="job__card__footer" style="margin-top: 35px;">
                            <?php
                            $totalMembers = $company->users->count();
                            $totalCategories = $company->categories->count();
                            $totalTechnologies = $company->technologies->count();
                            ?>
                            <span>{{ $totalMembers }} Orang</span>
                            <span>{{ $totalCategories }} Category</span>
                            <span>{{ $totalTechnologies }} Technology</span>
                        </div>
                        <div style="margin-top:20px;">
                            <a href="/company/detail/{{ $company->id }}" class="btn"
                                style="width: 100%;display:inline-block;text-align:center">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="d-flex justify-content-end mt-5">
                <div class="col-sm-6">
                    <div
                        class="pagination-block pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
                        @if ($companies->onFirstPage())
                            <div class="page-item disabled" style="margin-right: 10px;">
                                <span class="page-link"
                                    style="background-color: #fea82b; color: rgb(161, 8, 14); border-color: rgb(161, 8, 14);">Sebelumnya</span>
                            </div>
                        @else
                            <div class="page-item" style="margin-right: 10px;">
                                <a href="{{ $companies->appends(request()->all())->previousPageUrl() }}"
                                    class="page-link" id="page-prev"
                                    style="background-color: #fea82b; color: rgb(161, 8, 14); border-color: rgb(161, 8, 14);">Sebelumnya</a>
                            </div>
                        @endif

                        <!-- Page Numbers -->
                        <span id="page-num" class="pagination">
                            @foreach ($companies->links()->elements[0] as $page => $url)
                                @if ($page == $companies->currentPage())
                                    <span class="page-item active" style="margin-right: 10px;">
                                        <span class="page-link"
                                            style="background-color: rgb(161, 8, 14); color: #fff; border-color: rgb(161, 8, 14);">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $companies->appends(request()->all())->url($page) }}" class="page-item"
                                        style="margin-right: 10px;">
                                        <span class="page-link"
                                            style="background-color: #fea82b; color: rgb(161, 8, 14); border-color: rgb(161, 8, 14);">{{ $page }}</span>
                                    </a>
                                @endif
                            @endforeach
                        </span>

                        @if ($companies->hasMorePages())
                            <div class="page-item">
                                <a href="{{ $companies->appends(request()->all())->nextPageUrl() }}" class="page-link"
                                    id="page-next"
                                    style="background-color: #fea82b; color: rgb(161, 8, 14); border-color: rgb(161, 8, 14);">Berikutnya</a>
                            </div>
                        @else
                            <div class="page-item disabled">
                                <span class="page-link"
                                    style="background-color: #fea82b; color: rgb(161, 8, 14); border-color: rgb(161, 8, 14);">Berikutnya</span>
                            </div>
                        @endif
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
                    Our platform is designed to help you find the perfect job and
                    achieve your professional dreams.
                </p>
            </div>
            <div class="footer__col">
                <h4 class="text-white">Quick Links</h4>
                <ul class="footer__links">
                    <li><a href="#" class="text-white">Home</a></li>
                    <li><a href="#" class="text-white">About Us</a></li>
                    <li><a href="#" class="text-white">Jobs</a></li>
                    <li><a href="#" class="text-white">Testimonials</a></li>
                    <li><a href="#" class="text-white">Contact Us</a></li>
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
                            <span><i class="ri-map-pin-2-fill"></i></span> 123 Main Street,
                            Anytown, USA
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="footer__bar text-white">
            Copyright © 2024 Web Design Mastery. All rights reserved.
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
