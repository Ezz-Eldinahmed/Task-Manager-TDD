<x-app-layout>
    <div class="p-10">
        <div class="flex justify-between">
            <div class="flex">
                <h3 class="font-light text-2xl">Project / {{$project->title}}</h3>
            </div>
            <div class="float-right flex">
                @foreach ($project->members as $member)
                <div class="mr-2">
                    <img src="/images/Rob-cc2d68e18be04acf90a74623c1087fd6.jpg" class="w-8 h-8 rounded-full mx-0.5">
                </div>
                @endforeach
                <a href="{{route('projects.edit',$project)}}" class="rounded-md bg-blue-500 px-2 py-1 text-white">Edit
                    Project</a>
            </div>
        </div>

        <p class="my-2 text-gray-500">Tasks</p>
        <div class="grid grid-cols-3">

            <div class="col-span-2">
                @forelse ($project->tasks as $task)
                <div class="shadow-lg bg-white rounded-md mr-4 mb-3  {{($task->completed ? 'bg-blue-400' : '')}}">
                    <div class="px-4 py-2">
                        <form method="POST" action="{{route('tasks.update',$task)}}">
                            @csrf
                            @method('PUT')
                            <div class="flex">
                                <input value="{{$task->body}}"
                                    class="w-full py-2 px-1 font-bold text-sm {{($task->completed ? 'text-white bg-blue-400' : '')}}"
                                    name="body">

                                <input name="completed" class="my-2 px-1 rounded-md" onchange="this.form.submit()"
                                    {{($task->completed ? 'checked' : '')}} type="checkbox">
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                @endforelse

                <div class="shadow-lg bg-white rounded-md mr-4 mb-3">
                    <div class="px-4 py-2">
                        <div class="text-gray-400 text-md border-l-4 border-solid border-blue-400 -ml-4 pl-3 mb-3">
                            <form method="POST" action="{{route('tasks.store',$project)}}">
                                @csrf
                                <input placeholder="Add Task" class="w-full py-2 px-1 font-normal text-sm" name="body">
                                @error('body')<span class="text-md font-bold text-red-500 text-sm">{{$message}}</span>
                                @enderror
                            </form>
                        </div>
                        <p class="text-gray-600 font-light">
                            {{-- {{$task->description}} --}}
                        </p>
                    </div>
                </div>

                @include('projects.card.notes')

                <div class="my-4">
                    <a href="{{route('projects.index')}}" class="rounded-md bg-blue-600 px-3 py-1 text-white">Back</a>
                </div>

            </div>
            <div>
                @include('projects.card.details')

                <div class="shadow-lg bg-gray-200 rounded-md max-w-sm mt-2">
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-3 sm:px-6">
                            @forelse ($project->activity as $activity)

                            @include('projects.activity.card')

                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>

                @can('manage',$project)
                @include('projects.card.invite')
                @endcan

            </div>
        </div>
    </div>
</x-app-layout>
