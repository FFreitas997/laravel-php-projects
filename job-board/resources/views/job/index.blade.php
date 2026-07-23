@php use App\Models\Job; @endphp
<x-layout>

    <x-breadcrumbs class="mb-4" :links="['Jobs' => route('jobs.index')]"/>

    <x-card class="mb-4 text-sm">

        <form action="{{ route('jobs.index') }}" method="GET">
            <div class="mb-4 grid grid-cols-2 gap-4 md:grid-cols-2">
                <div>
                    <div class="mb-1 font-semibold">Search</div>
                    <x-text-input name="search" value="{{ request('search') }}" placeholder="Search for any text ..."/>
                </div>
                <div>
                    <div class="mb-1 font-semibold">Salary</div>
                    <div class="flex">
                        <x-text-input name="salary_min" value="{{ request('salary_min') }}" placeholder="Min"/>
                        <span class="mx-2 mt-2">-</span>
                        <x-text-input name="salary_max" value="{{ request('salary_max') }}" placeholder="Max"/>
                    </div>
                </div>

                <div>
                    <div class="mb-1 font-semibold">Experience</div>
                    <x-radio-group name="experience" :options="Job::$experience"/>
                </div>

                <div>
                    <div class="mb-1 font-semibold">Category</div>
                    <x-radio-group name="category" :options="Job::$category"/>
                </div>

            </div>
            <button class="bg-blue-500 text-white px-4 py-2 rounded cursor-pointer">Filter</button>
        </form>

    </x-card>


    @foreach($jobs as $job)
        <x-job-card :$job>
            <x-link-button :href="route('jobs.show', $job)">
                Details
            </x-link-button>
        </x-job-card>
    @endforeach

</x-layout>
