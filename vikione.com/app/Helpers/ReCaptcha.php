<?php
namespace App\Helpers;

/**
 * Google ReCaptha
 * 
 * @version 1.0
 * @since 1.1.4
 */

use GuzzleHttp\Client;
use Illuminate\Validation\ValidationException;

trait ReCaptcha
{
    public function isRecaptchaEnabled()
    {
        return recaptcha();
    }

    public function checkReCaptcha($recaptcha_code, $ajax=false)
    {
        $validate = true; $score = 1;
        if( $this->isRecaptchaEnabled() && !empty($recaptcha_code) ) {
            $validate = false; $score = 0.1;
            try {
                $client = new Client();
                $reCap = $client->post('https://www.google.com/recaptcha/api/siteverify', ['form_params' => [
                    'secret' => recaptcha('secret'),
                    'response' => $recaptcha_code,
                ]]);
                $response = $reCap->getBody();
                $response = json_decode($response);
                if($response) {
                    $score = $response->score;
                    if ($response->success == true && $response->score >= 0.6) {
                        $validate = true; 
                    }
                } 
            } catch (\Exception $e) {
            }
        }
        if($ajax==true) {
            $ajax_out = [ 'msg' => 'success', 'score' => $score ]; 
            if($validate===false) {
                $ajax_out = [ 'msg' => 'error', 'message' => __('auth.recaptcha'), 'score' => $score ];
            }
            return $ajax_out;
        } else {
            if($validate===false) {
                throw ValidationException::withMessages([
                    'recaptcha' => [__('auth.recaptcha')],
                ])->status(429);
            }
        }

        return 4;
    }
}
