<?php

namespace Vertuoza\Usecases\Settings\CollaboratorTypes;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;

class CollaboratorTypeUseCase
{
    public CollaboratorTypeByIdUseCase $collaboratorTypeById;

    public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
    {
        $this->collaboratorTypeById = new CollaboratorTypeByIdUseCase($repositories, $userContext);
    }
}
