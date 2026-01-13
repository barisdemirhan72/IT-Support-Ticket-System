<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Ensure only admin users can access category management
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->canManageCategories()) {
                abort(403, 'Unauthorized action. Only administrators can manage categories.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the categories.
     */
    public function index(Request $request)
    {
        $query = Category::withCount('tickets');

        // Apply search filter
        if ($request->has('search') && $request->search !== '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Apply status filter
        if ($request->has('status') && $request->status !== '') {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        // Sort categories
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $categories = $query->paginate(15);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        // Set default value for is_active if not provided
        $validated['is_active'] = $request->has('is_active') ? $request->is_active : true;

        $category = Category::create($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        $category->loadCount('tickets');

        // Get recent tickets in this category
        $recentTickets = $category->tickets()
            ->with(['user', 'assignedTo'])
            ->latest()
            ->take(10)
            ->get();

        return view('categories.show', compact('category', 'recentTickets'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        // Set default value for is_active if not provided
        $validated['is_active'] = $request->has('is_active') ? $request->is_active : false;

        $category->update($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        // Check if the category has any tickets
        if ($category->tickets()->count() > 0) {
            return redirect()
                ->route('categories.index')
                ->with('error', 'Cannot delete category with existing tickets. Please reassign or delete tickets first.');
        }

        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category deleted successfully!');
    }

    /**
     * Toggle the active status of the category.
     */
    public function toggleStatus(Category $category)
    {
        $category->update([
            'is_active' => !$category->is_active,
        ]);

        $status = $category->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->route('categories.index')
            ->with('success', "Category {$status} successfully!");
    }
}
