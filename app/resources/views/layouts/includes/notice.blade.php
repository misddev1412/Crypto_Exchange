@php($noticeKey = 0)
@foreach(get_notices() as $notice)
    <div class="notice-modal modal fade show" id="notice-{{$noticeKey}}" data-next-modal="{{ $noticeKey + 1 }}"
         tabindex="-1"
         role="dialog"
         aria-labelledby="notice-{{$noticeKey}}-title" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-0 bg-{{ $notice->type }}">
                <div class="modal-header rounded-0 py-2 px-3 border-bottom-0">
                    <h5 class="modal-title" id="notice-{{$noticeKey++}}-title">{{ $notice->title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0 line-height-medium">
                    {{ $notice->description }}
                </div>
            </div>
        </div>
    </div>
@endforeach
