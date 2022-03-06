<div class="flex justify-between">
    <h3 class="text-sm font-medium text-gray-900">

        @include('projects.activity.'.$activity->description)

        @if (isset($activity->subject->body))
        {{$activity->subject->body}}
        @endif
    </h3>
    <p class="mt-1 text-xs text-gray-500">
        {{$activity->created_at->diffForHumans()}}
    </p>
</div>
