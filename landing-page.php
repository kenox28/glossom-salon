<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glosh Beauty Salon</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Poppins & Inter Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    
    <!-- SplitType -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://unpkg.com/split-type@0.3.2/dist/index.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #F233C2;
            --dark: #1F2937;
            --white: #FFFFFF;
            --light-bg: #FAFAFA;
            --light-bg-2: #F8F8F8;
            --light-bg-3: #FCFCFC;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            background: var(--white);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
        }

        /* Typography */
        h1 {
            font-size: 4.2rem;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.025em;
        }

        h2 {
            font-size: 2.8rem;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.015em;
        }

        h3 {
            font-size: 1.875rem;
            font-weight: 700;
            line-height: 1.3;
        }

        h4 {
            font-size: 1.25rem;
            font-weight: 600;
        }

        p {
            font-size: 1.125rem;
            line-height: 1.75;
            color: #6B7280;
        }

        /* Icon base — used everywhere emoji used to be */
        .icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 0;
        }

        .icon svg {
            width: 1em;
            height: 1em;
            display: block;
        }

        /* Navbar */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            padding: 1.2rem 2rem;
            background: linear-gradient(135deg, #F233C2 0%, #E91F96 100%);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(242, 51, 194, 0.25);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        nav.scrolled {
            padding: 0.8rem 2rem;
            box-shadow: 0 12px 48px rgba(242, 51, 194, 0.3);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            list-style: none;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            font-size: 0.95rem;
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 0;
            width: 0;
            height: 2.5px;
            background: rgba(255, 255, 255, 1);
            transition: width 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .nav-links a:hover {
            color: white;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-links a.active {
            color: white;
        }

        .nav-links a.active::after {
            width: 100%;
        }

        .cta-button {
            background: linear-gradient(135deg, #F233C2 0%, #E91F96 100%);
            color: black;
            padding: 0.7rem 1.4rem;
            border-radius: 0.4rem;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            overflow: hidden;
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            cursor: pointer;
            backdrop-filter: blur(10px);
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            transform: translate(-50%, -50%);
            transition: width 0.5s cubic-bezier(0.34, 1.56, 0.64, 1), height 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .cta-button:hover::before {
            width: 300px;
            height: 300px;
        }

        .cta-button:hover {
            transform: translateY(-3px);
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.6);
            box-shadow: 0 15px 40px rgba(242, 51, 194, 0.4), inset 0 0 20px rgba(255, 255, 255, 0.1);
        }

        .secondary-button {
            background: transparent;
            color: var(--primary);
            border: 1.5px solid var(--primary);
            padding: 0.7rem 1.4rem;
            border-radius: 0.4rem;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .secondary-button::after {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--primary);
            z-index: -1;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .secondary-button:hover::after {
            transform: scaleX(1);
        }

        .secondary-button:hover {
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(242, 51, 194, 0.25);
        }

        /* Hamburger toggle (hidden on desktop) */
        .nav-toggle {
            display: none;
            width: 42px;
            height: 42px;
            border: none;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 0.4rem;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 5px;
            flex-shrink: 0;
            transition: background 0.3s ease;
        }

        .nav-toggle:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .nav-toggle .bar {
            width: 22px;
            height: 2.5px;
            background: white;
            border-radius: 2px;
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s ease;
        }

        .nav-toggle.open .bar:nth-child(1) {
            transform: translateY(7.5px) rotate(45deg);
        }

        .nav-toggle.open .bar:nth-child(2) {
            opacity: 0;
        }

        .nav-toggle.open .bar:nth-child(3) {
            transform: translateY(-7.5px) rotate(-45deg);
        }

        .nav-scrim {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 20, 25, 0.35);
            z-index: 45;
            opacity: 0;
            transition: opacity 0.35s ease;
            pointer-events: none;
        }

        .nav-scrim.open {
            display: block;
            opacity: 1;
            pointer-events: auto;
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 4rem;
            overflow: hidden;
            background: linear-gradient(180deg, #FFFFFF 0%, #FCFCFC 50%, #FAFAFA 100%);
            position: relative;
        }

        @media (min-width: 768px) {
            .hero {
                padding-top: 5rem;
            }
        }

        @media (min-width: 1024px) {
            .hero {
                padding-top: 5.5rem;
            }
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -10%;
            right: -5%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle at 40% 40%, rgba(242, 51, 194, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(60px);
            animation: drift 20s ease-in-out infinite;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -10%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle at 60% 60%, rgba(242, 51, 194, 0.06) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(60px);
            animation: drift 25s ease-in-out infinite reverse;
        }

        @keyframes drift {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(30px, 30px); }
        }

        .hero-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            width: 100%;
            padding: 0 1.5rem;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        @media (min-width: 768px) {
            .hero-container {
                grid-template-columns: 1fr 1fr;
                gap: 3rem;
                padding: 0 2rem;
            }
        }

        @media (min-width: 1024px) {
            .hero-container {
                gap: 4rem;
                padding: 0 3rem;
            }
        }

        @media (min-width: 1280px) {
            .hero-container {
                padding: 0 4rem;
            }
        }

        .hero-content h1 {
            margin-bottom: 1.5rem;
            color: var(--dark);
        }

        .hero-content p {
            font-size: 1.25rem;
            color: #6B7280;
            margin-bottom: 2rem;
            line-height: 1.8;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        @media (min-width: 640px) {
            .hero-buttons {
                gap: 1.5rem;
            }
        }

        .hero-visual {
            position: relative;
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (min-width: 768px) {
            .hero-visual {
                height: 350px;
            }
        }

        @media (min-width: 1024px) {
            .hero-visual {
                height: 450px;
            }
        }

        /* Geometric shapes for hero */
        .geometric-shape {
            position: absolute;
            opacity: 0.8;
        }

        .shape-1 {
            width: 280px;
            height: 280px;
            background: linear-gradient(135deg, rgba(242, 51, 194, 0.15) 0%, rgba(242, 51, 194, 0.05) 100%);
            clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
            top: 10%;
            right: 10%;
            animation: float 6s ease-in-out infinite;
        }

        .shape-2 {
            width: 200px;
            height: 200px;
            background: linear-gradient(45deg, rgba(242, 51, 194, 0.1) 0%, rgba(242, 51, 194, 0.02) 100%);
            clip-path: polygon(0% 50%, 50% 0%, 100% 50%, 50% 100%);
            bottom: 20%;
            left: 5%;
            animation: float 8s ease-in-out infinite reverse;
        }

        .shape-3 {
            width: 150px;
            height: 150px;
            background: linear-gradient(225deg, rgba(242, 51, 194, 0.12) 0%, transparent 100%);
            clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);
            top: 50%;
            right: 5%;
            animation: float 7s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(30px); }
        }

        /* Background Image Styling */
        section {
            position: relative;
        }

        section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            z-index: -1;
            opacity: 0.5;
        }

        #overview::before {
            background-image: linear-gradient(135deg, rgba(242, 51, 194, 0.05), rgba(242, 51, 194, 0.02)), 
                              url('glossom.jpg');
            opacity: 0.3;
        }

        #services::before {
            background: none;
        }

        #why::before {
            background-image: linear-gradient(135deg, rgba(242, 51, 194, 0.04), rgba(242, 51, 194, 0.01)),
                              url('glossom.jpg');
            opacity: 0.25;
        }

        #stats::before {
            background: none;
        }

        #gallery::before {
            background-image: linear-gradient(180deg, rgba(242, 51, 194, 0.03), rgba(242, 51, 194, 0.01));
            opacity: 1;
        }

        #appointment::before {
            background-image: linear-gradient(135deg, rgba(242, 51, 194, 0.08), rgba(242, 51, 194, 0.04));
            opacity: 0.4;
        }

        #contact::before {
            background: none;
        }

        section.alt-bg {
            background: rgba(242, 51, 194, 0.02);
        }

        /* Sections */
        section {
            padding: 5.5rem 1.5rem;
            max-width: 100%;
            margin: 0 auto;
            position: relative;
        }

        section > .section-header,
        section > div:not(.booking-form),
        section > form,
        section > p {
            position: relative;
            z-index: 1;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
        }

        section .section-header {
            padding: 0 1rem;
        }

        @media (min-width: 640px) {
            section {
                padding: 5.5rem 2rem;
            }

            section .section-header {
                padding: 0 1.5rem;
            }
        }

        @media (min-width: 1024px) {
            section {
                padding: 6rem 3rem;
            }

            section .section-header {
                padding: 0 2rem;
            }
        }

        @media (min-width: 1280px) {
            section {
                padding: 7rem 4rem;
            }

            section .section-header {
                padding: 0 2.5rem;
            }
        }

        .section-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        @media (min-width: 768px) {
            .section-header {
                margin-bottom: 3rem;
            }
        }

        @media (min-width: 1024px) {
            .section-header {
                margin-bottom: 3.5rem;
            }
        }

        @media (min-width: 1280px) {
            .section-header {
                margin-bottom: 4rem;
            }
        }

        .section-header h2 {
            margin-bottom: 1rem;
            color: var(--dark);
        }

        .section-header p {
            font-size: 1.15rem;
            color: #9CA3AF;
            max-width: 600px;
            margin: 0 auto;
            font-weight: 500;
        }

        /* Business Overview Cards */
        .overview-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            padding: 0 0.5rem;
        }

        @media (min-width: 640px) {
            .overview-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 2rem;
                padding: 0 1rem;
            }
        }

        @media (min-width: 1024px) {
            .overview-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 2.5rem;
                padding: 0 1.5rem;
            }
        }

        .overview-card {
            background: rgba(242, 51, 194, 0.02);
            padding: 2rem;
            border-radius: 0.6rem;
            text-align: center;
            border: 10px solid rgba(242, 51, 194, 0.08);
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
        }

        .overview-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(242, 51, 194, 0.03);
            border: 1px solid rgba(242, 51, 194, 0.1);
            border-radius: 0.6rem;
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            z-index: -1;
        }

        .overview-card:hover {
            transform: translateY(-12px);
        }

        .overview-card:hover::before {
            background: rgba(242, 51, 194, 0.08);
            border-color: rgba(242, 51, 194, 0.3);
            box-shadow: 0 20px 50px rgba(242, 51, 194, 0.12);
        }

        .overview-number {
            font-size: 2.5rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .overview-card h4 {
            font-size: 1.125rem;
            margin-bottom: 0.75rem;
        }

        .overview-card p {
            font-size: 1rem;
            color: #0F1419;
        }

        /* Services Grid */
        .services-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            padding: 0 0.5rem;
        }

        @media (min-width: 640px) {
            .services-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 2rem;
                padding: 0 1rem;
            }
        }

        @media (min-width: 1024px) {
            .services-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 2.5rem;
                padding: 0 1.5rem;
            }
        }

        .service-card {
            background: rgba(242, 51, 194, 0.02);
            padding: 2.2rem 2rem;
            border-radius: 0.8rem;
            border: 1px solid rgba(242, 51, 194, 0.08);
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(242, 51, 194, 0.06), transparent);
            transition: left 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .service-card:hover::before {
            left: 100%;
        }

        .service-card:hover {
            transform: translateY(-14px);
            border-color: rgba(242, 51, 194, 0.3);
            box-shadow: 0 25px 50px rgba(242, 51, 194, 0.1);
            background: rgba(242, 51, 194, 0.04);
        }

        .service-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, rgba(242, 51, 194, 0.12) 0%, rgba(242, 51, 194, 0.04) 100%);
            border: 1px solid rgba(242, 51, 194, 0.15);
            border-radius: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.6rem;
            color: var(--primary);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .service-icon svg {
            width: 26px;
            height: 26px;
        }

        .service-card:hover .service-icon {
            background: linear-gradient(135deg, rgba(242, 51, 194, 0.18) 0%, rgba(242, 51, 194, 0.08) 100%);
            border-color: rgba(242, 51, 194, 0.25);
        }

        .service-card h4 {
            margin-bottom: 0.75rem;
            font-size: 1.25rem;
        }

        .service-card p {
            font-size: 1rem;
            color: #9CA3AF;
            margin-bottom: 1.5rem;
        }

        .service-price {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }

        /* Why Choose Us */
        .features-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            padding: 0 0.5rem;
        }

        @media (min-width: 640px) {
            .features-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 2rem;
                padding: 0 1rem;
            }
        }

        @media (min-width: 1024px) {
            .features-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 2.5rem;
                padding: 0 1.5rem;
            }
        }

        .feature-card {
            background: rgba(242, 51, 194, 0.02);
            padding: 2.5rem 2rem;
            border-radius: 0.8rem;
            border: 1px solid rgba(242, 51, 194, 0.08);
            text-align: center;
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .feature-card:hover {
            border-color: rgba(242, 51, 194, 0.3);
            box-shadow: 0 20px 50px rgba(242, 51, 194, 0.1);
            transform: translateY(-10px);
            background: rgba(242, 51, 194, 0.04);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--primary);
        }

        .feature-icon svg {
            width: 42px;
            height: 42px;
        }

        .feature-card h4 {
            margin-bottom: 0.75rem;
        }

        .feature-card p {
            font-size: 1rem;
            color: #9CA3AF;
        }

        /* Stats Section */
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2.5rem;
            text-align: center;
            padding: 0 0.5rem;
        }

        @media (min-width: 640px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 2rem;
                padding: 0 1rem;
            }
        }

        @media (min-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 3rem;
                padding: 0 1.5rem;
            }
        }

        .stat-item {
            padding: 2rem 0;
        }

        .stat-number {
            font-family: 'Poppins', sans-serif;
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1.125rem;
            color: #6B7280;
            font-weight: 500;
        }

        /* Gallery Preview */
        .gallery-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.2rem;
            grid-auto-rows: 180px;
            padding: 0 0.5rem;
        }

        @media (min-width: 640px) {
            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem;
                grid-auto-rows: 180px;
                padding: 0 1rem;
            }
        }

        @media (min-width: 1024px) {
            .gallery-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 1.8rem;
                grid-auto-rows: 210px;
                padding: 0 1.5rem;
            }
        }

        .gallery-item {
            background: linear-gradient(135deg, rgba(242, 51, 194, 0.08) 0%, rgba(242, 51, 194, 0.02) 100%);
            border-radius: 0.8rem;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 1px solid rgba(242, 51, 194, 0.1);
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .gallery-item:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 60px rgba(242, 51, 194, 0.15);
            border-color: rgba(242, 51, 194, 0.3);
        }

        .gallery-item.tall {
            grid-row: span 1;
        }

        .gallery-item.wide {
            grid-column: span 2;
        }

        .gallery-overlay {
            position: absolute;
            inset: 0;
            background: rgba(242, 51, 194, 0.9);
            opacity: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            transition: opacity 0.3s ease;
            color: white;
        }

        .gallery-overlay svg {
            width: 34px;
            height: 34px;
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, #F233C2 0%, #E91F96 100%);
            color: white;
            text-align: center;
            padding: 3.5rem 1.5rem;
            border-radius: 1.2rem;
            position: relative;
            overflow: hidden;
        }

        @media (min-width: 640px) {
            .cta-section {
                padding: 4.5rem 2rem;
            }
        }

        @media (min-width: 1024px) {
            .cta-section {
                padding: 6rem 3rem;
            }
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -15%;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(40px);
            animation: float 15s ease-in-out infinite;
        }

        .cta-section::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -10%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(40px);
            animation: float 18s ease-in-out infinite reverse;
        }

        .cta-section h2 {
            color: white;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .cta-section > p {
            color: rgba(255, 255, 255, 0.85) !important;
            position: relative;
            z-index: 1;
        }

        .cta-section .cta-button {
            background: rgba(242, 51, 194, 0.9);
            color: var(--primary);
            position: relative;
            z-index: 1;
            font-weight: 700;
            border: none;
        }

        .cta-section .cta-button::before {
            background: rgba(242, 51, 194, 0.2);
        }

        .cta-section .cta-button:hover {
            background: white;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            transform: translateY(-4px);
        }

        /* Contact Section */
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            padding: 0 0.5rem;
        }

        @media (min-width: 640px) {
            .contact-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 2rem;
                padding: 0 1rem;
            }
        }

        @media (min-width: 1024px) {
            .contact-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 2.5rem;
                padding: 0 1.5rem;
            }
        }

        .contact-card {
            background: rgba(242, 51, 194, 0.02);
            padding: 2.2rem;
            border-radius: 0.8rem;
            border: 1px solid rgba(242, 51, 194, 0.08);
            text-align: center;
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .contact-card:hover {
            transform: translateY(-10px);
            border-color: rgba(242, 51, 194, 0.3);
            box-shadow: 0 20px 50px rgba(242, 51, 194, 0.1);
            background: rgba(242, 51, 194, 0.04);
        }

        .contact-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--primary);
        }

        .contact-icon svg {
            width: 34px;
            height: 34px;
        }

        .contact-card h4 {
            margin-bottom: 0.75rem;
        }

        .contact-card p {
            font-size: 1rem;
            color: #6B7280;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1.5rem;
        }

        .social-link {
            width: 44px;
            height: 44px;
            border-radius: 0.6rem;
            background: rgba(242, 51, 194, 0.08);
            border: 1px solid rgba(242, 51, 194, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: var(--primary);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            font-size: 1.1rem;
        }

        .social-link svg {
            width: 19px;
            height: 19px;
        }

        .social-link:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(242, 51, 194, 0.25);
        }

        /* Footer */
        footer {
            background: #0F1419;
            color: white;
            padding: 3.5rem 2rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .footer-section h4 {
            color: white;
            margin-bottom: 1rem;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.75rem;
        }

        .footer-section a {
            color: #D1D5DB;
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.3s ease;
        }

        .footer-section a:hover {
            color: var(--primary);
        }

        .footer-bottom {
            text-align: center;
            color: #9CA3AF;
            font-size: 0.95rem;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            /* Hamburger takes over for tablet + mobile; desktop design untouched */
            .nav-toggle {
                display: flex;
            }

            .nav-links {
                position: fixed;
                top: 0;
                right: 0;
                height: 100vh;
                width: min(78vw, 320px);
                background: linear-gradient(160deg, #F233C2 0%, #E91F96 100%);
                flex-direction: column;
                align-items: flex-start;
                justify-content: flex-start;
                gap: 0;
                padding: 6rem 2rem 2rem;
                transform: translateX(100%);
                transition: transform 0.45s cubic-bezier(0.34, 1.56, 0.64, 1);
                box-shadow: -20px 0 60px rgba(15, 20, 25, 0.25);
                z-index: 46;
            }

            .nav-links.open {
                transform: translateX(0);
            }

            .nav-links li {
                width: 100%;
                border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            }

            .nav-links li:last-child {
                border-bottom: none;
                margin-top: 1.5rem;
            }

            .nav-links a {
                display: block;
                width: 100%;
                padding: 1.1rem 0;
                font-size: 1.05rem;
            }

            .nav-links a::after {
                display: none;
            }

            .nav-links a.cta-button {
                width: 100%;
                text-align: center;
                padding: 0.9rem 1.4rem;
            }
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2.2rem;
            }

            h2 {
                font-size: 1.9rem;
            }

            .hero-container {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .hero-visual {
                height: 280px;
            }

            section {
                padding: 4rem 1.5rem;
            }

            .services-grid,
            .overview-grid,
            .features-grid,
            .contact-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .cta-button,
            .secondary-button {
                width: 100%;
                text-align: center;
            }

            .stat-number {
                font-size: 2.2rem;
            }

            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.2rem;
                grid-auto-rows: 160px;
            }

            .gallery-item.tall,
            .gallery-item.wide {
                grid-column: span 1;
                grid-row: span 1;
            }
        }

        @media (max-width: 640px) {
            .logo {
                font-size: 1.2rem;
            }

            h1 {
                font-size: 1.8rem;
            }

            h2 {
                font-size: 1.4rem;
            }

            .hero {
                padding-top: 5.5rem;
                min-height: 95vh;
            }

            .section-header p {
                font-size: 0.95rem;
            }

            .gallery-grid {
                grid-template-columns: 1fr;
                grid-auto-rows: 180px;
            }

            nav {
                padding: 0.9rem 1.5rem;
            }

            section {
                padding: 3.5rem 1rem;
            }

            .overview-grid,
            .services-grid,
            .features-grid {
                gap: 1.5rem;
            }

            .hero-visual {
                height: 200px;
            }

            .stat-number {
                font-size: 2rem;
            }
        }

        /* Animations */
        .fade-up {
            opacity: 0;
            transform: translateY(30px);
        }

        .reveal {
            opacity: 0;
        }

        /* Booking Form */
        .booking-form {
            background: white;
            padding: 2rem 1.5rem;
            border-radius: 1.2rem;
            border: 1px solid rgba(242, 51, 194, 0.1);
            max-width: 600px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        @media (min-width: 640px) {
            .booking-form {
                padding: 3rem 2rem;
            }
        }

        @media (min-width: 1024px) {
            .booking-form {
                padding: 3.5rem 3rem;
            }
        }

        .booking-form h2 {
            text-align: center;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }

        .booking-form > p {
            text-align: center;
            color: #9CA3AF;
            margin-bottom: 2rem;
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        @media (min-width: 640px) {
            .form-row {
                grid-template-columns: 1fr 1fr;
                gap: 1.5rem;
            }
        }

        .form-row .form-group {
            margin-bottom: 0;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.7rem;
            font-size: 0.95rem;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.9rem 1.2rem;
            border: 1.5px solid rgba(242, 51, 194, 0.15);
            border-radius: 0.6rem;
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            background: rgba(242, 51, 194, 0.02);
            color: var(--dark);
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #D1D5DB;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: rgba(242, 51, 194, 0.4);
            background: white;
            box-shadow: 0 0 0 3px rgba(242, 51, 194, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .services-checkbox-group {
            margin-bottom: 2rem;
        }

        .services-checkbox-group label {
            display: block;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }

        .checkbox-list {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.8rem;
        }

        @media (min-width: 640px) {
            .checkbox-list {
                grid-template-columns: 1fr 1fr;
                gap: 1rem;
            }
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 1rem;
            border: 1.5px solid rgba(242, 51, 194, 0.1);
            border-radius: 0.6rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .checkbox-item:hover {
            border-color: rgba(242, 51, 194, 0.3);
            background: rgba(242, 51, 194, 0.04);
        }

        .checkbox-item input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .checkbox-item label {
            margin: 0;
            cursor: pointer;
            font-weight: 500;
        }

        .form-submit {
            width: 100%;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #F233C2 0%, #E91F96 100%);
            color: white;
            border: none;
            border-radius: 0.6rem;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            overflow: hidden;
        }

        .form-submit::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.5s, height 0.5s;
        }

        .form-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(242, 51, 194, 0.3);
        }

        .form-submit:hover::before {
            width: 300px;
            height: 300px;
        }

        @media (max-width: 640px) {
            .booking-form {
                padding: 2rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .checkbox-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav id="navbar">
        <div class="nav-container">
            <a href="#home" class="logo"><img src="glossom-logo.png" alt="Glossom Logo" height="40px" width="40px"></a>

            <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="navLinks">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>

            <ul class="nav-links" id="navLinks">
                <li><a href="#home" class="nav-link active">Home</a></li>
                <li><a href="#services" class="nav-link">Services</a></li>
                <li><a href="#gallery" class="nav-link">Gallery</a></li>
                <li><a href="#contact" class="nav-link">Contact</a></li>
                <li><a href="#appointment" class="cta-button">Book Now</a></li>
            </ul>
        </div>
    </nav>
    <div class="nav-scrim" id="navScrim"></div>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-container">
            <div class="hero-content">
                <img src="glossom-logo.png" alt="Glosh Beauty Salon" height="100px" width="100px" class="logo-image">
                <h1 class="hero-headline">Glosh Beauty Salon</h1>
                <p>Experience the art of professional beauty and grooming. Our skilled stylists bring precision, passion, and personality to every service.</p>
                <div class="hero-buttons">
                    <button class="cta-button" onclick="scrollToSection('#appointment')">Book Appointment</button>
                    <button class="secondary-button" onclick="scrollToSection('#services')">View Services</button>
                </div>
            </div>
            <div class="hero-visual">
                <div class="geometric-shape shape-1"></div>
                <div class="geometric-shape shape-2"></div>
                <div class="geometric-shape shape-3"></div>
            </div>
        </div>
    </section>

    <!-- Business Overview -->
    <section id="overview" class="alt-bg">
        <div class="section-header">
            <h2>Why Our Clients Love Us</h2>
            <p>Setting the standard for premium barbershop experience</p>
        </div>
        <div class="overview-grid">
            <div class="overview-card fade-up">
                <div class="overview-number">10+</div>
                <h4>Years of Experience</h4>
                <p>A decade of excellence in styling and grooming</p>
            </div>
            <div class="overview-card fade-up">
                <div class="overview-number">12</div>
                <h4>Professional Stylists</h4>
                <p>Certified and passionate about their craft</p>
            </div>
            <div class="overview-card fade-up">
                <div class="overview-number">5★</div>
                <h4>Quality Service</h4>
                <p>Consistently rated excellent by our clients</p>
            </div>
            <div class="overview-card fade-up">
                <div class="overview-number">100%</div>
                <h4>Comfortable Environment</h4>
                <p>Premium facilities and welcoming atmosphere</p>
            </div>
        </div>
    </section>

    <!-- Services Preview -->
    <section id="services">
        <div class="section-header">
            <h2>Our Services</h2>
            <p>Professional grooming solutions tailored to your needs</p>
        </div>
        <div class="services-grid">
            <div class="service-card fade-up">
                <div class="service-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="6" r="2.5"/><circle cx="6" cy="18" r="2.5"/><path d="M8.5 7.5L20 20M20 4L8.5 16.5"/></svg>
                </div>
                <h4>Premium Haircut</h4>
                <p>Classic cuts, fades, and modern styles executed with precision</p>
                <div class="service-price">₱350</div>
            </div>
            <div class="service-card fade-up">
                <div class="service-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3C7 3 3 6.8 3 11.5 3 16 6.5 19 11 19c.6 0 1-.4 1-1 0-.3-.1-.5-.3-.7-.2-.2-.3-.5-.3-.8 0-.6.4-1 1-1h1.6c2.8 0 5-2.2 5-5C19 5.9 15.9 3 12 3z"/><circle cx="7.5" cy="10.5" r="1"/><circle cx="11" cy="7.5" r="1"/><circle cx="15" cy="8.5" r="1"/><circle cx="16.5" cy="12.5" r="1"/></svg>
                </div>
                <h4>Hair Coloring</h4>
                <p>Expert color treatment for transformation and confidence</p>
                <div class="service-price">₱450</div>
            </div>
            <div class="service-card fade-up">
                <div class="service-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3s5 5.5 5 10a5 5 0 0 1-10 0c0-4.5 5-10 5-10z"/></svg>
                </div>
                <h4>Hair Treatment</h4>
                <p>Deep conditioning and rejuvenation for healthy hair</p>
                <div class="service-price">₱500</div>
            </div>
            <div class="service-card fade-up">
                <div class="service-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 10V8a4 4 0 0 1 8 0v2"/><path d="M6 10h12l-1 6.5A4 4 0 0 1 13 20h-2a4 4 0 0 1-4-3.5L6 10z"/><path d="M9.5 14.5c0 1 .7 1.5 1.2 2M14.5 14.5c0 1-.7 1.5-1.2 2"/></svg>
                </div>
                <h4>Beard Grooming</h4>
                <p>Precision beard trim, shaping, and care</p>
                <div class="service-price">₱350</div>
            </div>
            <div class="service-card fade-up">
                <div class="service-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4c3 2 5 5 5 9v7"/><path d="M20 4c-3 2-5 5-5 9v7"/><path d="M9 20h6"/><path d="M6 8h1.5M6 11h2M6 14h2.5M18 8h-1.5M18 11h-2M18 14h-2.5"/></svg>
                </div>
                <h4>Hair Styling</h4>
                <p>Professional styling for special occasions</p>
                <div class="service-price">₱400</div>
            </div>
            <div class="service-card fade-up">
                <div class="service-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l1.8 4.7L18.5 9l-4.7 1.8L12 15.5l-1.8-4.7L5.5 9l4.7-1.3L12 3z"/><path d="M18.5 15.5l.9 2.3 2.3.9-2.3.9-.9 2.3-.9-2.3-2.3-.9 2.3-.9.9-2.3z"/></svg>
                </div>
                <h4>Premium Package</h4>
                <p>Complete grooming experience with consultation</p>
                <div class="service-price">₱1250</div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section id="why" class="alt-bg">
        <div class="section-header">
            <h2>Why Choose Glosh Beauty Salon</h2>
            <p>Excellence in every detail</p>
        </div>
        <div class="features-grid">
            <div class="feature-card fade-up">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.5"/><path d="M5 20c0-3.5 3.1-6 7-6s7 2.5 7 6"/></svg>
                </div>
                <h4>Experienced Barbers</h4>
                <p>Highly trained professionals with years of expertise</p>
            </div>
            <div class="feature-card fade-up">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="5"/><path d="M9 12.5L7 21l5-2.5L17 21l-2-8.5"/></svg>
                </div>
                <h4>Premium Products</h4>
                <p>Only the finest grooming products from top brands</p>
            </div>
            <div class="feature-card fade-up">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h13l4 4v6a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7z"/><path d="M16 7V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v2"/><circle cx="8.5" cy="14.5" r="1.5"/></svg>
                </div>
                <h4>Affordable Pricing</h4>
                <p>Premium quality without breaking the bank</p>
            </div>
            <div class="feature-card fade-up">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9l8-5 8 5v10a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9z"/><path d="M9 20v-6h6v6"/></svg>
                </div>
                <h4>Clean Environment</h4>
                <p>Immaculate facilities and strict hygiene standards</p>
            </div>
            <div class="feature-card fade-up">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="4.5"/><circle cx="12" cy="12" r="1"/></svg>
                </div>
                <h4>Personalized Styling</h4>
                <p>Custom recommendations for your unique look</p>
            </div>
            <div class="feature-card fade-up">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/></svg>
                </div>
                <h4>Quick Turnaround</h4>
                <p>Efficient service without compromising quality</p>
            </div>
        </div>
    </section>

    <!-- Statistics -->
    <section id="stats">
        <div class="section-header">
            <h2>By The Numbers</h2>
            <p>Trusted by thousands of satisfied clients</p>
        </div>
        <div class="stats-grid">
            <div class="stat-item fade-up">
                <div class="stat-number counter" data-target="5000">0</div>
                <div class="stat-label">Happy Clients</div>
            </div>
            <div class="stat-item fade-up">
                <div class="stat-number counter" data-target="15">0</div>
                <div class="stat-label">Services Offered</div>
            </div>
            <div class="stat-item fade-up">
                <div class="stat-number" style="font-size: 3rem;">5★</div>
                <div class="stat-label">Customer Rating</div>
            </div>
            <div class="stat-item fade-up">
                <div class="stat-number counter" data-target="10">0</div>
                <div class="stat-label">Years in Business</div>
            </div>
        </div>
    </section>

    <!-- Gallery Preview -->
    <section id="gallery" class="alt-bg">
        <div class="section-header">
            <h2>Gallery</h2>
            <p>See our latest work and transformations</p>
        </div>
        <div class="gallery-grid">
            <div class="gallery-item">
                <img src="glosh1.jpg" alt="Gallery image" />
            </div>
            <div class="gallery-item tall">
                <img src="glosh2.jpg" alt="Gallery image" />

                <div class="gallery-overlay"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-6.5 10-6.5S22 12 22 12s-3.5 6.5-10 6.5S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg></div>
            </div>
            <div class="gallery-item">
                <img src="glosh3.jpg" alt="Gallery image" />

                <div class="gallery-overlay"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-6.5 10-6.5S22 12 22 12s-3.5 6.5-10 6.5S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg></div>
            </div>
            <div class="gallery-item">
                <img src="glosh4.jpg" alt="Gallery image" />

                <div class="gallery-overlay"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-6.5 10-6.5S22 12 22 12s-3.5 6.5-10 6.5S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg></div>
            </div>
            <div class="gallery-item wide">
                <img src="glosh5.jpg" alt="Gallery image" />

                <div class="gallery-overlay"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-6.5 10-6.5S22 12 22 12s-3.5 6.5-10 6.5S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg></div>
            </div>
            <div class="gallery-item">
                <img src="glosh6.jpg" alt="Gallery image" />

                <div class="gallery-overlay"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-6.5 10-6.5S22 12 22 12s-3.5 6.5-10 6.5S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg></div>
            </div>
            <div class="gallery-item">
                <img src="glosh7.jpg" alt="Gallery image" />

                <div class="gallery-overlay"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-6.5 10-6.5S22 12 22 12s-3.5 6.5-10 6.5S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg></div>
            </div>
            <div class="gallery-item">
                <img src="glosh8.jpg" alt="Gallery image" />

                <div class="gallery-overlay"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-6.5 10-6.5S22 12 22 12s-3.5 6.5-10 6.5S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg></div>
            </div>
            <div class="gallery-item">
                <img src="glosh9.jpg" alt="Gallery image" />

                <div class="gallery-overlay"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-6.5 10-6.5S22 12 22 12s-3.5 6.5-10 6.5S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg></div>
            </div>
            
        </div>
    </section>

    <!-- Appointment Booking Form -->
    <section id="appointment">
        <div class="booking-form">
            <h2>Book Your Appointment</h2>
            <p>Reserve your spot and experience premium grooming services</p>
            
            <form id="bookingForm" onsubmit="handleFormSubmit(event)">
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" placeholder="John" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" placeholder="Doe" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="john@example.com" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="(234) 567-890" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="service_id">Service</label>
                        <select id="service_id" name="service_id" required>
                            <option value="">Select a service</option>
                            <option value="1">Premium Haircut</option>
                            <option value="2">Hair Coloring</option>
                            <option value="3">Hair Treatment</option>
                            <option value="4">Beard Grooming</option>
                            <option value="5">Hair Styling</option>
                            <option value="6">Premium Package</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="preferred_date">Preferred Date</label>
                        <input type="date" id="preferred_date" name="preferred_date" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="preferred_time">Preferred Time</label>
                        <input type="time" id="preferred_time" name="preferred_time" required>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea id="notes" name="notes" placeholder="Tell us about your preferred look or any special requests"></textarea>
                    </div>
                </div>

                <button type="submit" class="form-submit">Book Appointment</button>
            </form>
        </div>
    </section>

    <!-- Contact Preview -->
    <section id="contact">
        <div class="section-header">
            <h2>Get In Touch</h2>
            <p>Visit us or reach out with any questions</p>
        </div>
        <div class="contact-grid">
            <div class="contact-card fade-up">
                <div class="contact-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.5 7-12a7 7 0 0 0-14 0c0 5.5 7 12 7 12z"/><circle cx="12" cy="9" r="2.5"/></svg></div>
                <h4>Address</h4>
                <p>Bonifacio St., Brgy. East Ormoc City.</p>
            </div>
            <div class="contact-card fade-up">
                <div class="contact-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4h3.5l1.5 4-2 1.5a11 11 0 0 0 5.5 5.5l1.5-2 4 1.5V18a2 2 0 0 1-2 2C10.5 20 4 13.5 4 6a2 2 0 0 1 1-2z"/></svg></div>
                <h4>Phone</h4>
                <p><a href="tel:+1234567890" style="color: #6B7280; text-decoration: none;">0966-853-2418, 0910-244-4878, 0953-245-4819</a></p>
            </div>
            <div class="contact-card fade-up">
                <div class="contact-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/></svg></div>
                <h4>Business Hours</h4>
                <p>Mon - Fri: 9:00 AM - 8:00 PM<br>Sat: 10:00 AM - 6:00 PM<br>Sun: Closed</p>
            </div>
            <div class="contact-card fade-up">
                <div class="contact-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.5 3.5 6 3.5 9s-1 6.5-3.5 9c-2.5-2.5-3.5-6-3.5-9s1-6.5 3.5-9z"/></svg></div>
                <h4>Follow Us</h4>
                <div class="social-links">
                    <a href="#" class="social-link" title="Facebook" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 8h-2a2 2 0 0 0-2 2v2H9v3h2v6h3v-6h2.2l.8-3H14v-1.5c0-.6.4-1 1-1h1.5V8z"/></svg></a>
                    <a href="#" class="social-link" title="Instagram" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="4"/><circle cx="12" cy="12" r="3.5"/><circle cx="16.2" cy="7.8" r="0.6" fill="currentColor" stroke="none"/></svg></a>
                    <a href="#" class="social-link" title="Google Maps" aria-label="Google Maps"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.5 7-12a7 7 0 0 0-14 0c0 5.5 7 12 7 12z"/><circle cx="12" cy="9" r="2.5"/></svg></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h4>Glosh Beauty Salon</h4>
                <p style="font-size: 0.95rem; color: #9CA3AF;">Premium salon & barbershop delivering excellence in grooming since 2014.</p>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#gallery">Gallery</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Services</h4>
                <ul>
                    <li><a href="#services">Premium Haircut</a></li>
                    <li><a href="#services">Hair Coloring</a></li>
                    <li><a href="#services">Beard Grooming</a></li>
                    <li><a href="#services">Hair Treatment</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Follow Us</h4>
                <div class="social-links">
                    <a href="#" class="social-link" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 8h-2a2 2 0 0 0-2 2v2H9v3h2v6h3v-6h2.2l.8-3H14v-1.5c0-.6.4-1 1-1h1.5V8z"/></svg></a>
                    <a href="#" class="social-link" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="4"/><circle cx="12" cy="12" r="3.5"/><circle cx="16.2" cy="7.8" r="0.6" fill="currentColor" stroke="none"/></svg></a>
                    <a href="#" class="social-link" aria-label="Twitter"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 5.5c-.7.4-1.5.7-2.3.8.8-.5 1.4-1.3 1.7-2.2-.8.5-1.6.8-2.6 1a3.7 3.7 0 0 0-6.3 3.4C8.7 8.2 6 6.8 4.1 4.5c-.4.7-.6 1.4-.6 2.2 0 1.6.8 2.9 2 3.8-.7 0-1.4-.2-2-.5 0 2.2 1.6 4 3.6 4.4-.4.1-.8.2-1.2.2-.3 0-.6 0-.8-.1.6 1.8 2.2 3 4.2 3.1A7.6 7.6 0 0 1 3 19.4a10.7 10.7 0 0 0 5.8 1.7c7 0 10.8-5.8 10.8-10.8v-.5c.7-.5 1.4-1.2 1.9-2z"/></svg></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Glosh Beauty Salon. All rights reserved. | <a href="#" style="color: #9CA3AF;">Privacy Policy</a> | <a href="#" style="color: #9CA3AF;">Terms of Service</a></p>
        </div>
    </footer>

    <script>
        // Register GSAP plugins
        gsap.registerPlugin(ScrollTrigger);

        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Hamburger menu toggle (mobile / tablet)
        const navToggle = document.getElementById('navToggle');
        const navLinksEl = document.getElementById('navLinks');
        const navScrim = document.getElementById('navScrim');

        function openMenu() {
            navToggle.classList.add('open');
            navLinksEl.classList.add('open');
            navScrim.classList.add('open');
            navToggle.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
        }

        function closeMenu() {
            navToggle.classList.remove('open');
            navLinksEl.classList.remove('open');
            navScrim.classList.remove('open');
            navToggle.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        }

        navToggle.addEventListener('click', () => {
            if (navLinksEl.classList.contains('open')) {
                closeMenu();
            } else {
                openMenu();
            }
        });

        navScrim.addEventListener('click', closeMenu);

        window.addEventListener('resize', () => {
            if (window.innerWidth > 1024) {
                closeMenu();
            }
        });

        // Smooth anchor navigation with active link
        const navLinks = document.querySelectorAll('.nav-link');
        const sections = document.querySelectorAll('section');

        function scrollToSection(selector) {
            const element = document.querySelector(selector);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach((section) => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= sectionTop - 200) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach((link) => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('active');
                }
            });
        });

        navLinks.forEach((link) => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const href = link.getAttribute('href');
                scrollToSection(href);
                closeMenu();
            });
        });

        // Book Now button in the menu also closes the drawer on click
        document.querySelectorAll('.nav-links .cta-button').forEach((btn) => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                scrollToSection('#appointment');
                closeMenu();
            });
        });

        // Fade-up entrance animations (no scroll dragging)
        const fadeUpElements = document.querySelectorAll('.fade-up');

        fadeUpElements.forEach((element, index) => {
            gsap.to(element, {
                scrollTrigger: {
                    trigger: element,
                    start: 'top 85%',
                    once: true,
                },
                opacity: 1,
                y: 0,
                duration: 0.8,
                delay: index * 0.08,
                ease: 'cubic-bezier(0.34, 1.56, 0.64, 1)',
            });
        });

        // Hero headline split animation
        const heroHeadline = document.querySelector('.hero-headline');
        if (heroHeadline) {
            const splitType = new SplitType(heroHeadline, { types: 'words,chars' });
            gsap.from(splitType.chars, {
                opacity: 0,
                y: 20,
                duration: 0.9,
                stagger: 0.04,
                ease: 'cubic-bezier(0.34, 1.56, 0.64, 1)',
            });
        }

        // Removed scroll-driven parallax - using normal smooth scroll instead

        // Animated counters
        const counters = document.querySelectorAll('.counter');
        counters.forEach((counter) => {
            const target = parseInt(counter.dataset.target);
            gsap.to(counter, {
                scrollTrigger: {
                    trigger: counter,
                    start: 'top 80%',
                    toggleActions: 'play none none reverse',
                },
                innerText: target,
                duration: 2.5,
                snap: { innerText: 1 },
                ease: 'cubic-bezier(0.34, 1.56, 0.64, 1)',
                onUpdate: function () {
                    counter.innerText = Math.floor(parseFloat(counter.innerText));
                },
            });
        });

        // Service cards 3D hover effect
        const serviceCards = document.querySelectorAll('.service-card');
        serviceCards.forEach((card) => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const rotateX = (y - centerY) / 15;
                const rotateY = (centerX - x) / 15;

                gsap.to(card, {
                    rotationX: rotateX,
                    rotationY: rotateY,
                    transformOrigin: '50% 50% 0',
                    transformStyle: 'preserve-3d',
                    duration: 0.4,
                    ease: 'cubic-bezier(0.34, 1.56, 0.64, 1)',
                });
            });

            card.addEventListener('mouseleave', () => {
                gsap.to(card, {
                    rotationX: 0,
                    rotationY: 0,
                    duration: 0.5,
                    ease: 'cubic-bezier(0.34, 1.56, 0.64, 1)',
                });
            });
        });

        // Mouse interaction on hero
        const heroVisual = document.querySelector('.hero-visual');
        if (heroVisual) {
            document.addEventListener('mousemove', (e) => {
                const x = e.clientX / window.innerWidth - 0.5;
                const y = e.clientY / window.innerHeight - 0.5;
                gsap.to(heroVisual, {
                    x: x * 25,
                    y: y * 25,
                    duration: 0.6,
                    ease: 'cubic-bezier(0.34, 1.56, 0.64, 1)',
                });
            });
        }

        // Observe for prefers-reduced-motion
        const mediaQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
        if (mediaQuery.matches) {
            gsap.globalTimeline.timeScale(0);
        }

        // Form submission handler
        async function handleFormSubmit(event) {
            event.preventDefault();

            const form = document.getElementById('bookingForm');
            const submitButton = form.querySelector('button[type="submit"]');
            const payload = {
                first_name: document.getElementById('first_name').value.trim(),
                last_name: document.getElementById('last_name').value.trim(),
                email: document.getElementById('email').value.trim(),
                phone: document.getElementById('phone').value.trim(),
                service_id: parseInt(document.getElementById('service_id').value, 10),
                preferred_date: document.getElementById('preferred_date').value,
                preferred_time: document.getElementById('preferred_time').value,
                notes: document.getElementById('notes').value.trim()
            };

            if (!payload.first_name || !payload.last_name || !payload.email || !payload.phone || !payload.service_id || !payload.preferred_date || !payload.preferred_time) {
                alert('Please fill in all required appointment details.');
                return;
            }

            submitButton.disabled = true;
            submitButton.textContent = 'Submitting...';

            try {
                const response = await fetch('api/book.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const result = await response.json();

                const messageBox = document.createElement('div');
                messageBox.style.cssText = `
                    padding: 1.25rem;
                    background: ${response.ok ? 'linear-gradient(135deg, #F233C2 0%, #E91F96 100%)' : '#fee2e2'};
                    color: ${response.ok ? 'white' : '#991b1b'};
                    border-radius: 0.6rem;
                    text-align: center;
                    margin-bottom: 1.5rem;
                    animation: slideDown 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
                `;
                messageBox.innerHTML = `<p style="font-weight: 700; font-size: 1rem; margin: 0;">${result.message || 'Appointment request received.'}</p>`;

                const existingMessage = form.parentElement.querySelector('.booking-response');
                if (existingMessage) {
                    existingMessage.remove();
                }

                messageBox.className = 'booking-response';
                form.parentElement.insertBefore(messageBox, form);

                if (response.ok) {
                    form.reset();
                }
            } catch (error) {
                const messageBox = document.createElement('div');
                messageBox.className = 'booking-response';
                messageBox.style.cssText = `
                    padding: 1.25rem;
                    background: #fee2e2;
                    color: #991b1b;
                    border-radius: 0.6rem;
                    text-align: center;
                    margin-bottom: 1.5rem;
                    animation: slideDown 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
                `;
                messageBox.innerHTML = '<p style="font-weight: 700; font-size: 1rem; margin: 0;">Unable to submit your request right now. Please try again.</p>';

                const existingMessage = form.parentElement.querySelector('.booking-response');
                if (existingMessage) {
                    existingMessage.remove();
                }
                form.parentElement.insertBefore(messageBox, form);
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Book Appointment';
            }
        }
    </script>

    <style>
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-20px);
            }
        }
    </style>
</body>
</html>