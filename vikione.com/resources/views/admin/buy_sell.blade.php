@extends('layouts.admin')
@section('title', 'Buy / Sell Market')

@section('content')

<div class="page-content">
    <div class="container">
        @include('layouts.messages')
        @include('vendor.notice')
        <div class="card content-area content-area-mh">
            <div class="card-innr">
                <div class="card-head has-aside">
                    <h4 class="card-title">{{ ucfirst($is_page) }} Buy / Sell</h4>
                    <div class="card-opt">
                        <!-- <ul class="btn-grp btn-grp-block guttar-20px">
                            <li>
                                <a href="#" class="btn btn-sm btn-auto btn-primary" data-toggle="modal" data-target="#addTnx">
                                    <em class="fas fa-plus-circle"></em><span>Add <span class="d-none d-sm-inline-block">Tokens</span></span>
                                </a>
                            </li>
                        </ul> -->
                    </div>
                </div>
                
                <div class="page-nav-wrap">
                    <div class="page-nav-bar justify-content-between bg-lighter">
                        <div class="page-nav w-100 w-lg-auto">
                            <ul class="nav">
                                <li class="nav-item{{ (is_page('buysell.pending') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.buysell', 'pending') }}">Pending</a></li>
                                <li class="nav-item {{ (is_page('buysell.processing') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.buysell', 'processing') }}">Processing</a></li>
                                <li class="nav-item {{ (is_page('buysell.approved') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.buysell', 'approved') }}">Approved</a></li>
                                <li class="nav-item {{ (is_page('buysell.canceled') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.buysell', 'canceled') }}">Canceled</a></li>
                                <li class="nav-item {{ (is_page('buysell') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.buysell') }}">All</a></li>
                            </ul>
                        </div>
                        <div class="search flex-grow-1 pl-lg-4 w-100 w-sm-auto">
                            <form action="{{ route('admin.buysell') }}" method="GET" autocomplete="off">
                                <div class="input-wrap">
                                    <span class="input-icon input-icon-left"><em class="ti ti-search"></em></span>
                                    <input type="search" class="input-solid input-transparent" placeholder="ID to quick search" value="{{ request()->get('s', '') }}" name="s">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                @if($buysell->total() > 0) 
                <table class="data-table admin-tnx">
                    <thead>
                        <tr class="data-item data-head">
                            <th class="data-col">ID</th>
                            <th class="data-col">Seller</th>
                            <th class="data-col">Buyer</th>
                            <th class="data-col">Amount</th>
                            <th class="data-col">Detail</th>
                            <th class="data-col">Status</th>
                            <th class="data-col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($buysell as $item)
                        @php 
                            $btn_status = $item->status=='pending'?'info':($item->status=='canceled'?'secondary':($item->status=='processing'?'primary':($item->status=='approved'?'success':'danger'))) ;
                        @endphp
                        <tr class="data-item" id="tnx-item-{{ $item->id }}">
                            <td class="data-col">
                                {{ $item->id }}
                            </td>
                            <td class="data-col">
                                {!! $item['user_seller']['email'] !!}
                            </td>
                            <td class="data-col">
                                @if($item['user_buyer'] != null)
                                    {{$item['user_buyer']['email']}}
                                @else
                                    None
                                @endif
                            </td>
                            <td class="data-col">
                                {{ $item->amount }}
                            </td>
                            <td class="data-col  status_{{ $item->id }}">
                                <span class="dt-type-md badge badge-outline badge-md badge-{{ $btn_status }}">{{ ucwords($item->status) }}</span>
                            </td>
                            <td class="data-col">
                                <div class="btn-group action_{{ $item->id }}">
                                    @if($item->status == 'approved' || $item->status == 'canceled')
                                        <button type="button" class="btn btn-warning btn-sm dropdown-toggle disabled" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action
                                        </button>

                                        <div class="dropdown-menu">
                                            <button  class="dropdown-item action" onclick="approveTransaction({{$item->id}},'approved', 'approve')" data-id="{{ $item->id }}">Approve</button>
                                            <button  class="dropdown-item action" onclick="approveTransaction({{$item->id}},'canceled', 'cancel')" data-id="{{ $item->id }}">Cancel</button>
                                        </div>

                                    @endif         
                                </div>
                            </td>
                        </tr>{{-- .data-item --}}
                        @endforeach
                    </tbody>
                </table>
                @else 
                    <div class="bg-light text-center rounded pdt-5x pdb-5x">
                        <p><em class="ti ti-server fs-24"></em><br>{{ ($is_page=='all') ? 'No transaction found!' : 'No '.$is_page.' transaction here!' }}</p>
                        <p><a class="btn btn-primary btn-auto" href="{{ route('admin.buysell') }}">View All Transactions</a></p>
                    </div>
                @endif

               
            </div>{{-- .card-innr --}}
        </div>{{-- .card --}}
    </div>{{-- .container --}}
</div>

@endsection

@section('modals')



{{-- Modal End --}}
<script>

var approveTransaction = (id,status,text) => {
        Swal.fire({
        title: 'Are you sure?',
        text: "This action will "+text+" this transaction",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, '+text+' it!'
        }).then((result) => {
            if (result.isConfirmed) {
                var data = {id: id, status:status}
                $.ajax({
                    url: "{{route('admin.buysell.update')}}",
                    type: 'POST',
                    data: data,
                    cache: false,
                    dataType: 'json',

                    success: function (data) {
                            Swal.fire({
                                title: 'Your work has been saved',
                                timer: 2000,
                                timerProgressBar: true,
                                icon: 'success',
                                onClose: () => {
                                    window.location.reload()
                                }
                               
                            })
                    },
                    error: function () {
                        Swal.fire({
                                title: 'Something is wrong!',
                                timer: 2000,
                                timerProgressBar: true,
                                icon: 'error',
                                onClose: () => {
                                    window.location.reload()
                                }
                               
                            })
                    }
                });
            }
        })

    }

    
  
</script>
@endsection