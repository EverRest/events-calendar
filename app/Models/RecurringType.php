<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecurringType extends Model
{
    use HasFactory;

    /**
     * @var string[] $fillable
     */
    protected $fillable = [
        'recurring_type',
    ];

    /**
     * @return HasMany
     */
    public function reccurringPatterns(): HasMany
    {
        return $this->hasMany(RecurringPattern::class);
    }
}
