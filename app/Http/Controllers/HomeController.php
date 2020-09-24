<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
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

    public function storecontactus(Request $request)
    {
        Contact::create([
            'name'   => $request->get('name'),
            'email'  => $request->get('email'),
            'content'  => $request->get('content')
        ]);
        session()->flash('message', "Successfuly sent!.");
        return back();
    }
}
