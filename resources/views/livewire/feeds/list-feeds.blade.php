@php use App\EtsyService; @endphp
<div>
    <div class="grid grid-cols-2 items-center mb-4">
        <h2 class="font-bold text-xl">Etsy Stores</h2>
        <div class="text-right">

            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" wire:click="connect">
                Connect a store
            </button>
        </div>
    </div>

    @if($feeds->isEmpty())
        <div class="text-center">
            No stores connected. Please connect a store to get started.
        </div>
    @else
        <div class="grid-cols-1 gap-4 grid">
            @foreach($feeds as $feed)
                <div class="border border-gray-300 rounded p-4 my-2 grid-cols-2 gap-2 grid items-center md:grid-cols-6">
                    <div class="col-span-2 md:col-span-1 text-center md:text-left mb-3 md:mb-0">
                        <strong>{{ $feed->shop_name }}</strong>
                    </div>
                    <div class="text-center">
                        <strong>{{ $feed->created_at->timezone(\Illuminate\Support\Facades\Auth::user()->timezone)->format('d/m/y H:i') }}</strong>
                        <div class="text-gray-400">Date added</div>
                    </div>
                    <div class="text-center">
                        <strong>{{ $feed->items_count }}</strong>
                        <div class="text-gray-400">Items</div>
                    </div>
                    <div class="text-center">
                        <strong>{{ $feed->last_update?->timezone(\Illuminate\Support\Facades\Auth::user()->timezone)->format('d/m/y H:i') ?? 'Scheduled...' }}</strong>

                        <div class="text-gray-400">
                            Last Update
                        </div>
                    </div>
                    <div class="text-center">
                        <strong>
                            @if($feed->last_update === null || $feed->update_scheduled)
                                Scheduled ...
                            @else
                                {{ $feed->last_update->addSeconds($feed->update_frequency)->diffForHumans(short: true) }}
                            @endif
                        </strong>

                        <div class="text-gray-400">
                            Next Update
                            @if($feed->last_update !== null && !$feed->update_scheduled)
                                <button class="inline bg-transparent font-bold py-2 px-4 rounded border text-blue-200 border-blue-100"
                                            title="Schedule Update"
                                            wire:click="updateFeed({{ $feed }})">
                                    <svg class="w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160H352c-17.7 0-32 14.3-32 32s14.3 32 32 32H463.5c0 0 0 0 0 0h.4c17.7 0 32-14.3 32-32V80c0-17.7-14.3-32-32-32s-32 14.3-32 32v35.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1V432c0 17.7 14.3 32 32 32s32-14.3 32-32V396.9l17.6 17.5 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352H160c17.7 0 32-14.3 32-32s-14.3-32-32-32H48.4c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z"/></svg>
                                </button>
                           @endif
                        </div>
                    </div>
                    <div class="text-center col-span-2 md:col-span-1">
                        <a href="{{ route('store', ['feed' => $feed]) }}" class="block w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                wire:navigate>Edit
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
