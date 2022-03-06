<div class="overflow-hidden shadow-lg bg-white rounded-md">
    <a href=" {{route('projects.show',$project)}}">
        <div class="px-4 py-4">
            <div class="font-bold text-2xl border-l-4 border-solid border-blue-400 -ml-4 pl-3 mb-3">
                {{$project->title}}</div>
            <p class="text-gray-600 font-light">
                {{$project->description}}
            </p>
        </div>
    </a>
    @can('manage',$project)
    <form action="{{route('projects.destroy',$project)}}" class="float-right" method="POST">
        @method('DELETE')
        @csrf

        <button type="submit" class="text-sm rounded-md p-2 m-1 text-blue-600 bg-gray-100">Delete</button>
    </form>
    @endcan
</div>
