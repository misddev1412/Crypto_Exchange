<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\TicketCommentRequest;
use App\Models\Ticket\Ticket;
use App\Services\Core\DataTableService;
use App\Services\Core\TicketService;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\{Auth, DB};
use Illuminate\View\View;

class AdminTicketController extends Controller
{
    public $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index(): View
    {
        $searchFields = [
            ['id', __('Ticket ID')],
            ['title', __('Heading')],
        ];

        $orderFields = [
            ['id', __('Ticket ID')],
            ['title', __('Heading')],
            ['created_at', __('Date')],
        ];

        $filters = [
            ['status', __('Status'), ticket_status()],
            ['assigned_to', __('Assigned To'), 'preset', null,
                [
                    [__('Only Me'), '=', Auth::id()]
                ]
            ]
        ];

        $queryBuilder = Ticket::with('assignedUser.profile')
            ->when(!Auth::user()->is_super_admin, function ($query) {
                $query->whereNull('assigned_to')
                    ->orWhere('assigned_to', Auth::id());
            })
            ->orderBy('created_at', 'desc');

        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->setFilterFields($filters)
            ->create($queryBuilder);
        $data['title'] = __('Tickets');

        return view('ticket.admin.index', $data);
    }

    public function show(Ticket $ticket): View
    {

        return view('ticket.admin.show', $this->ticketService->show($ticket));
    }

    public function comment(TicketCommentRequest $request, Ticket $ticket): void
    {
        $this->ticketService->comment($request, $ticket);
    }

    public function download(Ticket $ticket, string $fileName): void
    {
        $this->ticketService->download($ticket, $fileName);
    }

    public function assign(Request $request, Ticket $ticket): RedirectResponse
    {
        $request->validate([
            'assigned_to' => 'required_with:from_form|exists:users,id'
        ]);
        if ($ticket->status != STATUS_OPEN) {
            return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __('This ticket cannot be assigned.'));
        }

        $params = [
            'assigned_to' => $request->get('assigned_to', Auth::id()),
            'status' => STATUS_PROCESSING
        ];
        if ($ticket->update($params)) {
            return redirect()->route('admin.tickets.index')->with(RESPONSE_TYPE_SUCCESS, __('The ticket has been assigned successfully'));

        }
        return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __('Failed to assign ticket.'));
    }

    public function close(Ticket $ticket): void
    {
        $this->ticketService->close($ticket);
    }

    public function resolve(Ticket $ticket): RedirectResponse
    {
        if ($ticket->changeStatus(STATUS_RESOLVED)) {
            return redirect()->route('admin.tickets.index')->with(RESPONSE_TYPE_SUCCESS, __('The ticket has been resolved successfully'));
        }
        return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to resolve the ticket.'));
    }
}
