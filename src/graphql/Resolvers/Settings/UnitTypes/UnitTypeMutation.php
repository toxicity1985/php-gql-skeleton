<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;

class UnitTypeMutation extends ObjectType
{
    static function get()
    {
        return [
            'createUnitType' => [
                'type' => Types::get(UnitType::class),
                'args' => [
                    'input' => Type::nonNull(Types::get(UnitTypeInput::class)),
                ],
                'resolve' => static fn($rootValue, $args, RequestContext $context) => $context->useCases->unitType->unitTypeCreate->handle($args['input']['name'])
            ]
        ];
    }
}