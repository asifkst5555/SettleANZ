@extends('layouts.app')

@section('page_styles')
<style>
.migration-page {
    background: linear-gradient(180deg, #f6f8fb 0%, #ffffff 24%, #f7fbfa 100%);
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}
.migration-hero {
    padding: 100px 0;
    background: radial-gradient(circle at 10% 20%, rgba(245, 240, 232, 0.4) 0%, rgba(255, 255, 255, 1) 90%);
    border-bottom: 1px solid #ececec;
}
.migration-hero__inner {
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
}
.migration-hero__badge {
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
.badge-dot-orange {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #e8773a;
}
.migration-hero h1 {
    font-size: clamp(2.2rem, 4.5vw, 3.5rem);
    font-weight: 800;
    color: #065E5B;
    line-height: 1.15;
    letter-spacing: -1.5px;
    margin-bottom: 1.5rem;
}
.migration-hero p {
    font-size: 1.15rem;
    color: #2c3a47;
    line-height: 1.7;
    max-width: 650px;
    margin: 0 auto 2rem;
}
.migration-section {
    padding: 80px 0;
    border-bottom: 1px solid #f2f2f2;
}
.migration-section:last-of-type {
    border-bottom: none;
}
.migration-section__inner {
    max-width: 900px;
    margin: 0 auto;
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
.migration-section h2 {
    font-size: 2rem;
    font-weight: 800;
    color: #065E5B;
    margin-bottom: 1.5rem;
    letter-spacing: -0.8px;
    line-height: 1.25;
}
.migration-section p {
    font-size: 1.05rem;
    color: #2c3a47;
    line-height: 1.8;
    margin-bottom: 1.25rem;
}
.agent-card {
    background: #ffffff;
    border: 1px solid rgba(16, 88, 98, 0.08);
    border-radius: 18px;
    padding: 32px;
    box-shadow: 0 10px 30px rgba(10, 35, 45, 0.04);
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 24px;
    align-items: start;
}
.agent-card__avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0e4f58 0%, #13344f 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 2rem;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    flex-shrink: 0;
}
.agent-card__content h3 {
    font-size: 1.3rem;
    font-weight: 800;
    color: #065E5B;
    margin-bottom: 0.25rem;
}
.agent-card__marn {
    font-size: 0.85rem;
    color: #e8773a;
    font-weight: 600;
    margin-bottom: 0.75rem;
}
.agent-card__content p {
    font-size: 0.95rem;
    color: #607080;
    line-height: 1.6;
    margin: 0;
}
.visa-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-top: 2rem;
}
.visa-card {
    background: #ffffff;
    border: 1px solid rgba(16, 88, 98, 0.08);
    border-radius: 18px;
    padding: 28px;
    box-shadow: 0 10px 30px rgba(10, 35, 45, 0.04);
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
}
.visa-card:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 20px 40px rgba(10, 35, 45, 0.08);
}
.visa-card h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #065E5B;
    margin-bottom: 0.5rem;
}
.visa-card p {
    font-size: 0.92rem;
    color: #607080;
    line-height: 1.6;
    margin: 0;
}
.migration-cta {
    padding: 80px 0 100px;
    background: #f5f0e8;
    text-align: center;
}
.migration-cta__inner {
    max-width: 700px;
    margin: 0 auto;
}
.migration-cta h2 {
    font-size: 2rem;
    font-weight: 800;
    color: #065E5B;
    margin-bottom: 1rem;
}
.migration-cta p {
    font-size: 1.05rem;
    color: #607080;
    line-height: 1.7;
    margin-bottom: 2rem;
}
.cta-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #e8773a;
    color: #ffffff;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 700;
    font-size: 1.05rem;
    padding: 1rem 2rem;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(232, 119, 58, 0.25);
}
.cta-button:hover {
    background: #d3662d;
    color: #ffffff;
}
.cta-button svg {
    width: 20px;
    height: 20px;
    transition: transform 0.2s ease;
}
.cta-button:hover svg {
    transform: translateX(4px);
}
@media (max-width: 767px) {
    .visa-grid { grid-template-columns: 1fr; }
    .agent-card { grid-template-columns: 1fr; text-align: center; }
    .agent-card__avatar { margin: 0 auto; }
}
</style>
@endsection

