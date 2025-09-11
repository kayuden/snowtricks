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
     */
    protected function defaults(): array|callable
    {
        /*
        return [
            'name' => self::faker()->word(),
            //'name' => self::$counter++,
            'type' => self::faker()->randomElement(['Flip', 'Grab', 'Rotation', 'Slide']),
            'description' => self::faker()->sentence(150),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'editedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'imagePaths' => ['monimage_1_20250509_151654.jpg'],
            'mainImage' => 'monimage_1_20250509_151654.jpg',
            'videoEmbeds' => ['<iframe width="560" height="315" src="https://www.youtube.com/embed/ZqdFGAoHDzc?si=nIMpkwshIgfxzHBq" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>'],
        ];
        */
        return [];
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
