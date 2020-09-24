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
use App\Models\Contact;
use Facade\Ignition\Tabs\Tab;
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

        if(Auth::user()->role > 1)
        {
            $projects = Project::where('status','0')->get();
        }
        else
        {
             $projects = Project::where('user_id',Auth::user()->id)->where('status','0')->get();
        }
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
            if($numofTask == 0)
            {
                $pro = 0;
            }
            else
            {
                $pro = ($numofComplete/$numofTask)*100;
            }

            $data['pro'] = intval($pro);
            $data['color'] = $item->color;
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
        $results = Project::where('user_id',Auth::user()->id)->where('status','1')->get();
        $page = "archieve";
        return view('user.archieve',compact('page','results'));
    }

    public function settings(Request $request)
    {
        $page = "settings";
        return view('user.settings',compact('page'));
    }

    public function deadline(Request $request)
    {
        $results = Project::where('user_id',Auth::user()->id)->get();
        $page = "deadline";
        return view('user.deadline',compact('page','results'));
    }
    public function inbox(Request $request)
    {
        $contacts = Contact::all();
        $page = "inbox";
        return view('user.inbox',compact('page','contacts'));
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
            'date'              => $request->get('date'),
            'status'            => '0',
            'color'             => $request->get('color')
        ]);
        return redirect('/dashboard/project/'.$project->id);
    }

    public function projectview(Request $request,$id)
    {
        $project = Project::find($id);
        $tasks = Task::where('project_id',$id)->latest()->get();
        $attachments = Attach::where('project_id',$id)->latest()->get();
        $comments = Comment::where('project_id',$id)->latest()->get();

        $page = "dashboard";
        $data = array();
        $results = array();
        foreach($tasks as $item)
        {

            $data['id'] = $item->id;
            $data['title'] = $item->title;
            $data['description'] = $item->description;
            $data['status'] = $item->status;
            $comment = Comment::where('task_id',$item->id)->where('user_id',Auth::user()->id)->first();
            if(!empty($comment))
            {
                $data['comment_id'] = $comment->id;
                $data['comment'] = $comment->content;
            }
            else
            {
                $data['comment_id'] = '';
                $data['comment'] = '';
            }

            array_push($results,$data);
        }

        return view('user.viewproject',compact('page','project','tasks','attachments','comments','results'));
    }

    public function deleteproject(Request $request)
    {
        $id = $request->get('id');
        $project = Project::find($id);

        $project->delete();
        if($request->get('page') == '1')
        {
            return redirect('/dashboard/archieve');
        }
        else
        {
            return redirect('/dashboard');
        }

    }
    public function delete_project_all(Request $request)
    {
        $id = $request->get('id');
        if($id == '0')
        {
            $projects = Project::where('status','1')->get();
            foreach($projects as $item)
            {
                $tasks = Task::where('project_id',$item->id)->get();
                foreach($tasks as $taskitem)
                {
                    $taskitem->delete();
                }
                $comments = Comment::where('project_id',$item->id)->get();
                foreach($comments as $commentitem)
                {
                    $commentitem->delete();
                }
                $item->delete();
            }
            return true;
        }
        else
        {
            return false;
        }
    }
    public function movetoarchieve(Request $request)
    {
        $id = $request->get('id');
        $project = Project::find($id);

        $project->status = '1';
        $project->save();

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
        $project->description = $request->get('description');
        $project->date = $request->get('date');
        $project->save();
        return true;
    }

    public function create_task(Request $request)
    {

        $attach = "";
        if($request->file('attach'))
        {
            $attach = "taskAttach".time().'.'.$request->file('attach')->getClientOriginalExtension();

            $request->file('attach')->move(public_path('upload/attach'),$attach);
        }

        $id = $request->get('id');
        $task = Task::create([
            'project_id'        => $id,
            'title'             => $request->get('title'),
            'description'       => $request->get('description'),
            'attach'            => $attach,
            'status'            => '0'
        ]);
        return true;
    }

    public function update_task(Request $request)
    {
        $id = $request->get('id');
        $task = Task::find($id);
        if($task)
        {
            $task->title = $request->get('title');
            $task->description = $request->get('description');
            $task->status = $request->get('status');
            $task->save();
            return true;
        }
        else
        {
            return false;
        }
    }

    public function complete_task(Request $request)
    {
        $id = $request->get('id');
        $task = Task::find($id);
        if($task)
        {
            $task->status = $request->get('status');
            $task->save();
            return true;
        }
        else
        {
            return false;
        }
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
        $commentid = $request->get('commentid');
        if(!empty($commentid))
        {
            $comment = Comment::find($commentid);
            $comment->content = $request->get('comment');
            $comment->save();
            return true;
        }
        else
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

    }

    public function delete_comment(Request $request)
    {
        $commentid = $request->get('cid');
        if(!empty($commentid))
        {
            $comment = Comment::find($commentid);
            $comment->delete();
            return true;
        }
        else
        {
            return false;
        }

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
        $name = $request->get('name');
        $existMail = User::where('email',$email)->count();
        $existName = User::where('name',$name)->count();
        if($existMail > 0)
        {
            $result = "email";
            return response()->json($result);
        }
        elseif($existName > 0)
        {
            $result = "name";
            return response()->json($result);
        }
        else
        {
            $password = $request->get('password');
            $user = User::create([

                'name'              => $name,
                'email'             => $email,
                'paid'              => '0',
                'password'          => Hash::make($password),
                'role'              => '1',
                'plan'              => $request->get('plan')
            ]);
            return true;
        }

    }

    public function edit_user(Request $request)
    {
        $id = $request->get('id');
        $emailcon = new User;
        $namecon = new User;
        $emailcon = $emailcon->whereNotIn('id', array($id));
        $namecon = $namecon->whereNotIn('id', array($id));

        $email = $request->get('email');
        $name = $request->get('name');
        $existName = $namecon->where('name',$name)->count();
        $existMail = $emailcon->where('email',$email)->count();

        if($existMail > 0)
        {
            $result = "email";
            return response()->json($result);
        }
        elseif($existName > 0)
        {
            $result = "name";
            return response()->json($result);
        }
        else
        {
            $cur_user = User::find($id);
            $cur_user->name = $name;
            $cur_user->email = $email;
            $cur_user->plan = $request->get('plan');
            if($request->get('password'))
            {
                $cur_user->password = Hash::make($request->get('password'));
            }
            $cur_user->save();
            return true;
        }

    }

    public function create_plan(Request $request)
    {
        $plan = Plan::create([

            'period'       => $request->get('period'),
            'price'        => $request->get('price'),
            'name'        => $request->get('name')
        ]);
        return true;

    }

    public function delete_message(Request $request)
    {
        $id = $request->get('id');
        if($id=='0')
        {
            $messages = Contact::all();
            foreach($messages as $item)
            {
                $item->delete();
            }
            return true;
        }
        else
        {
            $message = Contact::find($id);
            $message->delete();
            return true;
        }




    }

    public function messageview(Request $request,$id)
    {
        $message = Contact::find($id);
        if($message)
        {
             $page = 'inbox';
            return view('user.inboxview',compact('page','message'));
        }
        else
        {
            return redirect('/dashboard/inbox');
        }

    }



    public function plan(Request $request)
    {
        $plans = Plan::all();
        $page = "dashboard";
        return view('user.plan',compact('page','plans'));
    }
    public function user(Request $request)
    {
        $plans = Plan::all();
        $users = User::all();
        $page = "user";
        return view('user.user',compact('page','users','plans'));
    }
    public function managerplan(Request $request)
    {
        $plans = Plan::all();
        $page = "managerplan";
        return view('user.managerplan',compact('page','plans'));
    }
}
