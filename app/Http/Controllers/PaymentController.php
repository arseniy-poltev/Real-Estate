<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Service\GlobalConstant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\ExpressCheckout;

class PaymentController extends Controller
{
    public function index()
    {
        return view('auth.checkout');
    }

    private $api_context;

    /**
     ** We declare the Api context as above and initialize it in the contructor
     **/
    public function __construct()
    {
        $this->provider = new ExpressCheckout();
    }

    /**
     ** This method sets up the paypal payment.
     **/
    public function createPayment(Request $request)
    {
//        $recurring = ($request->get('mode') === 'recurring') ? true : false;
        $recurring = false;
        $cart = $this->getCheckoutData($recurring);
        try {
            $response = $this->provider->setExpressCheckout($cart, $recurring);
            return redirect($response['paypal_link']);
        } catch (\Exception $e) {
            session()->put(['code' => 'danger', 'message' => GlobalConstant::PAYMENT_FAIL_MSG]);
            return redirect('/checkout');
        }
    }

    /**
     ** This method confirms if payment with paypal was processed successful and then execute the payment,
     ** we have 'paymentId, PayerID and token' in query string.
     **/
    public function confirmPayment(Request $request)
    {
//        $recurring = ($request->get('mode') === 'recurring') ? true : false;
        $recurring = false;
        $token = $request->get('token');
        $PayerID = $request->get('PayerID');
        $cart = $this->getCheckoutData($recurring);
        // Verify Express Checkout Token
        $response = $this->provider->getExpressCheckoutDetails($token);
        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
            if ($recurring === true) {
                $response = $this->provider->createMonthlySubscription($response['TOKEN'], 9.99, $cart['subscription_desc']);
                if (!empty($response['PROFILESTATUS']) && in_array($response['PROFILESTATUS'], ['ActiveProfile', 'PendingProfile'])) {
                    $status = 'Processed';
                } else {
                    $status = 'Invalid';
                }
            } else {
                // Perform transaction on PayPal
                $payment_status = $this->provider->doExpressCheckoutPayment($cart, $token, $PayerID);
                $status = $payment_status['PAYMENTINFO_0_PAYMENTSTATUS'];

                if ($status == 'Completed') {

                    $transaction = new Transaction();
                    $transaction->email = Auth::User()->email;
                    $transaction->currency = 'USD';
                    $transaction->amount = GlobalConstant::PAYMENT_SUBSCRIPTION_AMOUNT;
                    $transaction->save();

                    Auth::User()->payment_verified = true;
                    Auth::User()->subscription_date = Carbon::now();
                    Auth::User()->save();
                    session()->put(['code' => 'success', 'message' => GlobalConstant::PAYMENT_SUCCESS_MSG]);
                    return redirect('/checkout');
                } else {
                    session()->put(['code' => 'danger', 'message' => GlobalConstant::PAYMENT_FAIL_MSG]);
                    return redirect('/checkout');
                }
            }
        }
    }

    protected function getCheckoutData($recurring = false)
    {
        $data = [];
        $order_id = rand(111111, 999999);
        if ($recurring === true) {
            $data['items'] = [
                [
                    'name' => 'Monthly Subscription ' . config('paypal.invoice_prefix') . ' #' . $order_id,
                    'price' => 0,
                    'qty' => 1,
                ],
            ];
            $data['return_url'] = url('/paypal/ec-checkout-success?mode=recurring');
            $data['subscription_desc'] = 'Monthly Subscription ' . config('paypal.invoice_prefix') . ' #' . $order_id;
        } else {
            $data['items'] = [
                [
                    'name' => 'Squarefoot',
                    'price' => GlobalConstant::PAYMENT_SUBSCRIPTION_AMOUNT,
                    'qty' => 1,
                ],
            ];
            $data['return_url'] = url('/checkout/confirm');
        }
        $data['invoice_id'] = config('paypal.invoice_prefix') . '_' . $order_id;
        $data['invoice_description'] = "Order #$order_id Invoice";
        $data['cancel_url'] = url('/');
        $total = 0;
        foreach ($data['items'] as $item) {
            $total += $item['price'] * $item['qty'];
        }
        $data['total'] = $total;
        return $data;
    }
}
