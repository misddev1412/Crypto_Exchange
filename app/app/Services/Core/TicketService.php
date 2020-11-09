<?php

namespace App\Services\Core;

use App\Models\Ticket\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketService
{
    public function show(Ticket $ticket): array
    {
        $data['ticket'] = $ticket->load(['user.profile', 'comments.user.profile', 'assignedUser.profile']);
        $data['title'] = __('Ticket Details');
        return $data;
    }

    public function comment(Request $request, Ticket $ticket): RedirectResponse
    {
        $request->validate(['content' => 'required']);

        $params = [
            'user_id' => Auth::id(),
            'content' => $request->get('content')
        ];

        if ($request->hasFile('attachment')) {
            $name = md5($ticket->id . auth()->id() . time());
            $uploadedAttachment = app(FileUploadService::class)->upload($request->file('attachment'), config('commonconfig.ticket_attachment'), $name, '', '', 'public');

            if ($uploadedAttachment) {
                $params['attachment'] = $uploadedAttachment;
            }
        }

        if ($ticket->comments()->create($params)) {
            return redirect()
                ->back()
                ->with(RESPONSE_TYPE_SUCCESS, __('The message has been created successfully'))
                ->send();

        }
        return redirect()
            ->back()
            ->withInput()
            ->with(RESPONSE_TYPE_ERROR, __('Failed to create message.'))
            ->send();
    }

    public function download(Ticket $ticket, string $fileName)
    {
        if ($ticket->comments()->where('attachment', $fileName)->first()) {
            $path = config('commonconfig.ticket_attachment') . $fileName;
            return Storage::disk('public')->download($path);
        }
        return Storage::download(null);
    }

    public function close(Ticket $ticket): RedirectResponse
    {
        if ($ticket->changeStatus(STATUS_CLOSED)) {
            return redirect()
                ->back()
                ->with(RESPONSE_TYPE_SUCCESS, __('The ticket has been closed successfully'))
                ->send();
        }
        return redirect()
            ->back()
            ->with(RESPONSE_TYPE_ERROR, __('Failed to close the ticket.'))
            ->send();
    }
}
