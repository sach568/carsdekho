<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarsDekho - Find Your Dream Car</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1a73e8;
            --secondary-color: #f8f9fa;
        }

        /* Header */
        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
        }

        .nav-link {
            font-weight: 500;
        }

        /* Banner Section */
        .banner-section {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
                url('https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }

        @media (max-width: 768px) {
            .banner-section {
                padding: 60px 0;
            }
        }

        /* Car Cards */
        .car-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            height: 100%;
        }

        .car-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .car-img {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }

        .price-tag {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--primary-color);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9rem;
        }

        /* Section Titles */
        .section-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 40px;
            text-align: center;
        }

        .section-title h2 {
            display: inline-block;
            font-weight: 600;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: var(--primary-color);
        }

        /* Footer */
        footer {
            background: #2c3e50;
            color: white;
            padding: 60px 0 20px;
        }

        .footer-links a {
            color: #bdc3c7;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: white;
        }

        .social-icons a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            margin-right: 10px;
            color: white;
            transition: all 0.3s;
        }

        .social-icons a:hover {
            background: var(--primary-color);
            transform: translateY(-3px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .car-img {
                height: 180px;
            }

            .banner-section h1 {
                font-size: 2rem;
            }

            .banner-section p {
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .car-img {
                height: 150px;
            }

            .section-title h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- Header Section -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                @if($header && $header->logo)
                    <img src="{{ Storage::url($header->logo) }}" height="40" alt="CarsDekho" class="me-2">
                @endif
                <i class="fas fa-car"></i> CarsDekho
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('home') }}">Home</a>
                    </li>
                    @if($header && $header->menu_items)
                        @foreach(json_decode($header->menu_items, true) as $item)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ $item['url'] ?? '#' }}">{{ $item['name'] }}</a>
                            </li>
                        @endforeach
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.form') }}">Book Test Drive</a>
                    </li>
                    @auth
                        @if(Auth::user()->is_admin)
                            <li class="nav-item">
                                <a class="nav-link text-primary" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-cog"></i> Admin Panel
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
                @if($header && ($header->phone || $header->email))
                    <div class="ms-lg-3 mt-2 mt-lg-0">
                        @if($header->phone)
                            <small class="text-muted d-block d-lg-inline">
                                <i class="fas fa-phone me-1"></i> {{ $header->phone }}
                            </small>
                        @endif
                        @if($header->email)
                            <small class="text-muted ms-lg-3 d-block d-lg-inline mt-1 mt-lg-0">
                                <i class="fas fa-envelope me-1"></i> {{ $header->email }}
                            </small>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <!-- 1st Banner Section -->
    <section class="banner-section">
        <div class="container">
            <h1 class="display-4 mb-4 fw-bold">Find Your Dream Car</h1>
            <p class="lead mb-4">Browse through our extensive collection of new and pre-owned cars</p>
            <div class="mt-4">
                <a href="{{ route('customer.form') }}" class="btn btn-primary btn-lg px-4 py-2 me-2">
                    <i class="fas fa-calendar-check me-2"></i>Book Test Drive
                </a>
                <a href="#most-searched" class="btn btn-outline-light btn-lg px-4 py-2">
                    <i class="fas fa-search me-2"></i>Explore Cars
                </a>
            </div>
        </div>
    </section>

    <!-- Most Searched Cars Section -->
    <section id="most-searched" class="py-5">
        <div class="container">
            <div class="section-title">
                <h2>Most Searched Cars</h2>
                <p class="text-muted mt-2">Popular choices among our customers</p>
            </div>
            <div class="row">
                @forelse($mostSearchedCars as $car)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="car-card">
                            <div class="position-relative">
                                <img src="{{ Storage::url($car->image) }}" class="car-img" alt="{{ $car->name }}">
                                <div class="price-tag">₹{{ number_format($car->price) }}</div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title fw-bold">{{ $car->name }}</h5>
                                <p class="card-text text-muted">{{ $car->model }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-primary">
                                        <i class="fas fa-search me-1"></i> {{ $car->search_count }} searches
                                    </span>
                                    <a href="{{ route('customer.form') }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-info-circle me-1"></i>Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-car fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No cars available at the moment</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Latest Cars Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="section-title">
                <h2>Latest Cars</h2>
                <p class="text-muted mt-2">New arrivals in our showroom</p>
            </div>
            <div class="row">
                @forelse($latestCars as $car)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="car-card">
                            <div class="position-relative">
                                <img src="{{ Storage::url($car->image) }}" class="car-img" alt="{{ $car->name }}">
                                <div class="price-tag">₹{{ number_format($car->price) }}</div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title fw-bold">{{ $car->name }}</h5>
                                <p class="card-text text-muted">{{ $car->model }}</p>
                                @if($car->features)
                                    <p class="card-text small text-muted mb-3">
                                        {{ Str::limit($car->features, 100) }}
                                    </p>
                                @endif
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('customer.form') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-calendar-alt me-1"></i>Book Test Drive
                                    </a>
                                    <button class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-heart me-1"></i>Wishlist
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-car fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No latest cars available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="mb-3">
                        @if($footer && $footer->logo)
                            <img src="{{ Storage::url($footer->logo) }}" height="40" class="me-2">
                        @endif
                        CarsDekho
                    </h5>
                    @if($footer && $footer->description)
                        <p class="text-light">{{ $footer->description }}</p>
                    @endif
                    <div class="social-icons mt-3">
                        @if($footer && $footer->social_links)
                            @foreach(json_decode($footer->social_links, true) as $social)
                                <a href="{{ $social['url'] ?? '#' }}" title="{{ $social['platform'] }}">
                                    <i class="fab fa-{{ strtolower($social['platform']) }}"></i>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="mb-3">Contact Info</h5>
                    @if($footer)
                        <ul class="list-unstyled">
                            @if($footer->address)
                                <li class="mb-2">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    {{ $footer->address }}
                                </li>
                            @endif
                            @if($footer->phone)
                                <li class="mb-2">
                                    <i class="fas fa-phone me-2"></i>
                                    {{ $footer->phone }}
                                </li>
                            @endif
                            @if($footer->email)
                                <li class="mb-2">
                                    <i class="fas fa-envelope me-2"></i>
                                    {{ $footer->email }}
                                </li>
                            @endif
                        </ul>
                    @endif
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="mb-3">Quick Links</h5>
                    <div class="footer-links">
                        <a href="{{ route('home') }}">Home</a>
                        <a href="{{ route('customer.form') }}">Book Test Drive</a>
                        <a href="#most-searched">Most Searched Cars</a>
                        <a href="#about">About Us</a>
                        <a href="#contact">Contact Us</a>
                    </div>
                </div>
            </div>

            <hr class="bg-light mt-4 mb-3">

            <div class="text-center pt-3">
                <p class="mb-0">&copy; {{ date('Y') }} CarsDekho. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // scrol anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>

</html>