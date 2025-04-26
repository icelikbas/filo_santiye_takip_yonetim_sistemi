<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filo Akaryakıt Yönetim Sistemi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('/api/placeholder/1920/600');
            background-size: cover;
            opacity: 0.2;
            z-index: 0;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
        }
        
        .section-title {
            position: relative;
            margin-bottom: 30px;
            padding-bottom: 15px;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            height: 4px;
            width: 60px;
            background-color: var(--accent-color);
        }
        
        .feature-box {
            padding: 30px;
            border-radius: 8px;
            transition: all 0.3s ease;
            background-color: #ffffff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            height: 100%;
        }
        
        .feature-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: var(--primary-color);
        }
        
        .stats-counter {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .stats-section {
            background-color: #f8f9fa;
            padding: 80px 0;
        }
        
        .testimonial-card {
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        .testimonial-img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .contact-info i {
            color: var(--primary-color);
            margin-right: 10px;
        }
        
        footer {
            background-color: var(--secondary-color);
            color: #fff;
            padding: 50px 0 20px;
        }
        
        .footer-links li {
            margin-bottom: 10px;
        }
        
        .footer-links a {
            color: #ddd;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer-links a:hover {
            color: #fff;
            text-decoration: none;
        }
        
        .social-icons a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            margin-right: 10px;
            color: #fff;
            transition: all 0.3s ease;
        }
        
        .social-icons a:hover {
            background-color: var(--primary-color);
            transform: translateY(-3px);
        }
        
        .module-section {
            padding: 80px 0;
        }
        
        .module-item {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .module-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-gas-pump me-2"></i> Filo Akaryakıt Yönetim Sistemi
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Ana Sayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Hakkımızda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#modules">Modüller</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Özellikler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">İletişim</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Filonuzu Akıllıca Yönetin</h1>
                    <p class="lead mb-4">Filo Akaryakıt Yönetim Sistemi ile araç filosu yönetimini dijitalleştirin, yakıt maliyetlerinizi optimize edin ve operasyonel verimliliğinizi artırın.</p>
                    <div class="d-flex gap-3">
                        <a href="#contact" class="btn btn-primary btn-lg">Daha Fazla Bilgi</a>
                        <a href="#demo" class="btn btn-outline-light btn-lg">Demo İste</a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block text-center">
                    <img src="/api/placeholder/600/400" alt="Filo Yönetim Sistemi" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-5" id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="section-title">Hakkımızda</h2>
                    <p class="lead">Filo Akaryakıt Yönetim Sistemi, şirketlerin araç filolarını etkili bir şekilde yönetmelerine yardımcı olmak için tasarlanmış kapsamlı bir çözümdür.</p>
                    <p>Sistemimiz, araç ve sürücü yönetiminden yakıt takibine, bakım planlamasından belge yönetimine kadar tüm filo operasyonlarınızı tek bir platformda birleştirir. Kullanıcı dostu arayüzü ve gelişmiş raporlama özellikleriyle, filo yöneticilerinin daha bilinçli kararlar almasını sağlayarak işletme maliyetlerini optimize eder.</p>
                    <p>10 yılı aşkın sektör deneyimimizle, Türkiye'nin önde gelen kurumlarına hizmet vermekteyiz. Teknoloji odaklı yaklaşımımız ve müşteri memnuniyetine verdiğimiz önem ile sektörde fark yaratıyoruz.</p>
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="feature-box text-center">
                                <i class="fas fa-chart-line feature-icon"></i>
                                <h4>Maliyet Optimizasyonu</h4>
                                <p>Yakıt tüketimini optimize ederek maliyetlerinizi %20'ye kadar düşürün.</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="feature-box text-center">
                                <i class="fas fa-tachometer-alt feature-icon"></i>
                                <h4>Performans Analizi</h4>
                                <p>Ayrıntılı raporlarla filo performansınızı sürekli analiz edin.</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="feature-box text-center">
                                <i class="fas fa-shield-alt feature-icon"></i>
                                <h4>Güvenlik</h4>
                                <p>Sürücü davranışlarını izleyerek kaza riskini azaltın.</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="feature-box text-center">
                                <i class="fas fa-cloud feature-icon"></i>
                                <h4>Bulut Tabanlı</h4>
                                <p>Her yerden erişilebilen bulut tabanlı çözüm.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 col-6 mb-4 mb-md-0">
                    <div class="stats-counter">500+</div>
                    <p>Müşteri</p>
                </div>
                <div class="col-md-3 col-6 mb-4 mb-md-0">
                    <div class="stats-counter">50.000+</div>
                    <p>Yönetilen Araç</p>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stats-counter">%25</div>
                    <p>Ortalama Tasarruf</p>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stats-counter">10+</div>
                    <p>Yıllık Deneyim</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Modules Section -->
    <section class="module-section" id="modules">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="section-title text-center mx-auto" style="display: inline-block;">Sistem Modülleri</h2>
                    <p class="lead">Filo Akaryakıt Yönetim Sistemimiz, tüm filo operasyonlarınızı yönetmeniz için gerekli tüm araçları sunar.</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="module-item">
                        <div class="module-icon">
                            <i class="fas fa-car"></i>
                        </div>
                        <h4>Araç Yönetimi</h4>
                        <p>Araçların envanterini tutun, detaylı bilgilerini kaydedin ve araçlarınızın performansını analiz edin.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="module-item">
                        <div class="module-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h4>Sürücü Yönetimi</h4>
                        <p>Sürücü bilgilerini kaydedin, ehliyet ve sertifika durumlarını takip edin, performans analizi yapın.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="module-item">
                        <div class="module-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <h4>Görevlendirmeler</h4>
                        <p>Araç-sürücü görevlendirmelerini planlayın ve takip edin, çakışmaları önleyin.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="module-item">
                        <div class="module-icon">
                            <i class="fas fa-wrench"></i>
                        </div>
                        <h4>Bakım Takibi</h4>
                        <p>Periyodik bakımları planlayın, bakım maliyetlerini kaydedin ve bakım geçmişini görüntüleyin.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="module-item">
                        <div class="module-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h4>Muayene Takibi</h4>
                        <p>Araç muayene tarihlerini takip edin, yaklaşan muayeneler için hatırlatmalar alın.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="module-item">
                        <div class="module-icon">
                            <i class="fas fa-gas-pump"></i>
                        </div>
                        <h4>Yakıt Takibi</h4>
                        <p>Yakıt alımlarını kaydedin, yakıt tüketimini analiz edin ve anormal durumları tespit edin.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="module-item">
                        <div class="module-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h4>Belge Yönetimi</h4>
                        <p>Sigorta, ruhsat ve diğer önemli belgeleri dijital ortamda saklayın ve takip edin.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="module-item">
                        <div class="module-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h4>Trafik Cezaları</h4>
                        <p>Trafik cezalarını kaydedin, analiz edin ve sürücülere göre raporlayın.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="module-item">
                        <div class="module-icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <h4>Raporlama</h4>
                        <p>Kapsamlı raporlar ve grafiklerle filo operasyonlarınızı analiz edin ve optimize edin.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light" id="features">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="section-title text-center mx-auto" style="display: inline-block;">Özellikleri</h2>
                    <p class="lead">Sistemimizin sunduğu avantajlar ve temel özellikleri.</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="d-flex align-items-start">
                        <div class="me-3">
                            <i class="fas fa-check-circle text-primary fa-2x"></i>
                        </div>
                        <div>
                            <h4>Gerçek Zamanlı İzleme</h4>
                            <p>Filonuzdaki araçların konumunu ve durumunu gerçek zamanlı olarak takip edin.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="d-flex align-items-start">
                        <div class="me-3">
                            <i class="fas fa-check-circle text-primary fa-2x"></i>
                        </div>
                        <div>
                            <h4>Otomatik Hatırlatmalar</h4>
                            <p>Bakım, muayene ve belge yenileme tarihleri için otomatik hatırlatmalar alın.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="d-flex align-items-start">
                        <div class="me-3">
                            <i class="fas fa-check-circle text-primary fa-2x"></i>
                        </div>
                        <div>
                            <h4>Gelişmiş Raporlama</h4>
                            <p>Özelleştirilebilir raporlarla filo performansınızı detaylı şekilde analiz edin.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="d-flex align-items-start">
                        <div class="me-3">
                            <i class="fas fa-check-circle text-primary fa-2x"></i>
                        </div>
                        <div>
                            <h4>Mobil Uyumlu</h4>
                            <p>Mobil cihazlardan da sisteme erişerek filonuzu her yerden yönetin.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="d-flex align-items-start">
                        <div class="me-3">
                            <i class="fas fa-check-circle text-primary fa-2x"></i>
                        </div>
                        <div>
                            <h4>Kullanıcı Yetkilendirme</h4>
                            <p>Farklı yetki seviyelerine sahip kullanıcılar tanımlayarak sistem güvenliğini sağlayın.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="d-flex align-items-start">
                        <div class="me-3">
                            <i class="fas fa-check-circle text-primary fa-2x"></i>
                        </div>
                        <div>
                            <h4>Entegrasyon</h4>
                            <p>Muhasebe yazılımları ve diğer kurumsal sistemlerle entegre çalışabilme.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title text-center mx-auto mb-5" style="display: inline-block;">Müşterilerimiz Ne Diyor?</h2>
            
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="testimonial-card">
                        <div class="d-flex align-items-center mb-4">
                            <img src="/api/placeholder/70/70" alt="Müşteri" class="testimonial-img me-3">
                            <div>
                                <h5 class="mb-0">Ahmet Yılmaz</h5>
                                <p class="text-muted mb-0">XYZ Lojistik, Filo Müdürü</p>
                            </div>
                        </div>
                        <p class="mb-0">"Filo Akaryakıt Yönetim Sistemi sayesinde yakıt maliyetlerimizi %22 oranında düşürdük. Kullanımı kolay arayüzü ve detaylı raporlama özellikleri ile işimizin vazgeçilmez bir parçası haline geldi."</p>
                    </div>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <div class="testimonial-card">
                        <div class="d-flex align-items-center mb-4">
                            <img src="/api/placeholder/70/70" alt="Müşteri" class="testimonial-img me-3">
                            <div>
                                <h5 class="mb-0">Zeynep Kaya</h5>
                                <p class="text-muted mb-0">ABC Taşımacılık, Genel Müdür</p>
                            </div>
                        </div>
                        <p class="mb-0">"200 araçlık filomuzun yönetimini bu sistem sayesinde çok daha verimli hale getirdik. Özellikle bakım ve muayene takibi konusunda büyük kolaylık sağlıyor."</p>
                    </div>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <div class="testimonial-card">
                        <div class="d-flex align-items-center mb-4">
                            <img src="/api/placeholder/70/70" alt="Müşteri" class="testimonial-img me-3">
                            <div>
                                <h5 class="mb-0">Mehmet Demir</h5>
                                <p class="text-muted mb-0">DEF Dağıtım, Operasyon Direktörü</p>
                            </div>
                        </div>
                        <p class="mb-0">"Sistemin sunduğu gerçek zamanlı veri analizi ve raporlama özellikleri sayesinde daha stratejik kararlar alabiliyoruz. Teknik desteğin hızı ve kalitesi de ayrıca takdire şayan."</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-5 bg-light" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="section-title">İletişim</h2>
                    <p class="lead mb-4">Filo Akaryakıt Yönetim Sistemi hakkında daha fazla bilgi almak için bizimle iletişime geçin.</p>
                    
                    <div class="contact-info mb-4">
                        <p><i class="fas fa-map-marker-alt"></i> Adres: Atatürk Caddesi No:123, 34000 İstanbul</p>
                        <p><i class="fas fa-phone"></i> Telefon: +90 212 123 45 67</p>
                        <p><i class="fas fa-envelope"></i> E-posta: info@filoakarykait.com</p>
                    </div>
                    
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="card border-0 shadow">
                        <div class="card-body p-4">
                            <h4 class="mb-4">Bize Ulaşın</h4>
                            <form>
                                <div class="mb-3">
                                    <input type="text" class="form-control" placeholder="Adınız Soyadınız">
                                </div>
                                <div class="mb-3">
                                    <input type="email" class="form-control" placeholder="E-posta Adresiniz">
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" placeholder="Konu">
                                </div>
                                <div class="mb-3">
                                    <textarea class="form-control" rows="4" placeholder="Mesajınız"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Gönder</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Demo Section -->
    <section class="py-5" id="demo">
        <div class="container text-center">
            <h2 class="mb-4">Sistemimizi Ücretsiz Deneyin</h2>
            <p class="lead mb-4">30 günlük ücretsiz deneme sürümümüz ile sistemimizin sunduğu avantajları keşfedin.</p>
            <a href="#" class="btn btn-primary btn-lg">Demo Talebi Oluştur</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="text-uppercase mb-4">Filo Akaryakıt Yönetim Sistemi</h5>
                    <p>Filonuzu daha akıllı ve verimli yönetmeniz için tasarlanmış kapsamlı çözümler sunuyoruz.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase mb-4">Hızlı Erişim</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="#">Ana Sayfa</a></li>
                        <li><a href="#about">Hakkımızda</a></li>
                        <li><a href="#modules">Modüller</a></li>
                        <li><a href="#features">Özellikler</a></li>
                        <li><a href="#contact">İletişim</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase mb-4">Modüller</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="#">Araç Yönetimi</a></li>
                        <li><a href="#">Sürücü Takibi</a></li>