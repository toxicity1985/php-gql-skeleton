<?php

namespace Vertuoza\Repositories\Settings\CollaboratorTypes\Models;

use Vertuoza\Entities\Settings\CollaboratorTypeEntity;
use Vertuoza\Repositories\Settings\CollaboratorTypes\CollaboratorTypeMutationData;

class CollaboratorTypeMapper
{
    public static function modelToEntity(CollaboratorTypeModel $dbData): CollaboratorTypeEntity
    {
        $entity = new CollaboratorTypeEntity();
        $entity->id = $dbData->id . '';
        $entity->name = $dbData->name;
        $entity->first_name = $dbData->first_name;
        $entity->isSystem = $dbData->tenant_id === null;

        return $entity;
    }

    public static function serializeUpdate(CollaboratorTypeMutationData $mutation): array
    {
        return self::serializeMutation($mutation);
    }

    public static function serializeCreate(CollaboratorTypeMutationData $mutation, string $tenantId): array
    {
        return self::serializeMutation($mutation, $tenantId);
    }

    private static function serializeMutation(CollaboratorTypeMutationData $mutation, string $tenantId = null): array
    {
        $data = [
            'name' => $mutation->name,
            'first_name' => $mutation->first_name,
        ];

        if ($tenantId) {
            $data[CollaboratorTypeModel::getTenantColumnName()] = $tenantId;
        }
        return $data;
    }
}
