@extends('layouts.admin')
@section('title', 'Point Setting')

@section('content')
<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="main-content col-lg-12">
                @include('vendor.notice')
                <div class="content-area card">
                    <div class="card-innr">
                        <div class="card-head">
                            <h4 class="card-title">Point Settings</h4>
                        </div>
                        <div class="card-text">
                            <p>The configuration system converts user tokens to points and from point to token so that
                                it can be traded with different users. This configuration will be system-wide and is
                                individually configured for each user</p>
                        </div>
                        <div class="gaps-2x"></div>
                        <div class="card-text ico-setting setting-token-point">
                            <form action="{{ route('admin.ajax.settings.update') }}" method="POST"
                                id="point_setting_form" class="validate-modern">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Turn Multiply</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">x Multiply</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="input-item input-with-label">
                                            <label class="input-item-label">Receiving point (%/Point/Day)</label>
                                        </div>
                                    </div>

                                </div>

                                @for($i = 0; $i < 6; $i++)
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="input-item input-with-label">
                                                <label class="input-item-label">Turn {{ $i + 1 }}</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="input-item">
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text"
                                                        data-validation="required"
                                                        name="point_multiply_{{ $i + 1 }}"
                                                        placeholder="x{{ $i +1 }}"
                                                        value="{{ get_setting('point_multiply_'. ($i + 1)) }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="input-item">
                                                <div class="input-wrap">
                                                    <input class="input-bordered" required="" type="text"
                                                        data-validation="required"
                                                        name="point_receiving_{{ $i + 1 }}"
                                                        placeholder="0.{{ $i + 1 }}"
                                                        value="{{ get_setting('point_receiving_'. ($i + 1)) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                                <div class="gaps-1x"></div>
                                <div class="d-flex">
                                    @csrf
                                    <input type="hidden" name="type" value="point">
                                    <button class="btn btn-primary save-disabled" type="submit" disabled><i
                                            class="ti ti-reload"></i><span>Update</span></button>
                                </div>
                            </form>
                        </div>
                    </div>{{-- .card-innr --}}
                </div>{{-- .card --}}
            </div>{{-- .col --}}
        </div>{{-- .container --}}
    </div>{{-- .container --}}
</div>{{-- .page-content --}}
@endsection
