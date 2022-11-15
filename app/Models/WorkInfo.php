<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkInfo extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'work_info';

    protected $guarded = ['id', 'current_location'];

    protected $casts =
    [
        'start_location'    => 'array',
        'current_location'  => 'array',
        'end_location'      => 'array'
    ];

    protected $dates = ['start_time', 'end_time', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeToday($query)
    {
        $query->where('status', 'ongoing');
    }

    public function scopeHistory($query)
    {
        $query->where('status', 'closed');
    }

    public function close()
    {
        return $this->update(['status' => 'closed']);
    }
}
