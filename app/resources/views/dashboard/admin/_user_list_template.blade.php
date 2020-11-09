@if(count($users) > 0)
@component('components.table',['class'=> 'table-borderless'])
@slot('thead')
<tr class="text-white">
    <th>{{ __('Name') }}</th>
    <th>{{ __('Email') }}</th>
    <th>{{ __('Register') }}</th>
</tr>
@endslot
@foreach($users as $user)
<tr class="border-top lf-toggle-border-color">
    <td>
        @if(has_permission('admin.users.show'))
        <a href="{{ route('admin.users.show', $user->id) }}">
        <div class="d-flex">
            <div class="mt-0 mr-2">
                <img src="{{ get_avatar($user->avatar) }}"
                     alt="{{ $user->profile->full_name }}" class="lf-w-30px rounded-circle">
            </div>
            <div class="my-auto">
                <h6 class="my-0">{{ $user->profile->full_name }}</h6>
                <p class="my-0">{{ $user->username }}</p>
            </div>
        </div>
        </a>
        @else
            <div class="d-flex">
                <div class="mt-0 mr-2">
                    <img src="{{ get_avatar($user->avatar) }}"
                         alt="{{ $user->profile->full_name }}" class="lf-w-30px rounded-circle">
                </div>
                <div class="my-auto">
                    <h6 class="my-0">{{ $user->profile->full_name }}</h6>
                    <p class="my-0">{{ $user->username }}</p>
                </div>
            </div>
        @endif
    </td>
    <td>
        @if(has_permission('admin.users.show'))
        <a href="{{ route('admin.users.show', $user->id) }}">{{ $user->email }}</a>
        @else
        {{ $user->email }}
        @endif
    </td>
    <td>
        @if(has_permission('admin.users.show'))
        <a href="{{ route('admin.users.show', $user->id) }}">
            {{ $user->created_at->diffForHumans() }}
        </a>
        @else
        {{ $user->created_at->diffForHumans() }}
        @endif
    </td>
</tr>
@endforeach
@endcomponent
@else
<p class="text-center my-3">{{ __('Empty') }}</p>
@endif
