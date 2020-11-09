<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\{TicketCommentRequest, TicketRequest};
use App\Models\Ticket\Ticket;
use App\Services\Core\{DataTableService, FileUploadService, TicketService};
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\{Facades\Auth, Facades\DB, Facades\Storage};
use Illuminate\View\View;
use Ramsey\Uuid\Uuid;

class UserTicketController extends Controller
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
            ['status', __('Status'), ticket_status()]
        ];

        $queryBuilder = Ticket::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');


        $data['dataTable'] = app(DataTableService::class)
            ->setSearchFields($searchFields)
            ->setOrderFields($orderFields)
            ->setFilterFields($filters)
            ->create($queryBuilder);
        $data['title'] = __('My Tickets');

        return view('ticket.user.index', $data);
    }

    public function show(Ticket $ticket): View
    {
        return view('ticket.user.show', $this->ticketService->show($ticket));
    }

    public function create(): View
    {
        $data['title'] = __('Create Ticket');
        return view('ticket.user.create', $data);
    }


    public function store(TicketRequest $request): RedirectResponse
    {
        $params = [
            'user_id' => Auth::id(),
            'id' => Uuid::uuid4(),
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'previous_id' => $request->get('previous_id')
        ];

        if ($request->hasFile('attachment')) {
            $name = md5($params['id'] . auth()->id() . time());
            $uploadedAttachment = app(FileUploadService::class)->upload($request->file('attachment'), config('commonconfig.ticket_attachment'), $name, '', '', 'public');

            if ($uploadedAttachment) {
                $params['attachment'] = $uploadedAttachment;
            }
        }

        if (Ticket::create($params)) {
            return redirect()->route('tickets.index')->with(RESPONSE_TYPE_SUCCESS, __('Ticket has been created successfully.'));
        }

        return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __('Failed to create ticket.'));
    }

    public function comment(TicketCommentRequest $request, Ticket $ticket): RedirectResponse
    {
        return $this->ticketService->comment($request, $ticket);
    }

    public function download(Ticket $ticket, string $fileName)
    {
        return $this->ticketService->download($ticket, $fileName);
    }

    public function close(Ticket $ticket): RedirectResponse
    {
        return $this->ticketService->close($ticket);
    }
}
