<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogPostController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        $status = (string) $request->query('status', 'all');
        $baseQuery = BlogPost::query();
        $postsQuery = BlogPost::query();

        if ($status === 'draft') {
            $postsQuery->where('is_published', false);
        } elseif ($status === 'published') {
            $postsQuery->where('is_published', true);
        } elseif ($status === 'featured') {
            $postsQuery->where('is_featured_home', true);
        }

        return view('admin.blog-posts.index', [
            'metaTitle' => 'Blog Posts | SettleANZ Admin',
            'statusFilter' => $status,
            'stats' => [
                'all' => (clone $baseQuery)->count(),
                'draft' => (clone $baseQuery)->where('is_published', false)->count(),
                'published' => (clone $baseQuery)->where('is_published', true)->count(),
                'featured' => (clone $baseQuery)->where('is_featured_home', true)->count(),
            ],
            'posts' => $postsQuery
                ->orderByRaw('CASE WHEN is_published = 1 THEN 0 ELSE 1 END')
                ->orderByDesc('published_at')
                ->orderByDesc('updated_at')
                ->paginate(15)
                ->withQueryString(),
        ]);
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.blog-posts.create', [
            'metaTitle' => 'New Blog Post | SettleANZ Admin',
            'post' => new BlogPost([
                'author_name' => 'SettleANZ Team',
                'reading_time' => '6 min read',
                'image_class' => 'guide-feature-card__image--teal',
                'is_published' => false,
                'is_featured_home' => false,
                'schema_type' => 'Article',
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $this->validatePost($request);
        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);
        $validated['faq_items'] = $this->normalizeFaqItems($validated['faq_items'] ?? null);
        $validated = $this->applyPublicationState($validated, $request);

        $post = BlogPost::create($validated);

        $message = $validated['is_published'] ? 'Blog post published successfully.' : 'Draft saved successfully.';

        if ($request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('admin.blog-posts.edit', $post),
            ]);
        }

        return redirect()->route('admin.blog-posts.edit', $post)->with('status', $message);
    }

    public function edit(Request $request, BlogPost $blogPost): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.blog-posts.edit', [
            'metaTitle' => 'Edit Blog Post | SettleANZ Admin',
            'post' => $blogPost,
        ]);
    }

    public function update(Request $request, BlogPost $blogPost): RedirectResponse|JsonResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $this->validatePost($request, $blogPost->id);
        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);
        $validated['faq_items'] = $this->normalizeFaqItems($validated['faq_items'] ?? null);
        $validated = $this->applyPublicationState($validated, $request);

        $blogPost->update($validated);

        $message = $validated['is_published'] ? 'Blog post published successfully.' : 'Draft saved successfully.';

        if ($request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('admin.blog-posts.edit', $blogPost),
            ]);
        }

        return redirect()->route('admin.blog-posts.edit', $blogPost)->with('status', $message);
    }

    public function destroy(Request $request, BlogPost $blogPost): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $blogPost->delete();

        return redirect()->route('admin.blog-posts.index')->with('status', 'Blog post deleted successfully.');
    }

    public function updateStatus(Request $request, BlogPost $blogPost): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $request->validate([
            'action' => ['required', 'in:publish,unpublish,feature,unfeature'],
        ]);

        $action = $validated['action'];

        if ($action === 'publish') {
            $blogPost->update([
                'is_published' => true,
                'published_at' => $blogPost->published_at ?: Carbon::now(),
            ]);

            return back()->with('status', 'Blog post published successfully.');
        }

        if ($action === 'unpublish') {
            $blogPost->update([
                'is_published' => false,
                'is_featured_home' => false,
                'published_at' => null,
            ]);

            return back()->with('status', 'Blog post moved to drafts.');
        }

        if ($action === 'feature') {
            $blogPost->update([
                'is_featured_home' => true,
                'is_published' => true,
                'published_at' => $blogPost->published_at ?: Carbon::now(),
            ]);

            return back()->with('status', 'Blog post featured successfully.');
        }

        $blogPost->update([
            'is_featured_home' => false,
        ]);

        return back()->with('status', 'Blog post removed from featured successfully.');
    }

    public function importFile(Request $request): JsonResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $request->validate([
            'document' => ['required', 'file', 'mimes:pdf,docx,doc', 'max:10240'],
        ]);

        $file = $request->file('document');
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $tempPath = $file->getRealPath();

        try {
            if ($extension === 'docx' || $extension === 'doc') {
                $extracted = $this->parseDocx($tempPath);
            } elseif ($extension === 'pdf') {
                $extracted = $this->parsePdf($tempPath);
            } else {
                return response()->json(['message' => 'Unsupported file type.'], 422);
            }
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'message' => 'Could not parse the document. ' . $e->getMessage(),
            ], 500);
        }

        // Build derived fields
        $bodyText = trim(strip_tags($extracted['body_html']));
        $wordCount = $bodyText === '' ? 0 : str_word_count($bodyText);
        $minutes = max(1, (int) ceil($wordCount / 200));

        $title = $extracted['title'] ?: Str::limit($bodyText, 80, '');
        $excerpt = Str::limit(preg_replace('/\s+/', ' ', $bodyText), 280, '...');

        return response()->json([
            'title'        => $title,
            'excerpt'      => $excerpt,
            'body_html'    => $extracted['body_html'],
            'reading_time' => $minutes . ' min read',
            'word_count'   => $wordCount,
        ]);
    }

    /**
     * Parse a DOCX file using PhpWord. Returns ['title' => ..., 'body_html' => ...].
     */
    protected function parseDocx(string $path): array
    {
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($path);

        $title = '';
        $htmlParts = [];

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                $rendered = $this->renderDocxElement($element, $title);
                if ($rendered !== '') {
                    $htmlParts[] = $rendered;
                }
            }
        }

        return [
            'title'     => $title,
            'body_html' => implode("\n", $htmlParts),
        ];
    }

    /**
     * Render a single PhpWord element to HTML and capture the first heading as title.
     */
    protected function renderDocxElement($element, string &$title): string
    {
        $cls = class_basename($element);

        // Title element
        if ($cls === 'Title') {
            $text = $this->extractRunText($element);
            if ($title === '' && $text !== '') {
                $title = $text;
            }
            return '<h1>' . e($text) . '</h1>';
        }

        // Heading 1..6
        if ($cls === 'Heading' || method_exists($element, 'getDepth')) {
            $depth = method_exists($element, 'getDepth') ? (int) $element->getDepth() : 1;
            $level = max(2, min(4, $depth + 1)); // map depth 0 -> h2, 1 -> h2, 2 -> h3 etc.
            $text = $this->extractRunText($element);
            if ($title === '' && $text !== '') {
                $title = $text;
            }
            return '<h' . $level . '>' . e($text) . '</h' . $level . '>';
        }

        // List item
        if ($cls === 'ListItem' || $cls === 'ListItemRun') {
            $text = $this->extractRunText($element);
            if ($text === '') return '';
            return '<ul><li>' . e($text) . '</li></ul>';
        }

        // Text run / TextRun
        if ($cls === 'TextRun' || $cls === 'Text') {
            $html = $this->renderTextRun($element);
            if ($title === '') {
                $plain = trim(strip_tags($html));
                if ($plain !== '' && mb_strlen($plain) <= 120) {
                    $title = $plain;
                }
            }
            return $html === '' ? '' : '<p>' . $html . '</p>';
        }

        // Table
        if ($cls === 'Table') {
            return $this->renderDocxTable($element);
        }

        // Fallback: try to extract any text
        $fallback = $this->extractRunText($element);
        return $fallback === '' ? '' : '<p>' . e($fallback) . '</p>';
    }

    protected function extractRunText($element): string
    {
        if (method_exists($element, 'getText')) {
            $t = $element->getText();
            if (is_string($t)) return trim($t);
        }
        if (method_exists($element, 'getElements')) {
            $parts = [];
            foreach ($element->getElements() as $child) {
                if (method_exists($child, 'getText')) {
                    $t = $child->getText();
                    if (is_string($t)) $parts[] = $t;
                }
            }
            return trim(implode(' ', $parts));
        }
        return '';
    }

    protected function renderTextRun($element): string
    {
        if (!method_exists($element, 'getElements')) {
            $t = method_exists($element, 'getText') ? $element->getText() : '';
            return is_string($t) ? e(trim($t)) : '';
        }

        $html = '';
        foreach ($element->getElements() as $child) {
            if (!method_exists($child, 'getText')) continue;
            $text = $child->getText();
            if (!is_string($text) || $text === '') continue;
            $piece = e($text);

            $style = method_exists($child, 'getFontStyle') ? $child->getFontStyle() : null;
            if ($style) {
                if (method_exists($style, 'isBold') && $style->isBold()) {
                    $piece = '<strong>' . $piece . '</strong>';
                }
                if (method_exists($style, 'isItalic') && $style->isItalic()) {
                    $piece = '<em>' . $piece . '</em>';
                }
            }
            $html .= $piece;
        }
        return $html;
    }

    protected function renderDocxTable($element): string
    {
        if (!method_exists($element, 'getRows')) return '';

        $html = '<table>';
        foreach ($element->getRows() as $row) {
            $html .= '<tr>';
            foreach ($row->getCells() as $cell) {
                $cellHtml = '';
                foreach ($cell->getElements() as $cellElement) {
                    $tmp = '';
                    $cellHtml .= $this->renderDocxElement($cellElement, $tmp);
                }
                $html .= '<td>' . $cellHtml . '</td>';
            }
            $html .= '</tr>';
        }
        return $html . '</table>';
    }

    /**
     * Parse a PDF file using smalot/pdfparser. Detects headings heuristically.
     */
    protected function parsePdf(string $path): array
    {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf    = $parser->parseFile($path);
        $text   = $pdf->getText();

        // Normalise line endings, collapse excessive blank lines
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = preg_replace("/\n{3,}/", "\n\n", $text);

        $lines = array_map('trim', explode("\n", $text));
        $blocks = [];
        $current = '';

        foreach ($lines as $line) {
            if ($line === '') {
                if ($current !== '') { $blocks[] = $current; $current = ''; }
                continue;
            }
            $current = $current === '' ? $line : $current . ' ' . $line;
        }
        if ($current !== '') $blocks[] = $current;

        $title = '';
        $htmlParts = [];

        foreach ($blocks as $i => $block) {
            $clean = trim($block);
            if ($clean === '') continue;

            $isShort = mb_strlen($clean) <= 90;
            $endsWithoutPeriod = !preg_match('/[.!?]$/', $clean);
            $mostlyTitleCase = $this->looksLikeHeading($clean);

            if ($i === 0 && $isShort) {
                $title = $clean;
                $htmlParts[] = '<h1>' . e($clean) . '</h1>';
                continue;
            }

            if ($isShort && $endsWithoutPeriod && $mostlyTitleCase && mb_strlen($clean) >= 4) {
                $htmlParts[] = '<h2>' . e($clean) . '</h2>';
                if ($title === '') $title = $clean;
                continue;
            }

            // Bullet list detection
            if (preg_match('/^([•\-\*]|\d+[\.\)])\s+/', $clean)) {
                $items = preg_split('/(?:^|\s)([•\-\*]|\d+[\.\)])\s+/u', $clean, -1, PREG_SPLIT_NO_EMPTY);
                $listItems = '';
                foreach ($items as $it) {
                    $it = trim($it);
                    if ($it !== '') $listItems .= '<li>' . e($it) . '</li>';
                }
                if ($listItems !== '') {
                    $htmlParts[] = '<ul>' . $listItems . '</ul>';
                    continue;
                }
            }

            $htmlParts[] = '<p>' . e($clean) . '</p>';
        }

        return [
            'title'     => $title,
            'body_html' => implode("\n", $htmlParts),
        ];
    }

    protected function looksLikeHeading(string $text): bool
    {
        // Check: most words start with uppercase letter
        $words = preg_split('/\s+/', $text);
        if (count($words) === 0) return false;
        $upper = 0;
        foreach ($words as $w) {
            if (preg_match('/^[A-Z]/', $w)) $upper++;
        }
        return ($upper / count($words)) >= 0.6;
    }

    public function uploadImage(Request $request): JsonResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $request->validate([
            'image' => ['required', 'file', 'image', 'mimes:jpeg,jpg,png,webp,gif,avif', 'max:5120'],
        ]);

        $file = $request->file('image');
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());

        // Build a clean, slugged base name from the original filename
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $base = Str::slug($originalName) ?: 'image';

        $disk = Storage::disk('public');

        // Ensure unique filename
        $filename = $base . '.' . $extension;
        $i = 1;
        while ($disk->exists('blog/' . $filename)) {
            $filename = $base . '-' . $i . '.' . $extension;
            $i++;
        }

        try {
            $storedPath = $disk->putFileAs('blog', $file, $filename);
        } catch (\Throwable $exception) {
            \Log::error('Blog image upload failed: ' . $exception->getMessage());
            return response()->json([
                'message' => 'Failed to save file: ' . $exception->getMessage(),
            ], 500);
        }

        if ($storedPath === false || !$disk->exists($storedPath)) {
            \Log::error('Blog image upload verification failed for path: ' . $filename);
            return response()->json([
                'message' => 'File upload failed. Please check storage/blog folder permissions.',
            ], 500);
        }

        // Return URL from the public disk so the storage symlink is the only web-facing link.
        return response()->json([
            'filename' => $filename,
            'url'      => $disk->url($storedPath),
        ]);
    }

    protected function validatePost(Request $request, ?int $postId = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:blog_posts,slug,' . $postId],
            'category' => ['required', 'string', 'max:80'],
            'excerpt' => ['required', 'string', 'max:500'],
            'meta_title' => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'og_title' => ['nullable', 'string', 'max:120'],
            'og_description' => ['nullable', 'string', 'max:200'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'no_index' => ['nullable'],
            'schema_type' => ['nullable', 'string', 'max:60'],
            'focus_keyword' => ['nullable', 'string', 'max:120'],
            'secondary_keywords' => ['nullable', 'string', 'max:1000'],
            'author_name' => ['required', 'string', 'max:120'],
            'author_url' => ['nullable', 'url', 'max:255'],
            'faq_items' => ['nullable', 'string', 'max:20000'],
            'reading_time' => ['nullable', 'string', 'max:40'],
            'image' => ['nullable', 'string', 'max:255'],
            'image_class' => ['nullable', 'string', 'max:120'],
            'intro_content' => ['nullable', 'string'],
            'checks_content' => ['nullable', 'string'],
            'next_steps_content' => ['nullable', 'string'],
            'body_html' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['nullable'],
            'is_featured_home' => ['nullable'],
        ]);
    }

    protected function normalizeFaqItems(?string $raw): ?array
    {
        if (blank($raw)) {
            return null;
        }

        $decoded = json_decode($raw, true);

        if (!is_array($decoded)) {
            throw ValidationException::withMessages([
                'faq_items' => 'FAQ items could not be saved. Please review the FAQ section and try again.',
            ]);
        }

        $items = collect($decoded)
            ->map(function ($item): ?array {
                if (!is_array($item)) {
                    return null;
                }

                $question = trim((string) ($item['question'] ?? ''));
                $answer = trim((string) ($item['answer'] ?? ''));

                if ($question === '' || $answer === '') {
                    return null;
                }

                return [
                    'question' => Str::limit($question, 300, ''),
                    'answer' => Str::limit($answer, 3000, ''),
                ];
            })
            ->filter()
            ->values()
            ->all();

        return $items === [] ? null : $items;
    }

    protected function applyPublicationState(array $validated, Request $request): array
    {
        $statusAction = (string) $request->input('status_action', '');

        if ($statusAction === 'draft') {
            $validated['is_published'] = false;
        } elseif ($statusAction === 'publish') {
            $validated['is_published'] = true;
        }

        $validated['is_published'] = filter_var($validated['is_published'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $validated['is_featured_home'] = $validated['is_published']
            ? filter_var($validated['is_featured_home'] ?? false, FILTER_VALIDATE_BOOLEAN)
            : false;

        if ($validated['is_published']) {
            $validated['published_at'] = $validated['published_at'] ?? Carbon::now();
        } else {
            $validated['published_at'] = null;
        }

        return $validated;
    }

}
