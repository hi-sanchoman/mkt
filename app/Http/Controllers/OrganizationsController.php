<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\User;
use App\Models\Action;
use App\Models\Deal;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as ObjectRequest;
use Inertia\Inertia;

class OrganizationsController extends Controller
{
    public function index()
    {

        $organizations = Organization::paginate();

        foreach ($organizations->items() as $client) {
            $contact = Contact::where('organization_id', $client->id)->first();
            if($contact) {
                $client->last_name = $contact->name;
                $client->position = $contact->position;
                $client->phone = $contact->phone;
            } else {
                $client->last_name = '';
                $client->position = '';
                $client->phone = '';
            }

            $client->responsible = User::card($client->responsible_id);
            
        }

        return Inertia::render('Organizations/Index', [
            'filters' => Request::all('search', 'trashed'),
            'organizations' => $organizations
        ]);
    }

    public function getPlans(ObjectRequest $request) {

        $plan = User::plans($request->id);

        return $plan;
    }

    public function create()
    {
        return Inertia::render('Organizations/Create');
    }

    public function store(ObjectRequest $request)
    {   
        $organization = Organization::create([
            'name' => $request->name,
            'comments' => $request->comments,
            'status' => 1,
            'account_id' => 1,
            'responsible_id' => Auth::user()->id, 
        ]);

        Contact::create([
            'name' => $request->last_name,
            'position' => $request->position,
            'organization_id' => $organization->id,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'account_id' => 1,
        ]);


        return $this->edit($organization);
    }

    public function edit(Organization $organization)
    {
        $deals = Deal::where('client_id', $organization->id)->get();

        foreach($deals as $deal) {
            $deal->user = User::card($deal->responsible_id);
            $deal->file = File::find($deal->file_id);
        }

        $actions = $organization->actions()->orderBy('created_at', 'desc')->get();

        foreach($actions as $action) {
            $action->user = User::card($action->user_id);
        }
        

        return Inertia::render('Organizations/Edit', [
            'organization' => [
                'id' => $organization->id,
                'name' => $organization->name,
                'comments' => $organization->comments,
                'status' => $organization->status,
                'deleted_at' => $organization->deleted_at,
                'contacts' => $organization->contacts()->get(),
                'deals' => $deals,
            ],
            'responsible' => User::card($organization->responsible_id),
            'actions' => $actions->toArray(),
            'tab' => isset($_GET['tab']) ?  $_GET['tab'] : 1
        ]);
    }

    public function addAction(ObjectRequest $request) {
        
        $action = Action::create([
            'client_id' => $request->id,
            'type' => $request->type,
            'date' => $request->date,
            'user_id' => Auth::user()->id
        ]);

        if($request->type == 1) {
            $text = 'Звонок успешно добавлен!';
        }
        if($request->type == 2) {
            $text = 'Встреча успешно добавлена!';
        }

        return [
            'msg' => $text,
            'action' => $action
        ];
    }

    public function editClient(ObjectRequest $request)
    {
        $client = Organization::find($request->id);

        if($client) {
            $client->name = $request->name;
            $client->comments = $request->comments;
            $client->status = $request->status;
            $client->save();
        }

        return 'Данные о клиенте #' . $request->id. ' успешно изменены!';
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();

        return Redirect::back()->with('успешно', 'Клиент удален.');
    }

    public function restore(Organization $organization)
    {
        $organization->restore();

        return Redirect::back()->with('успешно', 'клиент восстановлен.');
    }

    public function comment(String $message,$id){
        /********* TODO */
        Comment::insert([
            'client_id' => $id,
            'user_id' => Auth::user()->id,
            'comment' => Comment::TEXT,
            ]);
            return Redirect::route('organizations')->with('успешно', 'Комментарий добавлен.');
    }

    public function status(ObjectRequest $request){ 

        return Organization::where('status', $request->status) 
                ->paginate()
                ->through(function ($organization) {
                    return [
                        'id' => $organization->id,
                        'name' => $organization->name,
                        'phone' => $organization->phone,
                        'city' => $organization->city,
                        'status' => $organization->status,
                        'deleted_at' => $organization->deleted_at,
                        'status'=> $organization->status, 
                        'stage' => $organization->stage,
                        'agreement' => $organization->agreement,
                        'responsible' => User::card($organization->responsible_id),
                    ];
                });
    }


    public function addContact(ObjectRequest $request) {
        $item = Organization::find($request->organization_id);

        if($item) {
            Contact::create([
                'name' => $request->name,
                'position' => $request->position,
                'organization_id' => $request->organization_id,
                'account_id' => 1,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'region' => $request->region,
            ]);
        }
        
        return redirect('/organizations/'. $request->organization_id.'/edit')->with([
            'success' => 'Контакт добавлен!'
        ]);
    }

    public function deleteContact(ObjectRequest $contact){
       // dd($contact->id);
        $contact = Contact::find($contact->id);
        $contact->delete();


        return "Контакт удален";
    }

    public function updateContact(ObjectRequest $request){
        $contact = Contact::find($request->id);
        
            $contact->name = $request->name;
            $contact->position = $request->position;
            $contact->organization_id = $request->organization_id;
            
            $contact->email = $request->email;
            $contact->phone = $request->phone;
            $contact->address = $request->address;
            $contact->city = $request->city;
            $contact->region = $request->region;
        
        $contact->save();
    }
}
