@extends('layouts.app')

@section('title', 'Edit Category')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit Category: {{ $category->name }}
    </h2>
@endsection

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Category Name *</label>
                <input type="text"
                       name="name"
                       id="name"
                       value="{{ old('name', $category->name) }}"
                       required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g., Hardware, Software, Network">
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description"
                          id="description"
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Brief description of this category...">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Active (users can select this category)</span>
                </label>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('categories.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Update Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
