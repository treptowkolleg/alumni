<?php

namespace App\Transformer;

use App\Entity\Tag;
use App\Enums\PerformanceCourseEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TagTransformer implements DataTransformerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  Tag|null $value
     */
    public function transform($value): string
    {
        if (null === $value) {
            return '';
        }

        return $value->getId();
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $value
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($value): ?Tag
    {
        // no issue number? It's optional, so that's ok
        if (!$value) {
            return null;
        }

        $issue = $this->entityManager
            ->getRepository(Tag::class)
            // query for the issue with this id
            ->find($value)
        ;

        if (null === $issue) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'A Tag with number "%s" does not exist!',
                $value
            ));
        }

        return $issue;
    }

}