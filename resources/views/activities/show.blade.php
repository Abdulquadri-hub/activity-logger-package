@extends('activity-logger::layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ __('activity-logger::messages.activity_details') }}
            </h3>
        </div>
        
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                        {{ __('activity-logger::messages.type') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $activity->type }}
                    </dd>
                </div>

                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                        {{ __('activity-logger::messages.date') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $activity->created_at->format('F j, Y H:i:s') }}
                    </dd>
                </div>

                @if($activity->user)
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                        {{ __('activity-logger::messages.user') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $activity->user->name }}
                    </dd>
                </div>
                @endif

                @if($activity->ip_address)
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                        {{ __('activity-logger::messages.ip_address') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $activity->ip_address }}
                    </dd>
                </div>
                @endif

                @if($activity->payload)
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">
                        {{ __('activity-logger::messages.additional_data') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <pre class="bg-gray-50 p-4 rounded-md overflow-x-auto">{{ json_encode($activity->payload, JSON_PRETTY_PRINT) }}</pre>
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('activity-logger.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            {{ __('activity-logger::messages.back_to_list') }}
        </a>
    </div>
</div>
@endsection