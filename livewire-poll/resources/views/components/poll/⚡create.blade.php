<?php

use App\Models\Poll;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

new class extends Component {
    public string $title = '';
    public array $options = ['First'];

    protected array $rules = [
        'title' => 'required|min:3|max:255',
        'options' => 'required|array|min:1|max:10',
        'options.*' => 'required|min:1|max:255'
    ];

    protected array $messages = [
        'options.*' => 'The option can\'t be empty.'
    ];

    public function render(): View|Factory|\Illuminate\View\View
    {
        return view('components.poll.⚡create');
    }

    public function addOption(): void
    {
        $this->options[] = '';
    }

    public function removeOption($index): void
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options);
    }

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function createPoll(): void
    {
        $this->validate();

        Poll::create([
            'title' => $this->title
        ])->options()->createMany(
            collect($this->options)
                ->map(fn($option) => ['name' => $option])
                ->all()
        );
        $this->reset(['title', 'options']);
        $this->dispatch('pollCreated');
    }
};
?>

<div>
    <form wire:submit.prevent="createPoll">
        <label>Poll Title</label>

        <input type="text" wire:model="title"/>

        @error('title')
        <div class="text-red-500">{{ $message }}</div>
        @enderror

        <div class="mb-4 mt-4">
            <button class="btn" wire:click.prevent="addOption">Add Option</button>
        </div>

        <div>
            @foreach ($options as $index => $option)
                <div class="mb-4">
                    <label>Option {{ $index + 1 }}</label>
                    <div class="flex gap-2">
                        <input type="text" wire:model="options.{{ $index }}"/>
                        <button class="btn"
                                wire:click.prevent="removeOption({{ $index }})">Remove
                        </button>
                    </div>
                    @error("options.{$index}")
                    <div class="text-red-500">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn">Create Poll</button>
    </form>
</div>
