<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\ExpressCheckout;
use Illuminate\Support\Facades\Auth;
use Session;
use App\User;
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
        $user->save();

        return redirect('/dashboard');
    }
}
