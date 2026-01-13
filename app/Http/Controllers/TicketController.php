<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the tickets.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Ticket::with(['user', 'category', 'assignedTo']);

        // Filter tickets based on user role
        if ($user->isUser()) {
            // Regular users only see their own tickets
            $query->where('user_id', $user->id);
        } elseif ($user->isSupport()) {
            // Support staff can see all tickets or filter by assigned
            if ($request->has('view') && $request->view === 'assigned') {
                $query->where('assigned_to', $user->id);
            }
        }

        // Apply filters
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('priority') && $request->priority !== '') {
            $query->where('priority', $request->priority);
        }

        if ($request->has('category') && $request->category !== '') {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search') && $request->search !== '') {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Sort tickets
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $tickets = $query->paginate(15);

        $categories = Category::active()->get();
        $statuses = Ticket::getStatuses();
        $priorities = Ticket::getPriorities();

        return view('tickets.index', compact('tickets', 'categories', 'statuses', 'priorities'));
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        $categories = Category::active()->get();
        $priorities = Ticket::getPriorities();

        return view('tickets.create', compact('categories', 'priorities'));
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:' . implode(',', Ticket::getPriorities()),
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = Ticket::STATUS_NEW;

        $ticket = Ticket::create($validated);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket created successfully!');
    }

    /**
     * Display the specified ticket.
     */
    public function show(Ticket $ticket)
    {
        // Check authorization
        $this->authorizeTicketAccess($ticket);

        $ticket->load(['user', 'category', 'assignedTo', 'comments.user']);

        // Filter comments based on user role
        $user = Auth::user();
        if ($user->isUser()) {
            // Regular users only see public comments
            $ticket->setRelation('comments', $ticket->comments->where('is_internal', false)->values());
        }

        $supportUsers = User::where('role', 'support')
            ->orWhere('role', 'admin')
            ->get();

        $statuses = Ticket::getStatuses();

        return view('tickets.show', compact('ticket', 'supportUsers', 'statuses'));
    }

    /**
     * Show the form for editing the specified ticket.
     */
    public function edit(Ticket $ticket)
    {
        // Check authorization
        $this->authorizeTicketEdit($ticket);

        $categories = Category::active()->get();
        $priorities = Ticket::getPriorities();

        return view('tickets.edit', compact('ticket', 'categories', 'priorities'));
    }

    /**
     * Update the specified ticket in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        // Check authorization
        $this->authorizeTicketEdit($ticket);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:' . implode(',', Ticket::getPriorities()),
        ]);

        $ticket->update($validated);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket updated successfully!');
    }

    /**
     * Remove the specified ticket from storage.
     */
    public function destroy(Ticket $ticket)
    {
        // Check authorization
        $this->authorizeTicketEdit($ticket);

        $ticket->delete();

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Ticket deleted successfully!');
    }

    /**
     * Update the status of the specified ticket.
     */
    public function updateStatus(Request $request, Ticket $ticket)
    {
        // Only support staff can update status
        if (!Auth::user()->isSupport()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', Ticket::getStatuses()),
        ]);

        $ticket->updateStatus($validated['status']);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket status updated successfully!');
    }

    /**
     * Assign the ticket to a support staff member.
     */
    public function assign(Request $request, Ticket $ticket)
    {
        // Only support staff can assign tickets
        if (!Auth::user()->isSupport()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $ticket->assignTo($validated['assigned_to']);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket assigned successfully!');
    }

    /**
     * Add a comment to the ticket.
     */
    public function addComment(Request $request, Ticket $ticket)
    {
        // Check authorization
        $this->authorizeTicketAccess($ticket);

        $validated = $request->validate([
            'comment' => 'required|string',
            'is_internal' => 'boolean',
        ]);

        // Only support staff can add internal comments
        $isInternal = false;
        if (Auth::user()->isSupport() && $request->has('is_internal')) {
            $isInternal = $validated['is_internal'];
        }

        $ticket->addComment(
            Auth::id(),
            $validated['comment'],
            $isInternal
        );

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Comment added successfully!');
    }

    /**
     * Check if the current user can access the ticket.
     */
    protected function authorizeTicketAccess(Ticket $ticket)
    {
        $user = Auth::user();

        if ($user->isSupport()) {
            return; // Support staff can access all tickets
        }

        if ($user->id !== $ticket->user_id) {
            abort(403, 'You do not have permission to access this ticket.');
        }
    }

    /**
     * Check if the current user can edit the ticket.
     */
    protected function authorizeTicketEdit(Ticket $ticket)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return; // Admin can edit all tickets
        }

        // Regular users can only edit their own new tickets
        if ($user->isUser()) {
            if ($user->id !== $ticket->user_id) {
                abort(403, 'You do not have permission to edit this ticket.');
            }
            if (!$ticket->isNew()) {
                abort(403, 'You can only edit tickets with "New" status.');
            }
        }
    }
}
