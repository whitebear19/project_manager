<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Attach;
use App\Models\Comment;
use App\Models\Plan;
use Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $email = "";
    }

    protected function changeEnv($data = array()){
        if(count($data) > 0){

            // Read .env-file
            $env = file_get_contents(base_path() . '/.env');

            // Split string on every " " and write into array
            $env = preg_split('/\s+/', $env);;

            // Loop through given data
            foreach((array)$data as $key => $value){

                // Loop through .env-data
                foreach($env as $env_key => $env_value){

                    // Turn the value into an array and stop after the first split
                    // So it's not possible to split e.g. the App-Key by accident
                    $entry = explode("=", $env_value, 2);

                    // Check, if new key fits the actual .env-key
                    if($entry[0] == $key){
                        // If yes, overwrite it with the new one
                        $env[$env_key] = $key . "=" . $value;
                    } else {
                        // If not, keep the old one
                        $env[$env_key] = $env_value;
                    }
                }
            }

            // Turn the array back to an String
            $env = implode("\n", $env);

            // And overwrite the .env with the new data
            file_put_contents(base_path() . '/.env', $env);

            return true;
        } else {
            return false;
        }
    }
    public function dashboard(Request $request)
    {
        if(Auth::user()->role < 1)
        {
            return redirect('/dashboard/project/'.Auth::user()->project_id);
        }
        $projects = Project::where('user_id',Auth::user()->id)->get();
        $data = array();
        $results = array();
        foreach($projects as $item)
        {
            $task = new Task();
            $data['id'] = $item->id;
            $data['title'] = $item->title;
            $task = $task->where('project_id',$item->id);
            $numofTask = $task->count();
            $task = $task->where('status','1');
            $numofComplete = $task->count();
            $pro = ($numofComplete/$numofTask)*100;
            $data['pro'] = intval($pro);
            array_push($results,$data);
        }

        $page = "dashboard";
        return view('user.dashboard',compact('page','results'));
    }

    public function payment(Request $request)
    {
        $page = "payment";
        return view('user.payment',compact('page'));
    }

    public function archieve(Request $request)
    {
        $page = "archieve";
        return view('user.archieve',compact('page'));
    }

    public function settings(Request $request)
    {
        $page = "settings";
        return view('user.settings',compact('page'));
    }


    public function update_paypal(Request $request)
    {
        $paypal_username = str_replace(' ', '', $request->get('paypal_username'));
        $paypal_password = str_replace(' ', '', $request->get('paypal_password'));
        $paypal_secret = str_replace(' ', '', $request->get('paypal_secret'));

        $env_update = $this->changeEnv([
            'PAYPAL_SANDBOX_API_USERNAME'   => $paypal_username,
            'PAYPAL_SANDBOX_API_PASSWORD'   => $paypal_password,
            'PAYPAL_SANDBOX_API_SECRET'     => $paypal_secret,
        ]);
        if($env_update){
            return true;
        } else {
            return false;
        }

    }

    public function update_square(Request $request)
    {

    }

    public function update_password(Request $request)
    {
        $password = $request->get('password');
        if(Auth::check())
        {
            $user = User::find(Auth::user()->id);
            $user->password = Hash::make($password);
            $user->save();
            return true;
        }
        else
        {
            return 'auth';
        }

    }

    public function newproject(Request $request)
    {
        if(Auth::user()->paid == '0')
        {
            return redirect('/dashboard/plan');
        }
        $plans = Plan::all();
        $page = "dashboard";
        return view('user.newproject',compact('page','plans'));
    }

    public function storeproject(Request $request)
    {
        $project = Project::create([
            'user_id'           => Auth::user()->id,
            'title'             => $request->get('title'),
            'description'       => $request->get('description'),
            'date'              => $request->get('date')
        ]);
        return redirect('/dashboard/project/'.$project->id);
    }

    public function projectview(Request $request,$id)
    {
        $project = Project::find($id);
        $tasks = Task::where('project_id',$id)->get();
        $attachments = Attach::where('project_id',$id)->get();
        $comments = Comment::where('project_id',$id)->get();

        $page = "dashboard";
        return view('user.viewproject',compact('page','project','tasks','attachments','comments'));
    }

    public function deleteproject(Request $request)
    {
        $id = $request->get('id');
        $project = Project::find($id);

        $project->delete();
        return redirect('/dashboard');
    }
    public function deletetask(Request $request)
    {
        $id = $request->get('id');
        $task = Task::find($id);
        $task->delete();
        return back();
    }
    public function deleteuser(Request $request)
    {
        $id = $request->get('id');
        $user = User::find($id);
        $user->delete();
        return back();
    }

    public function deleteplan(Request $request)
    {
        $id = $request->get('id');
        $plan = Plan::find($id);
        $plan->delete();
        return back();
    }

    public function update_project(Request $request)
    {
        $id = $request->get('id');
        $project = Project::find($id);
        $project->title = $request->get('title');
        $project->description = $request->get('description');
        $project->date = $request->get('date');
        $project->save();
        return true;
    }

    public function create_task(Request $request)
    {
        $id = $request->get('id');
        $task = Task::create([
            'project_id'        => $id,
            'description'       => $request->get('description'),
            'status'            => '0'
        ]);
        return true;
    }

    public function update_task(Request $request)
    {
        $id = $request->get('id');
        $task = Task::find($id);
        $task->description = $request->get('description');
        $task->status = $request->get('status');
        $task->save();
        return true;
    }

    public function uploadattach(Request $request)
    {
        $fileLink = Str::random(5);
        $fileLink = $fileLink.time().'.'.$request->file('attachment')->getClientOriginalExtension();

        $request->file('attachment')->move(public_path('upload/attach'),$fileLink);

        Attach::create([
            'project_id'        => $request->get('id'),
            'name'              => $request->file('attachment')->getClientOriginalName(),
            'link'              => $fileLink
        ]);

        return true;
    }


    public function create_comment(Request $request)
    {
        $id = $request->get('id');
        $comment = Comment::create([
            'project_id'        => $id,
            'user_id'           => Auth::user()->id,
            'task_id'           => $request->get('taskid'),
            'content'           => $request->get('comment')
        ]);
        return true;
    }

    public function create_invite(Request $request)
    {
        $id = $request->get('id');
        $this->email = $request->get('invite_email');
        $existMail = User::where('email',$this->email)->count();
        if($existMail > 0)
        {
            $result = "email";
            return response()->json($result);
        }
        else
        {

            $password = Str::random(8);
            $user = User::create([
                'project_id'        => $id,
                'name'              => $request->get('invite_name'),
                'email'             => $this->email,
                'paid'              => '0',
                'password'          => Hash::make($password),
                'role'              => '0',
            ]);

            $data = [
                'email' => $this->email,
                'password' => $password
            ];
            \Mail::to($this->email)->send(new \App\Mail\SendInvite($data));
            return true;
        }

    }

    public function create_user(Request $request)
    {
        $email = $request->get('email');
        $existMail = User::where('email',$email)->count();
        if($existMail > 0)
        {
            $result = "email";
            return response()->json($result);
        }
        else
        {
            $password = $request->get('password');
            $user = User::create([

                'name'              => $request->get('name'),
                'email'             => $email,
                'paid'              => '0',
                'password'          => Hash::make($password),
                'role'              => '1'
            ]);
            return true;
        }

    }

    public function create_plan(Request $request)
    {
        $plan = Plan::create([

            'period'       => $request->get('period'),
            'price'        => $request->get('price')
        ]);
        return true;

    }

    public function plan(Request $request)
    {
        $plans = Plan::all();
        $page = "dashboard";
        return view('user.plan',compact('page','plans'));
    }
    public function user(Request $request)
    {
        $users = User::all();
        $page = "user";
        return view('user.user',compact('page','users'));
    }
    public function managerplan(Request $request)
    {
        $plans = Plan::all();
        $page = "managerplan";
        return view('user.managerplan',compact('page','plans'));
    }
}
