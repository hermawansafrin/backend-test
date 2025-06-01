<?php

namespace App\Repositories\Transaction;

class TransactionRepository
{
    /**
     * get data from db
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
     * Do storing transaction on db and return when data is stored
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
     * Find one transaction by id
     * @param int $id
     * @return array|null
     */
    public function findOne(int $id): array|null
    {
        $getter = app(Getter::class);
        $results = $getter->findOne($id);

        return $results;
    }

    /**
     * Do completing transaction on db
     * @param int $id
     * @param array $options
     * @return array|null
     */
    public function complete(int $id, ?array $options = []): array|null
    {
        $completer = app(Completer::class);
        $results = $completer->prepare($id, $options)->execute();

        return app(Getter::class)->simpleFindOne($id);
    }

    /**
     * Do cancelling transaction on db
     * @param int $id
     * @param array $options
     * @return array|null
     */
    public function cancel(int $id, ?array $options = []): array|null
    {
        $canceler = app(Canceller::class);
        $results = $canceler->prepare($id, $options)->execute();

        return app(Getter::class)->simpleFindOne($id);
    }

    /**
     * Do deleting data on db
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
