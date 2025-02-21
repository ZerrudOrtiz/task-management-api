<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'task_id';

    protected $fillable = ['title', 'description','due_date','status', 'created_by','assigned_to'];

    public function user()
    {
        return $this->belongsTo(User::class,'assigned_to', 'user_id');
    }
}
