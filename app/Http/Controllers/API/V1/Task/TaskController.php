<?php

namespace App\Http\Controllers\API\V1\Task;

use App\Http\Controllers\Controller;
use App\Http\Resources\Task\PaginateTaskCollection;
use App\Http\Resources\Task\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'titre'         => 'required|string|max:255',
            'description'   => 'required',
            'date_dech'     => ['required', 'after_or_equal:' . now()->format('Y-m-d')]
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message"=> $validator->errors(),
            ],401);
        }

        try
        {
            $task = Task::create([
                'user_id'       => Auth::user()->id,
                'titre'         => $request->titre,
                'description'   => $request->description,
                'date_dech'     => $request->date_dech,
                'statut'        => config('constant.TASK_STATUS.ACTIVE')
            ]);
            if (!$task) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Task not created',
                ], 401);
            }
            return response()->json([
                'status'  => true,
                'message' => 'Task created successfully',
                'data'    => new TaskResource($task),
            ],201);
        }
        catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ],400);
        }

    }

    public function index()
    {
        try
        {
            if (Auth::user()->role === 'admin') {
                $tasks = Task::withTrashed()->orderBy('id','desc')->paginate(10);

                return response()->json([
                    'status'  => true,
                    'data'    => new PaginateTaskCollection($tasks),
                ],201);
            }

            $tasks = Task::orderBy('id','desc')->paginate(10);
            return response()->json([
                'status'  => true,
                'data'    => new PaginateTaskCollection($tasks),
            ],201);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ],400);
        }

    }

    public function show($id)
    {
        try {
            Gate::authorize('view-task', Task::find($id));

            $task = Task::find($id);

            if (!$task) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Task not found',
                ],404);
            }
            return response()->json([
                'status'  => true,
                'data'    => new TaskResource($task),
            ],201);
        }
        catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ],400);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'titre'         => 'required|string|max:255',
            'description'   => 'required',
            'date_dech'     => ['required', 'after_or_equal:' . now()->format('Y-m-d')],
            'statut'        => 'required|in:'. config('constant.TASK_STATUS.ACTIVE'). ','. config('constant.TASK_STATUS.INACTIVE')
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message"=> $validator->errors(),
            ],401);
        }

        try {
            $task = Task::find($id);

            if (!$task) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Task not found',
                ],404);
            }

            Gate::authorize('update-task', $task);

            $task->update($request->all());

            return response()->json([
                'status'  => true,
                'message' => 'Task updated successfully',
                'data'    => new TaskResource($task),
            ],201);
        }
        catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ],400);
        }
    }

    public function destroy($id)
    {
        try {
            $task = Task::find($id);
            if (!$task) {
                return response()->json([
                    "status"  => false,
                    "message" => "Task not found."
                ], 404);
            }
            Gate::authorize('delete-task', $task);
            $taskDelete = $task->delete();
            if (!$taskDelete) {
                return response()->json([
                    'status' => false,
                    'message' => 'Server Error. Can\'t delete the task at this time.',
                ], 500);
            }
            else {
                return response()->json([
                    'status' => true,
                    'message' => 'Task deleted successfully.'
                ],201);
            }
        }
        catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ],401);
        }
    }

    public function restore()
    {
        try {
            Gate::authorize('restore-task');
            $taskRestore = Task::withTrashed()->restore();

            if (!$taskRestore) {
                return response()->json([
                    'status' => false,
                    'message' => 'Server Error. Can\'t restore the task at this time.',
                ], 500);
            }

            return response()->json([
                'status' => true,
                'message' => 'Task restored successfully.'
            ]);

        }
        catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
