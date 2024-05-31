@php use App\EtsyService; @endphp
<div>
    <h2>Feeds</h2>

    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" wire:click="connect">Add Feed
    </button>

    <table class="table-auto">
        <thead>
            <tr>
                <th>
                    Feed ID
                </th>
                <th>
                    Shop
                </th>
                <th>
                    Feed Url
                </th>
                <th>
                    Items in feed.
                </th>
                <th></th>
            </tr>

            @foreach($feeds as $feed)
                <tr wire:key="{{ $feed->id }}">
                    <td class="px-2">
                        {{ $feed->id }}
                    </td>
                    <td class="px-2">
                        {{ $feed->shop_name }}
                    </td>
                    <td class="px-2">
                        <a href="{{ route('etsy.feed', ['feed' => $feed]) }}" target="_blank" class="font-medium text-blue-600 underline dark:text-blue-500 hover:no-underline">View Feed</a>
                    </td>
                    <td class="px-2">
                        {{ $feed->items_count }}
                    </td>
                    <td class="px-2">
                        <button class="bg-transparent font-bold py-2 px-4 rounded border @if($feed->last_update === null) text-blue-200 border-blue-100 @else text-blue-700 border-blue-500 hover:bg-blue-500 hover:border-transparent @endif"
                                @if($feed->last_update === null) disabled @endif
                                wire:click="updateFeed({{ $feed }})">
                            @if($feed->last_update === null)
                                Update Scheduled
                            @else
                                Schedule Update
                            @endif
                        </button>
                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                wire:confirm="Are you sure you want to delete this feed?"
                                wire:click="deleteFeed({{ $feed }})">Delete Feed
                        </button>
                    </td>
                </tr>
            @endforeach
        </thead>
    </table>
</div>
