<?php

namespace App\Http\Controllers\Core;


use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductActiveController extends Controller
{

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'purchase_code' => 'required|uuid',
        ]);

        $filePath = storage_path('framework/nEZ7JXsiNtzgNNK487R7T8yNcZtucq18G1o7');

        try {
            $response = Http::post(PRODUCT_VERIFIER_URL, [
                'purchase_code' => $validatedData['purchase_code'],
                'server_addr' => $request->server('SERVER_ADDR'),
                'http_host' => $request->server('HTTP_HOST'),
            ]);
            if ($response->successful()) {
                file_put_contents($filePath, $response->body());
            } else {
                throw new Exception(__('Product could not be activated.'));
            }
        } catch (Exception $exception) {
            return back()
                ->with(RESPONSE_TYPE_ERROR, $exception->getMessage());
        }

        if ($response) {
            return redirect()
                ->route('home')
                ->with(RESPONSE_TYPE_SUCCESS, __("Product has been activated successfully."));
        }
        return back()
            ->with(RESPONSE_TYPE_ERROR, __('Product could not be activated.'));
    }
}

