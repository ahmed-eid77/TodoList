<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Component;
use Livewire\WithPagination;

class TodoList extends Component
{
    use WithPagination;
    public $name;
    public $search;
    public $editingTodoID;
    public $editingTodoName;

    public function create()
    {
        $this->validate([
            "name" => 'required|min:3|max:50'
        ]);

        $todo = Todo::create([
            'name' => $this->name
        ]);
        $this->reset('name');
        $this->resetPage();
        session()->flash('success', 'Created :)');

    }

    public function edit($id){
        $this->editingTodoID = $id;
        $this->editingTodoName = Todo::find($id)->name;
    }

    public function update(){

        $this->validate([
            'editingTodoName' => ['required','min:3','max:50'],
        ]);

        $todo = Todo::find($this->editingTodoID);
        $todo->update([
            'name' => $this->editingTodoName
        ]);
        $this->cancelEdit();
        session()->flash('success','Updated Successfully');
    }

    public function cancelEdit(){
        $this->reset(['editingTodoID','editingTodoName']);
    }

    public function delete($id)
    {
        $todo = Todo::find($id);
        $todo->delete();
    }

    public function toggle($id)
    {
        $todo = Todo::find($id);
        $todo->completed = !$todo->completed;
        $todo->save();
    }


    public function render()
    {
        $todos = Todo::latest()->where('name', 'like', "%{$this->search}%")->paginate(5);
        return view('livewire.todo-list', ['todos' => $todos]);
    }
}
