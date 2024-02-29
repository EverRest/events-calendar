<?php
declare(strict_types=1);

namespace App\Data\Transformers;

use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

class MaxNumOfOccurrences implements Transformer
{

    public function transform(DataProperty $property, mixed $value, TransformationContext $context): mixed
    {
        return strtoupper($value);
    }
}