@section('content')
<div class="migration-page">
    <section class="migration-hero">
        <div class="container">
            <div class="migration-hero__inner">
                <span class="migration-hero__badge">
                    <span class="badge-dot-orange"></span>
                    MIGRATION SERVICES
                </span>
                <h1>Expert Migration Advice You Can Trust</h1>
                <p>Professional visa and migration assistance from Vinit Joshi, a fully registered Migration Agent (MARN: 1386616) operating under the OMARA Code of Conduct.</p>
            </div>
        </div>
    </section>

    <section class="migration-section">
        <div class="container">
            <div class="migration-section__inner">
                <span class="section-badge">
                    <span class="section-badge__dot"></span>
                    Your Agent
                </span>
                <h2>Meet Vinit Joshi</h2>
                <div class="agent-card">
                    <div class="agent-card__avatar">VJ</div>
                    <div class="agent-card__content">
                        <h3>Vinit Joshi</h3>
                        <div class="agent-card__marn">Registered Migration Agent &bull; MARN: 1386616</div>
                        <p>Vinit is a qualified and registered migration agent bound by the OMARA Code of Conduct. He provides professional visa advice and application assistance across a wide range of Australian visa subclasses. Whether you are applying for a skilled visa, sponsoring a partner, or facing a complex refusal or cancellation matter, Vinit offers clear, strategic guidance tailored to your situation.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="migration-section">
        <div class="container">
            <div class="migration-section__inner">
                <span class="section-badge">
                    <span class="section-badge__dot"></span>
                    Visa Pathways
                </span>
                <h2>Visa Services We Assist With</h2>
                <p>Migration law is complex. Applying under the wrong subclass or missing a critical document can cost you time, money, and your visa. Vinit provides professional guidance across these key areas:</p>
                <div class="visa-grid">
                    <div class="visa-card">
                        <h3>Skilled Visas</h3>
                        <p>Subclasses 189, 190, 489, 491, 482, 186. Points-based and employer-sponsored pathways for skilled professionals.</p>
                    </div>
                    <div class="visa-card">
                        <h3>Partner & Family Visas</h3>
                        <p>Onshore and offshore partner visas, parent visas, and other family reunion pathways with full application support.</p>
                    </div>
                    <div class="visa-card">
                        <h3>Student Visas</h3>
                        <p>Subclass 500 student visa applications, including dependent considerations and pathway to permanent residency planning.</p>
                    </div>
                    <div class="visa-card">
                        <h3>Employer Sponsored</h3>
                        <p>TSS (482), ENS (186), and DAMA programs for businesses sponsoring skilled workers from overseas.</p>
                    </div>
                    <div class="visa-card">
                        <h3>Partner Visa</h3>
                        <p>Support for onshore partner visa applications and overcoming common evidentiary challenges.</p>
                    </div>
                    <div class="visa-card">
                        <h3>Complex Cases</h3>
                        <p>AAT/MRT tribunal reviews, ministerial interventions, health and character waivers, NOICC responses, and visa cancellation matters.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="migration-section" style="background: #fafcfa;">
        <div class="container">
            <div class="migration-section__inner">
                <span class="section-badge">
                    <span class="section-badge__dot"></span>
                    Common Pitfalls
                </span>
                <h2>Avoid Cost Mistakes</h2>
                <p>Many applicants lose their visa fees and face lengthy refusals because of avoidable errors. Common mistakes include applying under the incorrect visa subclass, submitting an Expression of Interest (EOI) with incorrect points, failing to provide a persuasive submission or cover letter, and not understanding health or character requirements before lodging.</p>
                <p>A registered migration agent review of your case before you apply can save you thousands of dollars and months of waiting. Vinit Joshi provides structured consultations to assess your eligibility and map out the strongest pathway.</p>
            </div>
        </div>
    </section>

    <section class="migration-cta">
        <div class="container">
            <div class="migration-cta__inner">
                <span class="section-badge">
                    <span class="section-badge__dot"></span>
                    Next Step
                </span>
                <h2>Book a Professional Consultation</h2>
                <p>Migration laws change frequently. A professional consultation with Vinit Joshi is the safest way to understand your options and avoid expensive mistakes. Book your consultation today.</p>
                <a href="{{ route('consultation') }}" class="cta-button">
                    <span>Book Your Consultation</span>
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 11-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
