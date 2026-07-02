@extends('layouts.app')

@section('content')
<style>
  /* Base & Utilities Scoped for Homepage */
  .hp-wrapper {
    font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    color: var(--body-text, #2C3A47);
    background: #fff;
    overflow-x: hidden;
  }
  .sec {
    padding: 96px 0;
    border-bottom: 1px solid #f2f2f2;
  }
  @media (max-width: 1024px) {
    .sec { padding: 72px 0; }
  }
  @media (max-width: 767px) {
    .sec { padding: 56px 0; }
    .hero-badge {
      font-size: 10px;
      letter-spacing: 0.4px;
      padding: 4px 10px 4px 5px;
      gap: 6px;
      margin-bottom: 16px;
      white-space: nowrap;
    }
    .badge-icon-wrap {
      width: 20px;
      height: 20px;
    }
    .badge-icon {
      width: 10px;
      height: 10px;
    }
  }
  @media (max-width: 360px) {
    .hero-badge {
      font-size: 9px;
      letter-spacing: 0.3px;
      padding: 3px 8px 3px 4px;
    }
  }
  .sec-sand {
    background: var(--page-background, #F5F0E8);
  }
  .sec-teal {
    background: #065E5B;
    color: #fff;
  }
  .sec-white {
    background: #fff;
  }
  .sec-container {
    max-width: var(--max-width, 1200px);
    margin: 0 auto;
    width: 100%;
    padding-left: 48px;
    padding-right: 48px;
    box-sizing: border-box;
  }
  @media (max-width: 1024px) {
    .sec-container {
      padding-left: 32px;
      padding-right: 32px;
    }
  }
  @media (max-width: 767px) {
    .sec-container {
      padding-left: 20px;
      padding-right: 20px;
    }
  }
  .sec-label {
    font-size: 13px;
    font-weight: 700;
    color: #0B7A75;
    text-transform: uppercase;
    letter-spacing: 2.4px;
    text-align: center;
    margin-bottom: 20px;
  }
  .sec-teal .sec-label {
    color: #9FE1CB;
  }
  .sec-h2 {
    font-size: clamp(2rem, 3.8vw, 2.5rem); /* 36px - 42px */
    font-weight: 800;
    color: #065E5B;
    text-align: center;
    margin-bottom: 20px; /* Heading -> Description is 20px */
    letter-spacing: -0.6px;
    line-height: 1.25;
    max-width: 920px;
    margin-left: auto;
    margin-right: auto;
  }
  .sec-teal .sec-h2 {
    color: #fff;
  }
  .sec-sub {
    font-size: 18px; /* Body: 18px */
    color: #5e7180;
    text-align: center;
    margin-bottom: 32px; /* Heading -> Grid/Content is 32px */
    line-height: 1.7; /* Line height 1.6-1.8 */
    max-width: 660px; /* Limit paragraph width for readability */
    margin-left: auto;
    margin-right: auto;
    font-weight: 400;
  }
  .sec-teal .sec-sub {
    color: #c5e2e1;
  }

  /* ═══════════════════════════════════════════════════════════════
     SECTION 1 — HERO
     ═══════════════════════════════════════════════════════════════ */
  .hero {
    background: #ffffff;
    border-bottom: 1px solid #E2E8F0;
    padding-top: 88px; /* Clears fixed header height exactly */
    position: relative;
    overflow: hidden;
    min-height: 100vh; /* Fills full viewport */
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-sizing: border-box;
  }
  .hero-container {
    width: 100%;
    max-width: 100%;
    margin: 0;
    display: grid;
    grid-template-columns: 1fr 1fr; /* 50% Left Content, 50% Right Image */
    align-items: center; /* Vertically center both columns */
    padding: 0;
    position: relative;
    flex-grow: 1;
    box-sizing: border-box;
  }
  .hero-left {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    padding: 32px 40px 32px max(24px, calc((100vw - 1280px) / 2)); /* Aligns left edge perfectly with centered grid */
    z-index: 3;
    box-sizing: border-box;
  }
  .hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    border: 1px solid #CBD5E1;
    background: #ffffff;
    color: #006B5D;
    font-size: 12.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding: 6px 18px 6px 8px;
    border-radius: 999px;
    margin-bottom: 24px; /* Spacing System: Badge -> Headline */
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
  }
  .badge-icon-wrap {
    width: 26px;
    height: 26px;
    background: #FFF3EB;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .badge-icon {
    width: 14px;
    height: 14px;
    color: #E8773A;
  }
  .hero-h1 {
    font-size: 52px;
    font-weight: 800;
    color: #0A4A45;
    line-height: 1.18;
    margin-bottom: 24px; /* Spacing System: Headline -> Description */
    letter-spacing: -1.4px;
    text-align: left;
    max-width: 600px;
  }
  .hl-orange-wrap {
    display: inline-flex;
    flex-direction: column;
    align-items: flex-start;
    vertical-align: bottom;
  }
  .hl-orange {
    color: #E8773A;
  }
  .hl-orange-bar {
    width: 100%;
    height: 4px;
    background: #E8773A;
    border-radius: 2px;
    margin-top: 2px;
  }
  .hl-dark {
    color: #0A4A45;
  }
  .hero-sub {
    font-size: 17.5px;
    color: #475569;
    line-height: 1.7;
    margin-bottom: 32px; /* Spacing System: Description -> Buttons */
    max-width: 540px;
    font-weight: 400;
    text-align: left;
  }
  .hero-ctas {
    display: flex;
    gap: 16px;
    margin-bottom: 24px; /* Spacing System: Buttons -> Trust Badge */
    align-items: center;
  }
  .btn-primary {
    background: #006B5D;
    color: #ffffff;
    border-radius: var(--radius-button, 24px);
    padding: 14px 32px;
    font-size: 16px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    text-align: center;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    box-shadow: 0 4px 16px rgba(0, 107, 93, 0.25);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    white-space: nowrap;
  }
  .btn-primary:hover {
    background: #005449;
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 8px 24px rgba(0, 107, 93, 0.35);
    color: #ffffff;
  }
  .cta-arrow {
    width: 18px;
    height: 18px;
    transition: transform 0.2s ease;
  }
  .btn-primary:hover .cta-arrow {
    transform: translateX(4px);
  }
  .btn-secondary {
    background: #ffffff;
    color: #006B5D;
    border: 1.5px solid #006B5D;
    border-radius: var(--radius-button, 24px);
    padding: 12.5px 30px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    text-align: center;
    display: inline-block;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    white-space: nowrap;
  }
  .btn-secondary:hover {
    background-color: #F0FDF4;
    transform: translateY(-2px) scale(1.02);
    color: #005449;
  }
  .hero-trust-badge {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    background: #F0FDF4;
    border: 1px solid #DCFCE7;
    border-radius: 999px;
    padding: 9px 22px 9px 12px;
    font-size: 14px;
    color: #166534;
    font-weight: 600;
  }
  .trust-check-wrap {
    width: 22px;
    height: 22px;
    background: #006B5D;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .trust-check {
    width: 12px;
    height: 12px;
    stroke: #ffffff;
  }
  .hero-right {
    position: relative;
    display: flex;
    align-items: flex-start; /* Aligns top of full-bleed image exactly with header bottom edge */
    justify-content: flex-end;
    width: 100%;
    height: 100%;
  }
  .hero-illustration-wrapper {
    position: relative;
    width: 100%;
    height: auto; /* Scales naturally with grid width */
    overflow: hidden;
    -webkit-mask-image: linear-gradient(to right, transparent 0%, black 20%, black 100%);
    mask-image: linear-gradient(to right, transparent 0%, black 20%, black 100%);
  }
  .hero-illustration-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 25%;
    background: linear-gradient(to right, #ffffff 0%, rgba(255, 255, 255, 0) 100%);
    z-index: 2;
    pointer-events: none;
  }
  .hero-illustration-wrapper::after {
    content: '';
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    height: 30%;
    background: linear-gradient(to top, #ffffff 0%, rgba(255, 255, 255, 0) 100%);
    z-index: 2;
    pointer-events: none;
  }
  .hero-illustration-wrapper img {
    width: 100%;
    height: auto; /* Scales height proportionally to preserve aspect ratio without cropping */
    object-fit: cover;
    object-position: center center; /* Center the image content horizontally and vertically to avoid cropping people */
    display: block;
  }

  /* Bottom Corporate Feature Cards Bar */
  .hero-features-container {
    max-width: 1452px;
    margin: -40px auto 0;
    padding: 0 24px 32px;
    width: 100%;
    position: relative;
    z-index: 10;
    box-sizing: border-box;
  }
  .hero-features-bar {
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid #E2E8F0;
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.04);
    padding: 20px 28px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
  }
  .hero-feature-item {
    display: flex;
    align-items: center;
    gap: 16px;
    position: relative;
  }
  .hero-feature-item:not(:last-child)::after {
    content: '';
    position: absolute;
    right: -12px;
    top: 20%;
    height: 60%;
    width: 1px;
    background: #E2E8F0;
  }
  .feature-icon-box {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .feature-icon-teal {
    background: #E6F4F3;
    color: #006B5D;
  }
  .feature-icon-orange {
    background: #FFF3EB;
    color: #E8773A;
  }
  .feature-icon-box svg {
    width: 22px;
    height: 22px;
  }
  .feature-text {
    display: flex;
    flex-direction: column;
  }
  .feature-title {
    font-size: 16px;
    font-weight: 700;
    color: #0A4A45;
    margin: 0 0 3px 0;
    line-height: 1.25;
  }
  .feature-desc {
    font-size: 14px;
    color: #64748B;
    margin: 0;
    line-height: 1.4;
  }
  @media (min-width: 1024px) {
    .desktop-only { display: inline; }
  }
  @media (max-width: 1023px) {
    .desktop-only { display: none; }
  }

  @media (max-width: 1023px) {
    .hero {
      min-height: auto;
      display: block;
      padding: 60px 0 20px 0;
    }
    .hero-container {
      grid-template-columns: 1fr;
      gap: 36px;
      display: grid;
    }
    .hero-left {
      padding: 0 24px;
      align-items: center;
      text-align: center;
    }
    .hero-h1 {
      text-align: center;
      font-size: 40px;
      max-width: 100%;
    }
    .hl-dark {
      white-space: normal;
    }
    .hl-orange-wrap {
      align-items: center;
    }
    .hero-sub {
      text-align: center;
      margin-left: auto;
      margin-right: auto;
    }
    .hero-illustration-wrapper {
      max-width: 100%;
      height: 360px;
      min-height: 315px;
      margin: 0 auto;
      border-radius: 20px;
      -webkit-mask-image: none;
      mask-image: none;
    }
    .hero-illustration-wrapper::before {
      display: none;
    }
    .hero-right {
      padding: 0 24px;
      box-sizing: border-box;
    }
    .hero-features-container {
      margin-top: 30px;
      padding: 0 24px 30px 24px;
    }
    .hero-features-bar {
      grid-template-columns: repeat(2, 1fr);
      gap: 24px;
      padding: 24px;
    }
    .hero-feature-item:not(:last-child)::after {
      display: none;
    }
  }
  @media (max-width: 640px) {
    .hero {
      padding: 70px 0 20px 0;
    }
    .hero-h1 {
      font-size: 32px;
      letter-spacing: -1px;
    }
    .hero-sub {
      font-size: 15.5px;
    }
    .hero-ctas {
      flex-direction: column;
      width: 100%;
      gap: 12px;
    }
    .btn-primary, .btn-secondary {
      width: 100%;
      justify-content: center;
      padding: 14px 20px;
      font-size: 15px;
    }
    .hero-trust-badge {
      border-radius: 16px;
      text-align: left;
    }
    .hero-illustration-wrapper {
      height: 288px;
    }
    .hero-features-bar {
      grid-template-columns: 1fr;
      gap: 20px;
    }
  }

  /* ═══════════════════════════════════════════════════════════════
     SECTION 2 — GET STARTED (Journey Cards - 2x2 Split Layout)
     ═══════════════════════════════════════════════════════════════ */
  .journey-sec {
    padding: 90px 24px;
    background: #ffffff;
  }
  .qe-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 32px;
    max-width: 1280px;
    margin: 0 auto;
  }
  .qe-card {
    border: var(--border-card, 1px solid rgba(16, 88, 98, 0.08));
    border-radius: var(--radius-card, 18px);
    background: #ffffff;
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    overflow: hidden;
    box-shadow: var(--shadow-card, 0 10px 30px rgba(0, 0, 0, 0.04));
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
  }
  .qe-card:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 20px 40px rgba(10, 35, 45, 0.08);
  }
  .qe-card-content {
    padding: 32px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
  .qe-icon-wrap {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: #E6F4F3;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
  }
  .qe-icon-wrap svg {
    width: 24px;
    height: 24px;
    color: #006B5D;
  }
  .qe-tag {
    font-size: 11px;
    font-weight: 800;
    color: #E8773A;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 8px;
  }
  .qe-title {
    font-size: 20px;
    font-weight: 800;
    color: #0A4A45;
    margin-bottom: 12px;
    line-height: 1.3;
  }
  .qe-desc {
    font-size: 13.5px;
    color: #64748B;
    line-height: 1.55;
    margin-bottom: 20px;
  }
  .qe-list {
    list-style: none;
    margin-bottom: 28px;
    padding: 0;
  }
  .qe-list li {
    font-size: 13px;
    color: #334155;
    padding-left: 24px;
    position: relative;
    margin-bottom: 10px;
    line-height: 1.45;
  }
  .qe-list li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 2px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background-color: #E8773A;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23ffffff' stroke-width='3.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='20 6 9 17 4 12'/%3E%3C/svg%3E");
    background-size: 10px;
    background-repeat: no-repeat;
    background-position: center;
  }
  .qe-cta-btn {
    background: #006B5D;
    color: #ffffff;
    border-radius: 8px;
    padding: 12px 20px;
    font-size: 13.5px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    width: fit-content;
    transition: background 0.2s ease, transform 0.2s ease;
    margin-top: auto;
  }
  .qe-cta-btn:hover {
    background: #005449;
    color: #ffffff;
    transform: translateY(-1px);
  }
  .qe-cta-arrow {
    width: 14px;
    height: 14px;
  }
  .qe-card-image {
    position: relative;
    width: 100%;
    height: 100%;
    min-height: 100%;
    overflow: hidden;
  }
  .qe-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  @media (max-width: 1199px) {
    .qe-card {
      grid-template-columns: 1fr;
    }
    .qe-card-image {
      height: 240px;
      min-height: 240px;
    }
  }
  @media (max-width: 900px) {
    .qe-grid {
      grid-template-columns: 1fr;
      gap: 24px;
    }
  }
  @media (max-width: 640px) {
    .qe-card-content {
      padding: 24px 20px;
    }
    .qe-title {
      font-size: 18px;
    }
    .qe-cta-btn {
      width: 100%;
      justify-content: center;
    }
  }

  /* ═══════════════════════════════════════════════════════════════
     SECTION 3 — PROBLEM REFRAME
     ═══════════════════════════════════════════════════════════════ */
  .pr-grid {
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    gap: 80px;
    align-items: center;
    max-width: 1152px;
    margin: 0 auto;
  }
  .pr-title {
    font-size: 36px;
    font-weight: 800;
    color: #065E5B;
    line-height: 1.4;
    margin-bottom: 36px;
    letter-spacing: -0.4px;
  }
  .pr-list {
    list-style: none;
    margin-bottom: 36px;
    padding: 0;
  }
  .pr-list li {
    font-size: 18px;
    color: #5e7180;
    padding: 12px 0 12px 36px;
    position: relative;
    line-height: 1.6;
  }
  .pr-list li::before {
    content: '✕';
    position: absolute;
    left: 0;
    top: 12px;
    color: #e8773a;
    font-weight: 700;
    font-size: 16px;
  }
  .pr-reframe {
    background: #E6F4F3;
    border-left: 5px solid #0B7A75;
    border-radius: 0 16px 16px 0;
    padding: 28px 32px;
    font-size: 21px;
    color: #065E5B;
    font-weight: 700;
    line-height: 1.65;
  }
  .pr-visual {
    background: #fff;
    border: 1px solid #e8edf2;
    border-radius: 20px;
    padding: 52px 48px;
    display: flex;
    flex-direction: column;
    gap: 28px;
  }
  .pr-step {
    display: flex;
    align-items: center;
    gap: 24px;
  }
  .pr-step-num {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #0B7A75;
    color: #fff;
    font-size: 18px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .pr-step-num.done {
    background: #E6F4F3;
    color: #0B7A75;
  }
  .pr-step-text {
    font-size: 18px;
    color: #3a4a57;
    font-weight: 600;
  }
  .pr-step-line {
    width: 3px;
    height: 26px;
    background: #e0e8e8;
    margin-left: 23px;
    margin-top: -14px;
    margin-bottom: -14px;
  }

  @media (max-width: 1023px) {
    .pr-grid {
      grid-template-columns: 1fr;
      gap: 40px;
    }
    .pr-title {
      font-size: 28px;
      margin-bottom: 24px;
    }
    .pr-list li {
      font-size: 16px;
      padding: 8px 0 8px 28px;
    }
    .pr-reframe {
      font-size: 18px;
      padding: 20px 24px;
    }
    .pr-visual {
      padding: 36px 24px;
    }
  }

  /* ═══════════════════════════════════════════════════════════════
     SECTION 4 — SERVICES (Settlement Journey)
     ═══════════════════════════════════════════════════════════════ */
  .jt-track {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 32px;
  }
  .jt-card {
    display: flex;
    flex-direction: column;
  }
  .jt-stage-label {
    font-size: 12.5px;
    font-weight: 800;
    color: #E8773A;
    text-transform: uppercase;
    letter-spacing: 2px;
    text-align: center;
    margin-bottom: 16px;
  }
  .jt-img {
    height: 200px;
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 26px;
  }
  .jt-img svg,
  .jt-img img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
  }
  .jt-num-row {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: -30px;
    position: relative;
    z-index: 2;
  }
  .jt-num {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #fff;
    border: 3px solid #0B7A75;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
  }
  .jt-body {
    background: #fff;
    border: 1px solid #e8edf2;
    border-radius: 20px;
    padding: 50px 26px 30px;
    text-align: center;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .jt-body:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.05);
  }
  .jt-title {
    font-size: 23px;
    font-weight: 800;
    color: #065E5B;
    margin-bottom: 14px;
  }
  .jt-desc {
    font-size: 14.5px;
    color: #5e7180;
    line-height: 1.75;
    margin-bottom: 22px;
    font-weight: 400;
    min-height: 120px;
    flex-grow: 1;
    text-align: left;
  }
  .jt-link {
    font-size: 15px;
    color: #0B7A75;
    font-weight: 700;
    text-decoration: none;
    display: inline-block;
    margin-top: auto;
    transition: color 0.2s;
  }
  .jt-link:hover {
    color: #E8773A;
  }

  @media (max-width: 1023px) {
    .jt-track {
      grid-template-columns: repeat(2, 1fr);
      gap: 32px;
    }
  }
  @media (max-width: 767px) {
    .jt-track {
      grid-template-columns: 1fr;
      gap: 40px;
    }
    .jt-body {
      padding: 45px 20px 25px;
    }
    .jt-desc {
      min-height: auto;
    }
  }

  /* ═══════════════════════════════════════════════════════════════
     SECTION 5 — STATS COUNTER BAR
     ═══════════════════════════════════════════════════════════════ */
  .hp-counter-section {
    background: #065E5B;
    padding: 60px 24px;
    border-bottom: 1px solid #044340;
    position: relative;
    overflow: hidden;
  }
  .hp-counter-grid {
    display: flex;
    justify-content: space-around;
    align-items: center;
    max-width: 1152px;
    margin: 0 auto;
    flex-wrap: wrap;
    gap: 32px;
  }
  .hp-counter-item {
    text-align: center;
    flex: 1;
    min-width: 200px;
  }
  .hp-counter-value {
    font-size: 48px;
    font-weight: 800;
    color: #fff;
    display: inline-block;
  }
  .hp-counter-suffix {
    color: #E8773A;
    font-size: 48px;
    font-weight: 800;
    display: inline-block;
    margin-left: 2px;
  }
  .hp-counter-label {
    font-size: 13px;
    color: #a8d4d2;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-weight: 700;
    margin-top: 8px;
    display: block;
  }
  .hp-counter-divider {
    width: 1px;
    height: 60px;
    background: rgba(255, 255, 255, 0.15);
  }
  @media (max-width: 767px) {
    .hp-counter-divider {
      display: none;
    }
    .hp-counter-grid {
      flex-direction: column;
      gap: 40px;
    }
    .hp-counter-value, .hp-counter-suffix {
      font-size: 38px;
    }
  }

  /* Partner logos strip — infinite marquee */
  .partner-strip {
    background: #ffffff;
    padding: 40px 0;
    border-bottom: 1px solid #e8edf2;
    overflow: hidden;
    position: relative;
  }
  .partner-strip::before,
  .partner-strip::after {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    width: 120px;
    z-index: 2;
    pointer-events: none;
  }
  .partner-strip::before {
    left: 0;
    background: linear-gradient(90deg, #ffffff, transparent);
  }
  .partner-strip::after {
    right: 0;
    background: linear-gradient(270deg, #ffffff, transparent);
  }
  .partner-strip__track {
    display: flex;
    width: max-content;
    animation: marquee-scroll 20s linear infinite;
  }
  .partner-strip:hover .partner-strip__track {
    animation-play-state: paused;
  }
  @keyframes marquee-scroll {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
  }
  .partner-strip__group {
    display: flex;
    align-items: center;
    flex-shrink: 0;
  }
  .partner-strip__logo {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    padding: 0 40px;
  }
  .partner-strip__logo img {
    max-height: 36px;
    width: auto;
    filter: grayscale(100%);
    opacity: 0.55;
    transition: filter 0.3s, opacity 0.3s;
  }
  .partner-strip__logo img:hover {
    filter: none;
    opacity: 1;
  }

  /* ═══════════════════════════════════════════════════════════════
     SECTION 6 — ROADMAP LEAD (Updated Layout)
     ═══════════════════════════════════════════════════════════════ */
  .roadmap-sec {
    padding: 90px 24px;
    background: #FAF9F6;
  }
  .rm-header-wrap {
    text-align: center;
    margin-bottom: 48px;
  }
  .rm-story-text {
    max-width: 1100px;
    margin: 0 auto;
    font-size: 17px;
    color: #475569;
    line-height: 1.75;
    text-align: center;
  }
  .rm-story-text p {
    margin-bottom: 16px;
  }
  .rm-bold-tag {
    font-weight: 800;
    color: #0A4A45 !important;
    font-size: 19px;
    margin-top: 20px;
  }
  .rm-box-container {
    background: #ffffff;
    border: 1px solid #E2E8F0;
    border-radius: 24px;
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    overflow: hidden;
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.04);
    align-items: center;
    max-width: 1150px;
    margin: 0 auto;
  }
  .rm-box-left {
    padding: 48px 44px;
  }
  .rm-box-title {
    font-size: 22px;
    font-weight: 800;
    color: #0A4A45;
    margin-bottom: 24px;
    line-height: 1.3;
  }
  .rm-box-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  .rm-box-list li {
    font-size: 15px;
    color: #334155;
    padding-left: 22px;
    position: relative;
    margin-bottom: 18px;
    line-height: 1.5;
  }
  .rm-box-list li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 7px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #E8773A;
  }
  .rm-box-right {
    background: #F8FAF8;
    padding: 40px 32px;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    border-left: 1px solid #F1F5F9;
    box-sizing: border-box;
  }
  .rm-form-card {
    background: #ffffff;
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    padding: 36px 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    width: 100%;
    max-width: 420px;
  }
  .rm-form-title {
    font-size: 20px;
    font-weight: 800;
    color: #0A4A45;
    margin-bottom: 24px;
    text-align: center;
    line-height: 1.3;
  }
  .rm-input {
    width: 100%;
    border: 1.5px solid #CBD5E1;
    border-radius: 10px;
    padding: 14px 18px;
    font-size: 15px;
    margin-bottom: 16px;
    color: #1E293B;
    display: block;
    outline: none;
    transition: border-color 0.2s;
    box-sizing: border-box;
  }
  .rm-input:focus {
    border-color: #006B5D;
  }
  .rm-btn {
    width: 100%;
    background: #E8773A;
    color: #ffffff;
    border: none;
    border-radius: 10px;
    padding: 15px;
    font-size: 16px;
    font-weight: 700;
    text-align: center;
    margin-bottom: 14px;
    cursor: pointer;
    transition: background-color 0.2s, transform 0.2s;
  }
  .rm-btn:hover {
    background: #d6682d;
    transform: translateY(-1px);
  }
  .rm-note {
    font-size: 12.5px;
    color: #64748B;
    text-align: center;
    line-height: 1.4;
  }
  .rm-bottom-callout {
    margin-top: 36px;
    text-align: center;
  }
  .rm-bottom-callout span {
    display: inline-block;
    background: #FFF3EB;
    border: 1px solid #FDE6D8;
    color: #C25E26;
    font-size: 15px;
    font-weight: 700;
    padding: 12px 28px;
    border-radius: 999px;
  }

  @media (max-width: 991px) {
    .rm-box-container {
      grid-template-columns: 1fr;
    }
    .rm-box-right {
      border-left: none;
      border-top: 1px solid #F1F5F9;
      padding: 36px 20px;
    }
    .rm-box-left {
      padding: 36px 24px;
    }
  }

  /* ═══════════════════════════════════════════════════════════════
     SECTION 7 — BRAND STORY
     ═══════════════════════════════════════════════════════════════ */
  .sec-teal {
    background: linear-gradient(135deg, #065E5B 0%, #033f3d 100%);
    color: #fff;
    position: relative;
  }
  .sec-teal::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 80% 20%, rgba(232, 119, 58, 0.08) 0%, rgba(232, 119, 58, 0) 50%);
    pointer-events: none;
  }
  .sec-teal p,
  .sec-teal li {
    color: rgba(255, 255, 255, 0.88) !important;
  }
  .sec-teal h2,
  .sec-teal h3 {
    color: #ffffff !important;
  }
  .bs-wrap {
    max-width: 1152px;
    margin: 0 auto;
    text-align: center;
    position: relative;
    z-index: 1;
  }
  .bs-eyebrow {
    font-size: 13px;
    font-weight: 700;
    color: #9FE1CB !important;
    text-transform: uppercase;
    letter-spacing: 2.4px;
    margin-bottom: 26px;
  }
  .bs-h {
    font-size: 42px;
    font-weight: 800;
    color: #fff;
    line-height: 1.45;
    margin-bottom: 50px;
    letter-spacing: -0.6px;
    max-width: 880px;
    margin-left: auto;
    margin-right: auto;
  }
  .bs-h .ora {
    color: #E8773A !important;
  }
  .bs-content-grid {
    display: grid;
    grid-template-columns: 0.95fr 1.05fr;
    gap: 48px;
    align-items: center;
    text-align: left;
    margin-bottom: 64px;
    max-width: 1152px;
    margin-left: auto;
    margin-right: auto;
  }
  .bs-content-left {
    display: flex;
    flex-direction: column;
  }
  .bs-image-card {
    border-radius: 24px;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    height: 192px;
    margin-bottom: 24px;
  }
  .bs-image-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }
  .bs-quote-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 24px;
    padding: 40px;
    position: relative;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
  }
  .bs-quote-icon {
    font-size: 80px;
    line-height: 1;
    color: rgba(232, 119, 58, 0.25);
    position: absolute;
    top: -10px;
    left: 24px;
    font-family: serif;
  }
  .bs-quote-text {
    font-size: 21px;
    font-weight: 600;
    line-height: 1.6;
    color: #ffffff !important;
    position: relative;
    z-index: 1;
    margin-bottom: 20px;
  }
  .bs-quote-author {
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: #E8773A !important;
  }
  .bs-content-right {
    font-size: 17px;
    line-height: 1.85;
    color: rgba(255, 255, 255, 0.88) !important;
  }
  .bs-content-right p {
    color: rgba(255, 255, 255, 0.88) !important;
    margin-bottom: 20px;
  }
  .bs-content-right p strong {
    color: #ffffff;
  }
  .bs-pillars {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 32px;
    margin-bottom: 56px;
  }
  .bs-pillar {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 20px;
    padding: 44px 30px;
    text-align: center;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s, background 0.3s, box-shadow 0.3s;
  }
  .bs-pillar:hover {
    transform: translateY(-5px);
    background: rgba(255,255,255,0.07);
    box-shadow: 0 24px 48px rgba(0, 0, 0, 0.2);
  }
  .bs-pillar-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: rgba(232,119,58,0.18);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
    font-size: 28px;
  }
  .bs-pillar-title {
    font-size: 20px;
    font-weight: 800;
    color: #fff !important;
    margin-bottom: 14px;
    line-height: 1.3;
  }
  .bs-pillar-text {
    font-size: 15px;
    color: #a8d4d2 !important;
    line-height: 1.75;
    font-weight: 400;
  }
  .bs-disclaimer {
    font-size: 13.5px;
    color: #6aadaa !important;
    line-height: 1.8;
    font-style: italic;
    margin-bottom: 46px;
    max-width: 760px;
    margin-left: auto;
    margin-right: auto;
  }
  .bs-cta {
    display: inline-block;
    background: #E8773A;
    color: #fff !important;
    border-radius: 12px;
    padding: 19px 44px;
    font-size: 17px;
    font-weight: 700;
    text-decoration: none;
    transition: background-color 0.2s;
  }
  .bs-cta:hover {
    background-color: #d3662d;
    color: #fff !important;
  }

  @media (max-width: 1023px) {
    .bs-h {
      font-size: 32px;
      margin-bottom: 36px;
    }
    .bs-content-grid {
      grid-template-columns: 1fr;
      gap: 32px;
      margin-bottom: 48px;
    }
    .bs-quote-card {
      padding: 32px 24px;
    }
    .bs-quote-text {
      font-size: 18px;
    }
    .bs-pillars {
      grid-template-columns: 1fr;
      gap: 24px;
    }
    .bs-pillar {
      padding: 32px 24px;
    }
  }

  /* ═══════════════════════════════════════════════════════════════
     SECTION 8 — SUCCESS STORIES (Testimonials)
     ═══════════════════════════════════════════════════════════════ */
  .testimonial-band__carousel {
    position: relative;
    margin-top: 2rem;
    overflow: hidden;
  }
  .testimonial-band__viewport {
    display: flex;
    gap: 32px;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    scroll-behavior: smooth;
    scrollbar-width: none;
    padding: 10px 0 30px;
  }
  .testimonial-band__viewport::-webkit-scrollbar {
    display: none;
  }
  .ss-card {
    flex: 0 0 calc((100% - 64px) / 3);
    scroll-snap-align: start;
    background: #fff;
    border: var(--border-card, 1px solid rgba(16, 88, 98, 0.08));
    border-radius: var(--radius-card, 18px);
    padding: 32px;
    display: flex;
    flex-direction: column;
    min-width: 320px;
    box-shadow: var(--shadow-card, 0 10px 30px rgba(0, 0, 0, 0.04));
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
  }
  .ss-card:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 20px 40px rgba(10, 35, 45, 0.08);
  }
  .ss-quote-mark {
    font-size: 56px;
    color: #E8773A;
    line-height: 0.6;
    margin-bottom: 18px;
    font-family: Georgia, serif;
    text-align: left;
  }
  .ss-text {
    font-size: 16.5px;
    color: #3a4a57;
    line-height: 1.85;
    margin-bottom: 30px;
    font-weight: 500;
    text-align: left;
    flex-grow: 1;
  }
  .ss-author {
    display: flex;
    align-items: center;
    gap: 18px;
    margin-top: auto;
  }
  .ss-av {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg,#0B7A75,#065E5B);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 800;
    font-size: 18px;
    flex-shrink: 0;
    overflow: hidden;
  }
  .ss-av img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .ss-name {
    font-size: 17px;
    font-weight: 800;
    color: #2C3A47;
    text-align: left;
  }
  .ss-loc {
    font-size: 14px;
    color: #94a3b0;
    font-weight: 400;
    text-align: left;
  }
  .testimonial-band__controls {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-bottom: 24px;
  }
  .testimonial-band__control {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: 1px solid #e8edf2;
    background: #fff;
    color: #0B7A75;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    outline: none;
    padding: 0;
  }
  .testimonial-band__control svg {
    width: 20px;
    height: 20px;
    display: block;
  }
  .testimonial-band__control:hover {
    background: #0B7A75;
    color: #fff;
    border-color: #0B7A75;
  }

  @media (max-width: 1023px) {
    .ss-card {
      flex: 0 0 calc((100% - 32px) / 2);
    }
  }
  @media (max-width: 767px) {
    .testimonial-band__viewport {
      flex-direction: column;
      overflow-x: visible;
      gap: 20px;
    }
    .ss-card {
      flex: 0 0 auto;
      width: 100%;
      min-width: 0;
      padding: 32px 24px;
    }
    .testimonial-band__controls {
      display: none;
    }
  }

  /* ═══════════════════════════════════════════════════════════════
     SECTION 9 — MOST USED GUIDES
     ═══════════════════════════════════════════════════════════════ */
  .mg-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 22px;
  }
  .mg-card {
    border: var(--border-card, 1px solid rgba(16, 88, 98, 0.08));
    border-radius: var(--radius-card, 18px);
    background: #fff;
    padding: 32px 24px;
    text-align: center;
    position: relative;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    justify-content: center;
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
    box-shadow: var(--shadow-card, 0 10px 30px rgba(0, 0, 0, 0.04));
  }
  .mg-card:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 20px 40px rgba(10, 35, 45, 0.08);
  }
  .mg-badge {
    position: absolute;
    top: -14px;
    right: 18px;
    background: #E8773A;
    color: #fff;
    font-size: 12px;
    font-weight: 800;
    padding: 6px 14px;
    border-radius: 14px;
    text-transform: uppercase;
    letter-spacing: 0.8px;
  }
  .mg-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    background: #E6F4F3;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 26px;
  }
  .mg-title {
    font-size: 16px;
    font-weight: 800;
    color: #065E5B;
    line-height: 1.45;
  }

  @media (max-width: 1023px) {
    .mg-grid {
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
    }
  }
  @media (max-width: 767px) {
    .mg-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
    }
  }
  @media (max-width: 480px) {
    .mg-grid {
      grid-template-columns: 1fr;
      gap: 12px;
    }
  }

  /* Dynamic Blog Section */
  .blog-section-title {
    font-size: 28px;
    font-weight: 800;
    color: #065E5B;
    text-align: center;
    margin-top: 70px;
    margin-bottom: 36px;
  }
  .blog-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 32px;
  }
  .blog-card {
    border: var(--border-card, 1px solid rgba(16, 88, 98, 0.08));
    border-radius: var(--radius-card, 18px);
    background: #fff;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
    box-shadow: var(--shadow-card, 0 10px 30px rgba(0, 0, 0, 0.04));
  }
  .blog-card:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 20px 40px rgba(10, 35, 45, 0.08);
  }
  .blog-card__media-link {
    display: block;
    aspect-ratio: 16/9;
    overflow: hidden;
  }
  .blog-card__image {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .blog-card__body {
    padding: 24px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
  }
  .blog-card__tag {
    font-size: 11px;
    font-weight: 800;
    color: #E8773A;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 10px;
  }
  .blog-card__body h3 {
    font-size: 18px;
    font-weight: 800;
    color: #065E5B;
    margin-bottom: 12px;
    line-height: 1.4;
  }
  .blog-card__body h3 a {
    color: inherit;
    text-decoration: none;
  }
  .blog-card__body h3 a:hover {
    color: #0B7A75;
  }
  .blog-card__excerpt {
    font-size: 14.5px;
    color: #5e7180;
    line-height: 1.6;
    margin-bottom: 20px;
    flex-grow: 1;
  }
  .blog-card__footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid #f2f2f2;
    padding-top: 16px;
    margin-top: auto;
  }
  .blog-card__meta {
    font-size: 12px;
    color: #94a3b0;
    display: flex;
    gap: 8px;
  }
  .blog-card__read {
    font-size: 13.5px;
    color: #0B7A75;
    font-weight: 700;
    text-decoration: none;
  }
  .blog-card__read:hover {
    color: #E8773A;
  }
  
  @media (max-width: 1023px) {
    .blog-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 24px;
    }
  }
  @media (max-width: 767px) {
    .blog-grid {
      grid-template-columns: 1fr;
      gap: 24px;
    }
  }

  .blog-more-btn-wrap {
    text-align: center;
    margin-top: 40px;
  }

  /* ═══════════════════════════════════════════════════════════════
     SECTION 10 — FINAL CTA
     ═══════════════════════════════════════════════════════════════ */
  .fc-inner {
    text-align: center;
    max-width: 760px;
    margin: 0 auto;
  }
  .fc-h {
    font-size: 42px;
    font-weight: 800;
    color: #fff;
    margin-bottom: 18px;
    letter-spacing: -0.6px;
    line-height: 1.25;
  }
  .fc-sub {
    font-size: 18px;
    color: #9dd5d2;
    margin-bottom: 48px;
    line-height: 1.85;
    font-weight: 400;
  }
  .fc-form {
    display: flex;
    gap: 16px;
    max-width: 680px;
    margin: 0 auto 22px;
  }
  .fc-form label {
    flex: 1;
  }
  .fc-form input {
    width: 100%;
    border-radius: 12px;
    padding: 19px 22px;
    font-size: 16px;
    border: none;
    outline: none;
    color: #2C3A47;
  }
  .fc-form button {
    background: #E8773A;
    color: #fff;
    border-radius: 12px;
    padding: 19px 36px;
    font-size: 16px;
    font-weight: 700;
    white-space: nowrap;
    border: none;
    cursor: pointer;
    transition: background-color 0.2s;
  }
  .fc-form button:hover {
    background-color: #d3662d;
  }
  .fc-micro {
    font-size: 14px;
    color: #7abfbb;
    font-weight: 400;
  }

  @media (max-width: 767px) {
    .fc-h {
      font-size: 30px;
    }
    .fc-form {
      flex-direction: column;
      gap: 12px;
    }
    .fc-form button {
      width: 100%;
    }
  }

  /* ═══════════════════════════════════════════════════════════════
     SECTION 11 — COUNTRY ACKNOWLEDGEMENT
     ═══════════════════════════════════════════════════════════════ */
  .country-acknowledgement {
    background: #f7faf9;
    border-top: 1px solid rgba(10, 107, 109, 0.12);
    padding: 60px 24px;
  }
  .country-acknowledgement__inner {
    max-width: 1152px;
    margin: 0 auto;
    border-left: 5px solid #E8773A;
    background: #ffffff;
    padding: 32px;
    box-shadow: 0 12px 32px rgba(15, 23, 42, 0.05);
  }
  .country-acknowledgement__label {
    font-size: 13px;
    font-weight: 800;
    color: #065E5B;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 12px;
  }
  .country-acknowledgement p {
    font-size: 16px;
    line-height: 1.8;
    color: #44525e;
    margin: 0;
  }
