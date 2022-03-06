<div class="overflow-hidden shadow-lg bg-white rounded-md mt-2">
    <div class="px-4 py-4">
        <div class="font-bold text-2xl border-l-4 border-solid border-blue-400 -ml-4 pl-3 mb-3">
            Invite User
        </div>
        @include('errors',['bag'=>'invitations'])

        <form action="{{route('project.invite',$project)}}" method="POST">
            @csrf
            <input type="text" class="w-full my-2 rounded-md px-1 font-bold text-sm" placeholder="Enter E-Mail"
                name="email">
            <button type="submit" class="text-sm rounded-md p-2 m-1 text-white bg-blue-500">Invite</button>
            @error('email')<span class="text-md font-bold text-red-500 text-sm">{{$message}}</span>
            @enderror

        </form>
    </div>
</div>
