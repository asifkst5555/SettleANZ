@php
    $faviconVer = file_exists(public_path('favicon.ico')) ? filemtime(public_path('favicon.ico')) : time();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $metaTitle }} | {{ config('app.name') }}</title>
    <meta name="description" content="{{ $metaDescription ?? '' }}">
    <link rel="icon" href="/favicon.ico?v={{ $faviconVer }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v={{ $faviconVer }}">
    <link rel="icon" type="image/png" sizes="48x48" href="/favicon-48x48.png?v={{ $faviconVer }}">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png?v={{ $faviconVer }}">
    <link rel="manifest" href="/site.webmanifest?v={{ $faviconVer }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex flex-col">
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <a href="/" class="text-xl font-bold text-blue-600">{{ config('app.name') }}</a>
                </div>
            </div>
        </header>

        <main class="flex-1">
            <div class="max-w-7xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
                <div class="lg:grid lg:grid-cols-2 lg:gap-12 items-start">
                    <div class="mb-10 lg:mb-0">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 mb-4">
                            Free Guide
                        </span>
                        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl mb-4">
                            {{ $ebook->title }}
                        </h1>
                        <p class="text-lg text-gray-600 mb-6">
                            {{ $ebook->description }}
                        </p>
                        <div class="space-y-3 text-sm text-gray-500">
                            @if($ebook->page_count)
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>{{ $ebook->page_count }} pages</span>
                            </div>
                            @endif
                            @if($ebook->author)
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>By {{ $ebook->author }}</span>
                            </div>
                            @endif
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                <span>PDF Format - Instant Download</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Get Your Free Copy</h2>
                        <p class="text-gray-500 mb-6">Enter your details below to instantly download.</p>

                        <form action="{{ route('ebook.capture') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="ebook_id" value="{{ $ebook->id }}">
                            <input type="hidden" name="source_page" value="{{ url()->current() }}">

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                                <input type="text" name="name" id="name" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border px-3 py-2"
                                    placeholder="John Smith">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email Address *</label>
                                <input type="email" name="email" id="email" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border px-3 py-2"
                                    placeholder="john@example.com">
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone (optional)</label>
                                <input type="tel" name="phone" id="phone"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border px-3 py-2"
                                    placeholder="+61 400 000 000">
                            </div>

                            <div>
                                <label for="company" class="block text-sm font-medium text-gray-700">Company (optional)</label>
                                <input type="text" name="company" id="company"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border px-3 py-2"
                                    placeholder="Your Company">
                            </div>

                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700">Country (optional)</label>
                                <select name="country" id="country"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border px-3 py-2">
                                    <option value="">Select Country</option>
                                    <option value="Australia">Australia</option>
                                    <option value="New Zealand">New Zealand</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="United States">United States</option>
                                    <option value="India">India</option>
                                    <option value="Philippines">Philippines</option>
                                    <option value="China">China</option>
                                    <option value="South Africa">South Africa</option>
                                </select>
                            </div>

                            <div class="flex items-start">
                                <input type="checkbox" name="consent" id="consent" required
                                    class="mt-1 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="consent" class="ml-2 text-sm text-gray-600">
                                    I agree to receive the ebook and related information. You can unsubscribe at any time. *
                                </label>
                            </div>
                            @error('consent') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                            <div style="position: absolute; left: -9999px;" aria-hidden="true">
                                <label for="website_url">Leave empty</label>
                                <input type="text" name="website_url" id="website_url" tabindex="-1" autocomplete="off">
                            </div>

                            <x-honeypot />
                            <x-math-verification />

                            <button type="submit"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                Download Ebook
                            </button>
                        </form>

                        <p class="text-xs text-gray-400 text-center mt-4">
                            We respect your privacy. Your information will never be shared.
                        </p>
                    </div>
                </div>
            </div>
        </main>

        <footer class="bg-white border-t mt-12">
            <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8 text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>
        </footer>
    </div>
</body>
</html>
