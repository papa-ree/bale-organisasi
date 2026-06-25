<?php

namespace Bale\BaleOrganisasi\Livewire\LandingPage\Post;

use Bale\Umpak\Livewire\UmpakComponent;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;

#[Layout('bale-organisasi::layouts.app')]
class PostShow extends UmpakComponent
{
    public string $slug;

    public function mount(string $slug): void
    {

        $this->slug = $slug;
    }

    public function render()
    {
        $post = $this->post($this->slug);

        if (!$post)
            abort(404);

        return view('bale-organisasi::livewire.landing-page.post.post-show', [
            'post' => $post
        ])
            ->layoutData(['post' => $post])
            ->title($post->title . ' — Bagian Organisasi');
    }

    #[Computed()]
    public function suggestedPosts()
    {
        return $this->getRandomPosts(4);
    }
}