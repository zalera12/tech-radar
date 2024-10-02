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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .accordion-button.custom-button {
            background-color: rgb(161, 8, 14);
            /* Merah */
            color: #fff;
            /* Putih untuk teks */
            font-weight: bold;
            /* Border kuning */
            border-radius: 8px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .accordion-button.custom-button:not(.collapsed) {
            background-color: #fea82b;
            /* Kuning saat terbuka */
            color: black;
            border-top-right-radius: 8px ;
            border-top-left-radius: 8px;
            border-bottom-right-radius: 0 ;
            border-bottom-left-radius: 0 ;
            /* Hitam untuk teks saat terbuka */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            /* Shadow */
        }

        .accordion-button.custom-button:focus {
            box-shadow: none;
        }

        .accordion-item {
            /* Hitam untuk background item */
            color: white;
            margin-bottom: 10px;
            border-radius: 8px;
        }

        .accordion-body {
            background-color: #fff;
            /* Putih untuk konten */
            color: black;
            /* Teks hitam untuk konten */
            border: 2px solid rgb(161, 8, 14);
            /* Border merah */
            
            padding: 20px;
            border-radius: 0 0 8px 8px;
        }

        .accordion-header {
            border-bottom: none;
            /* Hilangkan border bawah default */
        }

        /* Tambahan untuk efek hover */
        .accordion-button.custom-button:hover {
            background-color: #fea82b;
            /* Kuning saat hover */
            color: black;
            border-color: rgb(161, 8, 14);
            /* Merah saat hover */
        }

        html {
            scroll-behavior: smooth;
        }

  
    </style>

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
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#categories">Categories</a></li>
            <li><a href="#job">Companies</a></li>
            <li><a href="#service">Services</a></li>
            <li><a href="#client">Teams</a></li>
            @if (Auth::check())
            <li><a href="/index">Dashboard</a></li>
            @endif
            @if (Auth::check())
            <li>
                <form action="/logout" method="POST" class="btn" style="display: flex; align-items:center; gap:10px;">
                    @csrf
                    <button type="submit" style="background: transparent; border: none; color: white; cursor: pointer;">
                        Logout
                    </button>
                </form>
            </li> 
        @else
            <li>
                <a href="/loginAccount" class="btn" style="display: flex;align-items:center;gap:10px;">
                    <img src="/assets/google.png" style="width: 20px;">
                    <span style="color: white">Login</span>
                </a>
            </li>              
        @endif
        </ul>
    </nav>
    <header class="section__container header__container" id="home">
        <img src="/assets/js.png" alt="header" />
        <img src="assets/html.png" alt="header" />
        <img src="assets/amazon.png" alt="header" />
        <img src="assets/figma.png" alt="header" />
        <img src="assets/html.png" alt="header" />
        <img src="assets/tailwind.png" alt="header" />
        <h2>
            <img src="assets/bag.png" alt="radar" />
            Platform Tech Radar No.1
        </h2>
        <h1>Jelajahi, Analisis &<br />Tingkatkan <span>Teknologi Anda</span></h1>
        <p>
            Evolusi teknologi Anda dimulai di sini. Temukan berbagai teknologi, adopsi alat yang sesuai
            dengan kebutuhan dan tujuan tim Anda, serta tingkatkan pengembangan.
        </p>
        <div class="header__btns">
            <button class="btn">Jelajahi Teknologi</button>
            <a href="#">
                <span><i class="ri-play-fill"></i></span>
                Cara Kerjanya?
            </a>
        </div>



    </header>

    <section class="steps" id="about">
        <div class="section__container steps__container">
            <h2 class="section__header">
                Tentang Kami dalam 4 <span>Langkah Sederhana</span>
            </h2>
            <p class="section__description">
                Kami adalah platform yang membantu Anda memahami, mengeksplorasi, dan mengadopsi teknologi terbaru untuk
                mendorong inovasi dan kesuksesan di dunia digital.
            </p>

            <div class="steps__grid">
                <div class="steps__card">
                    <span><i class="ri-user-fill"></i></span>
                    <h4>Tentang Platform</h4>
                    <p>
                        Tech Radar menyediakan wawasan mendalam tentang tren dan teknologi terkini. Kami membantu tim
                        dan perusahaan untuk mengambil keputusan yang tepat dalam mengadopsi teknologi baru.
                    </p>
                </div>
                <div class="steps__card">
                    <span><i class="ri-search-fill"></i></span>
                    <h4>Eksplorasi Teknologi</h4>
                    <p>
                        Jelajahi berbagai teknologi di platform kami. Dengan informasi yang terstruktur dan fitur
                        pencarian yang canggih, Anda bisa menemukan solusi teknologi yang tepat untuk kebutuhan Anda.
                    </p>
                </div>
                <div class="steps__card">
                    <span><i class="ri-file-paper-fill"></i></span>
                    <h4>Analisis & Evaluasi</h4>
                    <p>
                        Dapatkan analisis mendalam mengenai setiap teknologi yang Anda minati. Lihat kelebihan,
                        kekurangan, dan tren adopsi sehingga Anda bisa membuat keputusan yang lebih tepat.
                    </p>
                </div>
                <div class="steps__card">
                    <span><i class="ri-briefcase-fill"></i></span>
                    <h4>Adopsi Teknologi</h4>
                    <p>
                        Setelah mengevaluasi, Anda bisa mengadopsi teknologi pilihan Anda untuk memperkuat strategi
                        teknologi tim Anda dan memajukan produktivitas perusahaan.
                    </p>
                </div>
            </div>
        </div>
    </section>


    <section class="section__container explore__container" id="categories">
        <h2 class="section__header">
            <span>Beragam Kategori Teknologi</span> Menunggu Anda untuk Dieksplorasi
        </h2>
        <p class="section__description">
            Temukan berbagai teknologi terkini dan tren yang relevan untuk membantu Anda mengambil keputusan dalam
            pengembangan teknologi di masa depan.
        </p>
        <div class="explore__grid">
            <div class="explore__card">
                <span><i class="ri-layout-fill"></i></span>
                <h4>Framework Front-end</h4>
                <p>200+ teknologi</p>
            </div>
            <div class="explore__card">
                <span><i class="ri-bar-chart-box-fill"></i></span>
                <h4>Framework Back-end</h4>
                <p>150+ teknologi</p>
            </div>
            <div class="explore__card">
                <span><i class="ri-megaphone-fill"></i></span>
                <h4>Tools DevOps</h4>
                <p>100+ tools</p>
            </div>
            <div class="explore__card">
                <span><i class="ri-database-2-fill"></i></span>
                <h4>Database</h4>
                <p>80+ teknologi</p>
            </div>
            <div class="explore__card">
                <span><i class="ri-cloud-fill"></i></span>
                <h4>Cloud Platforms</h4>
                <p>50+ layanan</p>
            </div>
            <div class="explore__card">
                <span><i class="ri-truck-fill"></i></span>
                <h4>API Management</h4>
                <p>40+ tools</p>
            </div>
            <div class="explore__card">
                <span><i class="ri-computer-fill"></i></span>
                <h4>Testing Tools</h4>
                <p>60+ tools</p>
            </div>
            <div class="explore__card">
                <span><i class="ri-smartphone-fill"></i></span>
                <h4>Mobile Development</h4>
                <p>30+ framework</p>
            </div>
        </div>
        <div class="explore__btn">
            <button class="btn">Mitra Perusahaan Kami</button>
        </div>
    </section>


    <section class="section__container job__container" id="job">
        <h2 class="section__header"><span>Perusahaan Terkemuka</span> yang Bermitra dengan Kami</h2>
        <p class="section__description">
            Temukan Perusahaan-Perguruan Terkemuka yang Bekerja Sama dengan Platform Tech Radar Kami
            dan Peluang Menarik dalam Mengembangkan Teknologi.
        </p>
        <div class="job__grid row">
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
        </div>
        <div class="explore__btn" style="margin-top: 5rem;">
            <a class="btn" href="/all-company">Lihat Semua Perusahaan</a>
        </div>
    </section>

    <section class="section__container offer__container" id="service">
        <h2 class="section__header">Apa yang Kami <span>Tawarkan</span></h2>
        <p class="section__description">
            Jelajahi Keunggulan dan Layanan yang Kami Sediakan untuk Memantau dan Mengelola Teknologi serta Inovasi Anda
        </p>
        <div class="offer__grid">
            <div class="offer__card">
                <img src="assets/offer-1.jpg" alt="offer" />
                <div class="offer__details">
                    <span>01</span>
                    <div>
                        <h4>Rekomendasi Teknologi</h4>
                        <p>
                            Rekomendasi teknologi yang dipersonalisasi sesuai dengan kebutuhan dan tren terkini
                        </p>
                    </div>
                </div>
            </div>
            <div class="offer__card">
                <img src="assets/offer-2.jpg" alt="offer" />
                <div class="offer__details">
                    <span>02</span>
                    <div>
                        <h4>Buat & Kembangkan Radar Teknologi</h4>
                        <p>Tampilkan inovasi Anda dengan desain radar teknologi yang profesional</p>
                    </div>
                </div>
            </div>
            <div class="offer__card">
                <img src="assets/offer-3.jpg" alt="offer" />
                <div class="offer__details">
                    <span>03</span>
                    <div>
                        <h4>Analisis Tren Teknologi</h4>
                        <p>Identifikasi tren teknologi terbaru yang relevan dengan bisnis Anda</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section__container" id="faq">
        <h2 class="section__header">Pertanyaan yang Sering <span>Diajukan</span></h2>
        <p class="section__description">
            Dapatkan jawaban atas pertanyaan seputar layanan Tech Radar kami dan cara platform ini membantu mengelola teknologi Anda.
        </p>
        
        <div class="accordion" id="accordionExample" style="margin-top: 3.5rem;">
            <!-- Accordion Item #1 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button custom-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Apa itu Tech Radar?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <strong>Tech Radar</strong> adalah alat visual yang dikembangkan oleh ThoughtWorks untuk membantu 
                        organisasi dalam mengevaluasi dan melacak teknologi baru. Dengan menggunakan radar ini, tim 
                        pengembang dapat memahami tren teknologi dan membuat keputusan yang lebih baik terkait 
                        adopsi, percobaan, atau penghindaran teknologi tertentu.
                    </div>
                </div>
            </div>
        
            <!-- Accordion Item #2 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed custom-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Bagaimana cara membaca Tech Radar?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        Tech Radar dibagi menjadi beberapa kuadran yang masing-masing menggambarkan kategori teknologi, 
                        seperti:
                        <ul>
                            <li><strong>Techniques</strong>: Metode atau praktik yang dapat diadopsi untuk meningkatkan 
                            efisiensi kerja.</li>
                            <li><strong>Tools</strong>: Alat dan perangkat lunak yang dapat membantu pengembangan 
                            dan kolaborasi tim.</li>
                            <li><strong>Platforms</strong>: Infrastruktur dan layanan yang dapat digunakan untuk 
                            pengembangan aplikasi.</li>
                            <li><strong>Languages & Frameworks</strong>: Bahasa pemrograman dan framework yang 
                            direkomendasikan untuk proyek baru.</li>
                        </ul>
                        Setiap kuadran memiliki status adopsi yang menunjukkan tingkat kesiapan teknologi tersebut 
                        untuk digunakan dalam proyek.
                    </div>
                </div>
            </div>
        
            <!-- Accordion Item #3 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed custom-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Apa saja kategori dalam Tech Radar?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        Dalam Tech Radar, terdapat beberapa kategori yang digunakan untuk mengelompokkan teknologi:
                        <ul>
                            <li><strong>Adopt</strong>: Teknologi yang sudah terbukti efektif dan disarankan untuk 
                            digunakan secara luas.</li>
                            <li><strong>Trial</strong>: Teknologi yang sedang diuji coba dalam beberapa proyek dan 
                            menunjukkan hasil positif.</li>
                            <li><strong>Assess</strong>: Teknologi yang memerlukan analisis lebih lanjut sebelum 
                            diadopsi.</li>
                            <li><strong>Hold</strong>: Teknologi yang tidak disarankan untuk digunakan saat ini 
                            karena risiko atau ketidakpastian yang tinggi.</li>
                        </ul>
                        Kategori ini membantu tim dalam membuat keputusan yang lebih informasional terkait teknologi 
                        yang sebaiknya digunakan dalam proyek mereka.
                    </div>
                </div>
            </div>
        
            <!-- Accordion Item #4 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed custom-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        Kenapa Tech Radar penting untuk tim pengembang?
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        Tech Radar penting karena memberikan panduan visual tentang teknologi yang sedang tren 
                        dan membantu tim pengembang untuk:
                        <ul>
                            <li>Memilih alat dan teknologi yang tepat untuk proyek mereka.</li>
                            <li>Meminimalkan risiko dengan menghindari teknologi yang belum terbukti.</li>
                            <li>Mendukung inovasi dengan memperkenalkan teknologi baru yang relevan.</li>
                            <li>Meningkatkan kolaborasi di antara anggota tim dengan memahami teknologi yang sama.</li>
                        </ul>
                        Dengan menggunakan Tech Radar, tim dapat lebih proaktif dalam mengadopsi teknologi baru dan 
                        tetap kompetitif di industri.
                    </div>
                </div>
            </div>
        
            <!-- Accordion Item #5 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFive">
                    <button class="accordion-button collapsed custom-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        Bagaimana cara mengupdate Tech Radar?
                    </button>
                </h2>
                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        Mengupdate Tech Radar biasanya dilakukan secara berkala (misalnya setiap 6 bulan) untuk 
                        memastikan informasi tetap relevan. Proses ini melibatkan:
                        <ul>
                            <li>Mengumpulkan umpan balik dari tim pengembang mengenai teknologi yang telah 
                            diadopsi.</li>
                            <li>Melakukan riset tentang teknologi baru dan tren yang sedang berkembang.</li>
                            <li>Menilai kembali status teknologi yang ada di radar dan mengklasifikasikannya 
                            sesuai dengan kategori yang tepat.</li>
                            <li>Memperbarui dokumen atau representasi visual dari Tech Radar untuk 
                            disebarkan kepada tim dan pemangku kepentingan lainnya.</li>
                        </ul>
                        Pembaruan yang teratur memastikan bahwa tim tetap up-to-date dengan perkembangan 
                        teknologi yang dapat mendukung tujuan mereka.
                    </div>
                </div>
            </div>
        </div>
        
        
    </section>

    </div>

    <section class="section__container client__container" id="client">
        <h2 class="section__header">Tim <span>Pengembang</span></h2>
        <p class="section__description">
            Kenali orang-orang dan tim yang berperan penting dalam mengembangkan dan membangun project Tech Radar ini.
        </p>
        
        <!-- Slider main container -->
        <div class="swiper">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
                <!-- Slides -->
                <div class="swiper-slide">
                    <div class="client__card">
                        <img src="assets/bangsam.jpeg" alt="client" style="width: 85px;height:85px;" />
                        <p>
                            Sebagai seorang mentor dalam proyek Tech Radar, saya bangga dapat membantu tim memahami 
                            dan memilih teknologi yang tepat untuk kebutuhan mereka. Dengan pendekatan yang sistematis, 
                            kami menciptakan panduan yang memudahkan organisasi untuk menavigasi dunia teknologi yang 
                            terus berkembang.
                        </p>
                        <h4>Sammi Aldhi Yanto</h4>
                        <h5>Mentor</h5>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="client__card">
                        <img src="assets/daffa.jpeg" alt="client" style="width: 85px;height:85px;" />
                        <p>
                            Dalam peran saya sebagai analis untuk proyek Tech Radar, saya terlibat dalam pengumpulan 
                            dan analisis data tentang berbagai teknologi. Kami memastikan bahwa informasi yang 
                            disediakan relevan dan dapat diandalkan, sehingga memudahkan pengguna dalam membuat 
                            keputusan strategis yang cerdas.
                        </p>
                        <h4>Daffa Dhyaulhaq</h4>
                        <h5>Analis</h5>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="client__card">
                        <img src="assets/rifki.jpg" alt="client" style="width: 85px;height:85px;" />
                        <p>
                            Sebagai UI & UX Designer dalam proyek Tech Radar, saya bertanggung jawab untuk menciptakan 
                            pengalaman pengguna yang intuitif. Kami berfokus pada desain yang memudahkan pengguna 
                            dalam mengeksplorasi dan memahami informasi tentang teknologi, sehingga mereka dapat 
                            dengan mudah membuat pilihan yang tepat.
                        </p>
                        <h4>Rifki Pratama</h4>
                        <h5>UI & UX Designer</h5>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="client__card">
                        <img src="assets/azza.png" alt="client" style="width: 85px;height:85px;" />
                        <p>
                            Dalam kapasitas saya sebagai programmer, saya terlibat dalam pengembangan dan 
                            implementasi proyek Tech Radar. Kami berupaya untuk menciptakan platform yang 
                            responsif dan efisien, memungkinkan pengguna untuk menemukan informasi teknologi yang 
                            mereka butuhkan dengan mudah dan cepat.
                        </p>
                        <h4>Azzairul</h4>
                        <h5>Programmer</h5>
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
    <script>
        
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ session('error') }}",
                confirmButtonText: 'Oke',
            });
        @endif   
        
   
    </script>
</body>

</html>
