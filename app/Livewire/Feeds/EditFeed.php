<?php

namespace App\Livewire\Feeds;

use App\Livewire\Forms\FeedForm;
use App\Models\Feed;
use Livewire\Component;
use Toaster;

class EditFeed extends Component
{
    public FeedForm $form;

    public function mount(Feed $feed): void
    {
        $this->authorize('update', $feed);
        $this->form->setFeed($feed);
    }

    public function deleteFeed(Feed $feed)
    {
        $this->form->feed->delete();
        Toaster::success('Store disconnected');
        return $this->redirect(route('dashboard'));
    }

    public function save()
    {
        $this->form->update();

        Toaster::success('Store updated');
        return $this->redirect(route('dashboard'));
    }

    public function render()
    {
        return view('livewire.feeds.edit-feed');
    }
}
