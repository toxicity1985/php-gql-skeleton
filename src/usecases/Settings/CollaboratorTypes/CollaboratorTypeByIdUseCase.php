<?php

namespace Vertuoza\Usecases\Settings\CollaboratorTypes;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\CollaboratorTypes\CollaboratorTypeRepository;
use React\Promise\Promise;

class CollaboratorTypeByIdUseCase
{
    private CollaboratorTypeRepository $collaboratorRepository;
    private UserRequestContext $userContext;

    public function __construct(
        RepositoriesFactory $repositories,
        UserRequestContext $userContext
    ) {
        $this->collaboratorRepository = $repositories->collaboratorType;
        $this->userContext = $userContext;
    }

    /**
     * @param string $id id of the unit type to retrieve
     * @return Promise<UnitTypeEntity>
     */
    public function handle(string $id): Promise
    {
        return $this->collaboratorRepository->getById($id, $this->userContext->getTenantId());
    }
}