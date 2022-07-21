<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{

    public function getAllTasksByUserId()
    {
        try {

            Log::info("Getting all Tasks by id");

            $userId = auth()->user()->id;

            $tasks = Task::query()->where('user_id', '=', $userId)->get()->toArray();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'tasks retrieved successfully',
                    'data' => $tasks
                ],
                200
            );
        } catch (\Exception $exception) {

            Log::error("Error getting task: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => "Error getting tasks"
                ],
                500
            );
        }
    }

    public function createTask(Request $request)
    {
        try {
            Log::info("Creating a task");

            $validator = Validator::make($request->all(), [
                'title' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => $validator->errors()
                    ],
                    400
                );
            };

            $title = $request->input('title');
            $userId = auth()->user()->id;

            $task = new Task();
            $task->title = $title;
            $task->user_id = $userId;

            $task->save();


            return response()->json(
                [
                    'success' => true,
                    'message' => "Task created"
                ],
                200
            );
        } catch (\Exception $exception) {
            Log::error("Error creating task: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => "Error creating tasks"
                ],
                500
            );
        }
    }

    public function getTaskById($id)
    {
        try {

            Log::info("Getting all Tasks by id");

            $userId = auth()->user()->id;

            $tasks = Task::query()
                ->where('user_id', '=', $userId)
                ->find($id);

            if (!$tasks) {
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'tasks doesnt exist'
                    ],
                    404
                );
            }

            return response()->json(
                [
                    'success' => true,
                    'message' => 'tasks retrieved successfully',
                    'data' => $tasks
                ],
                200
            );
        } catch (\Exception $exception) {

            Log::error("Error getting task: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => "Error getting tasks"
                ],
                500
            );
        }
    }

    public function updateTask(Request $request, $id)
    {
        try {
            Log::info('Updating task');

            $validator = Validator::make($request->all(), [
                'title' => ['string'],
                'status' => ['boolean']
            ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => $validator->errors()
                    ],
                    400
                );
            }
            $userId = auth()->user()->id;

            $task = Task::query()
                ->where('user_id', '=', $userId)
                ->find($id);

            if (!$task) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => "Task doesn't exists"
                    ],
                    404
                );
            }

            $title = $request->input('title');
            $status = $request->input('status');

            if (isset($title)) {
                $task->title = $title;
            };

            if (isset($status)) {
                $task->status = $status;
            };

            $task->save();

            return response()->json(
                [
                    'success' => true,
                    'message' => "Task " . $id . " changed"
                ],
                200
            );
        } catch (\Exception $exception) {

            Log::error("Error modifing the task: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => "Error modifing the task"
                ],
                500
            );
        }
    }

    public function deleteTask($id)
    {
        try {
            Log::info('Deleting task');

            $userId = auth()->user()->id;

            $task = Task::query()
                ->where('user_id', '=', $userId)
                ->find($id);

            if (!$task) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => "Task doesn't exists"
                    ],
                    404
                );
            }

            $task->delete($id);

            return response()->json(
                [
                    'success' => true,
                    'message' => "Task " . $id . " deleted"
                ],
                200
            );
        } catch (\Exception $exception) {

            Log::error("Error deleting the task: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => "Error deleting the task"
                ],
                500
            );
        }
    }

}
