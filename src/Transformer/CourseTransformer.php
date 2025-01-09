<?php

namespace App\Transformer;

use App\Enums\PerformanceCourseEnum;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CourseTransformer implements DataTransformerInterface
{
    public function transform($value): array
    {
        if (!$value) {
            return [];
        }

        return array_map(fn($course) => PerformanceCourseEnum::from($course), $value);
    }

    public function reverseTransform($value): array
    {
        if (!$value) {
            return [];
        }

        try {
            return array_map(fn($course) => PerformanceCourseEnum::from($course->value), $value);
        } catch (\ValueError $e) {
            throw new TransformationFailedException('Invalid role value.');
        }
    }

}