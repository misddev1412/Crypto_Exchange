@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                @component('components.alert',['type' => 'primary', 'class' => 'mb-4 rounded-0 bg-primary text-white lf-toggle-border-color'])
                    <h3 class="card-title">{{$ticket->title}}</h3>
                @endcomponent

            </div>
            <div class="col-lg-4 order-lg-1 mb-lg-0 mb-4">
                @component('components.card',['class' => 'lf-toggle-border-color lf-toggle-bg-card', 'headerClass' => 'bg-primary mb-2'])
                    @slot('header')
                        <h3 class="text-light">{{__('Ticket Information')}}</h3>
                    @endslot
                    <div class="border-bottom lf-toggle-border-color mb-3 pb-3 align-items-center">
                        <div class="mb-2">{{ __('Ticket ID') }} :</div>
                        <div class="text-muted">{{$ticket->id}}</div>
                    </div>
                    @if($ticket->ticket_id)
                        <div class="border-bottom lf-toggle-border-color d-flex mb-3 pb-3 align-items-center">
                            <div class="mr-3">{{ __('Reference ID') }} :</div>
                            <a target="_blank"
                               href="{{ route('trader.tickets.show', $ticket->ticket_id) }}">{{$ticket->ticket_id}}</a>
                        </div>
                    @endif

                    <div class="border-bottom lf-toggle-border-color d-flex mb-3 pb-3 align-items-center">
                        <div class="mr-3">{{ __('Assigned To') }} :</div>
                        <div class="text-muted">
                            @if(optional($ticket->assignedUser)->profile)
                                <a href="{{ route('profile.index',$ticket->assigned_to) }}">{{ optional(optional($ticket->assignedUser)->profile)->full_name}}</a>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="border-bottom lf-toggle-border-color d-flex mb-3 pb-3 align-items-center">
                        <div class="mr-3">{{ __('Date') }} :</div>
                        <div class="text-muted">{{$ticket->created_at->toDateTimeString()}}</div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="mr-3">{{__('Status')}} :</div>
                        <small
                            class="px-2 py-1 badge badge-{{ config('commonconfig.ticket_status.'. $ticket->status.'.color_class') }}">{{ ticket_status($ticket->status) }}</small>
                    </div>
                @endcomponent

                @if(in_array($ticket->status,[STATUS_OPEN,STATUS_PROCESSING]))
                    @component('components.card',['class'=> 'mt-4 lf-toggle-border-color lf-toggle-bg-card','headerClass' => 'bg-primary mb-2'])
                        @slot('header')
                            <h3 class="text-light">{{__('Action')}}</h3>
                        @endslot
                        @if($ticket->status == STATUS_OPEN)
                            @if(is_null($ticket->assigned_to))
                                <form
                                    action="{{ route('admin.tickets.assign',['ticket' => $ticket->id, 'from_form' => true]) }}"
                                    method="post">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        {{ Form::select('assigned_to', [],null, ['class' => 'select2 w-100']) }}
                                    </div>

                                    <div class="form-group">
                                        {{ Form::submit(__('Assign'), ['class' => 'btn btn-block btn-primary']) }}
                                    </div>

                                </form>
                            @endif

                            <a class="btn btn-block btn-info confirmation" data-form-method="put"
                               data-alert="{{ 'Are you sure?' }}" data-form-id="assign-{{ $ticket->id }}"
                               href="{{ route('admin.tickets.assign', ['ticket' => $ticket->id]) }}">{{ __('Assign Me') }}</a>
                        @endif
                        <a class="btn btn-block btn-danger confirmation" data-form-method="put"
                           data-alert="{{ 'Are you sure?' }}" data-form-id="close-{{ $ticket->id }}"
                           href="{{ route('admin.tickets.close', ['ticket' => $ticket->id]) }}">{{ __('Close Ticket') }}</a>
                        <a class="btn btn-block btn-success confirmation" data-form-method="put"
                           data-alert="{{ 'Are you sure?' }}" data-form-id="resolve-{{ $ticket->id }}"
                           href="{{ route('admin.tickets.resolve', ['ticket' => $ticket->id]) }}">{{ __('Resolve Ticket') }}</a>

                    @endcomponent
                @endif
            </div>
            <div class="col-lg-8 order-lg-0">
                @component('components.card',['class' => 'lf-toggle-border-color lf-toggle-bg-card', 'type' => 'info','headerClass' => 'bg-primary','bodyClass'=> 'pt-4'])
                    @slot('header')
                        <h3 class="card-title text-light">{{__('Discussion')}}</h3>
                    @endslot
                    <div class="single-comment mb-4">
                        <div class="ticket-comment-header d-flex align-items-center">
                            <img width="50" height="50" class="rounded-circle"
                                 src=" {{ get_avatar($ticket->user->avatar) }}" alt="">
                            <span class="ml-3">
                                <h4 class="mb-0">{{$ticket->user->profile->full_name}}</h4>
                                <small
                                    class="text-muted">{{$ticket->created_at !== null?$ticket->created_at->diffForHumans():''}}</small>
                            </span>
                        </div>
                        <div class="ticket-comment-body">
                            {{$ticket->content}}
                            @if($ticket->attachment)
                                <div class="attachment mt-4">
                                    {{ ticket_comment_attachment_link(route('admin.tickets.attachment.download',['ticket' => $ticket->id,'fileName' => $ticket->attachment]), $ticket->attachment) }}
                                </div>
                            @endif
                        </div>
                    </div>
                    @foreach($ticket->comments as $comment)
                        <div class="single-comment mb-4">
                            <div class="ticket-comment-header d-flex align-items-center">
                                <img width="50" height="50" class="rounded-circle"
                                     src=" {{ get_avatar($comment->user->avatar) }}" alt="">
                                <span class="ml-3">
                                <h4 class="mb-0">{{$comment->user->profile->full_name}}</h4>
                                <small
                                    class="text-muted">{{$comment->created_at !== null?$comment->created_at->diffForHumans():''}}</small>
                            </span>
                            </div>
                            <div class="ticket-comment-body">
                                {{$comment->content}}

                                @if($comment->attachment)
                                    <div class="attachment mt-4">
                                        {{ ticket_comment_attachment_link(route('admin.tickets.attachment.download',['ticket' => $ticket->id,'fileName' => $comment->attachment]), $comment->attachment) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endcomponent

                @includeWhen(($ticket->status == STATUS_PROCESSING),'ticket.user._comment_form')
            </div>
        </div>
    </div>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/jasny-bootstrap/css/jasny-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2-bootstrap4.min.css') }}">

    <style>
        .ticket-comment-header {
            margin-bottom: 25px;
            margin-left: 9px;
        }

        .ticket-comment-body {
            padding: 15px;
            position: relative;
        }

        .ticket-comment-body:after {
            content: '';
            border-left: 15px solid transparent;
            border-right: 15px solid transparent;
            border-bottom: 15px solid;
            position: absolute;
            top: -15px;
            left: 30px;
            -moz-transform: translateX(-15px);
            -o-transform: translateX(-15px);
            -webkit-transform: translateX(-15px);
            -ms-transform: translateX(-15px);
            transform: translateX(-15px);
        }

        .select2-container--bootstrap4 .select2-results__option--highlighted, .select2-container--bootstrap4 .select2-results__option--highlighted.select2-results__option[aria-selected="true"] {
            background: #eeeeee !important;
            color: #000000 !important;
        }

        span.select2-container .select2-selection--single {
            padding: 10px 5px !important;
        }

        .select2-container--bootstrap4.select2-container--focus .select2-selection {
            border: none;
            background: #eeeeee !important;
        }

        .select2-container .media img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .select2-container .select2-selection--single {
            height: auto !important;
            padding: 10px 0 !important;
        }

        * {
            outline: none;
        }

    </style>
@endsection

@section('script')
    <script src="{{ asset('plugins/jasny-bootstrap/js/jasny-bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.js') }}"></script>
    <script src="{{ asset('plugins/cvalidator/cvalidator-language-en.js') }}"></script>
    <script src="{{ asset('plugins/cvalidator/cvalidator.js') }}"></script>
    <script>
        "use strict";

        function receiverTemplate(data) {
            return (
                '<div class="d-flex align-items-center">' +
                '<img src="' + data.avatar + '" class=" mr-3 lf-w-40px lf-h-40px rounded border">' +
                '<div class="ml-2 line-height-standard">' +
                '<h6 class="mt-0 mb-1">' + data.name + ' <small class="text-muted ml-1">@' + data.username + '</small></h6>' +
                data.email +
                '</div>' +
                '</div>'
            );
        }

        $(document).ready(function () {
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: '{{ __("Select User") }}',
                minimumInputLength: 3,
                ajax: {
                    url: '{{ route('admin.users.search') }}',
                    data: function (params) {
                        return {
                            p_srch: params.term
                        }
                    },
                    processResults: function (data) {
                        let responseData = data.map((item) => {
                            return {
                                id: item.id,
                                title: item.email,
                                text: receiverTemplate(item),
                            };
                        });
                        // parse the results into the format expected by Select2.
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data
                        return {
                            results: responseData
                        };
                    },
                    cache: false
                },
                escapeMarkup: function (markup) {
                    return markup;
                }
            });

            $('#ticketCommentForm').cValidate({
                rules : {
                    'content' : 'required|max:500',
                    'attachment' : 'mimetypes:jpg,jpeg,png,doc,docx,pdf,txt|max:1024',
                }
            });
        });
    </script>
@endsection



