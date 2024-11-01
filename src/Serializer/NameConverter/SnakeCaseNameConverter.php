<?php 
// src/Serializer/NameConverter/SnakeCaseNameConverter.php

namespace App\Serializer\NameConverter;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class SnakeCaseNameConverter implements NameConverterInterface
{
    public function normalize(string $propertyName): string
    {
        return $propertyName; // Retourne le nom tel quel en snake_case
    }

    public function denormalize(string $propertyName): string
    {
        return $propertyName; // Retourne le nom tel quel en snake_case
    }
}
