<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhooksController extends Controller
{
    private $token = 'arKmUAvgn244G3siQ5dg';

    public function fbPages(Request $request)
    {
        logger('facebook pages hooks', request()->all());
        $mode = $request->get('hub_mode');
        $token = $request->get('hub_verify_token');
        $challenge = $request->get('hub_challenge');

        if ($mode && $token) {
            if ($mode === 'subscribe' && $token === $this->token) {
                logger('facebook pages hooks', request()->all());
                return response($challenge);
            }
        }

    }
}
