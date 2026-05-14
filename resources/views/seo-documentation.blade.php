@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SettleANZ SEO System Documentation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #2c3e50;
            line-height: 1.6;
            background: white;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px;
        }
        
        /* Cover Page */
        .cover-page {
            page-break-after: always;
            text-align: center;
            padding: 100px 40px;
            background: linear-gradient(135deg, #0b7a75 0%, #0e8b84 100%);
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .cover-page h1 {
            font-size: 48px;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .cover-page .subtitle {
            font-size: 24px;
            opacity: 0.9;
            margin-bottom: 50px;
            font-weight: 300;
        }
        
        .cover-page .version {
            font-size: 14px;
            opacity: 0.8;
            margin-top: 100px;
        }
        
        /* Table of Contents */
        .toc {
            page-break-after: always;
            padding: 40px;
        }
        
        .toc h2 {
            font-size: 28px;
            margin-bottom: 30px;
            color: #0b7a75;
            border-bottom: 3px solid #0b7a75;
            padding-bottom: 10px;
        }
        
        .toc ul {
            list-style: none;
            font-size: 14px;
        }
        
        .toc li {
            margin: 12px 0;
            padding-left: 20px;
        }
        
        .toc a {
            color: #0b7a75;
            text-decoration: none;
        }
        
        /* Sections */
        .section {
            page-break-inside: avoid;
            margin-bottom: 50px;
        }
        
        .section h2 {
            font-size: 28px;
            color: #0b7a75;
            margin: 40px 0 20px 0;
            border-bottom: 3px solid #0b7a75;
            padding-bottom: 10px;
            page-break-after: avoid;
        }
        
        .section h3 {
            font-size: 20px;
            color: #1a5a56;
            margin: 25px 0 12px 0;
            page-break-after: avoid;
        }
        
        .section p {
            margin-bottom: 15px;
            font-size: 14px;
            line-height: 1.8;
            text-align: justify;
        }
        
        .section ul, .section ol {
            margin-left: 20px;
            margin-bottom: 15px;
        }
        
        .section li {
            margin-bottom: 8px;
            font-size: 14px;
            line-height: 1.6;
        }
        
        /* Highlights */
        .highlight-box {
            background: #f0faf8;
            border-left: 4px solid #0b7a75;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
            page-break-inside: avoid;
        }
        
        .highlight-box h4 {
            color: #0b7a75;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .highlight-box p {
            margin: 0;
            font-size: 13px;
        }
        
        /* Feature Box */
        .feature-box {
            background: #ffffff;
            border: 2px solid #0b7a75;
            padding: 20px;
            margin: 20px 0;
            border-radius: 6px;
            page-break-inside: avoid;
        }
        
        .feature-box h4 {
            color: #0b7a75;
            margin-bottom: 10px;
            font-weight: 600;
            font-size: 15px;
        }
        
        .feature-box p {
            margin: 8px 0;
            font-size: 13px;
        }
        
        /* Two Column */
        .two-column {
            display: flex;
            gap: 40px;
            margin: 20px 0;
        }
        
        .two-column > div {
            flex: 1;
        }
        
        /* Code/Example Blocks */
        .example {
            background: #f4f7fb;
            border: 1px solid #dce7ee;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
            font-family: 'Monaco', 'Courier New', monospace;
            font-size: 12px;
            color: #22313d;
            overflow-x: auto;
        }
        
        /* Footer */
        .footer {
            margin-top: 60px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        
        /* Page Break */
        .page-break {
            page-break-after: always;
        }
        
        /* Step List */
        .step-list {
            margin: 20px 0;
        }
        
        .step-item {
            display: flex;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        
        .step-number {
            background: #0b7a75;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
            margin-right: 15px;
            margin-top: 2px;
        }
        
        .step-content h4 {
            color: #0b7a75;
            margin-bottom: 5px;
            font-size: 15px;
        }
        
        .step-content p {
            margin: 0;
            font-size: 13px;
        }
        
        /* Benefits List */
        .benefits {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }
        
        .benefit-item {
            background: #f0faf8;
            padding: 15px;
            border-radius: 4px;
            border-left: 3px solid #0b7a75;
            page-break-inside: avoid;
        }
        
        .benefit-item strong {
            color: #0b7a75;
            display: block;
            margin-bottom: 5px;
        }
        
        .benefit-item p {
            margin: 0;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <!-- COVER PAGE -->
    <div class="cover-page">
        <h1>SettleANZ</h1>
        <div class="subtitle">Complete SEO System Documentation</div>
        <p style="font-size: 18px; opacity: 0.95;">How to Write, Edit, and Publish Articles with AI-Powered SEO Optimization</p>
        <div class="version">Version 1.0 | May 2026</div>
    </div>

    <!-- TABLE OF CONTENTS -->
    <div class="toc">
        <h2>Table of Contents</h2>
        <ul>
            <li><a href="#intro">1. Introduction to the SEO System</a></li>
            <li><a href="#how-works">2. How the SEO System Works</a></li>
            <li><a href="#blog-creation">3. Creating and Editing Blog Posts</a></li>
            <li><a href="#ai-features">4. AI Writing and SEO Features</a></li>
            <li><a href="#google-recommended">5. Google-Recommended SEO Best Practices</a></li>
            <li><a href="#system-design">6. Technical System Design</a></li>
            <li><a href="#workflow">7. Complete Workflow Guide</a></li>
            <li><a href="#faq">8. Frequently Asked Questions</a></li>
        </ul>
    </div>

    <div class="container">
        <!-- SECTION 1: INTRODUCTION -->
        <div class="section" id="intro">
            <h2>1. Introduction to the SEO System</h2>
            
            <p>The SettleANZ SEO system is a comprehensive, AI-powered platform designed to help create and manage search engine optimized content. This system combines professional SEO best practices with modern AI technology to make content creation faster, easier, and more effective.</p>
            
            <div class="highlight-box">
                <h4>🎯 What This System Does</h4>
                <p>Automatically generates SEO-optimized metadata, validates on-page SEO factors, provides AI-powered content suggestions, and ensures all content meets Google's quality guidelines.</p>
            </div>
            
            <h3>Key Benefits</h3>
            <div class="benefits">
                <div class="benefit-item">
                    <strong>🚀 Faster Content Creation</strong>
                    <p>AI-assisted writing cuts content creation time in half while maintaining quality.</p>
                </div>
                <div class="benefit-item">
                    <strong>📊 Better Rankings</strong>
                    <p>SEO-optimized content ranks higher in Google search results.</p>
                </div>
                <div class="benefit-item">
                    <strong>✅ Quality Assurance</strong>
                    <p>Real-time SEO scoring helps identify issues before publishing.</p>
                </div>
                <div class="benefit-item">
                    <strong>🤖 AI-Powered Suggestions</strong>
                    <p>Smart recommendations improve readability and search visibility.</p>
                </div>
            </div>
        </div>

        <div class="page-break"></div>

        <!-- SECTION 2: HOW IT WORKS -->
        <div class="section" id="how-works">
            <h2>2. How the SEO System Works</h2>
            
            <p>The SettleANZ SEO system operates on three interconnected layers: content editing, SEO optimization, and AI assistance. Each layer works together to produce high-quality, search-optimized content.</p>
            
            <h3>The Three-Layer Architecture</h3>
            
            <div class="feature-box">
                <h4>Layer 1: Content Workspace</h4>
                <p>Where you write and edit your article body. The system provides a rich text editor with formatting options, image support, and real-time previews.</p>
            </div>
            
            <div class="feature-box">
                <h4>Layer 2: SEO Workspace</h4>
                <p>Manages all metadata: title tags, meta descriptions, focus keywords, Open Graph data, and structured data (FAQ, Article schema). Includes real-time SEO scoring.</p>
            </div>
            
            <div class="feature-box">
                <h4>Layer 3: AI Assistance</h4>
                <p>Provides AI-generated content suggestions, SEO improvements, and automated fills for metadata fields based on your content.</p>
            </div>
            
            <h3>Real-Time SEO Score</h3>
            <p>The system calculates an SEO score in real-time based on:</p>
            <ul>
                <li><strong>Meta Title</strong> - Should be 50-60 characters with focus keyword</li>
                <li><strong>Meta Description</strong> - Should be 140-160 characters with focus keyword</li>
                <li><strong>Focus Keyword</strong> - Main search phrase the article targets</li>
                <li><strong>Content Quality</strong> - Article length, readability, keyword usage</li>
                <li><strong>Structured Data</strong> - Schema markup for enhanced search results</li>
                <li><strong>Mobile Friendly</strong> - Content displays well on all devices</li>
            </ul>
        </div>

        <div class="page-break"></div>

        <!-- SECTION 3: BLOG CREATION -->
        <div class="section" id="blog-creation">
            <h2>3. Creating and Editing Blog Posts</h2>
            
            <h3>Step-by-Step: Create a New Blog Post</h3>
            
            <div class="step-list">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h4>Go to Blog Posts Admin</h4>
                        <p>Navigate to the Admin Panel → Blog Posts. Click "Create New Post".</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h4>Enter Basic Information</h4>
                        <p>Add the article title and select a category (Housing, Banking, Migration, Settlement, etc.). These are required fields.</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h4>Write or Import Content</h4>
                        <p>Either write directly in the editor or import from PDF/DOCX. The system auto-fills title, excerpt, and body from uploaded documents.</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h4>Optimize SEO Settings</h4>
                        <p>Fill in SEO metadata: title, description, focus keyword. Use "AI Fill SEO" for automatic suggestions.</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">5</div>
                    <div class="step-content">
                        <h4>Upload Featured Image</h4>
                        <p>Add a featured image (PNG, JPG, WEBP, GIF - max 5MB). This appears in search results and social sharing.</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">6</div>
                    <div class="step-content">
                        <h4>Review and Publish</h4>
                        <p>Check the real-time SEO score. Click "Save Draft" to save as draft or "Save & Publish" to go live.</p>
                    </div>
                </div>
            </div>
            
            <h3>Import from PDF or Word Document</h3>
            <p>The system can automatically extract content from PDF or DOCX files:</p>
            <div class="feature-box">
                <h4>How Document Import Works</h4>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Click "Choose file" or drag a PDF/DOCX onto the upload zone</li>
                    <li>System extracts: title, excerpt (first paragraph), and body content</li>
                    <li>Content is pre-filled into the editor for you to review and edit</li>
                    <li>You can make adjustments before saving</li>
                </ul>
            </div>
            
            <h3>Editor Features</h3>
            <ul>
                <li><strong>Rich Text Formatting:</strong> Bold, italic, underline, headings, lists, quotes</li>
                <li><strong>Links:</strong> Add internal and external links</li>
                <li><strong>Auto-Save:</strong> Draft automatically saves as you type</li>
                <li><strong>Source Edit:</strong> Edit raw HTML if needed</li>
                <li><strong>Preview:</strong> See how content looks before publishing</li>
            </ul>
        </div>

        <div class="page-break"></div>

        <!-- SECTION 4: AI FEATURES -->
        <div class="section" id="ai-features">
            <h2>4. AI Writing and SEO Features</h2>
            
            <p>The system includes two powerful AI features designed to streamline your workflow and improve content quality.</p>
            
            <h3>Feature 1: AI Write Draft</h3>
            <p>This feature generates a complete article draft based on your title and category.</p>
            
            <div class="highlight-box">
                <h4>How to Use AI Write Draft</h4>
                <ol style="padding-left: 20px; margin: 10px 0;">
                    <li>Enter a compelling article title</li>
                    <li>Optionally add a focus keyword you want to target</li>
                    <li>Click "AI Write Draft" button in the Content Workspace</li>
                    <li>Wait 30-60 seconds for AI to generate content</li>
                    <li>Review the generated content and make edits</li>
                    <li>Refine and personalize as needed</li>
                </ol>
            </div>
            
            <p><strong>What the AI generates:</strong></p>
            <ul>
                <li>Well-structured article (1500-2500 words typical)</li>
                <li>Introduction, body sections, conclusion</li>
                <li>Proper headings and subheadings</li>
                <li>Bullet points and lists where appropriate</li>
                <li>Professional, readable content</li>
            </ul>
            
            <h3>Feature 2: AI Fill SEO</h3>
            <p>This feature automatically generates optimized SEO metadata based on your article content.</p>
            
            <div class="highlight-box">
                <h4>How to Use AI Fill SEO</h4>
                <ol style="padding-left: 20px; margin: 10px 0;">
                    <li>Write or import your article content</li>
                    <li>Go to the SEO Workspace tab</li>
                    <li>Click "AI Fill SEO" button</li>
                    <li>System analyzes content and generates:</li>
                </ol>
                <ul style="padding-left: 40px; margin-top: 10px;">
                    <li>SEO title (50-60 characters)</li>
                    <li>Meta description (140-160 characters)</li>
                    <li>Focus keyword</li>
                    <li>Secondary keywords</li>
                    <li>Open Graph tags</li>
                    <li>FAQ schema markup</li>
                </ul>
            </div>
            
            <p><strong>What makes AI SEO fill powerful:</strong></p>
            <ul>
                <li>Analyzes your article to find key topics</li>
                <li>Generates keyword phrases that match search intent</li>
                <li>Creates titles and descriptions optimized for clicks</li>
                <li>Suggests FAQ questions readers commonly ask</li>
                <li>Ensures all recommendations follow Google guidelines</li>
            </ul>
        </div>

        <div class="page-break"></div>

        <!-- SECTION 5: GOOGLE RECOMMENDED -->
        <div class="section" id="google-recommended">
            <h2>5. Google-Recommended SEO Best Practices</h2>
            
            <p>The SettleANZ system is built on Google's official SEO recommendations and Search Quality Guidelines. Here's how we implement them:</p>
            
            <h3>1. Focus on Quality Content</h3>
            <div class="feature-box">
                <h4>Google's Guidance</h4>
                <p>Create content written by people, for people. Content should be original, useful, and demonstrate expertise.</p>
                <h4>How We Help</h4>
                <ul style="padding-left: 20px; margin: 10px 0;">
                    <li>AI suggestions enhance human-written content</li>
                    <li>Readability checks ensure clarity</li>
                    <li>Content length recommendations (800+ words for quality)</li>
                    <li>Plagiarism awareness</li>
                </ul>
            </div>
            
            <h3>2. Use Proper Meta Tags</h3>
            <div class="feature-box">
                <h4>Google's Guidance</h4>
                <p>Provide unique, descriptive page titles and meta descriptions. These help both users and search engines understand your content.</p>
                <h4>Our Implementation</h4>
                <ul style="padding-left: 20px; margin: 10px 0;">
                    <li>Title tag length: 50-60 characters (Google displays ~60 in results)</li>
                    <li>Meta description: 140-160 characters</li>
                    <li>Both should include target keyword naturally</li>
                    <li>Character counters prevent truncation</li>
                </ul>
            </div>
            
            <h3>3. Implement Structured Data</h3>
            <div class="feature-box">
                <h4>Google's Guidance</h4>
                <p>Use Schema.org structured data to help search engines understand content and display rich results.</p>
                <h4>What We Include</h4>
                <ul style="padding-left: 20px; margin: 10px 0;">
                    <li><strong>Article Schema:</strong> Marks up article metadata</li>
                    <li><strong>BlogPosting Schema:</strong> Identifies blog content</li>
                    <li><strong>NewsArticle Schema:</strong> For news-style content</li>
                    <li><strong>FAQ Schema:</strong> Enables FAQ rich results</li>
                    <li><strong>BreadcrumbList:</strong> Improves navigation in search</li>
                </ul>
            </div>
            
            <h3>4. Mobile-First Indexing</h3>
            <p>Google primarily indexes the mobile version of content. The SettleANZ system ensures:</p>
            <ul>
                <li>Responsive design that works on all devices</li>
                <li>Fast load times (images optimized)</li>
                <li>Readable text on mobile (no tiny fonts)</li>
                <li>Clickable elements properly spaced</li>
            </ul>
            
            <h3>5. Keyword Strategy</h3>
            <div class="feature-box">
                <h4>Our Approach</h4>
                <ul style="padding-left: 20px;">
                    <li>Focus on ONE primary keyword per article</li>
                    <li>Use secondary keywords naturally throughout</li>
                    <li>Include keyword in: title, description, headings, body</li>
                    <li>Avoid keyword stuffing (keep content natural)</li>
                    <li>Target long-tail keywords (more specific, lower competition)</li>
                </ul>
            </div>
            
            <h3>6. On-Page SEO Checklist</h3>
            <p>The system displays a real-time checklist:</p>
            <ul>
                <li>✓ Focus keyword set</li>
                <li>✓ Keyword in SEO title</li>
                <li>✓ Keyword in meta description</li>
                <li>✓ Keyword in slug (URL)</li>
                <li>✓ Keyword used naturally in article body</li>
                <li>✓ Article has proper headings</li>
                <li>✓ Meta descriptions all filled</li>
                <li>✓ Content length sufficient</li>
            </ul>
        </div>

        <div class="page-break"></div>

        <!-- SECTION 6: SYSTEM DESIGN -->
        <div class="section" id="system-design">
            <h2>6. Technical System Design</h2>
            
            <p>The SettleANZ SEO system is built using modern, scalable technologies designed for reliability, performance, and ease of use.</p>
            
            <h3>Architecture Overview</h3>
            
            <div class="example">
Frontend (User Interface)
    ├── Article Editor
    ├── SEO Settings Panel
    ├── Real-time SEO Score
    ├── AI Integration
    └── Document Import

Backend (Server)
    ├── Laravel Framework (PHP)
    ├── Database (SQLite/MySQL)
    ├── API Integration (AI Service)
    ├── PDF/Document Parser
    └── Notification System

External Services
    ├── AI Language Model (Content Generation)
    ├── Google Search Console
    └── Analytics
            </div>
            
            <h3>Key Components</h3>
            
            <h4>1. Rich Text Editor (TinyMCE)</h4>
            <p>Professional WYSIWYG editor with:</p>
            <ul>
                <li>Formatting toolbar (bold, italic, headings, etc.)</li>
                <li>Link and image insertion</li>
                <li>HTML source editing mode</li>
                <li>Character count</li>
                <li>Real-time preview</li>
            </ul>
            
            <h4>2. SEO Score Engine</h4>
            <p>Calculates scores based on:</p>
            <ul>
                <li>Meta tag quality and length</li>
                <li>Keyword presence and distribution</li>
                <li>Content length and readability</li>
                <li>Structured data presence</li>
                <li>Mobile compatibility</li>
            </ul>
            
            <h4>3. AI Integration</h4>
            <p>Powered by advanced language models that:</p>
            <ul>
                <li>Generate article content from titles and keywords</li>
                <li>Analyze content for SEO opportunities</li>
                <li>Generate optimized meta tags</li>
                <li>Suggest keyword phrases</li>
                <li>Create FAQ schema markup</li>
            </ul>
            
            <h4>4. Document Parser</h4>
            <p>Extracts content from PDF and DOCX files:</p>
            <ul>
                <li>PDF parsing using smalot/pdf-parser</li>
                <li>DOCX parsing using phpoffice/phpword</li>
                <li>Text extraction and cleanup</li>
                <li>Structure preservation</li>
            </ul>
            
            <h3>Data Flow</h3>
            <div class="example">
User Action → Frontend Form → AJAX Request → Backend Validation
    → AI Processing (if needed) → Database Save → 
    Response with SEO Score → Frontend Notification
            </div>
            
            <h3>Security Features</h3>
            <ul>
                <li><strong>Authentication:</strong> Admin-only access with session management</li>
                <li><strong>Input Validation:</strong> All user inputs validated server-side</li>
                <li><strong>CSRF Protection:</strong> Token-based form submissions</li>
                <li><strong>File Upload Security:</strong> Type validation, size limits, safe naming</li>
                <li><strong>SQL Injection Prevention:</strong> Parameterized queries</li>
                <li><strong>XSS Protection:</strong> Output escaping and sanitization</li>
            </ul>
        </div>

        <div class="page-break"></div>

        <!-- SECTION 7: WORKFLOW -->
        <div class="section" id="workflow">
            <h2>7. Complete Workflow Guide</h2>
            
            <h3>Scenario 1: Writing an Article from Scratch</h3>
            
            <div class="step-list">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h4>Plan Your Article</h4>
                        <p>Decide on topic, target audience, and main keyword. Example: "How to Open a Bank Account in Australia as a New Immigrant" with keyword "open bank account Australia new resident"</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h4>Create New Blog Post</h4>
                        <p>Go to Admin → Blog Posts → Create New Post. Enter title and category.</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h4>Use AI Write Draft (Optional)</h4>
                        <p>Click "AI Write Draft" to generate content. Takes 30-60 seconds. Review and edit the generated content to match your style and add specific information.</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h4>Polish the Content</h4>
                        <p>Edit for clarity, add your expertise, verify facts, add links to relevant resources. Make content personal and authentic.</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">5</div>
                    <div class="step-content">
                        <h4>Switch to SEO Tab</h4>
                        <p>Click on the "SEO" tab in the editor. Enter your focus keyword.</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">6</div>
                    <div class="step-content">
                        <h4>Use AI Fill SEO</h4>
                        <p>Click "AI Fill SEO". System analyzes your content and auto-fills: title, description, secondary keywords, and FAQ schema.</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">7</div>
                    <div class="step-content">
                        <h4>Review SEO Settings</h4>
                        <p>Check the generated SEO metadata. Edit if needed. Verify focus keyword appears naturally in your article. Check SEO score (aim for 80+).</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">8</div>
                    <div class="step-content">
                        <h4>Upload Featured Image</h4>
                        <p>Add a relevant image. This shows in search results and when shared on social media.</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">9</div>
                    <div class="step-content">
                        <h4>Save or Publish</h4>
                        <p>Click "Save Draft" to save without publishing, or "Save & Publish" to make live immediately.</p>
                    </div>
                </div>
            </div>
            
            <h3>Scenario 2: Importing from a PDF or Word Document</h3>
            
            <div class="step-list">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h4>Prepare Your Document</h4>
                        <p>Ensure your PDF or DOCX has: clear title, organized structure, and main content body.</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h4>Create New Blog Post</h4>
                        <p>Go to Admin → Blog Posts → Create New Post.</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h4>Upload Document</h4>
                        <p>In the Content Workspace, click "Choose file" or drag your PDF/DOCX onto the upload zone.</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h4>Review Extracted Content</h4>
                        <p>System auto-fills title, excerpt, and body. Check that everything extracted correctly.</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">5</div>
                    <div class="step-content">
                        <h4>Edit Content</h4>
                        <p>Fix any formatting issues, add links, improve readability. The editor preserves structure and styling.</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">6</div>
                    <div class="step-content">
                        <h4>Continue with SEO (Steps 5-9 from Scenario 1)</h4>
                        <p>Follow the same SEO optimization process as writing from scratch.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-break"></div>

        <!-- SECTION 8: FAQ -->
        <div class="section" id="faq">
            <h2>8. Frequently Asked Questions</h2>
            
            <h3>General Questions</h3>
            
            <div class="feature-box">
                <h4>Q: How does AI Write Draft work?</h4>
                <p>AI Write Draft uses advanced language models to generate article content based on your title, category, and keywords. It creates well-structured, original content that you can then personalize with your expertise.</p>
            </div>
            
            <div class="feature-box">
                <h4>Q: Is the AI-generated content unique?</h4>
                <p>Yes. Each article is generated fresh based on your specific title and parameters. The system doesn't copy from existing sources. However, you should always review and personalize the content to match your voice and add specific examples.</p>
            </div>
            
            <div class="feature-box">
                <h4>Q: What's the difference between focus keyword and secondary keywords?</h4>
                <p><strong>Focus keyword:</strong> The main phrase you want the article to rank for (e.g., "open bank account Australia"). <strong>Secondary keywords:</strong> Related phrases that support the main topic (e.g., "checking account Australia", "identification for bank account").</p>
            </div>
            
            <h3>SEO Questions</h3>
            
            <div class="feature-box">
                <h4>Q: What does the SEO score mean?</h4>
                <p>The SEO score (0-100) indicates how well-optimized your article is. It's based on meta tag quality, keyword implementation, content length, readability, and structured data. Aim for 80+ for best results.</p>
            </div>
            
            <div class="feature-box">
                <h4>Q: Why is my SEO score low?</h4>
                <p>Check the "On-Page Checklist" in the SEO panel. Common reasons: focus keyword not set, keyword not in title/description, article too short (&lt;800 words), or meta tags missing.</p>
            </div>
            
            <div class="feature-box">
                <h4>Q: How long does it take for new articles to rank?</h4>
                <p>Google typically crawls new content within 24-48 hours. Ranking position depends on competition and content quality. Most articles take 2-4 weeks to reach their target position, though some may rank within days.</p>
            </div>
            
            <div class="feature-box">
                <h4>Q: Can I edit SEO settings after publishing?</h4>
                <p>Yes! You can edit any SEO settings anytime, including after publishing. Changes take effect on the next Google crawl (typically 24-48 hours).</p>
            </div>
            
            <h3>Technical Questions</h3>
            
            <div class="feature-box">
                <h4>Q: What file formats are supported for document import?</h4>
                <p>PDF (.pdf) and Word documents (.docx, .doc). Files must be under 5MB. Supported formats: application/pdf, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/msword.</p>
            </div>
            
            <div class="feature-box">
                <h4>Q: What image formats work for featured images?</h4>
                <p>PNG, JPG, WEBP, AVIF, and GIF. Maximum size: 5MB. Recommended dimensions: 1200x600px for social sharing.</p>
            </div>
            
            <div class="feature-box">
                <h4>Q: How do I fix if AI Fill SEO doesn't fill some fields?</h4>
                <p>If the AI can't fill certain fields, manually enter them. Make sure your article content is substantial (800+ words) for best AI suggestions.</p>
            </div>
            
            <h3>Workflow Questions</h3>
            
            <div class="feature-box">
                <h4>Q: Can I save as draft and finish later?</h4>
                <p>Yes! Click "Save Draft" at any time. Your work is saved automatically as you type. Come back to finish later.</p>
            </div>
            
            <div class="feature-box">
                <h4>Q: What happens when I click "Save & Publish"?</h4>
                <p>The article is saved and immediately published to your website. It becomes visible to search engines and visitors. You can unpublish anytime by editing the article and unchecking "Published".</p>
            </div>
            
            <div class="feature-box">
                <h4>Q: Can I delete an article after publishing?</h4>
                <p>Yes. Edit the article and click the "Delete" button. Or unpublish it first (uncheck "Published") to hide it without deleting. Deleted articles cannot be recovered.</p>
            </div>
        </div>

        <div class="page-break"></div>

        <!-- FINAL PAGE -->
        <div class="section">
            <h2>Best Practices Summary</h2>
            
            <h3>For Best SEO Results:</h3>
            <ul>
                <li><strong>Content Length:</strong> Aim for 1000-2000 words for comprehensive coverage</li>
                <li><strong>Keyword Usage:</strong> Include focus keyword 1-2 times per 500 words (natural integration)</li>
                <li><strong>Structure:</strong> Use clear headings, lists, and formatting for readability</li>
                <li><strong>Links:</strong> Link to 3-5 relevant internal pages and 2-3 authoritative external sources</li>
                <li><strong>Images:</strong> Include 2-4 relevant images with descriptive alt text</li>
                <li><strong>Updates:</strong> Refresh outdated articles with fresh information annually</li>
                <li><strong>Publishing Frequency:</strong> Publish consistently (1-2 articles weekly if possible)</li>
                <li><strong>Mobile Testing:</strong> Always preview on mobile before publishing</li>
            </ul>
            
            <h3>Common Mistakes to Avoid:</h3>
            <ul>
                <li>❌ Keyword stuffing (overusing keywords unnaturally)</li>
                <li>❌ Duplicate content (exact same content on multiple pages)</li>
                <li>❌ Thin content (less than 300 words)</li>
                <li>❌ Missing meta tags</li>
                <li>❌ No focus keyword</li>
                <li>❌ Broken internal links</li>
                <li>❌ Poor mobile experience</li>
                <li>❌ No featured image</li>
            </ul>
            
            <div class="footer">
                <p><strong>SettleANZ SEO System Documentation v1.0</strong></p>
                <p>© 2026 SettleANZ. All rights reserved. For support, contact the development team.</p>
                <p>Last Updated: May 2026</p>
            </div>
        </div>
    </div>
</body>
</html>
@endsection
