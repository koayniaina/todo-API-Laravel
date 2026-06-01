<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Resources\TodoResource;

class TodoController extends Controller
{
   
  public function index(Request $request)
{
    $query = Todo::query();

    if ($request->search) {
        $query->where(
            'title',
            'like',
            "%{$request->search}%"
        );
    }

    return TodoResource::collection(
        $query->paginate(10)
    );
}


    public function show($id)
    {
        return new TodoResource(
        Todo::findOrFail($id)
    );
    }
    
  public function store(StoreTodoRequest $request)
{
    return Todo::create($request->validated());
}
  
    public function update(Request $request, $id)
    {
        $todo = Todo::findOrFail($id);

        $todo->update([
            'title' => $request->title,
            'description' => $request->description,
            'completed' => $request->completed,
        ]);

        return $todo;
    }

    public function destroy($id)
    {
        Todo::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Todo supprimé'
        ]);
    }
}