<?php

namespace App\Repositories\User;

class UserRepository
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
     * Do storing role on db and return when data is stored
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
     * Find one user by id
     * @param int $id
     * @return array|null
     */
    public function findOne(int $id): array|null
    {
        return app(Getter::class)->findOne($id);
    }

    /**
     * Do updating data on db and return when data is updated
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
