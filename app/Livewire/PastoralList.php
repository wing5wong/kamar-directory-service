<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Wing5wong\KamarDirectoryServices\Models\Attendance;
use Wing5wong\KamarDirectoryServices\Models\Pastoral;

class PastoralList extends Component
{
    public $date;
    public $pastorals;
    public $excludedIds = [];
    public $studentIDList = '';
    public $excludedActions = [
        'q.  Stood Down', //2 spaces.....
        'q. Stood Down (Internal)',
    ];

    public $casts = [
        'date' => 'date'
    ];

    public $manyLates;

    #[Computed]
    public function uniqueEntries()
    {
        return $this->pastorals->pluck('student_id')->diff($this->excludedIds)->unique()->count();
    }
    public function getCarbonDateProperty()
    {
        return Carbon::parse($this->date);
    }

    public function mount()
    {
        $this->date = Carbon::now()->previousWeekday()->toDateString();
        // $this->yesterdaysDate = $this->date->toDateString();
        $this->loadPastorals();
        $this->load3DayTruants();
    }

    public function updatedDate()
    {
        $this->excludedIds = [];
    }

    public function updated($property)
    {
        $this->loadPastorals();
        $this->load3DayTruants();
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


        $this->studentIDList = $this->pastorals->pluck('student_id')->diff($this->excludedIds)->unique()->implode("\n");
    }

    public function load3DayTruants()
    {
        $threeWeekDaysAgo = $this->carbonDate->clone()->subWeekDays(2)->toDateString();

        // Step 1: Get studentIDs that meet the condition
        $student_ids = Attendance::select('student_id')
            ->whereHas('student', function ($query) {
                $query->where('tutor', '!=', 'AE'); // exclude AE
            })
            //->whereDate('date', Carbon::now()->previousWeekday()->toDateString())   // previous weekday
            ->whereBetween('date', [$threeWeekDaysAgo, $this->date])
            ->whereRaw("(LENGTH(codes) - LENGTH(REPLACE(codes, 'T', '')) + LENGTH(codes) - LENGTH(REPLACE(codes, '?', ''))) >= 2")
            ->orderBy('student_id')
            ->orderBy('date')
            ->groupBy('student_id')
            ->havingRaw('COUNT(*) >= 3')
            ->pluck('student_id');

        // Step 2: Get the matching records for those student_ids
        $records = Attendance::with('student')->whereIn('student_id', $student_ids)
            ->whereBetween('date', [$threeWeekDaysAgo, $this->date])
            ->whereRaw("(LENGTH(codes) - LENGTH(REPLACE(codes, 'T', '')) + LENGTH(codes) - LENGTH(REPLACE(codes, '?', ''))) >= 2")
            ->get();

        $this->manyLates = $records;
    }

    public function render()
    {
        return view('livewire.pastoral-list');
    }
}
