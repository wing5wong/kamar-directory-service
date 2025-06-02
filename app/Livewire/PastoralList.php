<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Wing5wong\KamarDirectoryServices\Models\Pastoral;

class PastoralList extends Component
{
    public $date;
    public $pastorals;
    public $excludedIds = [];
    public $studentIDList = '';
    public $excludedActions = [
        'q.  Stood Down' //2 spaces.....
    ];

    public function mount()
    {
        $this->date = Carbon::now()->previousWeekday()->toDateString();
        $this->loadPastorals();
    }

    public function updated($property)
    {
        $this->loadPastorals();
    }
    public function updatedDate()
    {
        $this->excludedIds = [];
    }

    public function loadPastorals()
    {
        $this->pastorals = Pastoral::query()
            ->join('students', 'pastorals.student_id', '=', 'students.student_id')
            ->whereDate('dateevent', $this->date)
            ->where('type', 'D')
            ->whereNotIn('action1', $this->excludedActions)
            ->orderBy('students.tutor')
            ->orderBy('students.student_id')
            ->select('pastorals.*')
            ->with('student')
            //     ->toSql();
            // dd($this->pastorals);
            ->get();


        $this->studentIDList = $this->pastorals->pluck('student_id')->diff($this->excludedIds)->implode("\n");
    }

    public function render()
    {
        return view('livewire.pastoral-list');
    }
}
