<?php

namespace App\Http\Controllers\KycManagement;

use App\Http\Controllers\Controller;
use App\Models\Core\Notification;
use App\Models\Kyc\KycVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ApproveKycVerificationController extends Controller
{
    public function index(KycVerification $kycVerification): RedirectResponse
    {
        if ($kycVerification->status != STATUS_REVIEWING) {
            return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('The KYC approve failed for wrong ID.'));
        }

        DB::beginTransaction();
        try {
            $updateVerification = $kycVerification->update([
                'status' => STATUS_VERIFIED
            ]);
            if ($updateVerification) {
                $updateUser = $kycVerification->user()->update(['is_id_verified' => VERIFIED]);
                if (!$updateUser) {
                    DB::rollBack();
                    return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to approve.'));
                }
                $notification = ['user_id' => $kycVerification->user_id, 'message' => __("Your KYC verification request has been approved.")];
                Notification::create($notification);
            }
        } catch (Exception $exception) {
            return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to approve.'));
        }
        DB::commit();
        return redirect()->back()->with(RESPONSE_TYPE_SUCCESS, __('The KYC has been approved successfully.'));
    }
}
