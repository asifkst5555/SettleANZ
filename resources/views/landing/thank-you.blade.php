<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $metaTitle }}</title>
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

        <main class="flex-1 flex items-center justify-center">
            <div class="max-w-2xl mx-auto px-4 py-16 text-center">
                <div class="bg-white rounded-xl shadow-lg p-10">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Thank You!</h1>

                    @if($ebook)
                    <p class="text-lg text-gray-600 mb-6">
                        Your copy of <strong>{{ $ebook->title }}</strong> is on its way.
                    </p>
                    @else
                    <p class="text-lg text-gray-600 mb-6">
                        Your download is being prepared.
                    </p>
                    @endif

                    <p class="text-gray-500 mb-8">
                        We've sent the download link to your email. Please check your inbox (and spam folder).
                    </p>

                    <div class="bg-blue-50 rounded-lg p-4 mb-8 text-sm text-blue-800">
                        <p class="font-medium">Didn't receive the email?</p>
                        <p>Wait a few minutes and check your spam folder. The email will come from <strong>{{ config('mail.from.address') }}</strong>.</p>
                    </div>

                    <a href="/" class="inline-block bg-blue-600 text-white py-3 px-8 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                        Return to Homepage
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
