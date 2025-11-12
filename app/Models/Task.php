<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;    
     protected $fillable = [
        'judul',
        'deskripsi',
        'allcrew',
        'tanggal_mulai',
        'deadline',
        'tanggal_dikerjakan',
        'tanggal_selesai',
        'status',
    ];
    public function detailTasks()
    {
        return $this->hasMany(DetailTask::class, 'task_id');
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'detail_tasks', 'task_id', 'user_id');
    }
}
