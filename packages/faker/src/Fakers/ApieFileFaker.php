<?php
namespace Apie\Faker\Fakers;

use Apie\Common\ValueObjects\EntityNamespace;
use Apie\Faker\Interfaces\ApieClassFaker;
use Apie\Faker\Interfaces\ApieFileFaker as InterfacesApieFileFaker;
use Faker\Generator;
use ReflectionClass;

/** @implements ApieClassFaker<InterfacesApieFileFaker> */
class ApieFileFaker implements ApieClassFaker
{
    public function supports(ReflectionClass $class): bool
    {
        return $class->name === InterfacesApieFileFaker::class;
    }

    /**
     * @return array<int, InterfacesApieFileFaker>
     */
    public function getSupportedFileFakers(): array
    {
        $ns = new EntityNamespace('Apie\Faker\FileFakers');
        $supportedFileFakers = [];
        foreach ($ns->getClasses(__DIR__ . '/../FileFakers') as $class) {
            if ($class->getMethod('isSupported')->invoke(null)) {
                $supportedFileFakers[] = $class->newInstance();
            }
        }
        return $supportedFileFakers;
    }

    public function fakeFor(Generator $generator, ReflectionClass $class): InterfacesApieFileFaker
    {
        return $generator->randomElement($this->getSupportedFileFakers());
    }
}
