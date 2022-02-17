<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    public function store () {
        $data = $this->validateInput('store');

        $todo = new Todo();

        $todo->name = $data['name'];
        $todo->description = $data['description'];
        $todo->save();

        session()->flash('success', 'Todo created succesfully');

        return redirect('/');
    }

    public function index () {
        return view('index')->with('todos', Todo::latest()->get());
    }

    public function create () {
        return view('create');
    }

    public function details (Todo $todo) {
        return view('details')->with('todo', $todo);
    }

    public function edit (Todo $todo) {
        return view('edit')->with('todo', $todo);
    }

    public function update () {

        $data = $this->validateInput('update');

        $todo = Todo::find($data['id']);
        
        $todo->name = $data['name'];
        $todo->description = $data['description'];
        $todo->save();

        session()->flash('success', 'Todo updated succesfully');

        return redirect('/');
    }

    public function delete (Todo $todo) {
        $todo->delete();
        return redirect('/');
    }

    public function validateInput ($type) {
        switch ($type) {
            case 'store':
                try {
                    $this->validate(request(), [
                        'name' => ['required'],
                        'description' => ['required']
                    ]);
                } catch (ValidationException $e) {
                }
                break;
            case 'update':
                try {
                    $this->validate(request(), [
                        'id' => ['required'],
                        'name' => ['required'],
                        'description' => ['required']
                    ]);
                } catch (ValidationException $e) {
                }
                break;
        }

        return request()->all();
    }
}
