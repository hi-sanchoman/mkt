<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Deal;
use App\Models\User;
use App\Models\File;
use App\Models\Task;
use App\Models\Step;
use App\Models\Pivod;
use App\Models\Event;
use App\Models\Comment;
use App\Models\Organization;
use Illuminate\Http\Request;

class DealsController extends Controller
{
    public function index()
    {
    	$deals = Deal::with(['client'])->get();
    	
        foreach($deals as $deal) {
            $deal->user = User::card($deal->responsible_id);
        }
        
        return Inertia::render('Deals/Index',[
            'test' => $deals, 
            'creator' => User::card(22)
        ]);
    }

    public function test() {
        dd(Task::latest()->get());
    }

    public function edit($deal)
    {
        
        $_deal = Deal::find($deal);

        $_deal->filepath = File::find($_deal->file_id);


        $statuses = $_deal->STATUSES;
        
        foreach($statuses[1] as $key => $status) {
            
            if($_deal->status > $key) {
                $statuses[1][$key]['active'] = true;
            }else {
                $statuses[1][$key]['active'] = false;
            }
        }
      
        foreach($statuses[2] as $key => $status) {
            
            if($_deal->status > $key) {
                $statuses[2][$key]['active'] = true;
            }else {
                $statuses[2][$key]['active'] = false;
            }
        }

        foreach($_deal->STEPS as $key => $step) {
            foreach($step as $k => $s) {
                $u = User::where('position_id', $s['pos_id'])->first();
                $task = Task::where(['deal_id' => $_deal->id, 'user_id'=> $u->id, 'description' => $s['name']])->first();
                //dd($task);
                if($u) {
                    $_deal->STEPS[$key][$k]['resp'] = User::card($u->id);
                } else {
                    $_deal->STEPS[$key][$k]['resp'] = [];
                }
                
                if($task) {
                    $_deal->STEPS[$key][$k]['res'] = $task->status;
                } else {
                    $_deal->STEPS[$key][$k]['res'] = 0;
                }
                
            }
        }


        $files = [];
        $mycomments = [];

        $tasks = Task::where('deal_id',$_deal->id)->get()->pluck('id');
        $ids = Pivod::whereIn('task_id',$tasks)->get()->pluck('file_id');
        $comments = Comment::whereIn('model_id',$tasks)->get();
        $myfiles = File::whereIn('id',$ids)->get();
        foreach ($comments as $comment) {
            # code...

            $mycomments[] = [
                'user' => User::card($comment->user_id),
                'text' => $comment->text,
                'created_at' => $comment->created_at,
                //'step' => Task::where('id',$comment->model_id)->select('step')->get(),
            ];
        }
        foreach ($myfiles as $file) {
        
            $files[] = [
                        'name' => $file->name,
                        'path' => '/storage/'.$file->path,
                        'user' => User::card($file->user_id),
                        'created_at' => $file->created_at,
                       // 'step' => Task::where('id',Pivod::where('file_id',$file->id)->pluck('task_id'))->pluck('step'),

                    ];
            
        }
        /*
        $files = [];

        $tasks = Task::where('deal_id', $_deal->id)->get()->pluck('id', 'user_id', 'created_at');

        foreach($tasks as $task) {
            $f = File::find($task->file_id);
            if($file) {
                $files[] = [
                    'name' => $file->name,
                    'path' => $file->path,
                    'user' => User::card($task->user_id),
                    'created_at' => $task->created_at
                ];
            }
            

        }
        */

      //dd($_deal);
        return Inertia::render('Deals/Edit2',[

            'deal' => $_deal,
            'statuses' => $statuses,
            'steps' => $_deal->STEPS,
            'files' => $files,
            'comments' => $mycomments
        ]);
    }
    
    public function create()
    {
    	return Organization::all();
    }
    
    public function store(Request $request)
    {

        if(isset($request->file)) {
            $file_name = Auth::user()->id. '_' . time() . '.' . $request->file->getClientOriginalExtension();

            $request->file->storeAs('public/deals', $file_name);

        
            $file = File::create([
                'name' => $file_name,
                'path' => 'deals/'. $file_name,
                'type' => $request->file->getClientOriginalExtension(),
                'user_id' => Auth::user()->id,
            ]);
        }
        
        $deal = Deal::create([
            'name' => $request->title,
            'sum'  => $request->sum,
            'type' => $request->type,
            'status' => 0,
            'client_id' => $request->client_id ? $request->client_id : 0,
            'file_id' => isset($file) ? $file->id : 0,
            'responsible_id' => Auth::user()->id,
            'comment' => $request->comment
        ]);
        
        foreach ($deal->STEPS[$deal->status] as $key => $step) {
                
            $user = User::where('position_id', $step['pos_id'])->first();
        
            if($user) {
                
                $resp = User::find(Auth::user()->id);


                $task = Task::create([
                    'title' => 'Сделка #' . $deal->id,
                    'description' => $step['name'],
                    'user_id' => $user->id,
                    'auditor_id' => Auth::user()->id,
                    'start' => date('Y-m-d H:i:s'),
                    'deadline' => date('Y-m-d H:i:s',time() + 3600 * 24 * 1),
                    'type' => 1,
                    'status' => Task::NOT_STARTED, 
                    'urgent' => 0, 
                    'account_id' => Auth::user()->account_id,
                    'file_id' => 0,
                    'deal_id' => $deal->id,
                    //'step' => 0,
                ]);
                
             
                $event = new Event();
                $event->user_id = $user->id;
                $event->author_id = $resp->id;
                $event->text = $step['name'];
                $event->task_id = $task->id;
                $event->save();
            }
            
        }

        if($request->to == 'deal') {
            return redirect('deals')->with([
                'success' => 'Сделка создана'
            ]);
        } else {
            return redirect('/organizations/'. $request->client_id.'/edit')->with([
                'success' => 'Сделка создана'
            ]);
        }
        
    }

