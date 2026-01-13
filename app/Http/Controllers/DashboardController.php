<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isSupport()) {
            return $this->supportDashboard($user);
        }

        return $this->userDashboard($user);
    }

    /**
     * Display the dashboard for regular users.
     */
    protected function userDashboard(User $user)
    {
        // Get user's ticket statistics
        $totalTickets = Ticket::where('user_id', $user->id)->count();
        $openTickets = Ticket::where('user_id', $user->id)->open()->count();
        $closedTickets = Ticket::where('user_id', $user->id)->closed()->count();
        $newTickets = Ticket::where('user_id', $user->id)
            ->where('status', Ticket::STATUS_NEW)
            ->count();

        // Get recent tickets
        $recentTickets = Ticket::where('user_id', $user->id)
            ->with(['category', 'assignedTo'])
            ->latest()
            ->take(5)
            ->get();

        // Get tickets by status
        $ticketsByStatus = Ticket::where('user_id', $user->id)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Get tickets by category
        $ticketsByCategory = Ticket::where('user_id', $user->id)
            ->join('categories', 'tickets.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('count(*) as count'))
            ->groupBy('categories.name')
            ->pluck('count', 'name')
            ->toArray();

        // Get active categories
        $categories = Category::active()->get();

        return view('dashboard.user', compact(
            'totalTickets',
            'openTickets',
            'closedTickets',
            'newTickets',
            'recentTickets',
            'ticketsByStatus',
            'ticketsByCategory',
            'categories'
        ));
    }

    /**
     * Display the dashboard for support staff.
     */
    protected function supportDashboard(User $user)
    {
        // Get overall ticket statistics
        $totalTickets = Ticket::count();
        $openTickets = Ticket::open()->count();
        $closedTickets = Ticket::closed()->count();
        $newTickets = Ticket::where('status', Ticket::STATUS_NEW)->count();

        // Get tickets assigned to the support user
        $myAssignedTickets = Ticket::where('assigned_to', $user->id)->count();
        $myOpenTickets = Ticket::where('assigned_to', $user->id)->open()->count();

        // Get unassigned tickets
        $unassignedTickets = Ticket::whereNull('assigned_to')->open()->count();

        // Get recent tickets (all)
        $recentTickets = Ticket::with(['user', 'category', 'assignedTo'])
            ->latest()
            ->take(10)
            ->get();

        // Get tickets by status
        $ticketsByStatus = Ticket::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Get tickets by priority
        $ticketsByPriority = Ticket::select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();

        // Get tickets by category
        $ticketsByCategory = Ticket::join('categories', 'tickets.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('count(*) as count'))
            ->groupBy('categories.name')
            ->orderBy('count', 'desc')
            ->pluck('count', 'name')
            ->toArray();

        // Get urgent tickets
        $urgentTickets = Ticket::where('priority', Ticket::PRIORITY_URGENT)
            ->open()
            ->with(['user', 'category'])
            ->latest()
            ->take(5)
            ->get();

        // Get active categories
        $categories = Category::active()->withCount('tickets')->get();

        // Get support staff performance (if admin)
        $supportStats = [];
        if ($user->isAdmin()) {
            $supportStats = User::where('role', 'support')
                ->orWhere('role', 'admin')
                ->withCount([
                    'assignedTickets',
                    'assignedTickets as open_tickets_count' => function($query) {
                        $query->open();
                    },
                    'assignedTickets as closed_tickets_count' => function($query) {
                        $query->closed();
                    }
                ])
                ->get();
        }

        return view('dashboard.support', compact(
            'totalTickets',
            'openTickets',
            'closedTickets',
            'newTickets',
            'myAssignedTickets',
            'myOpenTickets',
            'unassignedTickets',
            'recentTickets',
            'ticketsByStatus',
            'ticketsByPriority',
            'ticketsByCategory',
            'urgentTickets',
            'categories',
            'supportStats'
        ));
    }

    /**
     * Get statistics for charts and graphs (AJAX endpoint).
     */
    public function statistics(Request $request)
    {
        $user = Auth::user();
        $period = $request->get('period', 30); // days

        $startDate = now()->subDays($period);

        // Base query depending on user role
        $query = Ticket::where('created_at', '>=', $startDate);

        if ($user->isUser()) {
            $query->where('user_id', $user->id);
        }

        // Tickets created over time
        $ticketsOverTime = $query
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Response time statistics
        $avgResponseTime = Ticket::where('created_at', '>=', $startDate)
            ->whereNotNull('assigned_to')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours'))
            ->value('avg_hours');

        // Resolution time statistics
        $avgResolutionTime = Ticket::where('created_at', '>=', $startDate)
            ->whereNotNull('resolved_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_hours'))
            ->value('avg_hours');

        return response()->json([
            'tickets_over_time' => $ticketsOverTime,
            'avg_response_time' => round($avgResponseTime ?? 0, 2),
            'avg_resolution_time' => round($avgResolutionTime ?? 0, 2),
        ]);
    }
}
