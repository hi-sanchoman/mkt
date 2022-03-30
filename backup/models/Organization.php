<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes;

    const NEW_CLIENT = 1;
    const PERMANENT = 2;
    const POSSIBLE = 3;

    protected $fillable = [
        'account_id',
        'name',
        'comments',
        'status',
        'responsible_id',
    ];


    public function responsible(){
        return $this->belongsTo(User::class,'responsible_id','id');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function actions()
    {
        return $this->hasMany(Action::class, 'client_id', 'id');
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }
    
    public function comments()
    {
    	return Comment::select('user_id','text', 'created_at')->with('user')->where([
            'model_id' => $this->id,
            'model_type' => 'client',
        ])->get();
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%'.$search.'%');
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
