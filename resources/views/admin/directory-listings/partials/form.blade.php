<section class="admin-panel-card">
    <form class="admin-edit-form" method="POST" action="{{ $action }}">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        <div class="admin-form-grid">
            <label>
                <span>Name</span>
                <input type="text" name="name" value="{{ old('name', $listing->name) }}" required>
            </label>
            <label>
                <span>Slug</span>
                <input type="text" name="slug" value="{{ old('slug', $listing->slug) }}">
            </label>
            <label>
                <span>Category</span>
                <input type="text" name="category" value="{{ old('category', $listing->category) }}" required>
            </label>
            <label>
                <span>City</span>
                <input type="text" name="city" value="{{ old('city', $listing->city) }}" required>
            </label>
            <label>
                <span>Rating</span>
                <input type="number" step="0.1" min="0" max="5" name="rating" value="{{ old('rating', $listing->rating) }}" required>
            </label>
            <label>
                <span>Logo filename</span>
                <input type="text" name="logo" value="{{ old('logo', $listing->logo) }}">
            </label>
            <label>
                <span>Phone</span>
                <input type="text" name="phone" value="{{ old('phone', $listing->phone) }}">
            </label>
            <label>
                <span>Email</span>
                <input type="email" name="email" value="{{ old('email', $listing->email) }}">
            </label>
            <label>
                <span>Website</span>
                <input type="text" name="website" value="{{ old('website', $listing->website) }}">
            </label>
            <label>
                <span>WhatsApp</span>
                <input type="text" name="whatsapp" value="{{ old('whatsapp', $listing->whatsapp) }}">
            </label>
            <label>
                <span>Booking URL</span>
                <input type="text" name="booking_url" value="{{ old('booking_url', $listing->booking_url) }}">
            </label>
        </div>

        <label>
            <span>Short description</span>
            <textarea name="description" rows="3" required>{{ old('description', $listing->description) }}</textarea>
        </label>
        <label>
            <span>Full description</span>
            <textarea name="full_description" rows="5">{{ old('full_description', $listing->full_description) }}</textarea>
        </label>
        <label>
            <span>Services, one per line</span>
            <textarea name="services" rows="6">{{ old('services', is_array($listing->services) ? implode(PHP_EOL, $listing->services) : '') }}</textarea>
        </label>

        <div class="admin-checkbox-row">
            <label class="admin-inline-checkbox">
                <input type="hidden" name="featured" value="0">
                <input type="checkbox" name="featured" value="1" @checked((bool) old('featured', $listing->featured))>
                <span>Featured</span>
            </label>
            <label class="admin-inline-checkbox">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1" @checked((bool) old('is_published', $listing->is_published))>
                <span>Published</span>
            </label>
        </div>

        <button class="button button--large" type="submit">Save listing</button>
    </form>
</section>
