@extends(View::exists('layouts.app') ? 'layouts.app' : 'errors::minimal')

@section('title', $title ?? 'Encryption Error')

@section('content')
    <div class="max-w-3xl mx-auto mt-20">
        <div class="bg-purple-50 border-l-4 border-purple-500 p-6 rounded shadow-sm">
            <h1 class="text-2xl font-bold text-purple-700 mb-3">
                {{ $title ?? 'Encryption/Decryption Error' }}
            </h1>

            <p class="text-gray-800 mb-5 leading-relaxed">
                @if(config('app.env') === 'local')
                    {{ $error['message'] ?? 'An unexpected encryption or decryption error occurred.' }}
                @else
                    @if(!empty($title) && str_contains(strtolower($title), 'encrypt'))
                        Unable to process encryption — the provided ID may be invalid.
                    @elseif(!empty($title) && str_contains(strtolower($title), 'decrypt'))
                        Unable to process decryption — the provided code may be invalid or expired.
                    @else
                        We couldn’t process your request right now. Please try again later.
                    @endif
                @endif
            </p>

            @if(config('app.env') === 'local' && !empty($error))
                <div class="bg-white border rounded-lg p-4 text-sm text-gray-700 shadow-inner">
                    <p><strong>Exception Type:</strong> {{ $error['type'] ?? 'N/A' }}</p>

                    @if(!empty($error['context']))
                        <hr class="my-3 border-gray-200">
                        <h2 class="font-semibold text-gray-800 mb-2">Debug Context</h2>
                        <pre class="bg-gray-100 p-3 rounded text-xs text-gray-800 whitespace-pre-wrap overflow-x-auto">
                        {{ json_encode($error['context'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}
                                            </pre>
                    @endif
                </div>
            @endif

            <div class="mt-6 text-center">
                <a href="{{ route('dashboard') }}"
                    class="inline-block bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition">
                    Back to Dashboard
                </a>
            </div>

        </div>
    </div>
@endsection