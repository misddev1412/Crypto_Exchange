<?php

namespace App\Http\Controllers\KycManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kyc\AdminKycReasonRequest;
use App\Models\Core\Notification;
use App\Models\Kyc\KycVerification;
use Exception;
use Illuminate\Support\Facades\DB;

class ExpiredKycVerificationController extends Controller
{
    public function index(AdminKycReasonRequest $request, KycVerification $kycVerification)
    {
        if ($kycVerification->status != STATUS_VERIFIED) {
            return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('The KYC expired failed for wrong ID.'));
        }

        DB::beginTransaction();
        try {
            $updateVerification = $kycVerification->update([
                'status' => STATUS_EXPIRED,
                'reason' => $request->reason
            ]);

            if ($updateVerification) {
                $updateUser = $kycVerification->user()->update(['is_id_verified' => UNVERIFIED]);
                if (!$updateUser) {
                    DB::rollBack();
                    return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to expired.'));
                }
                $notification = ['user_id' => $kycVerification->user->id, 'message' => __("Your KYC verification has been expired.")];
                Notification::create($notification);
            }
        } catch (Exception $exception) {
            return redirect()->back()->with(RESPONSE_TYPE_ERROR, __('Failed to expired.'));
        }
        DB::commit();
        return redirect()->back()->with(RESPONSE_TYPE_SUCCESS, __('The KYC has been expired successfully.'));
    }
}
