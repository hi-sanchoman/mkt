<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as ObjectRequest;
use Inertia\Inertia;

class GroupsController extends Controller
{
    public function index()
    {
        //$groups = Group::with('users')->get();
        
        if(Auth::user()->owner == 1) {
            $groups = Group::with('users')->get();
        } else {
            $group_ids = GroupUser::where('user_id', Auth::user()->id)->pluck('group_id')->toArray();
            $groups = Group::whereIn('id', $group_ids)->with('users')->get();
        }
        
    	return Inertia::render('Groups/Index',[
    		'groups' => $groups,
    	]);
    }
    public function store(ObjectRequest $request)
    {   
    	$group = Group::create([
            'name' => $request->group_name,
            'description' => $request->description,
            'account_id' => 1,
            'editors' => json_encode([Auth::user()->id]),
        ]);

        return $this->show($group);    
    }

    public function show(Group $group){
        return Inertia::render('Groups/Show',[
            'group' => Group::find($group->id),
            'members' => GroupUser::getUsers($group->id)
        ]);
    }

    public function saveUsers(ObjectRequest $request)
    {   
        $x = GroupUser::where('group_id', $request->group_id)->get();

        foreach($x as $y) {
           $y->delete();     
        }

        foreach($request->users as $user) {
            GroupUser::create([
                'group_id' => $request->group_id,
                'user_id' => $user['id'],
            ]);
        }
    }

    public function upload(ObjectRequest $request){
       
        $logo = $request->image;
        $image_name = $request->group_id. '_' . time() . '.' . $logo->getClientOriginalExtension();

        $logo->storeAs('groups',$image_name);

        $group = Group::find($request->group_id);
        $group->logo_path = 'groups/'.$image_name;
        $group->save();
        return 'groups/'.$image_name;
    }

    public function rename(ObjectRequest $request){
        $group = Group::find($request->group_id);
        $group->name = $request->group_name;
        $group->save();
        return $request->group_name; 
    }

    public function description(ObjectRequest $request){
        $group = Group::find($request->group_id);
        $group->description = $request->description;
        $group->save();
        return $request->group_des; 
    }
}