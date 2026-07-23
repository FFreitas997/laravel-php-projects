<div class="flex flex-row space-x-2 flex-wrap">
    <label class="mb-1 flex items-center" for="{{ $name }}">
        <input type="radio" name="{{ $name }}" value="" @checked(request($name) === null) />
        <span class="ml-2">All</span>
    </label>

    @foreach($options as $option)
        <label class="mb-1 flex items-center" for="{{ $name }}">
            <input type="radio" name="{{ $name }}" value="{{ $option }}" @checked(request($name) === $option) />
            <span class="ml-2">{{ ucfirst($option) }}</span>
        </label>
    @endforeach
</div>
