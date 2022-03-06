<div>
    <p class="my-4 text-gray-500">General Notes</p>

    <div class="shadow-lg bg-white rounded-md max-w-3xl max-h-52">
        <div class="p-4">
            <form action="{{route('projects.update',$project)}}" method="POST">
                @csrf
                @method('PUT')

                <textarea rows="5" class="w-full" name="notes"
                    placeholder="General Notes To Hint ...">{{$project->notes}}</textarea>

                @error('notes')<span class="text-md font-bold text-red-500 text-sm">{{$message}}</span>
                @enderror

                <button class="rounded-md bg-blue-600 px-3 py-1 text-white">Save</button>
            </form>
        </div>
    </div>
</div>
