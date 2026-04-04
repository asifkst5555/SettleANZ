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

        <section class="admin-stats-grid">
            <article class="admin-stat-card"><span>Total leads</span><strong>{{ $leadCount }}</strong><small>Stored enquiries and form submissions</small></article>
            <article class="admin-stat-card"><span>New leads</span><strong>{{ $newLeadCount }}</strong><small>Waiting for review or follow-up</small></article>
            <article class="admin-stat-card"><span>Contact form leads</span><strong>{{ $contactLeadCount }}</strong><small>Submitted through the contact page</small></article>
            <article class="admin-stat-card"><span>Booked consultations</span><strong>{{ $consultationBookingCount }}</strong><small>Requests sent from migration agents section</small></article>
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
                            <div><strong>{{ $lead->full_name ?: $lead->first_name ?: 'Unknown' }}</strong><small>{{ $lead->email }}</small></div>
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
                    <div><h3>Contact form submissions</h3><p>Latest messages sent from the public contact page.</p></div>
                    <a class="text-link" href="{{ route('admin.leads.index', ['type' => 'contact-page']) }}">View all contact leads</a>
                </div>
                <div class="admin-list-stack">
                    @forelse ($recentContactLeads as $lead)
                        <article class="admin-list-item">
                            <div><strong>{{ $lead->full_name ?: 'Unknown' }}</strong><small>{{ $lead->email }}</small><small>{{ \Illuminate\Support\Str::limit(data_get($lead->metadata, 'message'), 90) }}</small></div>
                            <div><small>{{ $lead->created_at?->format('d M Y') }}</small><a class="text-link" href="{{ route('admin.leads.edit', $lead) }}">Open</a></div>
                        </article>
                    @empty
                        <p class="admin-empty-text">No contact form submissions yet.</p>
                    @endforelse
                </div>
            </section>

            <section class="admin-panel-card">
                <div class="admin-section-head">
                    <div><h3>Content snapshot</h3><p>Quick links into content and partner management.</p></div>
                </div>
                <div class="admin-action-grid">
                    <a class="admin-action-card" href="{{ route('admin.blog-posts.create') }}"><strong>New blog post</strong><span>Create a new article and publish it to the blog.</span></a>
                    <a class="admin-action-card" href="{{ route('admin.directory-listings.create') }}"><strong>New directory listing</strong><span>Add a new service provider, partner, or featured business.</span></a>
                    <a class="admin-action-card" href="{{ route('admin.settings.edit') }}"><strong>Update site settings</strong><span>Edit contact details, footer links, and CTA destinations.</span></a>
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
