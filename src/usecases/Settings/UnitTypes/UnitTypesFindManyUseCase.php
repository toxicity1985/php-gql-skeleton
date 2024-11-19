<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\Repositories;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;
use Vertuoza\Entities\Settings\UnitTypeEntity;
use Vertuoza\Repositories\RepositoriesFactory;

class UnitTypesFindManyUseCase
{
    private UserRequestContext $userContext;
    private UnitTypeRepository $unitTypeRepository;

    public function __construct(
        RepositoriesFactory $repositories,
        UserRequestContext $userContext,
    ) {
        $this->unitTypeRepository = $repositories->unitType;
        $this->userContext = $userContext;
    }

    /**
     * @param string $id id of the unit type to retrieve
     * @return Promise<UnitTypeEntity>
     */
    public function handle()
    {
        return $this->unitTypeRepository->findMany($this->userContext->getTenantId());
    }
}
