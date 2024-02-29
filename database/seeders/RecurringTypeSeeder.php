<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\RecurringTypeEnum;
use App\Models\RecurringType;
use Illuminate\Database\Seeder;

class RecurringTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patterns = RecurringTypeEnum::values();
        foreach ($patterns as $pattern) {
            RecurringType::firstOrCreate(
                ['recurring_type' => $pattern,]
            );
        }
    }
}
