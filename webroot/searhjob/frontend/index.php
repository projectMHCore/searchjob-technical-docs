<?php
// Головна сторінка - SearchJob Professional
session_start();
$isAuth = isset($_SESSION['user_id']) || isset($_SESSION['token']);
$userRole = $_SESSION['role'] ?? 'jobseeker';
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SearchJob - Знайдіть роботу своєї мрії в Україні</title>    <meta name="description" content="Провідна платформа пошуку роботи в Україні. Понад 50,000+ вакансій від топових компаній. Знайдіть ідеальну роботу сьогодні!">
    <link rel="stylesheet" href="/frontend/assets/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
          body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            overflow-x: hidden;
            position: relative;
        }
        
        * {
            box-sizing: border-box;
        }
        
        img {
            max-width: 100%;
            height: auto;
        }
        
        *:not(.no-transition) {
            transition-property: transform, opacity, box-shadow;
            transition-duration: 0.3s;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
    
        .theme-toggle {
            background: none;
            border: 2px solid #e1e8ed;
            color: #2c3e50;
            padding: 0.5rem;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .theme-toggle:hover {
            border-color: #eaa850;
            color: #eaa850;
            transform: scale(1.1);
        }
        
        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #eaa850;
            text-decoration: none;
        }
        
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            color: #2c3e50;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }
        
        .nav-link:hover {
            color: #eaa850;
            background: rgba(234, 168, 80, 0.1);
            transform: translateY(-2px);
        }
        
        .nav-auth {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #eaa850, #d4922a);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(234, 168, 80, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(234, 168, 80, 0.4);
        }
        
        .btn-secondary {
            background: transparent;
            color: #2c3e50;
            padding: 0.75rem 1.5rem;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            border-color: #eaa850;
            color: #eaa850;
            transform: translateY(-2px);
        }
        .hero {
            min-height: 100vh;
            background: url('/frontend/assets/images/head_background.jpg') center center/cover no-repeat;
            background-attachment: scroll;
            display: flex;
            align-items: center;
            position: relative;
            padding-top: 80px;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(234, 168, 80, 0.8), rgba(212, 146, 42, 0.7));
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
            max-width: 800px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.1;
            text-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        
        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 3rem;
            opacity: 0.95;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        
        .search-form {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            margin-bottom: 3rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .search-input {
            flex: 1;
            min-width: 250px;
            padding: 1rem 1.5rem;
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #eaa850;
            box-shadow: 0 0 0 4px rgba(234, 168, 80, 0.1);
        }
        
        .search-btn {
            background: linear-gradient(135deg, #eaa850, #d4922a);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(234, 168, 80, 0.3);
        }
        
        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(234, 168, 80, 0.4);
        }
        
        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 3rem;
            flex-wrap: wrap;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            display: block;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        
        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }

        .section-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .section-title {
            font-size: 3rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 3rem;
            color: #2c3e50;
        }
        .partners {
            padding: 100px 0;
            background: #f8fafc;
            overflow: hidden;
        }
        
        .partners .section-title {
            margin-bottom: 4rem;
            text-align: center;
            position: relative;
            z-index: 10;
        }
        
        .partners-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 2rem;
            align-items: center;
            justify-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .partner-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100px;
            width: 100%;
            max-width: 180px;
            position: relative;
            z-index: 1;
        }
        
        .partner-logo:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 8px 30px rgba(234, 168, 80, 0.15);
        }
        
        .partner-logo img {
            max-width: 100px;
            max-height: 50px;
            width: auto;
            height: auto;
            object-fit: contain;            filter: grayscale(100%) opacity(0.7);
            transition: filter 0.3s ease;
        }
          .partner-logo:hover img {
            filter: grayscale(0%) opacity(1);
        }

        .features {
            padding: 100px 0;
            background: white;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 3rem;
        }
        
        .feature-card {
            background: white;
            padding: 3rem 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #f1f5f9;
        }
          .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.12);
            border-color: #eaa850;
            transition: all 0.2s ease;
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #eaa850, #d4922a);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: white;
            font-size: 2rem;
            box-shadow: 0 8px 25px rgba(234, 168, 80, 0.3);
        }
        
        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #2c3e50;
        }
        
        .feature-desc {
            color: #64748b;
            line-height: 1.7;
        }

        .categories {
            padding: 100px 0;
            background: #f8fafc;
        }
        
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .category-card {
            background: white;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid #f1f5f9;
        }
          .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 50px rgba(0,0,0,0.12);
            border-color: #eaa850;
            transition: all 0.2s ease;
        }
        
        .category-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .category-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #eaa850, #d4922a);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }
        
        .category-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2c3e50;
        }
        
        .category-desc {
            color: #64748b;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .category-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #eaa850;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .category-btn:hover {
            transform: translateX(5px);
        }
        
        .cta {
            padding: 100px 0;
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            text-align: center;
        }
          .cta-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            color: white;
        }
        
        .cta-subtitle {
            font-size: 1.25rem;
            margin-bottom: 3rem;
            opacity: 0.9;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            color: white;
        }
        
        .cta-buttons {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }
        
        .footer {
            background: #1a202c;
            color: white;
            padding: 60px 0 30px;
        }
        
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }
        
        .footer-section h4 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #eaa850;
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 0.75rem;
        }
        
        .footer-links a {
            color: #a0aec0;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer-links a:hover {
            color: #eaa850;
        }
        
        .footer-bottom {
            border-top: 1px solid #2d3748;
            padding-top: 2rem;
            text-align: center;
            color: #a0aec0;
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }
            
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.25rem;
            }
            
            .search-form {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-input {
                min-width: 100%;
            }
            
            .hero-stats {
                gap: 2rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .cta-title {
                font-size: 2rem;
            }
            
            .features-grid,
            .categories-grid {
                grid-template-columns: 1fr;
            }
              .partners-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 1.5rem;
            }
            
            .partner-logo {
                height: 80px;
                padding: 1rem;
            }
            
            .partner-logo img {
                max-width: 80px;
                max-height: 40px;
            }
        }
        
        @media (max-width: 480px) {
            .nav-container {
                padding: 1rem;
            }
            
            .hero-content {
                padding: 0 1rem;
            }
            
            .search-form {
                padding: 1.5rem;
            }
            
            .feature-card,
            .category-card {
                padding: 2rem 1.5rem;
            }
              .partners-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            
            .partner-logo {
                height: 70px;
                padding: 0.75rem;
            }
            
            .partner-logo img {
                max-width: 70px;
                max-height: 35px;
            }        }
        
        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #1a202c;
            --text-primary: #2c3e50;
            --text-secondary: #64748b;
            --text-light: #a0aec0;
            --border-color: #e1e8ed;
            --shadow: rgba(0,0,0,0.1);
        }
        
        [data-theme="dark"] {
            --bg-primary: #1a202c;
            --bg-secondary: #2d3748;
            --bg-tertiary: #4a5568;
            --text-primary: #ffffff;
            --text-secondary: #cbd5e0;
            --text-light: #a0aec0;
            --border-color: #4a5568;
            --shadow: rgba(0,0,0,0.3);
        }
        
        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95);
        }
          [data-theme="dark"] .navbar {
            background: rgba(26, 32, 44, 0.95);
        }

        [data-theme="dark"] .nav-container {
            background: transparent;
        }

        [data-theme="dark"] .nav-brand {
            color: #eaa850;
        }
        
        .features {
            background: var(--bg-primary);
        }
        
        .categories, .partners {
            background: var(--bg-secondary);
        }
        
        .feature-card, .category-card, .partner-logo {
            background: var(--bg-primary);
            border-color: var(--border-color);
            box-shadow: 0 8px 30px var(--shadow);
        }
        
        .feature-title, .category-title, .section-title {
            color: var(--text-primary);
        }
        
        .feature-desc, .category-desc {
            color: var(--text-secondary);
        }
        
        .nav-link {
            color: var(--text-primary);
        }
        
        .nav-link:hover {
            background: rgba(234, 168, 80, 0.1);
        }
        
        .btn-secondary {
            color: var(--text-primary);
            border-color: var(--border-color);
        }
        
        .search-form {
            background: rgba(255, 255, 255, 0.95);
        }
        
        [data-theme="dark"] .search-form {
            background: rgba(45, 55, 72, 0.95);
        }
        
        .search-input {
            background: var(--bg-primary);
            color: var(--text-primary);
            border-color: var(--border-color);
        }
          .theme-toggle {
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInFromTop {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% {
                transform: translate3d(0,0,0);
            }
            40%, 43% {
                transform: translate3d(0,-10px,0);
            }
            70% {
                transform: translate3d(0,-5px,0);
            }
            90% {
                transform: translate3d(0,-2px,0);
            }
        }        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }

        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }

        .feature-card.animate-on-scroll {
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-card.animate-on-scroll.animated {
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }

        .hero-content h1 {
            animation: slideInFromTop 1s ease-out 0.3s both;
        }

        .hero-content p {
            animation: slideInFromTop 1s ease-out 0.6s both;
        }

        .hero-content .cta-buttons {
            animation: slideInFromTop 1s ease-out 0.9s both;
        }        .feature-card:hover {
            transform: translateY(-10px) scale(1.05) !important;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15) !important;
            animation: none;
        }

        .category-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .partner-logo:hover {
            animation: bounce 0.8s ease-in-out;
        }

        .btn-primary {
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.6s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .nav-link {
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            width: 0;
            height: 2px;
            background: #eaa850;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .search-input:focus {
            transform: scale(1.02);
            box-shadow: 0 8px 25px rgba(234, 168, 80, 0.3);
        }

        .section-title {
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            width: 60px;
            height: 3px;
            background: linear-gradient(135deg, #eaa850, #d4922a);
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .feature-card:nth-child(1) { animation-delay: 0.1s; }
        .feature-card:nth-child(2) { animation-delay: 0.2s; }
        .feature-card:nth-child(3) { animation-delay: 0.3s; }

        .category-card:nth-child(1) { animation-delay: 0.1s; }
        .category-card:nth-child(2) { animation-delay: 0.2s; }
        .category-card:nth-child(3) { animation-delay: 0.3s; }
        .category-card:nth-child(4) { animation-delay: 0.4s; }

        .partner-logo:nth-child(1) { animation-delay: 0.1s; }
        .partner-logo:nth-child(2) { animation-delay: 0.15s; }
        .partner-logo:nth-child(3) { animation-delay: 0.2s; }
        .partner-logo:nth-child(4) { animation-delay: 0.25s; }
        .partner-logo:nth-child(5) { animation-delay: 0.3s; }
        .partner-logo:nth-child(6) { animation-delay: 0.35s; }
        .partner-logo:nth-child(7) { animation-delay: 0.4s; }

        .partner-logo img {
            transition: all 0.4s ease;
            filter: grayscale(100%);
        }

        .partner-logo:hover img {
            filter: grayscale(0%);
            transform: scale(1.1);
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .cta-buttons .btn-primary {
            animation: float 3s ease-in-out infinite;
        }

        .cta-buttons .btn-secondary {
            animation: float 3s ease-in-out infinite 1.5s;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="/frontend/index.php" class="nav-brand">
                <i class="fas fa-briefcase"></i>
                SearchJob
            </a>
              <div class="nav-menu">
                <a href="/frontend/index.php" class="nav-link">
                    <i class="fas fa-home"></i>
                    Головна
                </a>
                <a href="/frontend/vacancy_list.php" class="nav-link">
                    <i class="fas fa-search"></i>
                    Вакансії
                </a>
                <a href="/frontend/companies_list.php" class="nav-link">
                    <i class="fas fa-building"></i>
                    Компанії
                </a>
                <?php if ($isAuth && $userRole === 'employer'): ?>
                    <a href="/frontend/my_vacancies.php" class="nav-link">
                        <i class="fas fa-list"></i>
                        Мої вакансії
                    </a>
                <?php elseif ($isAuth && $userRole === 'jobseeker'): ?>
                    <a href="/frontend/my_applications.php" class="nav-link">
                        <i class="fas fa-file-alt"></i>
                        Мої заявки
                    </a>
                <?php endif; ?>
            </div>
              <div class="nav-auth">
                <button id="theme-toggle" class="theme-toggle" title="Переключити тему">
                    <i class="fas fa-moon"></i>
                </button>                <?php if (!$isAuth): ?>
                    <a href="/frontend/login.php" class="btn-secondary">Увійти</a>
                    <a href="/frontend/register.php" class="btn-primary">Реєстрація</a>
                <?php else: ?>
                    <a href="/frontend/profile.php" class="nav-link">
                        <i class="fas fa-user"></i>
                        Профіль
                    </a>
                    <a href="/frontend/logout.php" class="btn-secondary">Вихід</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" style="background-image: url('/frontend/assets/images/head_background.jpg');">
        <div class="section-container">
            <div class="hero-content">
                <h1 class="hero-title">Знайдіть роботу своєї мрії</h1>
                <p class="hero-subtitle">
                    Провідна платформа для пошуку роботи в Україні. Понад 50,000+ актуальних вакансій від топових компаній
                </p>
                
                <form class="search-form" action="/frontend/vacancy_list.php" method="GET">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Посада, компанія або ключові слова..."
                        class="search-input"
                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                    >
                    <input 
                        type="text" 
                        name="location" 
                        placeholder="Місто або регіон..."
                        class="search-input"
                        value="<?= htmlspecialchars($_GET['location'] ?? '') ?>"
                    >
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                        Шукати
                    </button>
                </form>
                
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">50K+</span>
                        <span class="stat-label">Активних вакансій</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">15K+</span>
                        <span class="stat-label">Компаній</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">100K+</span>
                        <span class="stat-label">Успішних працевлаштувань</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Partners Section -->
    <section class="partners">
        <div class="section-container">
            <h2 class="section-title">Наші партнери</h2>
            <div class="partners-grid">                <div class="partner-logo">
                    <img src="/frontend/assets/images/aurora.png" alt="Aurora">
                </div>
                <div class="partner-logo">
                    <img src="/frontend/assets/images/foxtrot.png" alt="Foxtrot">
                </div>
                <div class="partner-logo">
                    <img src="/frontend/assets/images/eva.png" alt="EVA">
                </div>
                <div class="partner-logo">
                    <img src="/frontend/assets/images/epicentr.png" alt="Epicentr">
                </div>
                <div class="partner-logo">
                    <img src="/frontend/assets/images/ikea.png" alt="IKEA">
                </div>
                <div class="partner-logo">
                    <img src="/frontend/assets/images/prostor.png" alt="Prostor">
                </div>
                <div class="partner-logo">
                    <img src="/frontend/assets/images/sinsay.png" alt="Sinsay">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="section-container">
            <h2 class="section-title">Чому SearchJob?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3 class="feature-title">Швидкий пошук</h3>
                    <p class="feature-desc">
                        Знаходьте релевантні вакансії за лічені хвилини завдяки розумним фільтрам та алгоритмам машинного навчання
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="feature-title">Перевірені компанії</h3>
                    <p class="feature-desc">
                        Всі роботодавці проходять ретельну верифікацію. Ваша безпека та захист персональних даних - наш пріоритет
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3 class="feature-title">Підтримка 24/7</h3>
                    <p class="feature-desc">
                        Наша команда експертів завжди готова допомогти вам на кожному кроці пошуку роботи
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories">
        <div class="section-container">
            <h2 class="section-title">Популярні категорії</h2>
            <div class="categories-grid">
                <div class="category-card">
                    <div class="category-header">
                        <div class="category-icon">
                            <i class="fas fa-code"></i>
                        </div>
                        <h3 class="category-title">IT та розробка</h3>
                    </div>
                    <p class="category-desc">
                        Front-end, Back-end, Full-stack розробка, DevOps, QA, Data Science
                    </p>
                    <a href="vacancy_list.php?category=it" class="category-btn">
                        Переглянути вакансії
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                
                <div class="category-card">
                    <div class="category-header">
                        <div class="category-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="category-title">Маркетинг</h3>
                    </div>
                    <p class="category-desc">
                        Digital маркетинг, SMM, SEO, контент-маркетинг, PR
                    </p>
                    <a href="vacancy_list.php?category=marketing" class="category-btn">
                        Переглянути вакансії
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                
                <div class="category-card">
                    <div class="category-header">
                        <div class="category-icon">
                            <i class="fas fa-palette"></i>
                        </div>
                        <h3 class="category-title">Дизайн</h3>
                    </div>
                    <p class="category-desc">
                        UI/UX дизайн, графічний дизайн, 3D моделювання, анімація
                    </p>
                    <a href="vacancy_list.php?category=design" class="category-btn">
                        Переглянути вакансії
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                
                <div class="category-card">
                    <div class="category-header">
                        <div class="category-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h3 class="category-title">Інженерія</h3>
                    </div>
                    <p class="category-desc">
                        Машинобудування, будівництво, енергетика, автомобільна промисловість
                    </p>
                    <a href="vacancy_list.php?category=engineering" class="category-btn">
                        Переглянути вакансії
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="section-container">
            <h2 class="cta-title">Готові почати свою кар'єру?</h2>
            <p class="cta-subtitle">
                Приєднуйтесь до тисяч професіоналів, які вже знайшли роботу мрії через SearchJob
            </p>
            
            <div class="cta-buttons">
                <?php if (!$isAuth): ?>
                    <a href="/frontend/register.php" class="btn-primary">
                        <i class="fas fa-user-plus"></i>
                        Створити акаунт
                    </a>
                    <a href="/frontend/vacancy_list.php" class="btn-secondary">
                        <i class="fas fa-search"></i>
                        Шукати роботу
                    </a>
                <?php else: ?>
                    <?php if ($userRole === 'employer'): ?>
                        <a href="/frontend/vacancy_create.php" class="btn-primary">
                            <i class="fas fa-plus"></i>
                            Додати вакансію
                        </a>
                    <?php else: ?>
                        <a href="/frontend/vacancy_list.php" class="btn-primary">
                            <i class="fas fa-search"></i>
                            Знайти роботу
                        </a>
                    <?php endif; ?>
                    <a href="/frontend/profile.php" class="btn-secondary">
                        <i class="fas fa-user"></i>
                        Мій профіль
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="section-container">
            <div class="footer-grid">
                <div class="footer-section">
                    <h4>SearchJob</h4>
                    <p style="color: #a0aec0; margin-bottom: 1.5rem;">
                        Провідна платформа для пошуку роботи в Україні
                    </p>
                    <div style="display: flex; gap: 1rem;">
                        <a href="#" style="color: #eaa850; font-size: 1.25rem;">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" style="color: #eaa850; font-size: 1.25rem;">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" style="color: #eaa850; font-size: 1.25rem;">
                            <i class="fab fa-telegram-plane"></i>
                        </a>
                        <a href="#" style="color: #eaa850; font-size: 1.25rem;">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>Для кандидатів</h4>
                    <ul class="footer-links">
                        <li><a href="/frontend/vacancy_list.php">Пошук вакансій</a></li>
                        <li><a href="/frontend/companies_list.php">Компанії</a></li>
                        <li><a href="/frontend/register.php">Створити резюме</a></li>
                        <li><a href="#">Кар'єрні поради</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Для роботодавців</h4>
                    <ul class="footer-links">
                        <li><a href="/frontend/vacancy_create.php">Додати вакансію</a></li>
                        <li><a href="/frontend/register.php">Реєстрація компанії</a></li>
                        <li><a href="#">Пошук кандидатів</a></li>
                        <li><a href="#">Тарифи</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Підтримка</h4>
                    <ul class="footer-links">
                        <li><a href="mailto:support@searchjob.com">support@searchjob.com</a></li>
                        <li><a href="tel:+380441234567">+380 44 123 45 67</a></li>
                        <li><a href="#">Допомога</a></li>
                        <li><a href="#">Умови використання</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>© 2025 SearchJob. Всі права захищені.</p>
            </div>
        </div>
    </footer>    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('theme-toggle');
            const html = document.documentElement;
            const icon = themeToggle.querySelector('i');
        
            const savedTheme = localStorage.getItem('theme') || 'light';
            html.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);
              themeToggle.addEventListener('click', function() {
                const currentTheme = html.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                
                html.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateThemeIcon(newTheme);
                
                const navbar = document.querySelector('.navbar');
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                if (scrollTop > 100) {
                    if (newTheme === 'dark') {
                        navbar.style.background = 'rgba(26, 32, 44, 0.98)';
                    } else {
                        navbar.style.background = 'rgba(255, 255, 255, 0.98)';
                    }
                } else {
                    if (newTheme === 'dark') {
                        navbar.style.background = 'rgba(26, 32, 44, 0.95)';
                    } else {
                        navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                    }
                }
                
                themeToggle.style.transform = 'rotate(360deg)';
                setTimeout(() => {
                    themeToggle.style.transform = '';
                }, 500);
            });
            
            function updateThemeIcon(theme) {
                if (theme === 'dark') {
                    icon.className = 'fas fa-sun';
                } else {
                    icon.className = 'fas fa-moon';
                }
            }

            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated');
                        
                        if (entry.target.classList.contains('feature-card')) {
                            setTimeout(() => {
                                entry.target.style.transform = 'translateY(0) scale(1)';
                            }, 100);
                        }
                    }
                });
            }, observerOptions);

            const animatedElements = document.querySelectorAll('.feature-card, .category-card, .partner-logo, .section-title, .cta-title, .cta-subtitle');
            animatedElements.forEach((element, index) => {
                element.classList.add('animate-on-scroll');
                observer.observe(element);
            });
            let lastScrollTop = 0;
            let isScrolling = false;
            
            window.addEventListener('scroll', function() {
                if (!isScrolling) {
                    window.requestAnimationFrame(function() {
                        const navbar = document.querySelector('.navbar');
                        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                        const isDarkTheme = document.documentElement.getAttribute('data-theme') === 'dark';
                        
                        if (scrollTop > 100) {
                            if (isDarkTheme) {
                                navbar.style.background = 'rgba(26, 32, 44, 0.98)';
                            } else {
                                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
                            }
                            navbar.style.boxShadow = '0 4px 30px rgba(0,0,0,0.1)';
                            navbar.style.backdropFilter = 'blur(10px)';
                        } else {
                            if (isDarkTheme) {
                                navbar.style.background = 'rgba(26, 32, 44, 0.95)';
                            } else {
                                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                            }
                            navbar.style.boxShadow = '0 2px 20px rgba(0,0,0,0.1)';
                            navbar.style.backdropFilter = 'blur(5px)';
                        }
                        
                        if (scrollTop > lastScrollTop && scrollTop > 200) {
                            navbar.style.transform = 'translateY(-100%)';
                        } else {
                            navbar.style.transform = 'translateY(0)';
                        }
                        
                        lastScrollTop = scrollTop;
                        isScrolling = false;
                    });
                }
                isScrolling = true;
            });

            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            document.querySelectorAll('.btn-primary, .btn-secondary').forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-3px) scale(1.05)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
                
                button.addEventListener('mousedown', function() {
                    this.style.transform = 'translateY(-1px) scale(1.02)';
                });
                
                button.addEventListener('mouseup', function() {
                    this.style.transform = 'translateY(-3px) scale(1.05)';
                });
            });

            const searchForm = document.querySelector('.search-form');
            const searchInput = document.querySelector('.search-input');
            
            if (searchForm && searchInput) {
                searchForm.addEventListener('submit', function(e) {
                    if (!searchInput.value.trim()) {
                        e.preventDefault();
                        searchInput.focus();
                        searchInput.style.borderColor = '#ef4444';
                        searchInput.style.transform = 'scale(1.02) translateX(-5px)';
                        
                        setTimeout(() => {
                            searchInput.style.transform = 'scale(1.02) translateX(5px)';
                        }, 100);
                        setTimeout(() => {
                            searchInput.style.transform = 'scale(1.02) translateX(-2px)';
                        }, 200);
                        setTimeout(() => {
                            searchInput.style.transform = 'scale(1.02) translateX(0)';
                            searchInput.style.borderColor = '';
                        }, 300);
                    } else {
                        searchInput.style.borderColor = '#10b981';
                        searchInput.style.transform = 'scale(1.05)';
                        setTimeout(() => {
                            searchInput.style.transform = 'scale(1)';
                        }, 200);
                    }
                });

                searchInput.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                    this.parentElement.style.boxShadow = '0 10px 30px rgba(234, 168, 80, 0.3)';
                });

                searchInput.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                    this.parentElement.style.boxShadow = '0 8px 30px rgba(0,0,0,0.1)';
                });
            } 
            document.querySelectorAll('.feature-card').forEach((card, index) => {
                card.addEventListener('mouseenter', function() {
                    this.style.animation = 'none';
                    this.style.transform = 'translateY(-15px) scale(1.05)';
                    this.style.boxShadow = '0 25px 50px rgba(0,0,0,0.2)';
                    
                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.style.transform = 'scale(1.3) rotate(5deg)';
                        icon.style.transition = 'all 0.3s ease';
                    }
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                    this.style.boxShadow = '0 10px 40px rgba(0,0,0,0.08)';
                    
                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.style.transform = 'scale(1) rotate(0deg)';
                    }
                });
            });

            document.querySelectorAll('.partner-logo').forEach((logo, index) => {
                logo.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px) scale(1.1)';
                    
                    const ripple = document.createElement('div');
                    ripple.style.position = 'absolute';
                    ripple.style.borderRadius = '50%';
                    ripple.style.background = 'rgba(234, 168, 80, 0.3)';
                    ripple.style.transform = 'scale(0)';
                    ripple.style.animation = 'ripple 0.6s ease-out';
                    ripple.style.top = '50%';
                    ripple.style.left = '50%';
                    ripple.style.width = '100px';
                    ripple.style.height = '100px';
                    ripple.style.marginTop = '-50px';
                    ripple.style.marginLeft = '-50px';
                    
                    this.style.position = 'relative';
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        if (ripple.parentNode) {
                            ripple.parentNode.removeChild(ripple);
                        }
                    }, 600);
                });
                
                logo.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            const rippleCSS = `
                @keyframes ripple {
                    to {
                        transform: scale(2);
                        opacity: 0;
                    }
                }
            `;
            const style = document.createElement('style');
            style.textContent = rippleCSS;
            document.head.appendChild(style);

            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const hero = document.querySelector('.hero');
                if (hero && scrolled < window.innerHeight) {
                    hero.style.transform = `translateY(${scrolled * 0.1}px)`;
                }
            });

            document.querySelectorAll('img').forEach(img => {
                img.addEventListener('load', function() {
                    this.style.opacity = '0';
                    this.style.animation = 'fadeInUp 0.6s ease-out forwards';
                });
            });

            const ctaSection = document.querySelector('.cta');
            if (ctaSection) {
                const ctaObserver = new IntersectionObserver(function(entries) {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const buttons = entry.target.querySelectorAll('.btn-primary, .btn-secondary');
                            buttons.forEach((button, index) => {
                                setTimeout(() => {
                                    button.style.transform = 'translateY(0) scale(1)';
                                    button.style.opacity = '1';
                                }, index * 200);
                            });
                        }
                    });
                }, { threshold: 0.3 });
                
                ctaObserver.observe(ctaSection);
            }
        });
    </script>
</body>
</html>
