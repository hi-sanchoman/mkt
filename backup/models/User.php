<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use League\Glide\Server;
use Auth;
use DB;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use SoftDeletes, Authenticatable, Authorizable;

    protected $casts = [
        'owner' => 'boolean',
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'position_id',
        'password', 
        'owner',
        'photo_path', 
        'remember_token', 
        'account_id'
    ];

    public function event(){
        return $this->hasMany(Events::class);
    }

    public function plan(){
        return $this->hasOne(Plan::class);
    }

    public function organization(){
        return $this->hasMany(Organization::class);
    }

    public function task(){
        return $this->hasMany(Task::class);
    }

    public function audtion(){
        return $this->hasMany(Task::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public static function plans(int $id = 0){
        
        if($id == 0) {
            $user_id = Auth::user()->id;
        } else {
            $user_id = $id;
        }
        

        $calls = Action::where('type', 1)->where('user_id', $user_id)->whereMonth('date', date('m'))->get();
        $meets = Action::where('type', 2)->where('user_id', $user_id)->whereMonth('date', date('m'))->get();
        $deals = Deal::where('responsible_id', $user_id)->where('status', 1)->whereMonth('created_at', date('m'))->get();


        $plan1 = Plan::where('user_id',  $user_id)->where('type', 1)->latest()->first();
        $plan2 = Plan::where('user_id',  $user_id)->where('type', 2)->latest()->first();
        $plan3 = Plan::where('user_id',  $user_id)->where('type', 3)->latest()->first();

        $plans = [];

        if($plan1) {
            $plans[] = [
                'id' => 1,
                'name' => 'Кол-во звонков',
                'plan' =>  $calls->count() . ' из ' . $plan1->value,
                'type' => 1,
                'fact' => round($calls->count() / $plan1->value * 100),
            ];
        }
        
        if($plan2) {
            $plans[] = [
                'id' => 2,
                'name' => 'Кол-во встреч',
                'plan' => $meets->count() . ' из ' . $plan2->value,
                'type' => 2,
                'fact' => round($meets->count() / $plan2->value * 100),
            ];
        }

        if($plan3) {
            $plans[] = [
                'id' => 3,
                'name' => 'Кол-во сделок',
                'plan' => $deals->count() . ' из ' . $plan3->value,
                'type' => 3,
                'fact' => round($deals->count() / $plan3->value * 100),
            ];
        }

        return $plans;
    }

    public static function getTasks() {
        $_tasks_1 = Task::where([
            'user_id' => Auth::user()->id,
        ])->orderBy('deadline', 'asc')->with('user')->with('auditor')->get();
        
        $_tasks_2 = Task::where([
            'auditor_id' => Auth::user()->id,
        ])->orderBy('deadline', 'asc')->with('user')->with('auditor')->get();

        return $_tasks_1->merge($_tasks_2);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public static function card(int $user_id)
    {   
        $user = self::select('id', 'first_name','last_name', 'photo_path', 'position_id')->where('id', $user_id)->first();
        if($user) {
            $user->position = Position::find($user->position_id);
            $user->name = $user->last_name . ' ' . $user->first_name;
        }
        return $user;
    }



    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::needsRehash($password) ? Hash::make($password) : $password;
    }

    public function photoUrl(array $attributes)
    {
        if ($this->photo_path) {
            return URL::to(App::make(Server::class)->fromPath($this->photo_path, $attributes));
        }
    }

    public function isDemoUser()
    {
        return $this->email === 'johndoe@example.com';
    }

    public function scopeOrderByName($query)
    {
        $query->orderBy('last_name')->orderBy('first_name');
    }

    public function scopeWhereRole($query, $role)
    {
        switch ($role) {
            case 'user': return $query->where('owner', false);
            case 'owner': return $query->where('owner', true);
        }
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', '%'.$search.'%')
                    ->orWhere('last_name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        })->when($filters['role'] ?? null, function ($query, $role) {
            $query->whereRole($role);
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
