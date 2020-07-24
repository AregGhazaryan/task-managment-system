<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    
    protected $with = array('status', 'user', 'users');

    public function users(){
        return $this->belongsToMany(\App\User::class);
    }

    public function status(){
        return $this->belongsTo(\App\Status::class);
    }
    
    public function user(){
        return $this->belongsTo(\App\User::class, 'created_by');
    }
}
