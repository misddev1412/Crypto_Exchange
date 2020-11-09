@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container py-5">
        <div class="wallet-address">
            @if($wallet->coin->deposit_status == ACTIVE)
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-3 lf-toggle-border-color">
                            <div class="card-body bg-primary text-white p-4 text-center">
                                <h2 class="card-title m-0">
                                    {{ __('Your :coin Deposit Address', ['coin' => $wallet->symbol]) }}
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5 mb-2">
                        <div class="card lf-toggle-bg-card lf-toggle-border-color">
                            <div class="card-body">
                                <figure class="text-center mb-0">
                                    @if(isset($addressSvg))
                                        {{ view_html($addressSvg) }}
                                        <p class="text-muted my-2">{{ __('Scan QR code or copy the address') }}</p>
                                        <div class="d-flex justify-content-center">
                                            <figcaption class="border line-height-maximum px-2" id="addressText">{{ $walletAddress }}</figcaption>
                                            <button class="btn btn-sm btn-primary py-1" id="copyAddressBtn">{{ __('Copy') }}</button>
                                        </div>
                                    @else
                                        <figcaption class="py-4">
                                            {{ view_html($walletAddress) }}
                                        </figcaption>
                                    @endif
                                </figure>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card lf-toggle-bg-card lf-toggle-border-color">
                            <div class="card-body">
                                <p class="my-3">
                                    {{ __('Only send :coinName (:coin) to this address. Sending any other digital asset will result in permanent loss.', ['coinName' => $wallet->coin->name, 'coin' => $wallet->symbol]) }}
                                </p>
                            </div>
                        </div>
                        <div class="card border-top-0 lf-toggle-bg-card lf-toggle-border-color">
                            <div class="card-body p-4">
                                <p class="my-3">
                                    {{ __('After making a deposit, you can track its progress on the Deposit & Withdrawal History page.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="card">
                            <div class="card-body p-4 text-center">
                                <h4 class="text-muted">
                                    <strong>{{ $walletAddress }}</strong>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
@section('style')
    <style>
        svg {
            max-width: 100%;
        }
    </style>
@endsection
@section('script')
    <script>
        "use strict";

        $(document).ready(function () {
            var copyAddressBtn = $("#copyAddressBtn");
            copyAddressBtn.on("click", function () {
                copyToClipboard("#addressText");
            });

            function copyToClipboard(element) {
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val($(element).html()).select();
                document.execCommand("copy");
                $temp.remove();
            }
        });
    </script>
@endsection
