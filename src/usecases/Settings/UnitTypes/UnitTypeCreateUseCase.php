<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\UnitTypeEntity;
use Vertuoza\Libs\Exceptions\FieldError;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;
use Vertuoza\Libs\Exceptions\BadUserInputException;

class UnitTypeCreateUseCase
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
    public function handle(string $name)
    {
        $pattern = "/^[a-zA-Z]+$/";
        $name = filter_var($name, FILTER_VALIDATE_REGEXP, [
            "options"=>[
                "regexp" => $pattern
            ]
        ]);

        if ($name === false) {
            throw new BadUserInputException(new FieldError('name', 'Name should contain only alphabet and spaces, and not nullable'), 'name');
        }

        $mutationData = new UnitTypeMutationData();
        $mutationData->name = $name;
        $newId = $this->unitTypeRepository->create($mutationData, $this->userContext->getTenantId());

        return $this->unitTypeRepository->getById($newId, $this->userContext->getTenantId());
    }
}