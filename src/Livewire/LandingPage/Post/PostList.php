<?php

namespace Bale\BaleOrganisasi\Livewire\LandingPage\Post;

use Bale\Umpak\Livewire\UmpakComponent;
use Livewire\Attributes\{Computed, Layout, Title, Url};

#[Layout('bale-organisasi::layouts.app')]
#[Title('Berita & Informasi — Bale Organisasi')]
class PostList extends UmpakComponent
{
    public int $amount = 9;

    #[Url]
    public string $search = '';

    #[Url]
    public string $date = '';

    public function updated($property): void
    {
        if (in_array($property, ['search', 'date'])) {
            $this->amount = 9;
        }
    }

    public function loadMore(): void
    {
        $this->amount += 9;
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->date = '';
        $this->amount = 9;
    }

    #[Computed]
    public function posts()
    {
        return $this->searchPosts($this->amount, $this->search, $this->date);
    }

    #[Computed]
    public function hasMore(): bool
    {
        return $this->countSearchPosts($this->search, $this->date) > $this->amount;
    }

    public function render()
    {
        return view('bale-organisasi::livewire.landing-page.post.post-list');
    }
}