@if (count($activity->changes['after'])==1)
{{$activity->user->name}} Have Update A Project {{key($activity->changes['after'])}}
@else
{{$activity->user->name}} Have Update A Project
@endif
