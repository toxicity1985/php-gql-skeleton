<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\CollaboratorTypes;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;

class CollaboratorTypeQuery
{
    public static function get()
    {
        return [
            'collaboratorTypeById' => [
                'type' => Types::get(CollaboratorType::class),
                'args' => [
                    'id' => new NonNull(Types::string()),
                ],
                'resolve' => static fn ($rootValue, $args, RequestContext $context) => $context->useCases->collaboratorType->collaboratorTypeById
                    ->handle($args['id'], $context)
            ],
        ];
    }
}
