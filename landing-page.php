<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prime Cuts - Premium Salon & Barbershop</title>
    
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
            color: #9CA3AF;
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
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
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

        .gallery-item:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 60px rgba(242, 51, 194, 0.15);
            border-color: rgba(242, 51, 194, 0.3);
        }

        .gallery-item.tall {
            grid-row: span 2;
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

            .nav-links {
                gap: 1.2rem;
                font-size: 0.9rem;
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
            .nav-links {
                gap: 0.8rem;
                font-size: 0.8rem;
            }

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
            <ul class="nav-links">
                <li><a href="#home" class="nav-link active">Home</a></li>
                <li><a href="#services" class="nav-link">Services</a></li>
                <li><a href="#gallery" class="nav-link">Gallery</a></li>
                <li><a href="#contact" class="nav-link">Contact</a></li>
                <li><a href="#appointment" class="cta-button">Book Now</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-container">
            <div class="hero-content">
                <h1 class="hero-headline">Premium Haircuts Crafted With Style</h1>
                <p>Experience the art of professional grooming. Our skilled stylists bring precision, passion, and personality to every cut.</p>
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
            <p>Setting the standard for premium barbershop experience since 2014</p>
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
                <div class="service-icon">✂️</div>
                <h4>Premium Haircut</h4>
                <p>Classic cuts, fades, and modern styles executed with precision</p>
                <div class="service-price">$35</div>
            </div>
            <div class="service-card fade-up">
                <div class="service-icon">🎨</div>
                <h4>Hair Coloring</h4>
                <p>Expert color treatment for transformation and confidence</p>
                <div class="service-price">$45</div>
            </div>
            <div class="service-card fade-up">
                <div class="service-icon">💆</div>
                <h4>Hair Treatment</h4>
                <p>Deep conditioning and rejuvenation for healthy hair</p>
                <div class="service-price">$50</div>
            </div>
            <div class="service-card fade-up">
                <div class="service-icon">🧔</div>
                <h4>Beard Grooming</h4>
                <p>Precision beard trim, shaping, and care</p>
                <div class="service-price">$25</div>
            </div>
            <div class="service-card fade-up">
                <div class="service-icon">💇</div>
                <h4>Hair Styling</h4>
                <p>Professional styling for special occasions</p>
                <div class="service-price">$40</div>
            </div>
            <div class="service-card fade-up">
                <div class="service-icon">✨</div>
                <h4>Premium Package</h4>
                <p>Complete grooming experience with consultation</p>
                <div class="service-price">$85</div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section id="why" class="alt-bg">
        <div class="section-header">
            <h2>Why Choose Prime Cuts</h2>
            <p>Excellence in every detail</p>
        </div>
        <div class="features-grid">
            <div class="feature-card fade-up">
                <div class="feature-icon">👨‍💼</div>
                <h4>Experienced Barbers</h4>
                <p>Highly trained professionals with years of expertise</p>
            </div>
            <div class="feature-card fade-up">
                <div class="feature-icon">🏆</div>
                <h4>Premium Products</h4>
                <p>Only the finest grooming products from top brands</p>
            </div>
            <div class="feature-card fade-up">
                <div class="feature-icon">💰</div>
                <h4>Affordable Pricing</h4>
                <p>Premium quality without breaking the bank</p>
            </div>
            <div class="feature-card fade-up">
                <div class="feature-icon">🏪</div>
                <h4>Clean Environment</h4>
                <p>Immaculate facilities and strict hygiene standards</p>
            </div>
            <div class="feature-card fade-up">
                <div class="feature-icon">🎯</div>
                <h4>Personalized Styling</h4>
                <p>Custom recommendations for your unique look</p>
            </div>
            <div class="feature-card fade-up">
                <div class="feature-icon">⏰</div>
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
                <div class="gallery-overlay">👁️</div>
            </div>
            <div class="gallery-item tall">
                <div class="gallery-overlay">👁️</div>
            </div>
            <div class="gallery-item">
                <div class="gallery-overlay">👁️</div>
            </div>
            <div class="gallery-item">
                <div class="gallery-overlay">👁️</div>
            </div>
            <div class="gallery-item wide">
                <div class="gallery-overlay">👁️</div>
            </div>
            <div class="gallery-item">
                <div class="gallery-overlay">👁️</div>
            </div>
            <div class="gallery-item">
                <div class="gallery-overlay">👁️</div>
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
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="firstName" placeholder="John" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastName" placeholder="Doe" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="john@example.com" required>
                </div>

                <div class="services-checkbox-group">
                    <label>Select Services</label>
                    <div class="checkbox-list">
                        <div class="checkbox-item">
                            <input type="checkbox" id="service1" name="services" value="Premium Haircut">
                            <label for="service1">Premium Haircut</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="service2" name="services" value="Hair Coloring">
                            <label for="service2">Hair Coloring</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="service3" name="services" value="Hair Treatment">
                            <label for="service3">Hair Treatment</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="service4" name="services" value="Beard Grooming">
                            <label for="service4">Beard Grooming</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="service5" name="services" value="Hair Styling">
                            <label for="service5">Hair Styling</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="service6" name="services" value="Premium Package">
                            <label for="service6">Premium Package</label>
                        </div>
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
                <div class="contact-icon">📍</div>
                <h4>Address</h4>
                <p>123 Style Avenue<br>Premium City, PC 12345</p>
            </div>
            <div class="contact-card fade-up">
                <div class="contact-icon">📞</div>
                <h4>Phone</h4>
                <p><a href="tel:+1234567890" style="color: #6B7280; text-decoration: none;">+1 (234) 567-890</a></p>
            </div>
            <div class="contact-card fade-up">
                <div class="contact-icon">⏱️</div>
                <h4>Business Hours</h4>
                <p>Mon - Fri: 9:00 AM - 8:00 PM<br>Sat: 10:00 AM - 6:00 PM<br>Sun: Closed</p>
            </div>
            <div class="contact-card fade-up">
                <div class="contact-icon">🌐</div>
                <h4>Follow Us</h4>
                <div class="social-links">
                    <a href="#" class="social-link" title="Facebook">f</a>
                    <a href="#" class="social-link" title="Instagram">📷</a>
                    <a href="#" class="social-link" title="Google Maps">📍</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h4>Prime Cuts</h4>
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
                    <a href="#" class="social-link">f</a>
                    <a href="#" class="social-link">📷</a>
                    <a href="#" class="social-link">🐦</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Prime Cuts. All rights reserved. | <a href="#" style="color: #9CA3AF;">Privacy Policy</a> | <a href="#" style="color: #9CA3AF;">Terms of Service</a></p>
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
        function handleFormSubmit(event) {
            event.preventDefault();
            
            const firstName = document.getElementById('firstName').value;
            const lastName = document.getElementById('lastName').value;
            const email = document.getElementById('email').value;
            const services = Array.from(document.querySelectorAll('input[name="services"]:checked'))
                .map(cb => cb.value);

            if (services.length === 0) {
                alert('Please select at least one service');
                return;
            }

            // Show success message
            const form = document.getElementById('bookingForm');
            const successMessage = document.createElement('div');
            successMessage.style.cssText = `
                padding: 1.5rem;
                background: linear-gradient(135deg, #F233C2 0%, #E91F96 100%);
                color: white;
                border-radius: 0.6rem;
                text-align: center;
                margin-bottom: 1.5rem;
                animation: slideDown 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            `;
            successMessage.innerHTML = `
                <p style="font-weight: 700; font-size: 1.1rem; margin: 0;">✓ Appointment Request Received!</p>
                <p style="margin: 0.5rem 0 0 0; color: rgba(255, 255, 255, 0.9);">We'll contact you at ${email} to confirm.</p>
            `;

            form.parentElement.insertBefore(successMessage, form);
            form.reset();

            // Remove success message after 5 seconds
            setTimeout(() => {
                successMessage.style.animation = 'slideUp 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards';
                setTimeout(() => successMessage.remove(), 500);
            }, 5000);
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
</body>
</html>