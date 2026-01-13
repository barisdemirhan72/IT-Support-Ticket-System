@extends('layouts.app')

@section('title', 'Dashboard')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
    </h2>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500">Total Tickets</div>
            <div class="text-3xl font-bold text-gray-900">{{ $totalTickets }}</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500">Open Tickets</div>
            <div class="text-3xl font-bold text-yellow-600">{{ $openTickets }}</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500">Closed Tickets</div>
            <div class="text-3xl font-bold text-green-600">{{ $closedTickets }}</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500">New Tickets</div>
            <div class="text-3xl font-bold text-blue-600">{{ $newTickets }}</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Recent Tickets</h3>
            <a href="{{ route('tickets.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Create New Ticket
            </a>
        </div>

        @if($recentTickets->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($recentTickets as $ticket)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">#{{ $ticket->id }}</td>
                    <td class="px-6 py-4">{{ Str::limit($ticket->title, 50) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->category->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            @if($ticket->status === 'new') bg-blue-100 text-blue-800
                            @elseif($ticket->status === 'in_progress') bg-yellow-100 text-yellow-800
                            @elseif($ticket->status === 'completed') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucwords(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('tickets.show', $ticket) }}" class="text-blue-600 hover:text-blue-900">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="text-center py-12">
            <p class="text-gray-500">No tickets yet.</p>
            <a href="{{ route('tickets.create') }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Create Your First Ticket
            </a>
        </div>
        @endif

        @if($recentTickets->count() > 0)
        <div class="px-6 py-4 border-t border-gray-200">
            <a href="{{ route('tickets.index') }}" class="text-blue-600 hover:text-blue-900">View All Tickets →</a>
        </div>
        @endif
    </div>
</div>
@endsection
