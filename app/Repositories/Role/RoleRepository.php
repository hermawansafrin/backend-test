<?php

namespace App\Repositories\Role;

class RoleRepository
{
    /**
     * function for get data on databases
     * @param array $request
     * @return mixed
     */
    public function get(array $request): mixed
    {
        $getter = app(Getter::class);
        $results = $getter->prepare($request)->execute();

        return $results;
    }

    /**
     * Get one data by id with specific option
     * @param int $id
     * @param bool $withPermission
     * @param bool $isTree
     * @return array|null
     */
    public function findOne(int $id, bool $withPermission, bool $isTree): array|null
    {
        return app(Getter::class)->findOne($id, $withPermission, $isTree);
    }

    /**
     * Do storeing role on db and return when data is stored
     * @param array $request
     * @return array|null
     */
    public function create(array $request): array|null
    {
        $creator = app(Creator::class);
        $results = $creator->prepare($request)->execute();

        if ($results === null) {
            return $results;
        }

        return app(Getter::class)->simpleFindOne($results);
    }

    /**
     * Do updating role on db and return when data is updated
     * @param int $id
     * @param array $request
     * @return array|null
     */
    public function update(int $id, array $request): ?array
    {
        $updater = app(Updater::class);
        $results = $updater->prepare($id, $request)->execute();

        if ($results === null) {
            return $results;
        }

        return app(Getter::class)->simpleFindOne($results);
    }

    /**
     * Do deleting role on db
     * @param int $id
     * @param array $options
     * @return void
     */
    public function delete(int $id, ?array $options = []): void
    {
        $deleter = app(Deleter::class);
        $results = $deleter->prepare($id, $options)->execute();
    }
}
