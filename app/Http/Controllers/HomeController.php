<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        $email = "";
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $this->email = "bendan198242@gmail.com";
        $password = "1111112";
        $data = [
            'email' => $this->email,
            'password' => $password
        ];
        \Mail::to($this->email)->send(new \App\Mail\SendInvite($data));

        return view('home');
    }
    public function aboutus()
    {
        return view('aboutus');
    }
    public function contactus()
    {
        return view('contactus');
    }
}
