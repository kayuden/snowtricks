<?php

namespace App\Factory;

use App\Entity\Trick;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Trick>
 */
final class TrickFactory extends PersistentProxyObjectFactory
{
    //private static int $counter = 1;

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Trick::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'name' => self::faker()->word(),
            //'name' => self::$counter++,
            'type' => self::faker()->randomElement(['Flip', 'Grab', 'Rotation', 'Slide']),
            'description' => self::faker()->sentence(150),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'editedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'imagePaths' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Trick $trick): void {})
        ;
    }
}
