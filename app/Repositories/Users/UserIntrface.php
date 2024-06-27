<?php

namespace App\Repositories\Users;

interface UserIntrface
{

    /**
     * Get all available User.
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
     * Paginate User.
     *
     * @param $perPage
     * @return mixed
     */
    public function paginate($perPage);
}
