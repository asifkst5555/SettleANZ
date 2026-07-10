@extends('admin.layouts.app')

@section('content')
    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">Overview</p>
                <h2>Operations dashboard</h2>
                <p>Track enquiries, monitor content, and manage the pieces that power the public site.</p>
            </div>
            <a class="button button--small" href="/" target="_blank" rel="noreferrer">Open site</a>
        </section>

        <section class="admin-card-stats-grid">
            <x-admin-stat-card label="Total Leads" :value="$leadCount" desc="Stored enquiries & submissions" />
            <x-admin-stat-card label="New Leads" :value="$newLeadCount" desc="Pending review or follow-up" :accent="true" />
            <x-admin-stat-card label="Ebook Downloads" :value="$ebookStats['overview']['total_downloads'] ?? 0" desc="Downloaded via magnet landing pages" />
            <x-admin-stat-card label="Consultations" :value="$consultationBookingCount" desc="Migration agent bookings" />
            <x-admin-stat-card label="Package Requests" :value="$packageBookingCount" desc="Settlement packages requested" />
        </section>

        <section class="admin-two-column-grid">
            <section class="admin-panel-card">
                <div class="admin-section-head">
                    <div><h3>Recent leads</h3><p>Latest enquiries from contact, migration, popup, and directory forms.</p></div>
                    <a class="text-link" href="{{ route('admin.leads.index') }}">Manage leads</a>
                </div>
                <div class="admin-list-stack">
                    @foreach ($recentLeads as $lead)
                        <article class="admin-list-item">
                            <div><strong>{{ $lead->full_name ?: $lead->first_name ?: 'Unknown' }}</strong><small><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; vertical-align: middle; margin-right: 0.25rem;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>{{ $lead->email }}</small></div>
                            <div><span class="admin-badge">{{ $lead->form_type }}</span><small>{{ ucfirst($lead->status) }}</small></div>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="admin-panel-card">
                <div class="admin-section-head">
                    <div><h3>Booked consultations</h3><p>Latest booking requests from featured migration agents.</p></div>
                    <a class="text-link" href="{{ route('admin.leads.index', ['type' => 'consultation-booking']) }}">View all bookings</a>
                </div>
                <div class="admin-list-stack">
                    @forelse ($recentConsultationBookings as $lead)
                        <article class="admin-list-item">
                            <div>
                                <strong>{{ $lead->full_name ?: 'Unknown' }}</strong>
                                <small>{{ data_get($lead->metadata, 'listing_name') ?: 'Migration consultation' }}</small>
                                <small>{{ data_get($lead->metadata, 'preferred_date') ?: 'Date not selected' }}{{ data_get($lead->metadata, 'preferred_time') ? ' - ' . data_get($lead->metadata, 'preferred_time') : '' }}</small>
                            </div>
                            <div>
                                <small>{{ $lead->created_at?->format('d M Y') }}</small>
                                <a class="text-link" href="{{ route('admin.leads.edit', $lead) }}">Open</a>
                            </div>
                        </article>
                    @empty
                        <p class="admin-empty-text">No consultation bookings yet.</p>
                    @endforelse
                </div>
            </section>
        </section>

        <section class="admin-two-column-grid">
            <section class="admin-panel-card">
                <div class="admin-section-head">
                    <div><h3>Package booking requests</h3><p>Latest booking requests from settlement services page.</p></div>
                    <a class="text-link" href="{{ route('admin.leads.index', ['type' => 'package_booking']) }}">View all package requests</a>
                </div>
                <div class="admin-list-stack">
                    @forelse ($recentPackageBookings as $lead)
                        <article class="admin-list-item">
                            <div>
                                <strong>{{ $lead->full_name ?: 'Unknown' }}</strong>
                                <small><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; vertical-align: middle; margin-right: 0.25rem;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>{{ $lead->email }}</small>
                                <small>{{ data_get($lead->metadata, 'subject') ?: 'Package booking' }}</small>
                            </div>
                            <div>
                                <small>{{ $lead->created_at?->format('d M Y') }}</small>
                                <a class="text-link" href="{{ route('admin.leads.edit', $lead) }}">Open</a>
                            </div>
                        </article>
                    @empty
                        <p class="admin-empty-text">No package booking requests yet.</p>
                    @endforelse
                </div>
            </section>

            <section class="admin-panel-card">
                <div class="admin-section-head">
                    <div><h3>Contact form submissions</h3><p>Latest messages sent from the public contact page.</p></div>
                    <a class="text-link" href="{{ route('admin.leads.index', ['type' => 'contact-page']) }}">View all contact leads</a>
                </div>
                <div class="admin-list-stack">
                    @forelse ($recentContactLeads as $lead)
                        <article class="admin-list-item">
                            <div><strong>{{ $lead->full_name ?: 'Unknown' }}</strong><small><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; vertical-align: middle; margin-right: 0.25rem;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>{{ $lead->email }}</small><small>{{ \Illuminate\Support\Str::limit(data_get($lead->metadata, 'message'), 90) }}</small></div>
                            <div><small>{{ $lead->created_at?->format('d M Y') }}</small><a class="text-link" href="{{ route('admin.leads.edit', $lead) }}">Open</a></div>
                        </article>
                    @empty
                        <p class="admin-empty-text">No contact form submissions yet.</p>
                    @endforelse
                </div>
            </section>

            <section class="admin-panel-card">
                <div class="admin-section-head">
                    <div><h3>Top Performing Ebooks</h3><p>Most popular downloads and conversion rates.</p></div>
                    <a class="text-link" href="{{ route('admin.ebook-analytics.index') }}">Full Analytics</a>
                </div>
                <div class="admin-table-wrap" style="margin-top: 1rem; border: none; box-shadow: none;">
                    <table class="admin-table" style="font-size: 0.85rem;">
                        <thead>
                            <tr>
                                <th style="padding: 0.5rem 0.75rem;">Ebook Title</th>
                                <th style="padding: 0.5rem 0.75rem; text-align: center;">Downloads</th>
                                <th style="padding: 0.5rem 0.75rem; text-align: center;">Leads</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ebookStats['top_ebooks'] ?? [] as $ebook)
                                <tr>
                                    <td style="padding: 0.65rem 0.75rem;">
                                        <strong>{{ $ebook['title'] }}</strong>
                                        <small style="color:var(--admin-muted); display: block; margin-top: 0.2rem;">Format: {{ strtoupper($ebook['file_type'] ?? 'pdf') }}</small>
                                    </td>
                                    <td style="padding: 0.65rem 0.75rem; text-align: center; font-weight: 700; color: #0b7a75;">
                                        {{ $ebook['download_count'] }}
                                    </td>
                                    <td style="padding: 0.65rem 0.75rem; text-align: center;">
                                        {{ $ebook['lead_count'] }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align: center; color: var(--admin-muted); padding: 1.5rem 0;">
                                        No ebooks published yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="admin-panel-card">
                <div class="admin-section-head">
                    <div><h3>Content snapshot</h3><p>Quick links into content and partner management.</p></div>
                </div>
                <div class="admin-action-grid">
                    <a class="admin-action-card" href="{{ route('admin.blog-posts.create') }}"><strong>New blog post</strong><span>Create a new article and publish it to the blog.</span></a>
                    <a class="admin-action-card" href="{{ route('admin.directory-listings.create') }}"><strong>New directory listing</strong><span>Add a new service provider, partner, or featured business.</span></a>
                    <a class="admin-action-card" href="{{ route('admin.settings.edit') }}"><strong>AI & Site Settings</strong><span>Manage AI assistant, API keys, knowledge base, and contact settings.</span></a>
                </div>
            </section>
        </section>

        <section class="admin-two-column-grid">
            <section class="admin-panel-card">
                <div class="admin-section-head">
                    <div><h3>Recent blog posts</h3><p>Newest content currently in the system.</p></div>
                    <a class="text-link" href="{{ route('admin.blog-posts.index') }}">View all posts</a>
                </div>
                <div class="admin-list-stack">
                    @foreach ($recentPosts as $post)
                        <article class="admin-list-item">
                            <div><strong>{{ $post->title }}</strong><small>{{ $post->category }}</small></div>
                            <div><small>{{ optional($post->published_at)->format('d M Y') }}</small><a class="text-link" href="{{ route('admin.blog-posts.edit', $post) }}">Edit</a></div>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="admin-panel-card">
                <div class="admin-section-head">
                    <div><h3>Featured listings</h3><p>Highlighted businesses currently promoted on-site.</p></div>
                    <a class="text-link" href="{{ route('admin.directory-listings.index') }}">View listings</a>
                </div>
                <div class="admin-list-stack">
                    @foreach ($featuredListings as $listing)
                        <article class="admin-list-item">
                            <div><strong>{{ $listing->name }}</strong><small>{{ $listing->category }} - {{ $listing->city }}</small></div>
                            <div><small>{{ $listing->rating }} stars</small><a class="text-link" href="{{ route('admin.directory-listings.edit', $listing) }}">Edit</a></div>
                        </article>
                    @endforeach
                </div>
            </section>
        </section>
    </div>
@endsection
