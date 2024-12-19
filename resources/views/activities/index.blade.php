@extends('activity-logger::layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold mb-4">{{ __('activity-logger::messages.activity_log') }}</h2>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __('activity-logger::messages.recent_activities') }}
                </h3>
                
                <div class="flex space-x-2">
                    @if(request()->has('user_id'))
                        <a href="{{ route('activity-logger.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            {{ __('activity-logger::messages.show_all') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="border-t border-gray-200">
            <ul class="divide-y divide-gray-200">
                @forelse($activities as $activity)
                    <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $activity->type }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $activity->created_at->diffForHumans() }}
                                        @if($activity->user)
                                            by {{ $activity->user->name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('activity-logger.show', $activity) }}" class="text-indigo-600 hover:text-indigo-900">
                                    {{ __('activity-logger::messages.view_details') }}
                                </a>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-4 sm:px-6 text-center text-gray-500">
                        {{ __('activity-logger::messages.no_activities') }}
                    </li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="mt-4">
        {{ $activities->links() }}
    </div>
</div>
@endsection