<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Plan;
use App\Models\Position;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request as Req;


class UsersController extends Controller
{
    public function index()
    {
        return Inertia::render('Users/Index', [
            'filters' => Request::all('search', 'role', 'trashed'),
            'users' => Auth::user()->account->users()
                ->orderByName()
                ->filter(Request::only('search', 'role', 'trashed'))
                ->get()
                ->transform(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'owner' => $user->owner,
                        'photo' => $user->photo_path,
                        'position' => Position::find($user->position_id),
                        'deleted_at' => $user->deleted_at,
                    ];
                }),
        ]);
    }

    public function savePlan(Req $request) {
        Plan::create([
            'type' => $request->type,
            'value' => $request->value,
            'user_id' => $request->user['code'],
            'month' => date('m'),
            'year' => date('Y'),
        ]);
    }

    public function plans() {

        if(Auth::user()->owner != 1) {
            return redirect('/');
        }
        $plans = Plan::all();

        foreach($plans as $plan) {
            $plan->user = User::card($plan->user_id);
        }
        return Inertia::render('Reports/P', [
            'plans' => $plans
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Create',[
            'positions' => Position::all()
        ]);
    }

    public function store()
    {
        
        Request::validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')],
            'password' => ['nullable'],
            'owner' => ['required', 'boolean'],
            'photo' => ['nullable', 'image'],
            'position' => ['required','max:50'],
        ]);
        Auth::user()->account->users()->create([
            'first_name' => Request::get('first_name'),
            'last_name' => Request::get('last_name'),
            'email' => Request::get('email'),
            'password' => Request::get('password'),
            'owner' => Request::get('owner'),
            'position_id' => Request::get('position'),
            'photo_path' => Request::file('photo') ? Request::file('photo')->store('users', 'public') : null,
        ]);

        return Redirect::route('users')->with('успешно', 'Пользователь добавлен.');
    }

    public function getProfile(int $id) {
        if($id == 0) {
            $user = Auth::user();
        } else {
            $user = User::find($id);
        }
        $user->position = Position::where('id', $user->position_id)->first();
        return $user; //@TODO Select need fields
    }

    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'owner' => $user->owner,
                'photo' => '/'.$user->photoUrl(['w' => 60, 'h' => 60, 'fit' => 'crop']),
                'deleted_at' => $user->deleted_at,
            ],
        ]);
    }

        public function edit2(User $user)
    {
        return Inertia::render('Users/Edit2', [
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'owner' => $user->owner,
                'photo' => $user->photoUrl(['w' => 60, 'h' => 60, 'fit' => 'crop']),
                'deleted_at' => $user->deleted_at,
            ],
        ]);
    }

    public function update(User $user)
    {
        if (App::environment('demo') && $user->isDemoUser()) {
            return Redirect::back()->with('ошибка', 'Обновление пробного пользователя запрещено.');
        }

        Request::validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable'],
            'owner' => ['required', 'boolean'],
            'photo' => ['nullable', 'image'],
        ]);

        $user->update(Request::only('first_name', 'last_name', 'email', 'owner'));

        if (Request::file('photo')) {
            $user->update(['photo_path' => Request::file('photo')->store('users', 'public')]);
        }

        if (Request::get('password')) {
            $user->update(['password' => Request::get('password')]);
        }

        return Redirect::back()->with('успешно', 'Данные обновлены.');
    }

    public function destroy(User $user)
    {
        if (App::environment('demo') && $user->isDemoUser()) {
            return Redirect::back()->with('ошибка', 'Удаление пользователя запрещено.');
        }

        $user->delete();

        return Redirect::back()->with('успешно', 'Пользователь удален.');
    }

    public function restore(User $user)
    {
        $user->restore();

        return Redirect::back()->with('успешно', 'Пользователь восстановлен.');
    }

    public function getUsers(){
        return User::with('position')->get(); // @TODO SELECT IMPORTANT FIELDSs
    }

    public function userOnlineStatus()
    {
        $users = DB::table('users')->get();
    
        foreach ($users as $user) {
            if (Cache::has('user-is-online-' . $user->id))
                echo "User " . $user->first_name . " is online.";
            else
                echo "User " . $user->first_name . " is offline.";
        }
    }

    public function profile(){
        $user = User::find(Auth::user()->id);
        $position = Position::find($user->position_id);

        return Inertia::render('Users/Profile',[
            'position' => $position
        ]);
    }
    
}
