<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

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
        $banks = $this->getBanks();
        $banks = $banks['data'];
        return view('home', ['banks' => $banks]);
    }


    private function getBanks() {

        $secret_key = config(['services.paystack.secret_key']);
        $url = "https://api.paystack.co/bank";
        $client = new Client(['header' =>['Authorization' => 'Bearer ' . $secret_key]]);


        try {
            $response = $client->request('GET', $url, []);
            $status = $response->getStatusCode();

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            //return $e->getRequest();
            return false;
        }
    }
}
