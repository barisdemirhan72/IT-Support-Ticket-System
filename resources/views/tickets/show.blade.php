@extends('layouts.app')

@section('title', 'Ticket #' . $ticket->id)

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ticket #{{ $ticket->id }}
        </h2>
        <div class="flex space-x-2">
            @if(auth()->user()->id === $ticket->user_id && $ticket->isNew())
                <a href="{{ route('tickets.edit', $ticket) }}" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                    Edit Ticket
                </a>
            @endif
            <a href="{{ route('tickets.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                Back to List
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Ticket Details -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-2xl font-bold text-gray-900">{{ $ticket->title }}</h3>
                    <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                        <span>Created {{ $ticket->created_at->format('M d, Y H:i') }}</span>
                        <span>•</span>
                        <span>Updated {{ $ticket->updated_at->format('M d, Y H:i') }}</span>
                    </div>
                </div>
                <div class="px-6 py-4">
                    <div class="prose max-w-none">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $ticket->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Comments</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    @forelse($ticket->comments as $comment)
                        <div class="border-l-4 {{ $comment->is_internal ? 'border-yellow-400 bg-yellow-50' : 'border-blue-400 bg-blue-50' }} pl-4 py-3">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-2">
                                    <span class="font-semibold text-gray-900">{{ $comment->user->name }}</span>
                                    @if($comment->is_internal)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-200 text-yellow-800">
                                            Internal Note
                                        </span>
                                    @endif
                                    @if($comment->user->isSupport())
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-200 text-green-800">
                                            Support
                                        </span>
                                    @endif
                                </div>
                                <span class="text-sm text-gray-500">{{ $comment->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $comment->comment }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No comments yet.</p>
                    @endforelse
                </div>

                <!-- Add Comment Form -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <form method="POST" action="{{ route('tickets.comments.store', $ticket) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Add Comment</label>
                            <textarea name="comment"
                                      id="comment"
                                      rows="3"
                                      required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Write your comment here..."></textarea>
                        </div>

                        @if(auth()->user()->isSupport())
                        <div class="mb-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_internal" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Internal note (only visible to support staff)</span>
                            </label>
                        </div>
                        @endif

                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Add Comment
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Status</h4>
                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                    @if($ticket->status === 'new') bg-blue-100 text-blue-800
                    @elseif($ticket->status === 'in_progress') bg-yellow-100 text-yellow-800
                    @elseif($ticket->status === 'completed') bg-green-100 text-green-800
                    @elseif($ticket->status === 'closed') bg-gray-100 text-gray-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucwords(str_replace('_', ' ', $ticket->status)) }}
                </span>

                @if(auth()->user()->isSupport())
                <form method="POST" action="{{ route('tickets.status.update', $ticket) }}" class="mt-4">
                    @csrf
                    @method('PATCH')
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Change Status</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md mb-2">
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ $ticket->status === $status ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                        Update Status
                    </button>
                </form>
                @endif
            </div>

            <!-- Priority Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Priority</h4>
                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                    @if($ticket->priority === 'urgent') bg-red-100 text-red-800
                    @elseif($ticket->priority === 'high') bg-orange-100 text-orange-800
                    @elseif($ticket->priority === 'medium') bg-blue-100 text-blue-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ ucfirst($ticket->priority) }}
                </span>
            </div>

            <!-- Category Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Category</h4>
                <p class="text-gray-900">{{ $ticket->category->name }}</p>
            </div>

            <!-- Requester Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Requester</h4>
                <p class="text-gray-900">{{ $ticket->user->name }}</p>
                <p class="text-sm text-gray-500">{{ $ticket->user->email }}</p>
            </div>

            <!-- Assignment Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Assigned To</h4>
                @if($ticket->assignedTo)
                    <p class="text-gray-900">{{ $ticket->assignedTo->name }}</p>
                    <p class="text-sm text-gray-500">{{ $ticket->assignedTo->email }}</p>
                @else
                    <p class="text-gray-500 italic">Not assigned yet</p>
                @endif

                @if(auth()->user()->isSupport())
                <form method="POST" action="{{ route('tickets.assign', $ticket) }}" class="mt-4">
                    @csrf
                    @method('PATCH')
                    <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">Assign To</label>
                    <select name="assigned_to" id="assigned_to" class="w-full px-3 py-2 border border-gray-300 rounded-md mb-2">
                        <option value="">-- Unassigned --</option>
                        @foreach($supportUsers as $user)
                            <option value="{{ $user->id }}" {{ $ticket->assigned_to === $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                        Assign
                    </button>
                </form>
                @endif
            </div>

            <!-- Dates Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Dates</h4>
                <div class="space-y-2 text-sm">
                    <div>
                        <span class="text-gray-600">Created:</span>
                        <span class="text-gray-900">{{ $ticket->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Updated:</span>
                        <span class="text-gray-900">{{ $ticket->updated_at->format('M d, Y H:i') }}</span>
                    </div>
                    @if($ticket->resolved_at)
                    <div>
                        <span class="text-gray-600">Resolved:</span>
                        <span class="text-gray-900">{{ $ticket->resolved_at->format('M d, Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions Card -->
            @if(auth()->user()->id === $ticket->user_id && $ticket->isNew() || auth()->user()->isAdmin())
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Actions</h4>
                <form method="POST" action="{{ route('tickets.destroy', $ticket) }}" onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
                        Delete Ticket
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
