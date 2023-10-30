<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Phase;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function kanban()
    {
        return view('tasks.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return \App\Models\Phase::with('tasks.user')->get();
    }

    /**
     * Display a listing of the Users resource.
     */
    public function users()
    {
        return \App\Models\User::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        // Create a new task from the $request
        $task = Task::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->name = $request->name;
        $task->phase_id = $request->phase_id;
        $task->user_id = $request->user_id;
        if($task->phase->is_completion){
            $task->completed_at = Carbon::now();
        }
        $task->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        Task::destroy($task->id);
    }


    /**
     * Set All Column/Task as Completed
     * @param mixed $id
     * 
     * @return [type]
     */
    function isCompletion(Phase $phase){

        $tasks = Task::where('phase_id',$phase->id)->where('completed_at',null)->update([
            'completed_at' => Carbon::now(),
        ]);
        if($phase->is_completion){
            $phase->is_completion = 0;
        }else{
            $phase->is_completion = 1;
        }
        $phase->save();
        return response()->json([
            'message' => 'Status Changed Successfully!'
        ]);
    }

    /**
     * Count All task
     * @param mixed $id
     * 
     * @return [type]
     */
    function tasksCount($id) {
        return Task::where('phase_id',$id)->count();
    }
}