</style>

<div class="hp-wrapper">
  
  <div class="hero">
    <div class="hero-container">
      <div class="hero-left">
        <div class="hero-badge">
          <span class="badge-icon-wrap">
            <svg class="badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
          </span>
          FOR NEW MIGRANTS, STUDENTS &amp; SKILLED WORKERS
        </div>
        <h1 class="hero-h1">
          We help you Settle in 
          <span class="hl-orange-wrap">
            <span class="hl-orange">90 Days</span>
            <span class="hl-orange-bar"></span>
          </span>
          <span class="hl-dark">— Not 9 Months</span>
        </h1>
        <p class="hero-sub">Most immigrants waste 3–9 months fixing early mistakes. SettleANZ's proven roadmap gets you from arrival to settled in 90 days with your career, home, and community in place.</p>
        <div class="hero-ctas">
          <button class="btn-primary" type="button" data-open-lead-modal>
            Get Your Free Roadmap 
            <svg class="cta-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
          </button>
          <a class="btn-secondary" href="{{ route('blog.index') }}">Explore Resources</a>
        </div>
        <div class="hero-trust-badge">
          <div class="trust-check-wrap">
            <svg class="trust-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
          <span>Built from real migrant experience. Australia &amp; New Zealand focused.</span>
        </div>
      </div>
      <div class="hero-right">
        <div class="hero-illustration-wrapper">
          <img src="{{ asset('media/hero/hero.webp') }}" alt="SettleANZ — Settle in Australia in 90 days" width="700" height="620">
        </div>
      </div>
    </div>

    <!-- Bottom Feature Cards Bar -->
    <div class="hero-features-container">
      <div class="hero-features-bar">
        <!-- Card 1 -->
        <div class="hero-feature-item">
          <div class="feature-icon-box feature-icon-teal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"></polygon><line x1="9" y1="3" x2="9" y2="18"></line><line x1="15" y1="6" x2="15" y2="21"></line></svg>
          </div>
          <div class="feature-text">
            <h4 class="feature-title">Personalized Roadmap</h4>
            <p class="feature-desc">Step-by-step plan tailored to your goals.</p>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="hero-feature-item">
          <div class="feature-icon-box feature-icon-orange">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
          </div>
          <div class="feature-text">
            <h4 class="feature-title">Save Time &amp; Money</h4>
            <p class="feature-desc">Avoid costly mistakes and delays.</p>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="hero-feature-item">
          <div class="feature-icon-box feature-icon-teal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="m9 12 2 2 4-4"></path></svg>
          </div>
          <div class="feature-text">
            <h4 class="feature-title">Trusted Guidance</h4>
            <p class="feature-desc">Reliable, up-to-date info from migration experts.</p>
          </div>
        </div>

        <!-- Card 4 -->
        <div class="hero-feature-item">
          <div class="feature-icon-box feature-icon-orange">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
          </div>
          <div class="feature-text">
            <h4 class="feature-title">Community Support</h4>
            <p class="feature-desc">Connect, share, and grow with others.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- GET STARTED (Journey Cards) -->
  <div class="sec sec-white journey-sec">
    <div class="sec-container" style="max-width: 1280px;">
      <div class="sec-label">YOUR JOURNEY, YOUR WAY</div>
      <h2 class="sec-h2">Where Are You In Your Settlement Journey?</h2>
      <p class="sec-sub">Choose your stage and get your personalized roadmap.</p>
      
      <div class="qe-grid">
        <!-- Card 1 -->
        <div class="qe-card">
          <div class="qe-card-content">
            <div>
              <div class="qe-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.2c.3.4.8.5 1.3.3l.5-.3c.4-.2.6-.6.5-1.1z"></path></svg>
              </div>
              <div class="qe-tag">BEFORE YOU FLY PREPARATION</div>
              <h3 class="qe-title">Planning Your Move (Future Migrant)</h3>
              <p class="qe-desc">Avoid the $5,000–$15,000 in wasted costs and 6-month delays caused by choosing the wrong visa pathway or city.</p>
              <ul class="qe-list">
                <li>Identify the right visa pathway.</li>
                <li>Understand the true cost of living in your target cities.</li>
                <li>Take proactive steps for a smooth arrival.</li>
                <li>Get honest insights into common challenges and how to prepare.</li>
              </ul>
            </div>
            <a class="qe-cta-btn" href="{{ route('guides.settlement-services') }}">
              Help Me Plan My Move 

            </a>
          </div>
          <div class="qe-card-image">
            <img src="{{ asset('media/home/Before you fly preparation.webp') }}" alt="Planning Your Move — Future Migrant" width="500" height="600">
          </div>
        </div>

        <!-- Card 2 -->
        <div class="qe-card">
          <div class="qe-card-content">
            <div>
              <div class="qe-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
              </div>
              <div class="qe-tag">BEFORE ARRIVAL SETUP</div>
              <h3 class="qe-title">Starting Your Studies (International Student)</h3>
              <p class="qe-desc">Avoid the $2,000–$4,000 in wasted housing costs and visa violations caused by not understanding work rights or choosing the wrong accommodation.</p>
              <ul class="qe-list">
                <li>Find safe, affordable accommodation near campus.</li>
                <li>Set up your bank account and SIM card before you land.</li>
                <li>Understand your work rights to protect your visa.</li>
                <li>Prioritize your first two weeks for a strong, stress-free start.</li>
              </ul>
            </div>
            <a class="qe-cta-btn" href="{{ route('guides.settlement-services') }}">
              Show Me What To Set Up First 

            </a>
          </div>
          <div class="qe-card-image">
            <img src="{{ asset('media/home/Before arrival setup.webp') }}" alt="Starting Your Studies — International Student" width="500" height="600">
          </div>
        </div>

        <!-- Card 3 -->
        <div class="qe-card">
          <div class="qe-card-content">
            <div>
              <div class="qe-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
              </div>
              <div class="qe-tag">CAREER START PLAN</div>
              <h3 class="qe-title">Building Your Career (Skilled Worker)</h3>
              <p class="qe-desc">Avoid the 3–6 month career setback caused by not understanding local experience requirements, workplace culture, or tax setup.</p>
              <ul class="qe-list">
                <li>Understand the demand for local experience and how to gain it fast.</li>
                <li>Set up your TFN, bank account, and super for correct paychecks.</li>
                <li>Navigate Australian workplace culture and expectations.</li>
                <li>Position yourself for career progression from month one.</li>
              </ul>
            </div>
            <a class="qe-cta-btn" href="{{ route('guides.settlement-services') }}">
              Help Me Hit the Ground Running 

            </a>
          </div>
          <div class="qe-card-image">
            <img src="{{ asset('media/home/Career start plan.webp') }}" alt="Building Your Career — Skilled Worker" width="500" height="600">
          </div>
        </div>

        <!-- Card 4 -->
        <div class="qe-card">
          <div class="qe-card-content">
            <div>
              <div class="qe-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
              </div>
              <div class="qe-tag">FIRST 90 DAYS SUPPORT</div>
              <h3 class="qe-title">Settling Into Everyday Life (New Arrival)</h3>
              <p class="qe-desc">Avoid weeks of confusion, wrong decisions, and isolation caused by not knowing what to do first or in what order.</p>
              <ul class="qe-list">
                <li>Secure rental accommodation, even without local history.</li>
                <li>Stop second-guessing decisions; know what to do and when.</li>
                <li>Find your social footing and build local connections.</li>
                <li>Follow a clear 90-day plan built by experienced newcomers.</li>
              </ul>
            </div>
            <a class="qe-cta-btn" href="{{ route('guides.settlement-services') }}">
              Start My Settlement Plan 

            </a>
          </div>
          <div class="qe-card-image">
            <img src="{{ asset('media/home/First 90 days support.webp') }}" alt="Settling Into Everyday Life — New Arrival" width="500" height="600">
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- PROBLEM REFRAME -->
  <div class="sec sec-sand">
    <div class="sec-container">
      <div class="pr-grid">
        <div>
          <h2 class="pr-title">Most People Don't Struggle Because They're Unprepared. They Struggle Because They're Un-Guided.</h2>
          <ul class="pr-list">
            <li>Banking asks for documents you don't have ready</li>
            <li>Rentals expect history you don't have</li>
            <li>Work systems feel unfamiliar</li>
            <li>Even basic life setup takes longer than expected</li>
          </ul>
          <div class="pr-reframe">We map your first 90 days so you know what matters first — and what can wait.</div>
        </div>
        <div class="pr-visual">
          <div class="pr-step">
            <div class="pr-step-num done">✓</div>
            <div class="pr-step-text">Get an Australian SIM card</div>
          </div>
          <div class="pr-step-line"></div>
          <div class="pr-step">
            <div class="pr-step-num done">✓</div>
            <div class="pr-step-text">Apply for your Tax File Number</div>
          </div>
          <div class="pr-step-line"></div>
          <div class="pr-step">
            <div class="pr-step-num">3</div>
            <div class="pr-step-text">Open your bank account</div>
          </div>
          <div class="pr-step-line"></div>
          <div class="pr-step">
            <div class="pr-step-num">4</div>
            <div class="pr-step-text">Set up Medicare</div>
          </div>
          <div class="pr-step-line"></div>
          <div class="pr-step">
            <div class="pr-step-num">5</div>
            <div class="pr-step-text">Find your first rental</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- SERVICES (Settlement Journey) -->
  <div class="sec sec-white">
    <div class="sec-container">
      <div class="sec-label">Our Services</div>
      <h2 class="sec-h2">Your Settlement Journey, Step By Step</h2>
      <p class="sec-sub">Everything we create is designed around the real stages newcomers go through.</p>
      
      <div class="jt-track">
        <!-- Stage 1 -->
        <div class="jt-card">
          <div class="jt-stage-label">Stage 01</div>
          <div class="jt-img">
            <img src="{{ asset('media/home/h_stage_1.webp') }}" alt="Arrive" loading="lazy" width="300" height="200">
          </div>
          <div class="jt-num-row">
            <div class="jt-num">✈️</div>
          </div>
          <div class="jt-body">
            <h3 class="jt-title">Arrive</h3>
            <p class="jt-desc">Airport to Home in 24 Hours. Skip the overwhelm. We handle airport pickup, temporary accommodation, SIM card setup, and neighborhood orientation, so you can focus on settling in, not logistics.</p>
            <a class="jt-link" href="{{ route('guides.settlement-services') }}">See what's included</a>
          </div>
        </div>

        <!-- Stage 2 -->
        <div class="jt-card">
          <div class="jt-stage-label">Stage 02</div>
          <div class="jt-img">
            <img src="{{ asset('media/home/h_stage_2.webp') }}" alt="Settle" loading="lazy" width="300" height="200">
          </div>
          <div class="jt-num-row">
            <div class="jt-num">🏠</div>
          </div>
          <div class="jt-body">
            <h3 class="jt-title">Settle</h3>
            <p class="jt-desc">Your First Home in 30 Days. Secure a rental without local credit history. Open bank accounts. Get insurance. Build your foundation. We guide you through every step so you're settled—not just moved in.</p>
            <a class="jt-link" href="{{ route('guides.settlement-services') }}">See what's included</a>
          </div>
        </div>

        <!-- Stage 3 -->
        <div class="jt-card">
          <div class="jt-stage-label">Stage 03</div>
          <div class="jt-img">
            <img src="{{ asset('media/home/h_stage_3.webp') }}" alt="Work &amp; Invest" loading="lazy" width="300" height="200">
          </div>
          <div class="jt-num-row">
            <div class="jt-num">📈</div>
          </div>
          <div class="jt-body">
            <h3 class="jt-title">Work &amp; Invest</h3>
            <p class="jt-desc">Land Your First Role in 120 Days or less. Local experience is the barrier. We show you how to overcome it. Resume optimization, interview coaching, workplace culture navigation, and professional networking—so you hit the ground running and build momentum from day one.</p>
            <a class="jt-link" href="{{ route('guides.settlement-services') }}">See what's included</a>
          </div>
        </div>

        <!-- Stage 4 -->
        <div class="jt-card">
          <div class="jt-stage-label">Stage 04</div>
          <div class="jt-img">
            <img src="{{ asset('media/home/h_stage_4.webp') }}" alt="Explore &amp; Enjoy" loading="lazy" width="300" height="200">
          </div>
          <div class="jt-num-row">
            <div class="jt-num">🧭</div>
          </div>
          <div class="jt-body">
            <h3 class="jt-title">Explore &amp; Enjoy</h3>
            <p class="jt-desc">Build Your Community in 90 Days. Settling isn't just about logistics; it's more about belonging. We connect you with social groups, cultural communities, and local activities so you build genuine friendships and feel at home, not just housed.</p>
            <a class="jt-link" href="{{ route('guides.settlement-services') }}">See what's included</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- STATS COUNTER BAR -->
  <div class="hp-counter-section">
    <div class="hp-counter-grid" data-counter-section>
      <div class="hp-counter-item">
        <span class="hp-counter-value" data-count="5">0</span><span class="hp-counter-suffix">+</span>
        <span class="hp-counter-label">Years in Business</span>
      </div>
      <div class="hp-counter-divider"></div>
      <div class="hp-counter-item">
        <span class="hp-counter-value" data-count="1000">0</span><span class="hp-counter-suffix">+</span>
        <span class="hp-counter-label">Immigrants Served</span>
      </div>
      <div class="hp-counter-divider"></div>
      <div class="hp-counter-item">
        <span class="hp-counter-value" data-count="12">0</span><span class="hp-counter-suffix">+</span>
        <span class="hp-counter-label">Cities Covered</span>
      </div>
      <div class="hp-counter-divider"></div>
      <div class="hp-counter-item">
        <span class="hp-counter-value" data-count="97">0</span><span class="hp-counter-suffix">%</span>
        <span class="hp-counter-label">Satisfaction Rate</span>
      </div>
    </div>
  </div>

  <!-- PARTNER LOGOS STRIP -->
  <div class="partner-strip">
    <div class="partner-strip__track">
      <div class="partner-strip__group">
        <div class="partner-strip__logo"><img src="{{ asset('media/partners/logos/wise.png') }}" alt="Wise" loading="lazy" width="120" height="30"></div>
        <div class="partner-strip__logo"><img src="{{ asset('media/partners/logos/safetywing.png') }}" alt="SafetyWing" loading="lazy" width="120" height="30"></div>
        <div class="partner-strip__logo"><img src="{{ asset('media/partners/logos/Booking.com.png') }}" alt="Booking.com" loading="lazy" width="120" height="30"></div>
        <div class="partner-strip__logo"><img src="{{ asset('media/partners/logos/cigna.png') }}" alt="Cigna" loading="lazy" width="120" height="30"></div>
        <div class="partner-strip__logo"><img src="{{ asset('media/partners/logos/OFX.png') }}" alt="OFX" loading="lazy" width="120" height="30"></div>
      </div>
      <div class="partner-strip__group">
        <div class="partner-strip__logo"><img src="{{ asset('media/partners/logos/wise.png') }}" alt="Wise" loading="lazy" width="120" height="30"></div>
        <div class="partner-strip__logo"><img src="{{ asset('media/partners/logos/safetywing.png') }}" alt="SafetyWing" loading="lazy" width="120" height="30"></div>
        <div class="partner-strip__logo"><img src="{{ asset('media/partners/logos/Booking.com.png') }}" alt="Booking.com" loading="lazy" width="120" height="30"></div>
        <div class="partner-strip__logo"><img src="{{ asset('media/partners/logos/cigna.png') }}" alt="Cigna" loading="lazy" width="120" height="30"></div>
        <div class="partner-strip__logo"><img src="{{ asset('media/partners/logos/OFX.png') }}" alt="OFX" loading="lazy" width="120" height="30"></div>
      </div>
    </div>
  </div>

  <!-- ROADMAP LEAD -->
  <div class="sec sec-sand roadmap-sec">
    <div class="sec-container" style="max-width: 1240px;">
      <div class="rm-header-wrap">
        <div class="sec-label">First 90 Days Road Map</div>
        <h2 class="sec-h2" style="max-width: 1100px; margin: 0 auto 20px;">Most People Don't Struggle Because They're Unprepared. They Struggle Because They're Un-Guided.</h2>
        <div class="rm-story-text">
          <p>You land. Nothing's set up. You don't know what to do first. Most immigrants lose 3–6 months fixing early mistakes. Our 90-Day Roadmap prevents that.</p>
          <p>We map your first 90 days, clarifying what matters most and what can wait. Download the exact checklist used by newcomers to avoid costly first-month mistakes.</p>
          <p class="rm-bold-tag">SettleANZ helps you get it right the first time.</p>
        </div>
      </div>

      <!-- Boxed Container (Left Checklist, Right Form) -->
      <div class="rm-box-container">
        <div class="rm-box-left">
          <h3 class="rm-box-title">Your Free 90-Day Roadmap Includes:</h3>
          <ul class="rm-box-list">
            <li><strong>Week 1:</strong> Banking, TFN, SIM card setup (exact order matters)</li>
            <li><strong>Week 2–4:</strong> Rental hunting strategy (how to compete without local credit history)</li>
            <li><strong>Month 2:</strong> Job search acceleration (industry-specific tips)</li>
            <li><strong>Month 3:</strong> Community integration (where to find your people)</li>
          </ul>
        </div>

        <div class="rm-box-right">
          <div class="rm-form-card">
            <h3 class="rm-form-title">Get the free 90-day roadmap</h3>
            <form method="POST" action="{{ route('lead-capture.store') }}">
              @csrf
              <input type="hidden" name="form_type" value="homepage-roadmap">
              <input type="hidden" name="source_page" value="homepage-roadmap">
              <label><span class="sr-only">Your name</span>
                <input class="rm-input" type="text" name="first_name" placeholder="Your name" required>
              </label>
              <label><span class="sr-only">Your email</span>
                <input class="rm-input" type="email" name="email" placeholder="Your email" required>
              </label>
              <button class="rm-btn" type="submit">Send Me the Roadmap</button>
            </form>
            <div class="rm-note">Takes under 5 minutes to read. Saves weeks of confusion.</div>
          </div>
        </div>
      </div>

      <!-- Bottom Callout Note -->
      <div class="rm-bottom-callout">
        <span>Common mistakes to avoid (the $2K–$5K errors most immigrants make)</span>
      </div>
    </div>
  </div>

  <!-- BRAND STORY -->
  <div class="sec sec-teal">
    <div class="sec-container">
      <div class="bs-wrap">
        <div class="bs-eyebrow">What Makes SettleANZ Different?</div>
        <h2 class="bs-h">Real Guidance from People Who’ve Actually Been in Your Shoes.</h2>
        <div class="bs-content-grid">
          <div class="bs-content-left">
            <div class="bs-image-card">
              <img src="{{ asset('media/home/family.webp') }}" alt="Relocation guidance" loading="lazy">
            </div>
            <div class="bs-quote-card">
              <p class="bs-quote-text">We know what the wrong suburb costs. We understand that if you take one wrong step, you lose weeks fixing it.</p>
              <div class="bs-quote-author">Lived Experience Matters</div>
            </div>
          </div>
          <div class="bs-content-right">
            <p>Most settlement advice comes from people who haven't actually gone through the process themselves. Ours comes from lived experience.</p>
            <p>The people behind SettleANZ have walked through the same path as you. All of us have gone through the challenges every new immigrant faces. The confusion of not knowing what to do first.</p>
            <p>That's why everything we create is built around real settlement priorities, not what the internet suggests.</p>
            <p>Whether you're preparing to arrive, finding your first home, setting up your finances, building your career, or helping your family settle in, our goal is simple: <strong>Give you practical guidance that helps you make informed decisions with confidence.</strong></p>
          </div>
        </div>
        <div class="bs-pillars">
          <div class="bs-pillar">
            <div class="bs-pillar-icon">💡</div>
            <h3 class="bs-pillar-title">Lived Experience</h3>
            <p class="bs-pillar-text">Every guide starts from a real settlement journey, not a search engine summary.</p>
          </div>
          <div class="bs-pillar">
            <div class="bs-pillar-icon">👥</div>
            <h3 class="bs-pillar-title">A Team, Not One Voice</h3>
            <p class="bs-pillar-text">Researchers and locals across AU &amp; NZ keep every guide accurate and current.</p>
          </div>
          <div class="bs-pillar">
            <div class="bs-pillar-icon">🧭</div>
            <h3 class="bs-pillar-title">Built Around Your Journey</h3>
            <p class="bs-pillar-text">Structured around the real stages newcomers go through — not generic categories.</p>
          </div>
        </div>
        <div class="bs-disclaimer">We do not provide migration advice. Only practical settlement guidance based on lived experience and research.</div>
        <a class="bs-cta" href="{{ route('about') }}">Learn More About SettleANZ</a>
      </div>
    </div>
  </div>

  <!-- SUCCESS STORIES (Testimonials) -->
  <div class="sec sec-white">
    <div class="sec-container">
      <div class="sec-label">Real Results</div>
      <h2 class="sec-h2">What changes after using SettleANZ</h2>
      <p class="sec-sub" style="margin-bottom: 24px;">Read genuine testimonials from people who transitioned smoothly to Australia and New Zealand.</p>
      
      <div class="testimonial-band__carousel" data-testimonial-carousel data-autoplay-interval="4500">
        <div class="testimonial-band__controls">
          <button class="testimonial-band__control testimonial-band__control--prev" type="button" data-testimonial-prev aria-label="Previous testimonials">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
          </button>
          <button class="testimonial-band__control testimonial-band__control--next" type="button" data-testimonial-next aria-label="Next testimonials">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
          </button>
        </div>

        <div class="testimonial-band__viewport" data-testimonial-track tabindex="0" aria-label="Testimonials carousel">
          <!-- Testimonial 1 -->
          <div class="ss-card">
            <div class="ss-quote-mark">"</div>
            <p class="ss-text">Moving to Australia felt overwhelming at first, but SettleANZ made the entire process much easier. From helping us understand the local systems to connecting us with trusted professionals, their support saved us countless hours of research and stress. We felt supported every step of the way.</p>
            <div class="ss-author">
              <div class="ss-av">
                <img src="{{ asset('media/testimonials/Josh A. – United Kingdom.webp') }}" alt="Josh A." loading="lazy" width="56" height="56">
              </div>
              <div>
                <div class="ss-name">Josh A.</div>
                <div class="ss-loc">United Kingdom</div>
              </div>
            </div>
          </div>

          <!-- Testimonial 2 -->
          <div class="ss-card">
            <div class="ss-quote-mark">"</div>
            <p class="ss-text">The personalised service we received from SettleANZ exceeded our expectations. Their practical advice on banking, accommodation, transport and settling into daily life helped our family feel at home much faster than we anticipated.</p>
            <div class="ss-author">
              <div class="ss-av">
                <img src="{{ asset('media/testimonials/Dennis B. – South Africa.webp') }}" alt="Dennis B." loading="lazy" width="56" height="56">
              </div>
              <div>
                <div class="ss-name">Dennis B.</div>
                <div class="ss-loc">South Africa</div>
              </div>
            </div>
          </div>

          <!-- Testimonial 3 -->
          <div class="ss-card">
            <div class="ss-quote-mark">"</div>
            <p class="ss-text">As a first-time migrant, I had so many questions before arriving in Australia. SettleANZ provided clear guidance, useful resources and genuine support. Their knowledge and commitment gave me confidence during a major life transition.</p>
            <div class="ss-author">
              <div class="ss-av">
                <img src="{{ asset('media/testimonials/Kaur J. – India.webp') }}" alt="Kaur J." loading="lazy" width="56" height="56">
              </div>
              <div>
                <div class="ss-name">Kaur J.</div>
                <div class="ss-loc">India</div>
              </div>
            </div>
          </div>

          <!-- Testimonial 4 -->
          <div class="ss-card">
            <div class="ss-quote-mark">"</div>
            <p class="ss-text">I was impressed by how responsive and knowledgeable the SettleANZ team was. They helped me understand the settlement process and connected me with services that would have taken me weeks to find on my own. Highly recommended.</p>
            <div class="ss-author">
              <div class="ss-av">
                <img src="{{ asset('media/testimonials/Ali M. – United Arab Emirates.webp') }}" alt="Ali M." loading="lazy" width="56" height="56">
              </div>
              <div>
                <div class="ss-name">Ali M.</div>
                <div class="ss-loc">United Arab Emirates</div>
              </div>
            </div>
          </div>

          <!-- Testimonial 5 -->
          <div class="ss-card">
            <div class="ss-quote-mark">"</div>
            <p class="ss-text">SettleANZ provided exactly the support I needed when relocating to Australia. Their advice was practical, honest and tailored to my family’s circumstances. They made what could have been a stressful experience feel organised and manageable.</p>
            <div class="ss-author">
              <div class="ss-av">
                <img src="{{ asset('media/testimonials/Nguyen K. – Vietnam.webp') }}" alt="Nguyen K." loading="lazy" width="56" height="56">
              </div>
              <div>
                <div class="ss-name">Nguyen K.</div>
                <div class="ss-loc">Vietnam</div>
              </div>
            </div>
          </div>

          <!-- Testimonial 6 -->
          <div class="ss-card">
            <div class="ss-quote-mark">"</div>
            <p class="ss-text">The arrival and settlement assistance from SettleANZ was outstanding. Having someone who understood the challenges of moving countries and could guide us through the important first steps made a huge difference. Excellent service and excellent value.</p>
            <div class="ss-author">
              <div class="ss-av">
                <img src="{{ asset('media/testimonials/Dresden L. – Germany.webp') }}" alt="Dresden L." loading="lazy" width="56" height="56">
              </div>
              <div>
                <div class="ss-name">Dresden L.</div>
                <div class="ss-loc">Germany</div>
              </div>
            </div>
          </div>

          <!-- Testimonial 7 -->
          <div class="ss-card">
            <div class="ss-quote-mark">"</div>
            <p class="ss-text">We chose the Premium Settlement Package and it was one of the best investments we made before moving. SettleANZ helped us navigate everything from local services to community connections. We settled in much faster because of their support.</p>
            <div class="ss-author">
              <div class="ss-av">
                <img src="{{ asset('media/testimonials/Taylor S. – New Zealand.webp') }}" alt="Taylor S." loading="lazy" width="56" height="56">
              </div>
              <div>
                <div class="ss-name">Taylor S.</div>
                <div class="ss-loc">New Zealand</div>
              </div>
            </div>
          </div>

          <!-- Testimonial 8 -->
          <div class="ss-card">
            <div class="ss-quote-mark">"</div>
            <p class="ss-text">Professional, knowledgeable and genuinely caring. SettleANZ helped me prepare for life in Australia before I even boarded my flight. Their guidance saved me time, money and unnecessary stress.</p>
            <div class="ss-author">
              <div class="ss-av">
                <img src="{{ asset('media/testimonials/Chen L. – Singapore.webp') }}" alt="Chen L." loading="lazy" width="56" height="56">
              </div>
              <div>
                <div class="ss-name">Chen L.</div>
                <div class="ss-loc">Singapore</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- MOST USED GUIDES & DYNAMIC BLOG -->
  <div class="sec sec-sand">
    <div class="sec-container">
      <div class="sec-label">Quick Start</div>
      <h2 class="sec-h2">Start here if you're unsure</h2>
      <p class="sec-sub">Start Here: Our Most-Read Guides</p>
      
      <div class="mg-grid">
        <!-- Guide 1 -->
        <a class="mg-card" href="{{ route('guides.settlement-services') }}">
          <div class="mg-badge">Most Used</div>
          <div class="mg-icon">🏦</div>
          <div class="mg-title">Open First Bank Account</div>
        </a>
        <!-- Guide 2 -->
        <a class="mg-card" href="{{ route('guides.settlement-services') }}">
          <div class="mg-icon">🏘️</div>
          <div class="mg-title">Find First Rental</div>
        </a>
        <!-- Guide 3 -->
        <a class="mg-card" href="{{ route('guides.settlement-services') }}">
          <div class="mg-icon">🩺</div>
          <div class="mg-title">Understand Medicare</div>
        </a>
        <!-- Guide 4 -->
        <a class="mg-card" href="{{ route('guides.settlement-services') }}">
          <div class="mg-icon">📄</div>
          <div class="mg-title">Get Your TFN</div>
        </a>
        <!-- Guide 5 -->
        <a class="mg-card" href="{{ route('guides.new-to-australia') }}">
          <div class="mg-icon">✅</div>
          <div class="mg-title">Before You Fly Checklist</div>
        </a>
      </div>

      <!-- DYNAMIC BLOG POSTS SECTION -->
      <h2 class="blog-section-title">Latest Insights &amp; Settlement Guides</h2>
      <div class="blog-grid" data-reveal-stagger="home-guides">
        @foreach ($latestPosts as $post)
          <article class="blog-card" data-reveal-stagger-item data-reveal-stagger-index="{{ $loop->index }}">
            <a class="blog-card__media-link" href="{{ route('blog.show', $post->slug) }}">
              @if (!empty($post->image))
                <img class="blog-card__image" src="{{ $post->image_url }}" alt="{{ $post->title }}" loading="lazy">
              @else
                <div class="blog-card__image" style="background: linear-gradient(135deg, #cfeae8, #7dc3bf); height: 100%;" aria-hidden="true"></div>
              @endif
            </a>
            <div class="blog-card__body">
              <p class="blog-card__tag">{{ $post->category }}</p>
              <h3><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h3>
              <p class="blog-card__excerpt">{{ $post->excerpt }}</p>
              <div class="blog-card__footer">
                <div class="blog-card__meta">
                  <span>{{ $post->author_name }}</span>
                  @if (!empty($post->reading_time))
                    <span>• {{ $post->reading_time }}</span>
                  @endif
                </div>
                <a class="blog-card__read" href="{{ route('blog.show', $post->slug) }}">Read article</a>
              </div>
            </div>
          </article>
        @endforeach
      </div>
      <div class="blog-more-btn-wrap">
        <a class="btn-secondary" href="{{ route('blog.index') }}">Browse All Guides</a>
      </div>

    </div>
  </div>

  <!-- FINAL CTA -->
  <div class="sec sec-teal">
    <div class="sec-container">
      <div class="fc-inner">
        <h2 class="fc-h">Start your first 90 days with clarity</h2>
        <p class="fc-sub">Join newcomers who are settling with structure instead of confusion.</p>
        
        <form class="fc-form" method="POST" action="{{ route('lead-capture.store') }}">
          @csrf
          <input type="hidden" name="form_type" value="homepage-footer">
          <input type="hidden" name="source_page" value="homepage-footer">
          <label><span class="sr-only">Your name</span>
            <input type="text" name="first_name" placeholder="Your name" required>
          </label>
          <label><span class="sr-only">Your email</span>
            <input type="email" name="email" placeholder="Your email" required>
          </label>
          <button type="submit">Get the Free Roadmap</button>
        </form>
        <div class="fc-micro">No spam. Just practical updates when needed.</div>
      </div>
    </div>
  </div>

  <!-- COUNTRY ACKNOWLEDGEMENT -->
  <div class="country-acknowledgement">
    <div class="country-acknowledgement__inner">
      <h3 class="country-acknowledgement__label">Acknowledgement of Country</h3>
      <p>SettleANZ acknowledges the Traditional Custodians of the lands on which we operate throughout Australia. We pay our respects to Elders past, present, and emerging. We extend that respect to all Aboriginal and Torres Strait Islander peoples today.</p>
    </div>
  </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ─── Testimonials Carousel & Auto-play ───
    const carousel = document.querySelector('[data-testimonial-carousel]');
    if (carousel) {
        const track = carousel.querySelector('[data-testimonial-track]');
        const prevBtn = carousel.querySelector('[data-testimonial-prev]');
        const nextBtn = carousel.querySelector('[data-testimonial-next]');
        const intervalTime = parseInt(carousel.getAttribute('data-autoplay-interval'), 10) || 4500;
        let autoPlayTimer = null;

        const getScrollAmount = () => {
            const card = track.firstElementChild;
            return card ? card.offsetWidth + 32 : 360;
        };

        const slideNext = () => {
            const maxScroll = track.scrollWidth - track.clientWidth;
            if (track.scrollLeft >= maxScroll - 10) {
                track.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
                track.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
            }
        };

        const slidePrev = () => {
            if (track.scrollLeft <= 10) {
                track.scrollTo({ left: track.scrollWidth, behavior: 'smooth' });
            } else {
                track.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' });
            }
        };

        if (nextBtn) nextBtn.addEventListener('click', () => { slideNext(); resetAutoPlay(); });
        if (prevBtn) prevBtn.addEventListener('click', () => { slidePrev(); resetAutoPlay(); });

        const startAutoPlay = () => {
            if (!autoPlayTimer) {
                autoPlayTimer = setInterval(slideNext, intervalTime);
            }
        };

        const stopAutoPlay = () => {
            if (autoPlayTimer) {
                clearInterval(autoPlayTimer);
                autoPlayTimer = null;
            }
        };

        const resetAutoPlay = () => {
            stopAutoPlay();
            startAutoPlay();
        };

        carousel.addEventListener('mouseenter', stopAutoPlay);
        carousel.addEventListener('mouseleave', startAutoPlay);
        carousel.addEventListener('touchstart', stopAutoPlay, { passive: true });
        carousel.addEventListener('touchend', startAutoPlay, { passive: true });

        startAutoPlay();
    }

    // ─── Stats Counter Animation ───
    const counterSection = document.querySelector('[data-counter-section]');
    if (counterSection) {
        let hasFired = false;
        const animateCounters = () => {
            if (hasFired) return;
            hasFired = true;

            counterSection.querySelectorAll('[data-count]').forEach(el => {
                const target = parseInt(el.getAttribute('data-count'), 10);
                const duration = 2000;
                let start = null;

                const step = timestamp => {
                    if (!start) start = timestamp;
                    const progress = Math.min((timestamp - start) / duration, 1);
                    // easeOutQuad
                    const eased = progress * (2 - progress);
                    const current = Math.round(eased * target);
                    el.textContent = current >= 1000 ? current.toLocaleString() : current;
                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    } else {
                        el.textContent = target >= 1000 ? target.toLocaleString() : target;
                    }
                };
                window.requestAnimationFrame(step);
            });
        };

        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounters();
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.2 });
            observer.observe(counterSection);
        } else {
            animateCounters();
        }
    }
});
</script>
@endsection
