<div>
    <form wire:submit="save" class="mt-6 space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-lg"> Edit Store connection for {{ $form->feed->shop_name }}</h2>
            <a class="block" href="{{route('dashboard')}}" wire:navigate>&nbsp;&lt;&nbsp;Back</a>
        </div>

        <div>
            <x-input-label for="brand_name" :value="__('Brand Name')" />
            <x-text-input type="text" wire:model="form.brand_name" id="brand_name" class="block mt-1 w-full" name="brand_name"/>
            <x-input-error :messages="$errors->get('form.brand_name')" class="mt-2" />
            <p>The Brand name as given in your feed.</p>
        </div>

        <div>
            <x-input-label for="google_product_category" :value="__('Google Product Category')" />
            <x-text-input type="text" wire:model="form.google_product_category" id="google_product_category" class="block mt-1 w-full" name="google_product_category" required/>
            <x-input-error :messages="$errors->get('form.google_product_category')" class="mt-2" />
            <p>This is the <a class="text-blue-500 underline" href="https://www.google.com/basepages/producttype/taxonomy-with-ids.en-US.txt">
                    Google Product Category
                </a> that will be added to all products in this Store. You can submit
                either the numeric ID, or the full category name</p>
        </div>

        <div>
            <x-input-label for="created_at" :value="__('Connection date')" />
            <x-text-input type="text"  id="created_at" class="block mt-1 w-full" name="created_at" readonly
                          value="{{ $form->feed->created_at->timezone(\Illuminate\Support\Facades\Auth::user()->timezone)->format('d/m/y H:i') }}"/>
            <x-input-error :messages="$errors->get('form.created_at')" class="mt-2" />
        </div>


        <div>
            <x-input-label for="feed_url" :value="__('Feed URL')" />
            <x-text-input type="text" value="{{ route('etsy.feed', ['feed' => $form->feed]) }}" id="feed_url" class="block mt-1 w-full" name="feed_url" readonly/>
            <x-input-error :messages="$errors->get('form.feed_url')" class="mt-2" />
        </div>


        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            <x-danger-button type="button"
                wire:confirm="Are you sure you want to disconnect this store?"
                wire:click="deleteFeed()"
            >{{ __('Disconnect Feed') }}</x-danger-button>
        </div>
    </form>

</div>
