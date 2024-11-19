<?php

namespace Vertuoza\Repositories;

use Overblog\DataLoader\DataLoader;
use Overblog\PromiseAdapter\PromiseAdapterInterface;
use React\Promise\Promise;
use Vertuoza\Repositories\Database\QueryBuilder;

abstract class AbstractRepository
{
    protected string $tableName;
    protected array $getbyIdsDL;
    protected QueryBuilder $db;
    protected PromiseAdapterInterface $dataLoaderPromiseAdapter;

    public function __construct(
        private QueryBuilder    $database,
        PromiseAdapterInterface $dataLoaderPromiseAdapter,
        string                  $tableName
    ) {
        $this->db = $database;
        $this->dataLoaderPromiseAdapter = $dataLoaderPromiseAdapter;
        $this->getbyIdsDL = [];
        $this->tableName = $tableName;
    }

    public function getByIds(array $ids, string $tenantId): Promise
    {
        return $this->getDataloader($tenantId)->loadMany($ids);
    }

    public function getById(string $id, string $tenantId): Promise
    {
        return $this->getDataloader($tenantId)->load($id);
    }

    abstract protected function getDataloader(string $tenantId): DataLoader;

    protected function clearCache(string $id)
    {
        foreach ($this->getbyIdsDL as $dl) {
            if ($dl->key_exists($id)) {
                $dl->clear($id);
                return;
            }
        }
    }

    protected function getQueryBuilder()
    {
        return $this->db->getConnection()->table($this->tableName);
    }
}
