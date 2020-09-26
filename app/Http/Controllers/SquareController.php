<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Nikolag\Square\Facades\Square;
class SquareController extends Controller
{
    public function charge(Request $request)
    {
        $transaction = Square::charge([
            'amount' => $request->get('price'),
            'card_nonce' => $request->get('card_nonce'),
            'location_id' => $request->get('location_id'),
            'currency' => 'USD'
        ]);
    }
}
