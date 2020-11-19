@extends('layouts.user')
@section('title', __('User Sell goods'))

@push('header')
<script type="text/javascript">
    // var view_transaction_url = "{{ route('user.ajax.transactions.view') }}";
</script>
@endpush

@section('content')
@include('layouts.messages')
<div class="card content-area content-area-mh">
    <div class="card-innr">
        <div class="card-head">
            <h4 class="card-title">{{__('Sell goods list')}}</h4>
            <div class="card-text">
                <!-- <p>To perform this transaction feature, you must be in KYCs</p> -->
            </div>
            <div class="gaps-2x"></div>
        </div>
        <div class="gaps-1x"></div>
        <div class="row">
            <div class="col-md-12">
                <!-- <div class="float-right position-relative">
                    <a href="#" class="btn btn-light-alt btn-xs dt-filter-text btn-icon toggle-tigger" style="position: relative;"> <em class="ti ti-settings"></em> </a>
                    <div class="toggle-class toggle-datatable-filter dropdown-content dropdown-dt-filter-text dropdown-content-top-left text-left">
                        
                        <ul class="dropdown-list dropdown-list-s2">
                            <li><h6 class="dropdown-title">{{ __('Status') }}</h6></li>
                            <li>
                                <input class="data-filter input-checkbox input-checkbox-sm" type="radio" name="tnx-status" id="status-all" checked value="">
                                <label for="status-all">{{ __('Show All') }}</label>
                            </li>
                            <li>
                                <input class="data-filter input-checkbox input-checkbox-sm" type="radio" name="tnx-status" id="status-approved" value="approved">
                                <label for="status-approved">{{ __('Approved') }}</label>
                            </li>
                            <li>
                                <input class="data-filter input-checkbox input-checkbox-sm" type="radio" name="tnx-status" value="pending" id="status-pending">
                                <label for="status-pending">{{ __('Pending') }}</label>
                            </li>
                            <li>
                                <input class="data-filter input-checkbox input-checkbox-sm" type="radio" name="tnx-status" value="canceled" id="status-canceled">
                                <label for="status-canceled">{{ __('Canceled') }}</label>
                            </li>
                        </ul>
                    </div>
                </div> -->
            </div>
        </div>
        <table class="data-table dt-filter-init user-tnx">
            <thead>
                <tr class="data-item data-head">
                    <th class="data-col">{{ __('no') }}</th>
                    <th class="data-col">{{ __('from') }}</th>
                    <th class="data-col">{{ __('to') }}</th>
                    <th class="data-col">{{ __('Amount') }}</th>
                    <th class="data-col">{{ __('detail') }}</th>
                    <th class="data-col">{{__('status') }}</th>
                    <th class="data-col">{{ __('action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sellgoods as $sellgood)
                    @php 
                        $btn_status = $sellgood->status=='pending'?'info':($sellgood->status=='canceled'?'secondary':($sellgood->status=='processing'?'primary':($sellgood->status=='approved'?'success':'danger'))) ;
                    @endphp
                <tr class="data-item tnx-item-{{ $sellgood->id }}">
                    <td class="data-col">
                        {{ $sellgood->id }}
                    </td>
                    <td class="data-col">
                        {{ $sellgood->user_seller->email }}
                    </td>
                    <td class="data-col">
                        {{ $sellgood->user_buyer->email }}
                    </td>
                    <td class="data-col">
                        {{ $sellgood->amount }}
                    </td>
                    <td class="data-col">
                        {{ $sellgood->details }}
                    </td>
                    <td class="data-col dt-point status_{{ $sellgood->id }}">
                        <span class="dt-type-md badge badge-outline badge-md badge-{{ $btn_status }}">{{ ucwords($sellgood->status) }}</span>
                    </td>
                    <td>
                        <div class="btn-group action_{{ $sellgood->id }}">
                        @if($sellgood->status!='pending' || $sellgood->seller == Auth::user()->id)
                            <button type="button" class="btn btn-warning btn-sm dropdown-toggle disabled" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action
                            </button>
                        @else
                            <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action
                            </button>

                            <div class="dropdown-menu">
                                <button  class="dropdown-item action" data-id="{{ $sellgood->id }}" data-value="processing">Accept</button >
                                <button  class="dropdown-item action" data-id="{{ $sellgood->id }}" data-value="canceled">Cancel</button >
                            </div>

                        @endif         
                        </div>
                    </td>
                    
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('modals')


@endsection

@section('script')
<script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(".action").on("click",function(){
            var id = $(this).data('id');
            var status = $(this).data('value');
            $.ajax({
                type: "POST",
                url: "{{ route('user.sell_goods.update') }}",
                data: {id: id, status:status},
                success: function(i) {
                    if(i.msg=="error"){
                        swal(i.message, "", "error");
                    }else{
                        swal("Successful!", "", "success");
                        if(status == 'canceled'){
                            $(".status_"+id).html('<span class="dt-type-md badge badge-outline badge-md badge-secondary">Canceled</span>');
                        }else if(status == 'processing'){
                            $(".status_"+id).html('<span class="dt-type-md badge badge-outline badge-md badge-primary">Processing</span>');
                        }
                        $(".action_"+id).html('<button type="button" class="btn btn-warning btn-sm dropdown-toggle disabled" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>');
                    }
                    
                },

            });
        })
    })
</script>
@endsection