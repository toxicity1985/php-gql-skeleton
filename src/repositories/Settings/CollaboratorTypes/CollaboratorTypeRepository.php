<?php

namespace Vertuoza\Repositories\Settings\CollaboratorTypes;

use Illuminate\Database\Query\Builder;
use Overblog\DataLoader\DataLoader;
use Overblog\PromiseAdapter\PromiseAdapterInterface;
use Vertuoza\Repositories\AbstractRepository;
use Vertuoza\Repositories\Database\QueryBuilder;
use Vertuoza\Repositories\Settings\CollaboratorTypes\Models\CollaboratorTypeMapper;
use Vertuoza\Repositories\Settings\CollaboratorTypes\Models\CollaboratorTypeModel;
use function React\Async\async;

class CollaboratorTypeRepository extends AbstractRepository
{
    public function __construct(
        private readonly QueryBuilder $database,
        PromiseAdapterInterface       $dataLoaderPromiseAdapter
    ) {
        parent::__construct($database, $dataLoaderPromiseAdapter, CollaboratorTypeModel::getTableName());
    }

    private function fetchByIds(string $tenantId, array $ids)
    {
        return async(function () use ($tenantId, $ids) {
            $query = $this->getQueryBuilder()
                ->where(function ($query) use ($tenantId) {
                    $query->where([CollaboratorTypeModel::getTenantColumnName() => $tenantId])
                        ->orWhere(CollaboratorTypeModel::getTenantColumnName(), null);
                });
            $query->whereNull('deleted_at');
            $query->whereIn(CollaboratorTypeModel::getPkColumnName(), $ids);

            $entities = $query->get()->mapWithKeys(function ($row) {
                $entity = CollaboratorTypeMapper::modelToEntity(CollaboratorTypeModel::fromStdclass($row));
                return [$entity->id => $entity];
            });

            // Map the IDs to the corresponding entities, preserving the order of IDs.
            return collect($ids)
                ->map(fn ($id) => $entities->get($id))
                ->toArray();
        })();
    }

    protected function getDataloader(string $tenantId): DataLoader
    {
        if (!isset($this->getbyIdsDL[$tenantId])) {

            $dl = new DataLoader(function (array $ids) use ($tenantId) {
                return $this->fetchByIds($tenantId, $ids);
            }, $this->dataLoaderPromiseAdapter);
            $this->getbyIdsDL[$tenantId] = $dl;
        }

        return $this->getbyIdsDL[$tenantId];
    }


    protected function getQueryBuilder(): Builder
    {
        return $this->db->getConnection()->table(CollaboratorTypeModel::getTableName());
    }

    public function countCollaboratorTypeWithName(string $name, string $tenantId, string|int|null $excludeId = null)
    {
        return async(
            fn () => $this->getQueryBuilder()
                ->where('name', $name)
                ->whereNull('deleted_at')
                ->where(function ($query) use ($excludeId) {
                    if (isset($excludeId))
                        $query->where('id', '!=', $excludeId);
                })
                ->where(function ($query) use ($tenantId) {
                    $query->where(CollaboratorTypeModel::getTenantColumnName(), '=', $tenantId)
                        ->orWhereNull(CollaboratorTypeModel::getTenantColumnName());
                })
        )();
    }

    public function findMany(string $tenantId)
    {
        return async(
            fn () => $this->getQueryBuilder()
                ->whereNull('deleted_at')
                ->where(function ($query) use ($tenantId) {
                    $query->where(CollaboratorTypeModel::getTenantColumnName(), '=', $tenantId)
                        ->orWhereNull(CollaboratorTypeModel::getTenantColumnName());
                })
                ->get()
                ->map(function ($row) {
                    return CollaboratorTypeMapper::modelToEntity(CollaboratorTypeModel::fromStdclass($row));
                })
        )();
    }

    public function create(CollaboratorTypeMutationData $data, string $tenantId): int|string
    {
        $newId = $this->getQueryBuilder()->insertGetId(
            CollaboratorTypeMapper::serializeCreate($data, $tenantId)
        );
        return $newId;
    }

    public function update(string $id, CollaboratorTypeMutationData $data)
    {
        $this->getQueryBuilder()
            ->where(CollaboratorTypeModel::getPkColumnName(), $id)
            ->update(CollaboratorTypeMapper::serializeUpdate($data));

        $this->clearCache($id);
    }
}