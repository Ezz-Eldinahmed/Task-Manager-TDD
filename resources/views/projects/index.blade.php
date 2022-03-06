<x-app-layout>
    <div class="mx-10 my-5">
        <div class="flex justify-between">
            <h3 class="pb-3 font-semibold text-2xl">All Projects</h3>

            <a href="{{route('projects.create')}}" class="float-right rounded-md bg-blue-500 p-3 text-white">Create
                Project +</a>
        </div>

        <p class="mb-4 text-gray-500">My Projects </p>

        <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse ($projects as $project)
            @include('projects.card.details')
            @empty
            <div class="overflow-hidden shadow-lg bg-white rounded-md">
                <div class="px-4 py-4">
                    <div class="text-md border-l-4 border-solid text-gray-400 border-blue-400 -ml-4 pl-3 mb-3">
                        No Project Added ...
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
