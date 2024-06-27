<?php

namespace App\Repositories\Tasks;

interface TaskIntrface
{
    /**
     * Get all available Task.
     * @return mixed
     */
    public function all();

    /**
     * {@inheritdoc}
     */
    public function create(array $data);

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data);

    /**
     * {@inheritdoc}
     */
    public function delete($id);

    /**
     * Paginate Task.
     *
     * @param $perPage
     * @return mixed
     */
    public function paginate($perPage);

    public function paginateWithTrashed($perPage);

    /**
     * Restore all deleted tasks.
     */
    public function restoreAll();
}
