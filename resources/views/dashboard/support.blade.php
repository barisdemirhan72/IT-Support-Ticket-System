@extends('layouts.app')

@section('title', 'Support Dashboard')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Support Dashboard
    </h2>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Statistics Cards -->
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

    <!-- My Tickets Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-200 rounded-lg shadow p-6">
            <div class="text-sm font-medium text-blue-700">My Assigned Tickets</div>
            <div class="text-2xl font-bold text-blue-900">{{ $myAssignedTickets }}</div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg shadow p-6">
            <div class="text-sm font-medium text-yellow-700">My Open Tickets</div>
            <div class="text-2xl font-bold text-yellow-900">{{ $myOpenTickets }}</div>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-lg shadow p-6">
            <div class="text-sm font-medium text-red-700">Unassigned Tickets</div>
            <div class="text-2xl font-bold text-red-900">{{ $unassignedTickets }}</div>
        </div>
    </div>

    <!-- Urgent Tickets -->
    @if($urgentTickets->count() > 0)
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-red-600">🚨 Urgent Tickets</h3>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($urgentTickets as $ticket)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">#{{ $ticket->id }}</td>
                    <td class="px-6 py-4">{{ Str::limit($ticket->title, 40) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->category->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            {{ ucwords(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('tickets.show', $ticket) }}" class="text-blue-600 hover:text-blue-900">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Recent Tickets -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Recent Tickets</h3>
            <a href="{{ route('tickets.index') }}" class="text-blue-600 hover:text-blue-900">View All →</a>
        </div>

        @if($recentTickets->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned To</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($recentTickets as $ticket)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">#{{ $ticket->id }}</td>
                    <td class="px-6 py-4">{{ Str::limit($ticket->title, 40) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->user->name }}</td>
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
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            @if($ticket->priority === 'urgent') bg-red-100 text-red-800
                            @elseif($ticket->priority === 'high') bg-orange-100 text-orange-800
                            @elseif($ticket->priority === 'medium') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($ticket->assignedTo)
                            {{ $ticket->assignedTo->name }}
                        @else
                            <span class="text-gray-400 italic">Unassigned</span>
                        @endif
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
        </div>
        @endif
    </div>

    <!-- Statistics by Category -->
    @if(count($ticketsByCategory) > 0)
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Tickets by Category</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($ticketsByCategory as $categoryName => $count)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="text-sm text-gray-600">{{ $categoryName }}</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $count }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Support Staff Performance (Admin Only) -->
    @if(count($supportStats) > 0)
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Support Team Performance</h3>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Staff Member</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Assigned</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Open</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Closed</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($supportStats as $staff)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $staff->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $staff->assigned_tickets_count }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $staff->open_tickets_count }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $staff->closed_tickets_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
