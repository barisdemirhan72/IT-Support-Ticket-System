@extends('layouts.app')

@section('title', 'Categories')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Categories
        </h2>
        <a href="{{ route('categories.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Create New Category
        </a>
    </div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($categories->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tickets</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">{{ $category->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $category->name }}</td>
                    <td class="px-6 py-4">{{ Str::limit($category->description ?? 'N/A', 50) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $category->tickets_count ?? 0 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($category->is_active)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('categories.edit', $category) }}" class="text-blue-600 hover:text-blue-900">Edit</a>

                            <form method="POST" action="{{ route('categories.toggle-status', $category) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                    {{ $category->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>

                            @if($category->tickets_count == 0)
                            <form method="POST" action="{{ route('categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($categories->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $categories->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-12">
            <p class="text-gray-500">No categories yet.</p>
            <a href="{{ route('categories.create') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Create First Category
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
