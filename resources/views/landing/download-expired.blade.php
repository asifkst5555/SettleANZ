@php
    $faviconVer = file_exists(public_path('favicon.ico')) ? filemtime(public_path('favicon.ico')) : time();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $metaTitle }}</title>
    <link rel="icon" href="/favicon.ico?v={{ $faviconVer }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v={{ $faviconVer }}">
    <link rel="icon" type="image/png" sizes="48x48" href="/favicon-48x48.png?v={{ $faviconVer }}">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png?v={{ $faviconVer }}">
    <link rel="manifest" href="/site.webmanifest?v={{ $faviconVer }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-lg mx-auto px-4 py-16 text-center">
            <div class="bg-white rounded-xl shadow-lg p-10">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-4">Download Link Expired</h1>
                <p class="text-gray-600 mb-8">
                    This download link has expired. Please request a new link by visiting the original ebook page.
                </p>
                <a href="/" class="inline-block bg-blue-600 text-white py-3 px-8 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                    Return to Homepage
                </a>
            </div>
        </div>
    </div>
</body>
</html>
