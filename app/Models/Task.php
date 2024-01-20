<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'assigned_to',
        "user_id"
    ];
    protected $appends = [
        'user_assigned_to'
    ];

    public function assignedTo(){
        return $this->belongsTo(User::class,'assigned_to');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function getUserAssignedToAttribute(){
        return $this->assignedTo->name;
    }

    public static function statuses($rules=false){

        $statuses =  array_map(function ($item){
            return $item['id'];
        },config("default.task_statuses"));

        if($rules) $statuses = implode(',',$statuses);

        return $statuses;
    }

}
