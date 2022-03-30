<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Deal;
use App\Models\Event;
use App\Models\Step;
use App\Models\Comment;
use App\Models\Subtask;
use App\Models\File;
use App\Models\Pivod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request as StaticRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use DB;

class TasksController extends Controller
{
	public function index(Request $request){
        $tasks;

        $deals = null;
        //if(Auth::user()->id == 28){
            $mytasks = Task::all();
            $tasks = Task::where('auditor_id',Auth::user()->id)->orWhere('user_id',Auth::user()->id)->orderBy('id', 'desc')->paginate(1000);
            $deals = Deal::select('id','name','status','created_at','client_id','responsible_id')->get();
        /*}else{
            $tasks = Task::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(10);

        }*/

           foreach($tasks->items() as $task) {
                $task->user = User::card($task->user_id);
                $task->auditor = User::card($task->auditor_id);
                $task->deal = Deal::find($task->deal_id);
            }
       
        return Inertia::render('Tasks/Index', [
            'filters' => StaticRequest::all('search'),
            'tasks' => $tasks,
            'deals' => $deals,
            'alltasks' => $mytasks
        ]);
       
	}

    public function accept(Request $request){

        //dd($request->all());
        $task = Task::find($request->task);

        
        
        


        

        $text_warning = Auth::user()->first_name." принял задачу : ". $task->title;

        if($request->status == 2){//принять

            if($task->status == 3) {
                $text_warning = Auth::user()->first_name." отправил на доработку задачу : ". $task->title;     
            } else {
                $text_warning = Auth::user()->first_name." принял задачу : ". $task->title; 
            }
  
        } else if($request->status == 3){//на проверку
            
            if($task->deal_id != 0) { // Если связана сделка
                

                $tasks = Task::where('deal_id', $task->deal_id)->get();

                $count = 0;
                foreach ($tasks as $key => $value) {
                    if($task->status == 3) $count++;
                }
                
                if($tasks->count() == $count) {

                    foreach ($tasks as $key => $value) {
                        $value->status = 1;
                        $value->save();
                    }

                    $deal = Deal::find($task->deal_id);
                    
                    if($deal->status >= 9) {
                        $deal->status = $deal->status + 1;
                        $deal->stage = 2;
                        $deal->save();
                    } else {
                        $deal->status = $deal->status + 1;
                        $deal->save();
                    }
                    
                    foreach ($deal->STEPS[$deal->status] as $key => $step) {
                
                        $user = User::where('position_id', $step['pos_id'])->first();
                    
                        
                        if($user) {
                            
                            $resp = User::find($deal->responsible_id);

                            if($resp->position_id != 13) {
                                $resp = User::where('position_id', 13)->first();
                            }
                            
                            $task = Task::create([
                                'title' => 'Сделка #' . $deal->id,
                                'description' => $step['name'],
                                'user_id' => $user->id,
                                'auditor_id' => $resp->id,
                                'start' => date('Y-m-d H:i:s'),
                                'deadline' => date('Y-m-d H:i:s',time() + 3600 * 24 * 7),
                                'type' => 1,
                                'status' => Task::NOT_STARTED, 
                                'urgent' => 0, 
                                'account_id' => Auth::user()->account_id,
                                'file_id' => 0,
                                'deal_id' => $deal->id,
                            ]);
                            
                         
        
                            $event = new Event();
                            $event->user_id = $user->id;
                            $event->author_id = $resp->id;
                            $event->text = $step['name'];
                            $event->task_id = $task->id;
                            $event->save();
                        }
                        
                    }

                }

            } else {
                $text_warning = Auth::user()->first_name." отправил на проверку задачу : ". $task->title;
            }
                
            

           
        } else {//выполнено
            $text_warning = Auth::user()->first_name."принял и закрыл задачу: ". $task->title;
        }
        
       
        $task->update([
            'status' => $request->status,
        ]);

        
        $event = new Event();
        $event->user_id = (Auth::user()->id == $task->user_id) ? $task->auditor_id: $task->user_id;
        $event->author_id = Auth::user()->id;
        $event->text =  $text_warning;
        $event->task_id = $task->id;
        $event->save();

        return redirect('tasks/' . $task->id)->with('success', 'Задание принято.');
    }


    public function addfiles(Request $request)
    {
        if(isset($request->file)) {
            $file_name = $request->file->getClientOriginalName(). '_' . time() . '.' . $request->file->getClientOriginalExtension();

            $request->file->storeAs('public/documents', $file_name);

        
            $file = File::create([
                'name' => $file_name,
                'path' => 'documents/'. $file_name,
                'type' => $request->file->getClientOriginalExtension(),
                'user_id' => Auth::user()->id,
            ]);
        }


       
       
       $pivod = new Pivod();
       $pivod->task_id = $request->task_id;
       $pivod->file_id = isset($file) ? $file->id : 0;
       $pivod->save();



       if(!isset($file))
        return "файл уже существует";
       else{
        return "файл добавлен";
       }
    }

