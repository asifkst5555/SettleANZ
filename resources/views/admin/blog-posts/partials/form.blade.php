<section class="admin-panel-card">
    <form class="admin-edit-form" method="POST" action="{{ $action }}">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        <div class="admin-form-grid">
            <label>
                <span>Title</span>
                <input type="text" name="title" value="{{ old('title', $post->title) }}" required>
            </label>
            <label>
                <span>Slug</span>
                <input type="text" name="slug" value="{{ old('slug', $post->slug) }}">
            </label>
            <label>
                <span>Category</span>
                <input type="text" name="category" value="{{ old('category', $post->category) }}" required>
            </label>
            <label>
                <span>Author</span>
                <input type="text" name="author_name" value="{{ old('author_name', $post->author_name) }}" required>
            </label>
            <label>
                <span>Reading time</span>
                <input type="text" name="reading_time" value="{{ old('reading_time', $post->reading_time) }}">
            </label>
            <label>
                <span>Published at</span>
                <input type="datetime-local" name="published_at" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}">
            </label>
            <label>
                <span>Image filename</span>
                <input type="text" name="image" value="{{ old('image', $post->image) }}" placeholder="Example.webp">
            </label>
            <label>
                <span>Fallback image class</span>
                <input type="text" name="image_class" value="{{ old('image_class', $post->image_class) }}">
            </label>
        </div>

        <label>
            <span>Excerpt</span>
            <textarea name="excerpt" rows="3" required>{{ old('excerpt', $post->excerpt) }}</textarea>
        </label>
        <label>
            <span>Intro content</span>
            <textarea name="intro_content" rows="5">{{ old('intro_content', $post->intro_content) }}</textarea>
        </label>
        <label>
            <span>What to check content</span>
            <textarea name="checks_content" rows="5">{{ old('checks_content', $post->checks_content) }}</textarea>
        </label>
        <label>
            <span>Best next step content</span>
            <textarea name="next_steps_content" rows="5">{{ old('next_steps_content', $post->next_steps_content) }}</textarea>
        </label>

        <div class="admin-checkbox-row">
            <label class="admin-inline-checkbox">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1" @checked((bool) old('is_published', $post->is_published))>
                <span>Published</span>
            </label>
            <label class="admin-inline-checkbox">
                <input type="hidden" name="is_featured_home" value="0">
                <input type="checkbox" name="is_featured_home" value="1" @checked((bool) old('is_featured_home', $post->is_featured_home))>
                <span>Show on homepage</span>
            </label>
        </div>

        <button class="button button--large" type="submit">Save blog post</button>
    </form>
</section>
