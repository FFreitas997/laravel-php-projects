<?php

use App\Models\Option;
use App\Models\Poll;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    // protected $listeners = [
    //     'pollCreated' => 'render'
    // ];
    #[On('pollCreated')]
    public function render(): View|Factory|\Illuminate\View\View
    {
        $polls = Poll::with('options.votes')->latest()->get();

        return view('components.poll.⚡polls', ['polls' => $polls]);
    }

    public function vote(Option $option): void
    {
        $option->votes()->create();
    }
};
?>

<div>
    @forelse ($polls as $poll)
        <div class="mb-4">
            <h3 class="mb-4 text-xl">
                {{ $poll->title }}
            </h3>
            @foreach ($poll->options as $option)
                <div class="mb-2">
                    <button class="btn" wire:click="vote({{ $option->id }})">Vote</button>
                    {{ $option->name }} ({{ $option->votes->count() }})
                </div>
            @endforeach
        </div>
    @empty
        <div class="text-gray-500">
            No polls available
        </div>
    @endforelse
</div>