    public function show(Task $task){

        $myuser = [];
        $mytasks = Task::where('description',$task->description)->where('auditor_id',$task->auditor_id)->where('created_at',$task->created_at)->get();

        if(Auth::user()->id == 28){
            foreach($mytasks as $t){
                if($t->user_id != $task->user_id)
                    $myuser[] = User::card($t->user_id);
            }
        }else{
            foreach($mytasks as $t){
                if($t->user_id != Auth::user()->id)
                    $myuser[] = User::card($t->user_id);
            }
        }
        if($task->user_id != Auth::user()->id && Auth::user()->id != 28) {
            if($task->auditor_id != Auth::user()->id ) {
                return redirect('/')->with(['success' => 'not found']);
            }
        }
        
        $subtasks = Subtask::where([
            'task_id' => $task->id
        ])->get();
        
        foreach($subtasks as $subtask) {
            $subtask->task = $task;
        }
        
        $ids = Pivod::where('task_id','=',$task->id)->get()->pluck('file_id');
        $files = File::whereIn('id',$ids)->get();
        

        return Inertia::render('Tasks/Show',[
            'task' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'user' => User::card($task->user_id),
                'auditor' =>User::card($task->auditor_id),
                'start' => $task->start,
                'type' => $task->type ? $task->type : 1,
                'deadline' => $task->deadline,
                'status' => $task->status,
                'urgent' => $task->urgent,
                'account_id' => 1,
                'file' => File::find($task->file_id),
                'deal_id' => $task->deal_id,
            ],
            
            'comments' => $task->comments(),
            'subtasks' => $subtasks,
            'files' => $files,
            'users' => $myuser
        ]);
    }

    public function comment(Request $request){
        
        $comment = Comment::create([
            'model_id' => $request->id,
            'model_type' => 'task',
            'user_id' => Auth::user()->id,
            'text' => $request->text,
            'type' => Comment::TEXT
        ]);

        $comment->user = User::card($comment->user_id);

        return $comment;
        
    }

    public function create() {
        return [
            'users' =>  User::select(DB::raw("CONCAT(last_name, ' ', first_name)  AS label"), 'id as code')->where('account_id', 1)->get()
        ];
    }

	public function store(Request $request)
    {
       
        $users = $request->user_id;

        
       
        if(isset($request->file)) {
            $file_name = $request->file->getClientOriginalName(). '_' . time() . '.' . $request->file->getClientOriginalExtension();

            $request->file->storeAs('documents', $file_name);

        
            $file = File::create([
                'name' => $file_name,
                'path' => 'documents/'. $file_name,
                'type' => $request->file->getClientOriginalExtension(),
                'user_id' => Auth::user()->id,
            ]);
        }

        foreach ($users as $user) {
            # code...
            $task = Task::create([
                'title' => $request->title,
                'description' => $request->description,
                'user_id' => $user,
                'auditor_id' => Auth::user()->id,
                'start' => $request->start,
                'deadline' => $request->deadline,
                'type' => $request->type ? $request->type : Task::TYPE_TASK,
                'status' => Task::NOT_STARTED, 
                'urgent' => $request->urgent ? 1 : 0, 
                'account_id' => Auth::user()->account_id,
                'file_id' => isset($file) ? $file->id : 0,
            ]);

            $task->start = $task->created_at;

            $event = new Event();
            $event->user_id = $user;
            $event->author_id = Auth::user()->id;
            $event->text = "Назначил вас ответственным по задаче : ". $request->title;
            $event->task_id = $task->id;
            $event->save();
        }



        return $this->show($task);    
    }

    public function edit(Task $task){ 
        return Inertia::render('Tasks/Edit', [
            'task' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'user_id' => $task->user_id,
                'auditor_id' => $task->auditor_id,
                'start' => $task->start,
                'type' => $task->type ? $task->type : 1,
                'deadline' => $task->deadline,
            ],
            'current_user' => User::find($task->user_id),
            'users' => User::all(),
        ]);
    }

    public function update(Task $task)
    {
        $task->update(
            StaticRequest::validate([
                'user_id' => ['required', 'max:50'],
                'deadline' => ['required', 'max:50'],
                'description' => ['required', 'max:50'],
            ])
        );

        return Redirect::back()->with('success', 'Task updated.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return Redirect::back()->with('success', 'Task deleted.');
    }

    public function restore(Task $task)
    {
        $task->restore();
        
        return Redirect::back()->with('success', 'Task restored.');
    }

    public function calendar(){
        return Inertia::render('Tasks/Calendar');
    }

    public function dela(){
        return Inertia::render('Tasks/Dela');
    }



}
