<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupUser extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'group_id',
        'user_id',
    ];

    public static function getUsers(int $group_id)
    {
        $users = self::where('group_id', $group_id)->get();

        $_users = [];
        

        foreach($users as $user) {
            $_user = [];
            $_user = User::card($user->user_id);
            array_push($_users, $_user);
        }
        
        return $_users;
    }
}
