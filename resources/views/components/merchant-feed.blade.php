<rss version="2.0">
    <channel>
        <created_at>{{ now()->toDateTimeString() }}</created_at>
        @foreach($feed->items as $item)
            <item>
                <g:id>{{ $item->listing_id }}</g:id>

            </item>
        @endforeach
    </channel>
</rss>
