@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-12">
                {{ $dataTable['filters'] }}
                {{ $dataTable['advanceFilters'] }}
                <div class="my-4">
                    @component('components.table',['class'=> 'lf-data-table'])
                        @slot('thead')
                            <tr class="bg-primary text-white">
                                <th class="all">{{ __('Title') }}</th>
                                <th class="min-desktop">{{ __('Slug') }}</th>
                                <th class="min-desktop">{{ __('Category') }}</th>
                                <th class="all">{{ __('Status') }}</th>
                                <th class="text-right all no-sort">{{ __('Action') }}</th>
                            </tr>
                        @endslot

                        @foreach($dataTable['items'] as $key=>$post)
                            <tr>
                                <td>{{ \Illuminate\Support\Str::limit($post->title, 50) }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($post->slug, 50) }}</td>
                                <td>{{ $post->postCategory->name }}</td>
                                <td>
                                    <span class="font-size-12 py-1 px-2 badge badge-{{ config('commonconfig.active_status.' . $post->is_published . '.color_class') }}">{{ publish_status($post->is_published) }}
                                    </span>
                                </td>
                                <td class="lf-action text-right">
                                    <div class="btn-group">
                                        <button type="button"
                                                class="btn btn-sm btn-info dropdown-toggle"
                                                data-toggle="dropdown"
                                                aria-expanded="false">
                                            <i class="fa fa-gear"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right"
                                             role="menu">
                                            @if(has_permission('posts.show'))
                                                <a href="{{ route('posts.show', $post->id) }}"
                                                   class="dropdown-item"><i class="fa fa-eye"></i> {{ __('Show') }}</a>
                                            @endif
                                            @if(has_permission('posts.edit'))
                                                <a href="{{ route('posts.edit', $post->id) }}"
                                                   class="dropdown-item"><i class="fa fa-edit"></i> {{ __('Edit') }}</a>
                                            @endif
                                            @if(has_permission('posts.toggle-status'))
                                                <a href="{{ route('posts.toggle-status', $post->id) }}"
                                                   class="dropdown-item confirmation"
                                                   data-form-id="put-{{ $post->id }}"
                                                   data-form-method="put"
                                                   href="{{ route('posts.toggle-status', $post->id) }}"
                                                   data-alert="{{$post->is_published? __('Do you want to unpublished this post?'): __('Do you want to published this post?')}}">
                                                    @if($post->is_published)
                                                        <i class="fa fa-refresh"></i>
                                                        {{ __('Unpublished') }}
                                                    @else
                                                        <i class="fa fa-check-square-o"></i>
                                                        {{ __('Publish') }}
                                                    @endif
                                                </a>
                                            @endif
                                            @if(has_permission('posts.destroy'))
                                                <a href="{{ route('posts.destroy', $post->id) }}"
                                                   class="dropdown-item confirmation"
                                                   data-form-id="delete-{{ $post->id }}"
                                                   data-form-method="delete"
                                                   href="{{ route('posts.destroy', $post->id) }}"
                                                   data-alert="{{__('Do you want to remove this post?')}}"><i class="fa fa-trash"></i> {{ __('Destroy') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endcomponent
                </div>
                {{ $dataTable['pagination'] }}
            </div>
        </div>
    </div>
@endsection

@section('style')
    @include('layouts.includes.list-css')
@endsection

@section('script')
    @include('layouts.includes.list-js')
@endsection
