<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use App\Supplier;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $suppliers = Supplier::all();

        $banks = $this->getBanks();
        $transfers = $this->listTransfers();

        $banks = $banks['data'];
        $transfers = $transfers['data'];

        return view('home', ['banks' => $banks, 'suppliers' => $suppliers, 'transfers' => $transfers]);
    }

    /**
     * Create a new supplier in DB and send to paystack to create recipient.
     */

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'bank_code' => 'required',
            'account_number' => 'required'
        ]);

        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->description = $request->description;
        $supplier->bank_code = $request->bank_code;
        $supplier->account_number = $request->account_number;

        $recipient = $this->createRecipient($supplier->name, $supplier->description, $supplier->account_number, $supplier->bank_code);

        if(is_array($recipient))
        {
            $supplier->recipient_code = $recipient['recipient_code'];
            $supplier->save();

            return redirect()->back()->with('message', 'Supplier created successfully');
        }
       return redirect()->back()->with('message', $recipient);
    }

    public function view_pay(Request $request)
    {
        $supplier = Supplier::find($request->id);
        return view('pay', ['supplier' => $supplier]);
    }

    public function make_payment(Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric',
        ]);
        $supplier = Supplier::find($request->id);
        $amount = $request->amount;
        $amount_in_kobo = $amount*100;
        $transaction = $this->transfer($amount_in_kobo, $supplier['recipient_code']);

        //if $transaction['status'] is pending redirect to homepage. If OTP, redirect to otp page
        if (is_array( $transaction)) {
            if($transaction['status'] == "otp")
            {
                return view('otp', ['supplier' => $supplier, 'transaction' => $transaction]);
            }
            else return redirect()->route('home')->with('message', "Payment complete.");
        }
        else return redirect()->back()->with('message', $transaction);
    }

    public function confirm(Request $request)
    {
        $validatedData = $request->validate([
            'otp' => 'required'
        ]);

        $transaction = $this->confirmTransfer($request->otp, $request->transfer_code);
        if (is_array($transaction)) {
            return redirect()->route('home')->with('message', "Payment complete.");
        }
        return redirect()->back()->with('message', $transaction);
    }


    private function getBanks()
    {
        $secret_key = \Config::get('services.paystack.secret_key');
        $url = "https://api.paystack.co/bank";
        $client = new Client();

        try {
            $response = $client->request('GET', $url, []);
            $status = $response->getStatusCode();
            return json_decode($response->getBody(), true);

        } catch (RequestException $e) {
            // $responseBody = $e->getResponse()->getBody(true);

            // return $responseBody;

            return false;
        }
    }

    private function listTransfers()
    {
        $secret_key = \Config::get('services.paystack.secret_key');
        $url = "https://api.paystack.co/transfer";
        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . $secret_key,
        ];

        try {
            $response = $client->request('GET', $url, [
                'headers' => $headers,
            ]);
            $status = $response->getStatusCode();
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            $responseBody = json_decode($e->getResponse()->getBody(true), true);
            return $responseBody['message'];
        }
    }

    private function createRecipient($name, $description, $account_number, $bank_code)
    {
        $secret_key = \Config::get('services.paystack.secret_key');

        $url = "https://api.paystack.co/transferrecipient";
        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . $secret_key,
            'Content-Type'        => 'application/json',
        ];
        $data = [
            'type' => 'nuban',
            'description' => $description,
            'name' => $name,
            'account_number' => $account_number,
            'bank_code' => $bank_code
        ];

        try {
            $response = $client->request('POST', $url, [
                'headers' => $headers,
                'json' => $data
            ]);

            $status = $response->getStatusCode();
            $result = json_decode($response->getBody(), true);
            return $result['data'];

        } catch (RequestException $e) {

            $responseBody = json_decode($e->getResponse()->getBody(true), true);
            return $responseBody['message'];
        }
    }

    private function transfer($amount, $recipient)
    {
        $secret_key = \Config::get('services.paystack.secret_key');

        $url = "https://api.paystack.co/transfer";
        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . $secret_key,
            'Content-Type'        => 'application/json',
        ];
        $data = [
            'source' => 'balance',
            'amount' => $amount,
            'recipient' => $recipient
        ];

        try {
            $response = $client->request('POST', $url, [
                'headers' => $headers,
                'json' => $data
            ]);

            $status = $response->getStatusCode();
            $result = json_decode($response->getBody(), true);
            return $result['data'];
        } catch (RequestException $e) {

            $responseBody = json_decode($e->getResponse()->getBody(true), true);
            return $responseBody['message'];
        }
    }

    private function confirmTransfer($otp, $transfer_code)
    {
        $secret_key = \Config::get('services.paystack.secret_key');

        $url = "https://api.paystack.co/transfer/finalize_transfer";
        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . $secret_key,
            'Content-Type'        => 'application/json',
        ];
        $data = [
            'otp' => $otp,
            'transfer_code' => $transfer_code,
        ];

        try {
            $response = $client->request('POST', $url, [
                'headers' => $headers,
                'json' => $data
            ]);

            $status = $response->getStatusCode();
            $result = json_decode($response->getBody(), true);
            return $result['data'];
        } catch (RequestException $e) {

            $responseBody = json_decode($e->getResponse()->getBody(true), true);
            return $responseBody['message'];
        }
    }
}
