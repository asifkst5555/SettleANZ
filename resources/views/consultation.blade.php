@extends('layouts.app')

@section('page_styles')
<style>
.consultation-page {
    background: linear-gradient(180deg, #f6f8fb 0%, #ffffff 24%, #f7fbfa 100%);
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}
.consultation-hero {
    padding: 100px 0;
    background: radial-gradient(circle at 10% 20%, rgba(245, 240, 232, 0.4) 0%, rgba(255, 255, 255, 1) 90%);
    border-bottom: 1px solid #ececec;
    text-align: center;
}
.consultation-hero__inner {
    max-width: 700px;
    margin: 0 auto;
}
.consultation-hero__badge {
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
.badge-dot-orange { width: 8px; height: 8px; border-radius: 50%; background-color: #e8773a; }
.consultation-hero h1 {
    font-size: clamp(2.2rem, 4.5vw, 3.5rem);
    font-weight: 800;
    color: #065E5B;
    line-height: 1.15;
    letter-spacing: -1.5px;
    margin-bottom: 1rem;
}
.consultation-hero p {
    font-size: 1.1rem;
    color: #607080;
    line-height: 1.7;
    max-width: 600px;
    margin: 0 auto;
}
.consultation-content {
    padding: 80px 0;
}
.consultation-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    align-items: start;
}
.info-card {
    background: #ffffff;
    border: 1px solid rgba(16, 88, 98, 0.08);
    border-radius: 18px;
    padding: 32px;
    box-shadow: 0 10px 30px rgba(10, 35, 45, 0.04);
    margin-bottom: 1.5rem;
}
.info-card:last-child { margin-bottom: 0; }
.info-card h2 {
    font-size: 1.2rem;
    font-weight: 800;
    color: #065E5B;
    margin-bottom: 1rem;
}
.info-card p, .info-card li {
    font-size: 0.95rem;
    color: #607080;
    line-height: 1.7;
}
.info-card ul { padding-left: 1.25rem; }
.info-card li { margin-bottom: 0.5rem; }
.consultation-form {
    background: #ffffff;
    border: 1px solid rgba(16, 88, 98, 0.08);
    border-radius: 18px;
    padding: 36px;
    box-shadow: 0 10px 30px rgba(10, 35, 45, 0.04);
}
.consultation-form h2 {
    font-size: 1.3rem;
    font-weight: 800;
    color: #065E5B;
    margin-bottom: 0.5rem;
}
.consultation-form .subtitle {
    font-size: 0.95rem;
    color: #607080;
    margin-bottom: 1.5rem;
    line-height: 1.5;
}
.form-group { margin-bottom: 1.25rem; }
.form-group label {
    display: block;
    font-size: 0.9rem;
    font-weight: 600;
    color: #2c3a47;
    margin-bottom: 0.35rem;
}
.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1.5px solid #dde5e4;
    border-radius: 10px;
    font-family: 'Inter', sans-serif;
    font-size: 0.95rem;
    color: #2c3a47;
    background: #fafcfa;
    transition: border-color 0.2s ease;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #0b7a75;
    background: #ffffff;
}
.form-group textarea { min-height: 100px; resize: vertical; }
.submit-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    background: #e8773a;
    color: #ffffff;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 700;
    font-size: 1.05rem;
    padding: 1rem;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(232, 119, 58, 0.25);
}
.submit-btn:hover { background: #d3662d; }
.urgent-note {
    margin-top: 1.5rem;
    padding: 1rem;
    background: #fff5f0;
    border: 1px solid #fadac8;
    border-radius: 12px;
    font-size: 0.85rem;
    color: #8a4a2a;
    line-height: 1.5;
}
.urgent-note strong { color: #c73a0f; }
@media (max-width: 767px) {
    .consultation-grid { grid-template-columns: 1fr; }
}
</style>
@endsection

@section('content')
<div class="consultation-page">
    <section class="consultation-hero">
        <div class="container">
            <div class="consultation-hero__inner">
                <span class="consultation-hero__badge">
                    <span class="badge-dot-orange"></span>
                    BOOK A CONSULTATION
                </span>
                <h1>Schedule Your Visa Consultation</h1>
                <p>A professional consultation with Vinit Joshi (MARN: 1386616) is the first step toward a confident migration outcome.</p>
            </div>
        </div>
    </section>

    <section class="consultation-content">
        <div class="container">
            <div class="consultation-grid">
                <div>
                    <div class="info-card">
                        <h2>What to Expect</h2>
                        <p>During your consultation, Vinit will review your personal circumstances, discuss your migration goals, and provide a professional assessment of your visa options. You will receive clear, actionable advice on the best pathway forward.</p>
                    </div>
                    <div class="info-card">
                        <h2>What to Prepare</h2>
                        <ul>
                            <li>Your passport and current visa details</li>
                            <li>A summary of your work history and qualifications</li>
                            <li>English language test results (if available)</li>
                            <li>Any correspondence from the Department of Home Affairs</li>
                            <li>Your questions and concerns</li>
                        </ul>
                    </div>
                    <div class="info-card">
                        <h2>Important Note</h2>
                        <p>Migration law is complex and constantly changing. The information provided during a consultation is tailored to your specific situation and should not be replaced by generic online advice. Vinit operates under the OMARA Code of Conduct, ensuring professional and ethical service.</p>
                    </div>
                </div>

                <div>
                    <form class="consultation-form" method="POST" action="{{ route('lead-capture.store') }}">
                        @csrf
                        <h2>Book Your Consultation</h2>
                        <p class="subtitle">Fill in your details and Vinit's team will contact you to schedule your appointment.</p>
                        <input type="hidden" name="source_page" value="consultation-page">
                        <input type="hidden" name="form_type" value="consultation-booking">

                        <div class="form-group">
                            <label for="first_name">First Name *</label>
                            <input type="text" id="first_name" name="first_name" required>
                        </div>

                        <div class="form-group">
                            <label for="last_name">Last Name *</label>
                            <input type="text" id="last_name" name="last_name" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone">
                        </div>

                        <div class="form-group">
                            <label for="visa_type">Visa Type Interested In</label>
                            <select id="visa_type" name="visa_type">
                                <option value="">Select...</option>
                                <option value="skilled">Skilled Migration (189, 190, 491)</option>
                                <option value="employer-sponsored">Employer Sponsored (482, 186)</option>
                                <option value="partner">Partner Visa</option>
                                <option value="student">Student Visa (500)</option>
                                <option value="family">Family Visa</option>
                                <option value="tribunal">AAT/MRT Tribunal Review</option>
                                <option value="cancellation">Visa Cancellation / NOICC</option>
                                <option value="citizenship">Citizenship</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="message">Tell Us About Your Situation</label>
                            <textarea id="message" name="message" placeholder="Briefly describe your immigration goals, current visa status, and any deadlines you are facing..."></textarea>
                        </div>

                        <button type="submit" class="submit-btn">
                            Submit Enquiry
                        </button>

                        <div class="urgent-note">
                            <strong>Urgent matter?</strong> If your appeal deadline is under 7 days, you have received a NOICC, or you are currently overstaying, please mention this in your message. Your enquiry will be flagged for urgent review.
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
