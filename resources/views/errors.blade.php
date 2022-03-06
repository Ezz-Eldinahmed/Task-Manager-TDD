@forelse ($errors->{ $bag ?? 'default' }->all() as $error)
<span class="text-md font-bold text-red-500 text-sm">{{$error}}</span>
@empty

@endforelse
