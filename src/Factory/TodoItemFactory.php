<?php

namespace App\Factory;

use App\Entity\TodoItem;
use App\Repository\TodoItemRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<TodoItem>
 *
 * @method static         TodoItem|Proxy createOne(array $attributes = [])
 * @method static         TodoItem[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static         TodoItem|Proxy find(object|array|mixed $criteria)
 * @method static         TodoItem|Proxy findOrCreate(array $attributes)
 * @method static         TodoItem|Proxy first(string $sortedField = 'id')
 * @method static         TodoItem|Proxy last(string $sortedField = 'id')
 * @method static         TodoItem|Proxy random(array $attributes = [])
 * @method static         TodoItem|Proxy randomOrCreate(array $attributes = [])
 * @method static         TodoItem[]|Proxy[] all()
 * @method static         TodoItem[]|Proxy[] findBy(array $attributes)
 * @method static         TodoItem[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static         TodoItem[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static         TodoItemRepository|RepositoryProxy repository()
 * @method TodoItem|Proxy create(array|callable $attributes = [])
 */
final class TodoItemFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'title' => self::faker()->text(80),
            'description' => self::faker()->optional(10)->text(200),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(TodoItem $todoItem) {})
        ;
    }

    protected static function getClass(): string
    {
        return TodoItem::class;
    }
}