    public function update(Request $request)
    {
        if($request->file){
            $file_name = Auth::user()->id. '_' . time() . '.' . $request->file->getClientOriginalExtension();

            $request->file->storeAs('public/deals', $file_name);

            $file = File::create([
                'name' => $file_name,
                'path' => 'deals/'. $file_name,
                'type' => $request->file->getClientOriginalExtension(),
                'user_id' => Auth::user()->id,
            ]);
        }



        $deal = Deal::find($request->deal_id);
        
        $deal->name = $request->title;
        $deal->sum = $request->sum;
        $deal->type = $request->type;
        $deal->comment = $request->comment;
        $deal->file_id = isset($file) ? $file->id : 0;
        $deal->save();

        return redirect()->back()->with([
            'success' => 'Сделка отредактирована'
        ]);
        
    }
    
    public function changeDealStatus(Request $request){ 
        $deal = Deal::find($request->id); 
       
      
        if($deal) {

              foreach ($deal->STEPS[$deal->status] as $key => $step) {
                    $task = Task::where('description',$step['name'])->where('deal_id',$deal->id)->get();
                    foreach($task as $t){
                        $t->status = 1;
                        $t->save();
                    }
                }
             
            if($deal->status != 17) {
                $deal->status = (int)$deal->status + 1;
                //$deal->status = (int)2;
                $deal->save();
            } 
            
            
            foreach ($deal->STEPS[$deal->status] as $key => $step) {
                
                $user = User::where('position_id', $step['pos_id'])->first();
                $check = Task::where('description',$step['name'])->where('deal_id',$deal->id)->get()->first();
                
                if($user) {
                    
                    $resp = User::find(Auth::user()->id);

                    if($resp->position_id != 13) {
                        $resp = User::where('position_id', 13)->first();
                    }
                    if(!$check){
                        if($deal->status == 11 || $deal->status == 15 || $deal->status == 14){
                            $date = date('Y-m-d H:i:s',time() + 3600 * 24 * 1000);
                        }else{
                            $date = date('Y-m-d H:i:s',time() + 3600 * 24 * 1);
                        }
                        $task = Task::create([
                            'title' => 'Сделка #' . $deal->id,
                            'description' => $step['name'],
                            'user_id' => $user->id,
                            'auditor_id' => Auth::user()->id,
                            'start' => date('Y-m-d H:i:s'),
                            'deadline' => $date,
                            'type' => 1,
                            'status' => Task::NOT_STARTED, 
                            'urgent' => 0, 
                            'account_id' => Auth::user()->account_id,
                            'file_id' => 0,
                            'deal_id' => $deal->id,
                        ]);

                    }
                 
                    $event = new Event();
                    $event->user_id = $user->id;
                    $event->author_id = $resp->id;
                    $event->text = $step['name'];
                    $event->task_id = $task->id;
                    $event->save();
                }
                
            }


            return 'Статус cделки #' . $request->id. ' успешно изменен!';
        } else {
            return 'Сделка не найдена!';
        }    
    }

    public function changeStatus(Request $request){
        
        $deal = Deal::find($request->id);
        
        if($deal) {
            
            $deal->status = $request->status;
            $deal->save();
            return 'Статус cделки #' . $request->id. ' успешно изменен!';
        } else {
            return 'Сделка не найдена!';
        }

        
    }
    public function backDealStatus(Request $request){
        
        
       $deal = Deal::find($request->id); 
       
      
        if($deal) {

              foreach ($deal->STEPS[$deal->status] as $key => $step) {
                    $task = Task::where('description',$step['name'])->where('deal_id',$deal->id)->get();
                    foreach($task as $t){
                        $t->status = 2;
                        $t->save();
                    }
                }
             
            if($deal->status != 17) {
                $deal->status = (int)$deal->status - 1;
                //$deal->status = (int)2;
                
                $deal->save();
            } 
            
            
            foreach ($deal->STEPS[$deal->status] as $key => $step) {
                
                $user = User::where('position_id', $step['pos_id'])->first();
                
                
                if($user) {
                    
                    $resp = User::find($deal->responsible_id);

                    if($resp->position_id != 13) {
                        $resp = User::where('position_id', 13)->first();
                    }
                 
                    $event = new Event();
                    $event->user_id = $user->id;
                    $event->author_id = $resp->id;
                    $event->text = $step['name'].' отправлено на доработку.';
                    $event->task_id = $task->id;
                    $event->save();
                }
                
            }


            return 'Статус cделки #' . $request->id. ' успешно изменен!';
        } else {
            return 'Сделка не найдена!';
        }  
        
        
    }
    
}
