<?php

namespace Vertuoza\Repositories;

use Overblog\PromiseAdapter\PromiseAdapterInterface;
use Vertuoza\Repositories\Database\QueryBuilder;
use Vertuoza\Repositories\Settings\CollaboratorTypes\CollaboratorTypeRepository;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;

class RepositoriesFactory
{
    public UnitTypeRepository $unitType;
    public CollaboratorTypeRepository $collaboratorType;

    public function __construct(QueryBuilder $database, PromiseAdapterInterface $dataLoaderPromiseAdapter)
    {
        $this->unitType = new UnitTypeRepository($database, $dataLoaderPromiseAdapter);
        $this->collaboratorType = new CollaboratorTypeRepository($database, $dataLoaderPromiseAdapter);
    }
}
