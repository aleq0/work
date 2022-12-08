<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected $appends = [
        'duration'
    ];

    protected $dates = ['start_time', 'end_time', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function duration(): Attribute
    {
        return Attribute::make(
            get: function($value, $attributes) {
                if (!empty($attributes['start_time']) && !empty($attributes['end_time'])) {
                    $startTime = Carbon::parse($attributes['start_time']);
                    $endTime = Carbon::parse($attributes['end_time']);

                    $totalDuration = $endTime->diffInSeconds($startTime);
                    return gmdate('H:i:s', $totalDuration);
                }
            }
        );
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
