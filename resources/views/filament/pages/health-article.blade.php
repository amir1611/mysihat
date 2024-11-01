<x-filament-panels::page>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="{{ asset('css/chat.css') }}" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    </head>
    @php
        $response = Http::get('https://newsapi.org/v2/top-headlines', [
            'category' => 'health',
            'apiKey' => env('NEWS_API_KEY'),
        ]);

        $data = $response->json();
        $articles = collect($data['articles'] ?? [])->filter(function ($article) {
            // Filter out articles that do not have a title, description, or URL
            return !empty($article['title']) &&
                !empty($article['description']) &&
                !empty($article['url']) &&
                !str_contains(strtolower($article['title']), '[removed]') &&
                !str_contains(strtolower($article['description']), '[removed]');
        });
    @endphp

    <div class="container px-4 py-8 mx-auto">
        @if ($response->successful() && $articles->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
                @foreach ($articles as $article)
                    <div class="overflow-hidden transition-transform duration-300 bg-white shadow-md rounded-xl hover:shadow-lg"
                        style="display: flex; flex-direction: column; height: 100%;">
                        @if (!empty($article['urlToImage']))
                            <div style="flex-shrink: 0;">
                                <img src="{{ $article['urlToImage'] }}" alt="{{ $article['title'] }}"
                                    style="width: 100%; height: 200px; object-fit: cover;"
                                    onerror="this.onerror=null; this.src='https://via.placeholder.com/400x300?text=No+Image';">
                            </div>
                        @endif

                        <div style="padding: 16px; display: flex; flex-direction: column; flex-grow: 1;">
                            <div style="color: #6366F1; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                                {{ $article['source']['name'] ?? 'Unknown Source' }}
                            </div>
                            <a href="{{ $article['url'] }}" target="_blank"
                                style="margin-top: 8px; font-size: 18px; font-weight: 500; color: #111827; text-decoration: none;">
                                {{ $article['title'] ?? 'No Title' }}
                            </a>

                            <p style="margin-top: 12px; color: #4B5563; text-align:justify; flex-grow: 1;">
                                {{ $article['description'] }}
                            </p>

                            <div
                                style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center;">
                                @if (!empty($article['author']))
                                    <span style="font-size: 12px; color: #6B7280; font-style: italic;">
                                        By {{ $article['author'] }}
                                    </span>
                                @endif
                                @if (!empty($article['publishedAt']))
                                    <span style="font-size: 12px; color: #6B7280;">
                                        {{ \Carbon\Carbon::parse($article['publishedAt'])->format('M j, Y') }}
                                    </span>
                                @endif
                            </div>

                            <a href="{{ $article['url'] }}" target="_blank"
                                style="margin-top: 16px; background-color: rgb(41, 50, 137); color: white; padding: 8px 16px; border-radius: 9999px; text-align: center; font-size: 14px; font-weight: 600; text-decoration: none; display: block;">
                                Read Full Article
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @elseif($response->failed())
            <div
                style="background-color: #FEF2F2; border: 1px solid #FECACA; color: #B91C1C; padding: 24px; border-radius: 12px;">
                <p style="font-weight: 600;">Error: Failed to load articles</p>
                <p style="font-size: 14px; margin-top: 8px;">Please try again later or contact support if the problem
                    persists.</p>
            </div>
        @else
            <div
                style="text-align: center; padding: 48px 0; color: #4B5563; background-color: #F3F4F6; border-radius: 12px;">
                <p style="font-size: 20px; font-weight: 600;">No articles found</p>
                <p style="margin-top: 8px;">Check back later for new health articles.</p>
            </div>
        @endif
    </div>
</x-filament-panels::page>
