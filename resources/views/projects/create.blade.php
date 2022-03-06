<x-app-layout>
    <div class="container bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h1 class="text-2xl mb-4 font-bold">Create Project</h1>

        <form method="POST" action="{{route('projects.store')}}" class="w-1/2">
            @csrf
            <div>
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                    focus:outline-none focus:shadow-outline" type="text" name="title" placeholder="Title"
                    value="{{old('title')}}">
                @error('title')<span class="text-md font-bold text-red-500 text-sm">{{$message}}</span>
                @enderror
            </div>
            <div class="my-2">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                <textarea
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    name="description" placeholder="Description">{{old('description')}}</textarea>
                @error('description')<span class="text-md font-bold text-red-500 text-sm">{{$message}}</span>
                @enderror
            </div>
            <div>
                <button
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="submit">Submit</button>
            </div>
        </form>
        <a href="{{route('projects.index')}}" class="float-right rounded-md bg-blue-600 p-3 mb-10 text-white">Back</a>
    </div>

</x-app-layout>
