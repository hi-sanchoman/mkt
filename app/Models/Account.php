<?php

namespace App\Models;

class Account extends Model
{
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function event()
    {
        return $this->hasMany(Event::class);
    }
}
