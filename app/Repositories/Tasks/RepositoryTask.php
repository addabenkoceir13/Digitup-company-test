<?php

namespace App\Repositories\Task;

use App\Models\Task;
use App\Repositories\Tasks\TaskIntrface;

class RepositoryTask implements TaskIntrface
{
    /**
     * {@inheritdoc}
     */
    public function all(){
        return Task::all();
    }


    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        return Task::find($id);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $task = Task::create($data);

        return $task;
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data)
    {
        $task = $this->find($id);

        $task->update($data);

        return $task;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        $task = $this->find($id);

        return $task->delete();
    }

    /**
     * @param $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|mixed
     */
    public function paginate($perPage)
    {
        $query = Task::query();

        $result = $query->orderBy('id', 'desc')
            ->paginate($perPage);

        return $result;
    }

    public function paginateWithTrashed($perPage){
        $query = Task::query()->withTrashed();

        $result = $query->orderBy('id', 'desc')
            ->paginate($perPage);

        return $result;
    }

    public function restoreAll(){
        Task::withTrashed()->restore();
    }
}
