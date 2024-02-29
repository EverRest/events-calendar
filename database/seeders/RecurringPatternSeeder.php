<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\RecurringPatternsEnum;
use App\Models\RecurringPattern;
use Illuminate\Database\Seeder;

class RecurringPatternSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patterns = RecurringPatternsEnum::toArray();
        foreach ($patterns as $pattern => $code) {
            RecurringPattern::firstOrCreate([
                'code' => $code,
                'title' => $pattern,
            ]);
        }
    }
}
