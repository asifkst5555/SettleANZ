<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $metaTitle }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-lg mx-auto px-4 py-16 text-center">
            <div class="bg-white rounded-xl shadow-lg p-10">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-4">Download Error</h1>
                <p class="text-gray-600 mb-2">
                    Sorry, something went wrong with your download.
                </p>
                @if(session('error'))
                <p class="text-red-500 text-sm mb-6">{{ session('error') }}</p>
                @else
                <p class="text-gray-500 text-sm mb-6">Please try again later or contact support.</p>
                @endif
                <a href="/" class="inline-block bg-blue-600 text-white py-3 px-8 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                    Return to Homepage
                </a>
            </div>
        </div>
    </div>
</body>
</html>
