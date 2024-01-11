<?php
namespace Apie\Tools;

use PhpDA\Parser\Filter\NamespaceFilterInterface;

class NamespaceFilter implements NamespaceFilterInterface
{
    private const FILTERED = ['twig', 'libphonenumber'];

    private const IMPORTANT = ['Apie', 'Symfony', 'Illuminate', 'Doctrine', 'Twig'];

    public function filter(array $nameParts)
    {
        if (in_array($nameParts[0], self::IMPORTANT)) {
            return $nameParts;
        }
        if (in_array($nameParts[0], self::FILTERED)) {
            return [$nameParts[0]];
        }
        
        return ['external'];
    }
}