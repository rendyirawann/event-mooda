<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketOrder;
use Illuminate\Support\Facades\Auth;

/** Dashboard Event Mooda (bukan POS). Superadmin = seluruh platform; organizer = miliknya. */
class EventDashboardController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $super = $user->isSuperadmin();
        $uid   = $user->id;

        $scopeEvent = fn ($q) => $super ? $q : $q->where('organizer_id', $uid);
        $scopeOrder = fn ($q) => $q->whereHas('event', fn ($e) => $super ? $e : $e->where('organizer_id', $uid));

        $totalEvents     = Event::where($scopeEvent)->count();
        $publishedEvents = Event::where($scopeEvent)->where('status', 'published')->count();
        $totalOrders     = TicketOrder::where($scopeOrder)->count();
        $paidOrders      = TicketOrder::where($scopeOrder)->where('status', 'paid')->count();
        $revenue         = (int) TicketOrder::where($scopeOrder)->where('status', 'paid')->sum('total');
        $ticketsSold     = Ticket::whereHas('event', fn ($e) => $super ? $e : $e->where('organizer_id', $uid))->count();
        $checkedIn       = Ticket::where('status', 'used')
            ->whereHas('event', fn ($e) => $super ? $e : $e->where('organizer_id', $uid))->count();

        $recentOrders = TicketOrder::where($scopeOrder)->where('status', 'paid')
            ->with('event')->latest('paid_at')->take(6)->get();

        $topEvents = Event::where($scopeEvent)->withCount(['tickets'])
            ->orderByDesc('tickets_count')->take(5)->get();

        return view('backend.event-dashboard', compact(
            'super', 'user', 'totalEvents', 'publishedEvents', 'totalOrders', 'paidOrders',
            'revenue', 'ticketsSold', 'checkedIn', 'recentOrders', 'topEvents'
        ));
    }
}
