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
                <th></th>
            </tr>

            @foreach($feeds as $feed)
                <tr wire:key="{{ $feed->id }}">
                    <td>
                        {{ $feed->id }}
                    </td>
                    <td>
                        {{ $feed->shop_name }}
                    </td>
                    <td>
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