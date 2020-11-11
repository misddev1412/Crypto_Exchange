@extends('layouts.admin')
@section('title', 'Affiliate Update')


@section('content')
<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="main-content col-lg-12">
                @include('vendor.notice')
                <div class="content-area card">
                    <div class="card-innr">
                        <div class="card-head">
                            <h4 class="card-title">Affiliate Update </h4>
                        </div>
                        <div class="gaps-1x"></div>
                        <div class="card-text ico-setting setting-token-details">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="input-item input-with-label">
                                        <label class="input-item-label">Select User</label>
                                        <div class="input-wrap">
                                            <select name="user" required="" class="select-block select-bordered" data-dd-class="search-on">
                                                @forelse($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @empty
                                                <option value="">No user found</option>
                                                @endif
                                            </select>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-item input-with-label">
                                        <label class="input-item-label">Token</label>
                                        <div class="input-wrap">
                                            <input class="input-bordered" type="text" name="token" placeholder="Token">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-item input-with-label">
                                        <label class="input-item-label"></label>
                                        <div class="input-wrap">
                                            <button id="caculate" class="btn btn-primary">Caculate Affiliate</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="data-table admin-affiliate">
                            <thead>
                                <tr class="data-item data-head">
                                    <th class="data-col dt-floor">Floor</th>
                                    <th class="data-col dt-email">Email</th>
                                    <th class="data-col dt-token">Point</th>
                                    <th class="data-col dt-amount">Token Blue</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>{{-- .card-innr --}}
                </div>{{-- .card --}}

            </div>{{-- .col --}}
        </div>{{-- .container --}}
    </div>{{-- .container --}}
</div>{{-- .page-content --}}
@endsection
@section('script')
<script>
$('#caculate').click(function(e) {
   $.post('{{ route('admin.ajax.affiliate.floor')}}', {_token: csrf_token, uid: $('select[name="user"]').val(), token: $('input[name="token"]').val() })
   .done(i=> {
       if(i.users) {
           html = '';
           for(user in i.users) {
            html += '<tr class="data-item">';
            html += '<td class="data-col dt-floor">'+ i.users[user].floor +'</td>';
            html += '<td class="data-col dt-email">'+ i.users[user].email +'</td>';
            html += '<td class="data-col dt-token">'+ i.users[user].point +'</td>';
            html += '<td class="data-col dt-amount">'+ i.users[user].token +'</td></tr>'
           }

           $('table.admin-affiliate tbody').html(html);
       }
   })
})
    
</script>    
@endsection
