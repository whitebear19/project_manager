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
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $email = "bendan198242@gmail.com";
        $password = "1111112";
        $data = [
            'email' => $email,
            'password' => $password
        ];
        \Mail::to($email)->send(new \App\Mail\SendInvite($data));

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
