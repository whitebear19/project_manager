<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\ExpressCheckout;
use Illuminate\Support\Facades\Auth;
use Session;
use App\User;
use App\Models\Plan;
class PayPalPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function handlePayment(Request $request)
    {
        $product = [];
        $price = $request->get('price');
        $plan = Plan::where('price',$price)->first();
        $product['items'] = [
            [
                'name' => 'Plan',
                'price' => $price,
                'desc'  => 'Select Plan',
                'qty' => 1
            ]
        ];

        $product['invoice_id'] = 1;
        $product['invoice_description'] = "Order for plan.";
        $product['return_url'] = route('success.payment');
        $product['cancel_url'] = route('cancel.payment');
        $product['total'] = $price;


        $paypalModule = new ExpressCheckout;

        $res = $paypalModule->setExpressCheckout($product);
        $res = $paypalModule->setExpressCheckout($product, true);

        $user = User::find(Auth::user()->id);

        $user->plan = $plan->period;
        $user->save();
        return redirect($res['paypal_link']);
    }

    public function paymentCancel()
    {
        session()->flash('message', "Something wrong! Please try again.");
        return redirect('/dashboard/plan');
    }

    public function paymentSuccess(Request $request)
    {
        $paypalModule = new ExpressCheckout;
        $response = $paypalModule->getExpressCheckoutDetails($request->token);

        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
            dd('Payment was successfull. The payment success page goes here!');
        }

        $user = User::find(Auth::user()->id);
        $user->paid = 1;
        if(empty($user->expired))
        {
            $now = date('Y-m-d', strtotime($user->plan.' months'));
        }
        else
        {
            $now = date('Y-m-d', strtotime($user->expired. ' + '.$user->plan.' months'));
        }
        $user->expired = $now;
        $user->save();

        return redirect('/dashboard');
    }
}
