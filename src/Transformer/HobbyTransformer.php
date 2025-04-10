<?php

namespace App\Transformer;

use App\Entity\HobbyCategory;
use Doctrine\ORM\EntityManagerInterface;
use PhpCsFixer\Tokenizer\AbstractTransformer;
use Symfony\Component\Form\DataTransformerInterface;

class HobbyTransformer implements DataTransformerInterface
{

    public function __construct(private EntityManagerInterface $em) {}

    public function transform($value): mixed
    {
        return $value; // Von Entity zu ID
    }

    public function reverseTransform($value): mixed
    {
        if (is_array($value)) {
            return $this->em->getRepository(HobbyCategory::class)->findBy(['id' => $value]);
        }
        return null;
    }

}