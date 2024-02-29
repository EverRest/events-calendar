<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Event extends Model
{
    use HasFactory;

    /**
     * @var string[] $fillable
     */
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'repeat_until',
        'start_time',
        'end_time',
        'parent_id',
    ];

    /**
     * @var string[] $hidden
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var string[] $casts
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'repeat_until' => 'date',
    ];

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'parent_id');
    }

    /**
     * @return HasOne
     */
    public function recurringPattern(): HasOne
    {
        return $this->hasOne(RecurringPattern::class);
    }
}
