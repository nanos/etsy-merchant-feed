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
            <x-input-label for="update_frequency" :value="__('Update frequency')" />
            <x-select-input type="text" id="update_frequency" class="block mt-1 w-full" name="update_frequency"
                          wire:model="form.update_frequency">
                @foreach(\App\UpdateFrequencyEnum::cases() as $case)
                    <option value="{{ $case->value }}">{{ $case->label() }}</option>
                @endforeach
            </x-select-input>
            <x-input-error :messages="$errors->get('form.update_frequency')" class="mt-2" />
            <p>Determines how often we'll update the feed from the Etsy API. You can always update a feed manually outside of the schedule, if needed.</p>
        </div>

        <div>
            <x-input-label for="utm_tags" :value="__('UTM Tags')" />
            <x-text-input type="text"  id="utm_tags" class="block mt-1 w-full" name="utm_tags"
                          wire:model="form.utm_tags"/>
            <x-input-error :messages="$errors->get('form.utm_tags')" class="mt-2" />
            <p>Optionally add your UTM tags here, as a simple string, e.g. <code>utm_source=instagram&utm_medium=cpc</code>.</p>
        </div>

        <div>
            <x-input-label for="feed_url" :value="__('Feed URL')" />
            <x-text-input type="text" value="{{ route('etsy.feed', ['feed' => $form->feed]) }}" id="feed_url" class="block mt-1 w-full" name="feed_url" readonly onclick="this.select()"/>
            <x-input-error :messages="$errors->get('form.feed_url')" class="mt-2" />
            <p>This is the link to your Merchant Feed for this store.</p>
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
