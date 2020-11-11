@extends('layouts.admin')
@section('title', 'Wallet Change Request')

@push('header')
<script type="text/javascript">
    var wallet_change_url = "{{ route('admin.ajax.users.wallet.action') }}";
</script>
@endpush

@section('content')
<div class="page-content">
    <div class="container">
      <div class="content-area card">
        <div class="card-innr">
          <div class="card-head has-aside">
              <h4 class="card-title">Wallet Request List</h4>
              <div class="card-opt">
                  <ul class="btn-grp btn-grp-block guttar-20px">
                      <li>
                          <a href="{{ route('admin.users') }}" class="btn btn-auto btn-outline btn-primary btn-sm">
                              <em class="fas fa-arrow-left"> </em><span>Back <span  class="d-none d-md-inline-block">to Users</span></span>
                          </a>
                      </li>
                  </ul>
              </div>
          </div>

          <div class="gaps-1x"></div>
          <table class="data-table dt-init user-list user-request">
            <thead>
                <tr class="data-item data-head">
                    <th class="data-col request-user">User </th>
                    <th class="data-col dt-verify request-old-wallet">Old Wallet</th>
                    <th class="data-col request-new-wallet">New Wallet</th>
                    <th class="data-col"></th>
                </tr>
            </thead>
            <tbody id="request-list">
                @foreach($meta_data as $item)
                <tr class="data-item request-{{ $item->id }}">
                    <td class="data-col data-user">
                        <span class="lead user-name">{{ $item->user->name }}</span>
                        <span class="sub sub-id">{{ $item->user->email }}</span>
                    </td>
                    <td class="data-col dt-verify old-wallet">
                        <span class="lead wallet-name">{{ ucfirst($item->user->walletType) }}</span>
                        <span class="sub wallet-address">{{ show_str($item->user->walletAddress, 10) }}</span>
                    </td>
                    @php 

                    @endphp
                    <td class="data-col new-wallet">
                        <span class="lead wallet-name">{{ ucfirst($item->data()->name) }}</span>
                        <span class="sub wallet-address">{{ $item->data()->address }}</span>
                    </td>
                    <td class="data-col text-right" title="Request Date {{ _date($item->created_at) }}">
                        <div class="relative d-inline-block">
                            <a href="#" class="btn btn-light-alt btn-xs btn-icon toggle-tigger"><em class="ti ti-more-alt"></em></a>
                            <div class="toggle-class dropdown-content dropdown-content-top-left">
                                <ul class="dropdown-list">
                                   <li><a data-id="{{ $item->id }}" data-action="approve" href="javascript:void(0)" class="wallet-change-action"><em class="fa fa-check-circle"></em> Approve</a></li>
                                   <li> <a data-id="{{ $item->id }}" data-action="reject" href="javascript:void(0)" class="wallet-change-action"><em class="fa fa-times-circle"></em> Reject</a></li>
                               </ul>
                           </div>
                       </div>
                   </td>

               </tr>
               @endforeach
            </tbody>
          </table>
        </div>
      </div>{{-- .card --}}
   </div>
</div>
@endsection