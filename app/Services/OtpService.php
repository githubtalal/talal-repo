<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class OtpService
{


    public static function send_otp($phone_number)
    {

        // login to the service
        $response = Http::withOptions([
            'verify' => false,
        ])->post('https://sms.jt.sy/api/login', [
            'email' => ' joomlatech@madfox.solutions',
            'password' => 'x__q)(RZBwb3y/t%',
        ]);

        $token = null;
        $check_login = $response->successful();

        if ($check_login == false) {

            return false;
        }

        $response = json_decode($response);

        $token = (string)$response->token;


        // send the otp message
        $otp_code = self::generateOTP();

        $response = Http::withOptions([
            'verify' => false,
        ])->withToken($token)
            ->post('https://sms.jt.sy/api/send', [
                'phone_numbers' => '963' . $phone_number,
                'body' => 'your eCart code is : ' . $otp_code,
            ]);

        $check_sending_message = $response->successful();

        if ($check_sending_message == false) {

            return false;
        }


        // set otp code in session
        session(['otp_code' => $otp_code]);

        return true;
    }


    public static function generateOTP($n = 6)
    {

        $generator = "1357902468";

        $result = "";

        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, (rand() % (strlen($generator))), 1);
        }

        return $result;
    }

}
