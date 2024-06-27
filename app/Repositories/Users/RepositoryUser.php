<?php

namespace App\Repositories\User;


use App\Repositories\Users\UserIntrface;
use Illuminate\Foundation\Auth\User;

class RepositoryUser implements UserIntrface
{
    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return User::all();
    }
    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        return User::find($id);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $user = User::create($data);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data)
    {
        $user = $this->find($id);

        $user->update($data);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        $user = $this->find($id);

        return $user->delete();
    }

    /**
     * @param $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|mixed
     */
    public function paginate($perPage)
    {
        $query = User::query();

        $result = $query->orderBy('id', 'desc')
            ->paginate($perPage);

        return $result;
    }
}
