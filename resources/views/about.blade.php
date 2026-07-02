@extends('layouts.app')

@section('page_styles')
    <style>
        .about-page {
            background: linear-gradient(180deg, #f6f8fb 0%, #ffffff 24%, #f7fbfa 100%);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .about-hero {
            padding: 120px 0;
            background: radial-gradient(circle at 10% 20%, rgba(245, 240, 232, 0.4) 0%, rgba(255, 255, 255, 1) 90%);
            border-bottom: 1px solid #ececec;
        }

        .about-hero__layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
        }

        .about-hero__content {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .about-hero__badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1.5px solid rgba(11, 122, 117, 0.2);
            background: rgba(11, 122, 117, 0.05);
            color: #065E5B;
            font-size: 13.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 8px 18px;
            border-radius: 99px;
            margin-bottom: 24px;
        }

        .badge-accent-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #e8773a;
        }

        .about-hero__title {
            font-size: clamp(2.5rem, 4.8vw, 3.8rem);
            font-weight: 800;
            color: #065E5B;
            line-height: 1.15;
            letter-spacing: -1.5px;
            margin-bottom: 2rem;
        }

        .hero-quote-card {
            background: #ffffff;
            border: var(--border-card, 1px solid rgba(16, 88, 98, 0.08));
            border-radius: var(--radius-card, 18px);
            padding: 32px;
            box-shadow: var(--shadow-card, 0 10px 30px rgba(10, 35, 45, 0.04));
            position: relative;
        }

        .hero-quote-headline {
            font-size: 1.4rem;
            font-weight: 700;
            color: #065E5B;
            line-height: 1.45;
            margin-bottom: 1rem;
            letter-spacing: -0.3px;
        }

        .about-hero__lead {
            font-family: 'Inter', sans-serif;
            font-size: 1.05rem;
            color: #2c3a47;
            line-height: 1.7;
            margin: 0;
        }

        .about-hero__media {
            position: relative;
        }

        .about-hero__image-frame {
            position: relative;
            width: 100%;
            min-height: 528px;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 24px 56px rgba(6, 94, 91, 0.12);
            border: 1px solid rgba(11, 122, 117, 0.12);
        }

        .about-hero__image-frame img {
            display: block;
            width: 100%;
            height: 100%;
            min-height: 528px;
            object-fit: cover;
            object-position: center top;
            transition: transform 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .about-hero__image-frame:hover img {
            transform: scale(1.03);
        }

        .about-hero__image-badge {
            position: absolute;
            bottom: 24px;
            left: 24px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(11, 122, 117, 0.15);
            border-radius: 99px;
            padding: 8px 18px;
            font-family: 'Inter', sans-serif;
            font-size: 0.8rem;
            font-weight: 600;
            color: #065E5B;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
            z-index: 2;
        }

        .badge-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #e8773a;
            position: relative;
        }

        .badge-dot::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background-color: #e8773a;
            opacity: 0.4;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.4; }
            50% { transform: scale(1.6); opacity: 0; }
            100% { transform: scale(1); opacity: 0.4; }
        }

        /* Journey Timeline Styles */
        .about-journey {
            padding: 100px 0;
            background: #ffffff;
            border-bottom: 1px solid #f2f2f2;
        }

        .about-journey__grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 80px;
            align-items: center;
        }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #e6f4f3;
            color: #0b7a75;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 6px 14px;
            border-radius: 99px;
            margin-bottom: 1.5rem;
        }

        .section-badge__dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #e8773a;
        }

        .journey-h2 {
            font-size: 38px;
            font-weight: 800;
            color: #065E5B;
            margin-bottom: 2rem;
            letter-spacing: -0.8px;
            line-height: 1.25;
        }

        .journey-paragraphs p {
            font-family: 'Inter', sans-serif;
            font-size: 1.05rem;
            color: #2c3a47;
            line-height: 1.8;
            margin-bottom: 1.5rem;
        }

        .journey-paragraphs p.journey-lead {
            font-size: 1.25rem;
            font-weight: 600;
            color: #065E5B;
            line-height: 1.6;
        }

        .about-journey__timeline {
            display: flex;
            flex-direction: column;
            gap: 2rem;
            position: relative;
            padding-left: 3.5rem;
            border-left: 3px solid rgba(11, 122, 117, 0.15);
        }

        .timeline-card {
            background: #ffffff;
            border: var(--border-card, 1px solid rgba(16, 88, 98, 0.08));
            border-radius: var(--radius-card, 18px);
            padding: 32px;
            box-shadow: var(--shadow-card, 0 10px 30px rgba(10, 35, 45, 0.04));
            position: relative;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
        }

        .timeline-card:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 20px 40px rgba(10, 35, 45, 0.08);
        }

        .timeline-marker {
            position: absolute;
            left: calc(-3.5rem - 25px);
            top: 2rem;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #0b7a75;
            border: 4px solid #ffffff;
            box-shadow: 0 4px 10px rgba(11, 122, 117, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }

        .timeline-year {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .timeline-content h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #065E5B;
            margin-bottom: 0.5rem;
        }

        .timeline-content p {
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            color: #607080;
            line-height: 1.6;
            margin: 0;
        }

        /* Community Section Styles */
        .about-community {
            padding: 100px 0;
            background: #f5f0e8;
            border-bottom: 1px solid #e0dbd3;
        }

        .about-community__header {
            text-align: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .community-h2 {
            font-size: 38px;
            font-weight: 800;
            color: #065E5B;
            margin-bottom: 1.5rem;
            letter-spacing: -0.8px;
        }

        .community-intro {
            font-family: 'Inter', sans-serif;
            font-size: 1.15rem;
            color: #4A5568;
            line-height: 1.8;
            text-align: center;
        }

        .about-community__grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }

        .community-card {
            background: #ffffff;
            border: var(--border-card, 1px solid rgba(16, 88, 98, 0.08));
            border-radius: var(--radius-card, 18px);
            padding: 32px;
            box-shadow: var(--shadow-card, 0 10px 30px rgba(10, 35, 45, 0.04));
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
        }

        .community-card:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 20px 40px rgba(10, 35, 45, 0.08);
        }

        .community-card__icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: #e6f4f3;
            color: #0b7a75;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .community-card__icon svg {
            width: 30px;
            height: 30px;
        }

        .community-card h3 {
            font-size: 1.4rem;
            font-weight: 800;
            color: #065E5B;
            margin-bottom: 1rem;
        }

        .community-card p {
            font-family: 'Inter', sans-serif;
            font-size: 1.05rem;
            color: #2c3a47;
            line-height: 1.75;
            margin: 0;
        }

        /* Mission Styles */
        .about-mission {
            padding: 100px 0;
            background: #ffffff;
            border-bottom: 1px solid #f2f2f2;
        }

        .about-mission__box {
            max-width: 1170px;
            margin: 0 auto;
            background: linear-gradient(135deg, #0e4f58 0%, #13344f 100%);
            border-radius: 32px;
            padding: 5rem 4rem;
            text-align: center;
            color: #ffffff;
            box-shadow: 0 30px 60px rgba(14, 79, 88, 0.2);
            position: relative;
            overflow: hidden;
        }

        .about-mission__box::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 10% 10%, rgba(255, 255, 255, 0.08), transparent 40%);
            pointer-events: none;
        }

        .mission-quote-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 2.5rem;
            color: #e8773a;
            opacity: 0.9;
        }

        .mission-quote-icon svg {
            width: 100%;
            height: 100%;
        }

        .mission-text {
            font-size: clamp(1.6rem, 3.2vw, 2.2rem);
            font-weight: 700;
            line-height: 1.5;
            margin-bottom: 2.5rem;
            letter-spacing: -0.5px;
            color: #ffffff;
        }

        .mission-divider {
            width: 60px;
            height: 4px;
            background: #e8773a;
            margin: 0 auto 2rem;
            border-radius: 2px;
        }

        .mission-signature {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.85);
            margin: 0;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Outer CTA Panel override styles to keep standard theme */
        .about-cta {
            padding: 100px 0 120px;
            background: #f5f0e8;
        }

        .about-cta__header {
            text-align: center;
            max-width: 800px;
            margin: 0 auto 4rem;
        }

        .cta-h2 {
            font-size: 38px;
            font-weight: 800;
            color: #065E5B;
            margin-bottom: 1.5rem;
            letter-spacing: -0.8px;
        }

        .cta-intro {
            font-family: 'Inter', sans-serif;
            font-size: 1.15rem;
            color: #607080;
            line-height: 1.7;
        }

        .about-cta__grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .cta-card {
            background: #ffffff;
            border: var(--border-card, 1px solid rgba(16, 88, 98, 0.08));
            border-radius: var(--radius-card, 18px);
            padding: 32px;
            box-shadow: var(--shadow-card, 0 10px 30px rgba(10, 35, 45, 0.04));
            display: flex;
            flex-direction: column;
            position: relative;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
        }

        .cta-card:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 20px 40px rgba(10, 35, 45, 0.08);
        }

        .cta-card.highlighted {
            border: 2px solid #0b7a75;
            box-shadow: 0 20px 45px rgba(11, 122, 117, 0.06);
        }

        .cta-card.highlighted::after {
            content: 'Recommended';
            position: absolute;
            top: -14px;
            right: 28px;
            background: #0b7a75;
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 99px;
        }

        .cta-card__badge {
            align-self: flex-start;
            background: rgba(11, 122, 117, 0.08);
            color: #0b7a75;
            font-family: 'Inter', sans-serif;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 4px 10px;
            border-radius: 99px;
            margin-bottom: 1.5rem;
        }

        .cta-card.highlighted .cta-card__badge {
            background: #e8773a;
            color: #ffffff;
        }

        .cta-card h3 {
            font-size: 1.35rem;
            font-weight: 800;
            color: #065E5B;
            margin-bottom: 1rem;
        }

        .cta-card p {
            font-family: 'Inter', sans-serif;
            font-size: 0.98rem;
            color: #2c3a47;
            line-height: 1.7;
            margin-bottom: 2rem;
            flex-grow: 1;
        }

        .cta-card__button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #0b7a75;
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(11, 122, 117, 0.15);
        }

        .cta-card__button svg {
            width: 18px;
            height: 18px;
            transition: transform 0.2s ease;
        }

        .cta-card__button:hover {
            background: #065e5b;
            color: #ffffff;
        }

        .cta-card__button:hover svg {
            transform: translateX(4px);
        }

        .cta-card.highlighted .cta-card__button {
            background: #e8773a;
            box-shadow: 0 4px 12px rgba(232, 119, 58, 0.25);
        }

        .cta-card.highlighted .cta-card__button:hover {
            background: #d3662d;
        }

        /* Responsive Breakpoints */
        @media (max-width: 1080px) {
            .about-hero__layout,
            .about-journey__grid,
            .about-community__grid {
                grid-template-columns: 1fr;
                gap: 3rem;
            }
            
            .about-cta__grid {
                grid-template-columns: 1fr 1fr;
            }
            
            .about-hero__image-frame,
            .about-hero__image-frame img {
                min-height: 418px;
            }
            
            .about-journey__timeline {
                border-left: none;
                padding-left: 0;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1.5rem;
            }
            
            .timeline-marker {
                display: none;
            }
        }

        @media (max-width: 767px) {
            .about-hero {
                padding: 40px 0;
            }

            .about-hero__content {
                align-items: center;
                text-align: center;
            }

            .about-journey__narrative {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .journey-h2,
            .journey-paragraphs p {
                text-align: center;
            }

            .about-journey__timeline {
                grid-template-columns: 1fr;
                align-items: stretch;
            }

            .timeline-card {
                text-align: center;
            }

            .community-card {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 2rem 1.5rem;
            }

            .community-card__icon {
                margin-left: auto;
                margin-right: auto;
            }

            .cta-card {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .cta-card__badge {
                align-self: center;
            }

            .about-cta__grid {
                grid-template-columns: 1fr;
            }
            
            .about-mission__box {
                padding: 3rem 1.5rem;
            }
            
            .about-hero h1 {
                font-size: 2.2rem;
            }
            
            .journey-h2, .community-h2, .cta-h2 {
                font-size: 2rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="about-page">
        <!-- Hero Section -->
        <section id="top" class="about-hero">
            <div class="container about-hero__layout">
                <div class="about-hero__content">
                    <span class="about-hero__badge">
                        <span class="badge-accent-dot"></span>
                        ABOUT SETTLEANZ
                    </span>
                    <h1 class="about-hero__title">SettleANZ: Built on Real Immigrants' Experiences</h1>
                    
                    <div class="hero-quote-card">
                        <h2 class="hero-quote-headline">He Had Faced Enough. So on the Day He Became a Citizen, He Made Himself a Promise.</h2>
                        <p class="about-hero__lead">Years of hidden fees, wrong suburbs, and figuring it out alone. All of it led to one decision: No one else should have to learn this the hard way. SettleANZ was born.</p>
                    </div>
                </div>

                <div class="about-hero__media">
                    <div class="about-hero__image-frame">
                        <img src="{{ asset('media/about/founder.webp') }}" alt="SettleANZ Founder — Built on real immigrant experience" width="600" height="600">
                        <div class="about-hero__image-badge">
                            <span class="badge-dot"></span>
                            <span>Lived Experience</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Journey Section -->
        <section class="about-journey">
            <div class="container">
                <div class="about-journey__grid">
                    <div class="about-journey__narrative">
                        <div class="section-badge">
                            <span class="section-badge__dot"></span>
                            Our Story
                        </div>
                        <h2 class="journey-h2">Our Founder's Journey</h2>
                        <div class="journey-paragraphs">
                            <p class="journey-lead">In 2001, I arrived in Australia with one suitcase and a head full of dreams. I knew no one.</p>
                            <p>I quickly discovered a system not built for newcomers: hidden bank fees, confusing Medicare, and the impossible loop of needing an address for a bank account without a rental history. I worked jobs below my qualifications, signed contracts I barely understood, and questioned my decision every night.</p>
                            <p>What changed everything were small acts of kindness and timely advice: a stranger's explanation, a landlord's trust, a colleague's referral. These moments underscored the profound impact of accurate information. By 2004, as an Australian citizen, I made a promise: no one else should endure such avoidable struggles.</p>
                        </div>
                    </div>
                    
                    <div class="about-journey__timeline">
                        <div class="timeline-card">
                            <div class="timeline-marker">
                                <span class="timeline-year">2001</span>
                            </div>
                            <div class="timeline-content">
                                <h3>Arrived with a Suitcase</h3>
                                <p>Landed alone, navigating hidden bank fees, confusing Medicare, and the address-banking loop.</p>
                            </div>
                        </div>
                        
                        <div class="timeline-card">
                            <div class="timeline-marker">
                                <span class="timeline-year">2004</span>
                            </div>
                            <div class="timeline-content">
                                <h3>The Citizenship Promise</h3>
                                <p>Swore a promise that no other newcomer should have to learn this system the hard way.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Community Section -->
        <section class="about-community">
            <div class="container">
                <div class="about-community__header">
                    <div class="section-badge">
                        <span class="section-badge__dot"></span>
                        Our Growth
                    </div>
                    <h2 class="community-h2">From One Promise to a Community</h2>
                    <p class="community-intro" style="margin-bottom: 1.5rem;">That promise became SettleANZ. Initially, I helped newcomers navigate banking, housing, and the unspoken rules of a new country. Soon, others joined immigrants who had faced similar challenges, and those I had helped who now wanted to pay it forward. What began as one man's commitment evolved into a supportive community of immigrants, each understanding the journey firsthand.</p>
                    <p class="community-intro">Today, SettleANZ helps newcomers settle in their new homeland without confusion. We do this by combining lived experience with proven guidance. Every service we offer is built on real immigrant experiences.</p>
                </div>
            </div>
        </section>

        <!-- Mission Statement -->
        <section class="about-mission">
            <div class="container">
                <div class="about-mission__box">
                    <div class="mission-quote-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                        </svg>
                    </div>
                    <h2 class="mission-text">You shouldn't have to figure it out alone. You shouldn't have to learn the hard way. You deserve clarity, guidance, and support from someone who's been there.</h2>
                    <div class="mission-divider"></div>
                    <p class="mission-signature">— Our Mission</p>
                </div>
            </div>
        </section>

        <!-- Call To Actions -->
        <section class="about-cta">
            <div class="container">
                <div class="about-cta__header">
                    <div class="section-badge">
                        <span class="section-badge__dot"></span>
                        Next Steps
                    </div>
                    <h2 class="cta-h2">Ready to Start Your Settlement Journey?</h2>
                    <p class="cta-intro">Choose how you want to begin. Explore our guides or reach out for custom relocation support.</p>
                </div>
                
                <div class="about-cta__grid">
                    <!-- Card 1 -->
                    <div class="cta-card">
                        <span class="cta-card__badge">Self-Guided</span>
                        <h3>Get Your Free 90-Day Roadmap</h3>
                        <p>Access our comprehensive, step-by-step checklist. Learn the right order to get your bank account, TFN, Medicare, and housing sorted.</p>
                        <a href="{{ route('guides.new-to-australia') }}" class="cta-card__button">
                            <span>Get Your Roadmap</span>
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 11-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                    
                    <!-- Card 2 -->
                    <div class="cta-card highlighted">
                        <span class="cta-card__badge">1-on-1 Help</span>
                        <h3>Book a Free 15-Min Strategy Call</h3>
                        <p>Unsure where to begin or have specific relocation questions? Let's have a quick, free video call to establish your priorities.</p>
                        <a href="{{ route('contact') }}" class="cta-card__button">
                            <span>Book a Free Call</span>
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 11-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                    
                    <!-- Card 3 -->
                    <div class="cta-card">
                        <span class="cta-card__badge">Concierge</span>
                        <h3>Explore Our Services & Pricing</h3>
                        <p>From airport pickups to rental finding and neighbourhood orientation, view our premium support packages and transparent pricing.</p>
                        <a href="{{ route('guides.settlement-services') }}" class="cta-card__button">
                            <span>Explore Services</span>
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 11-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
