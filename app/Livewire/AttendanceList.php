<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Wing5wong\KamarDirectoryServices\Models\Attendance;

class AttendanceList extends Component
{
    public $tCount = 3;
    public $lCount = 3;
    public $date;
    public $attendances;
    public $ignoreFullDay = true;
    public $skippedAttendances;
    public $excludedIds = [];
    public $studentIDList;
    public $p1Lates;

    public function mount()
    {
        $this->date = Carbon::now()->previousWeekday()->toDateString();
        $this->loadAttendances();
        $this->loadSkippedAttendances();
        $this->loadP1Lates();
    }

    public function loadSkippedAttendances()
    {
        $this->skippedAttendances = Attendance::select('attendances.*')
            ->join('students', 'attendances.student_id', '=', 'students.student_id')
            ->where('students.tutor', '!=', 'AE')
            ->whereDate('attendances.date', $this->date)
            ->where(function ($query) {
                //     $query->where(function ($q) {
                //         $q->whereNotIn('slot_3', ['T', '?'])
                //             ->whereIn('slot_2', ['?', 'T']);
                //     })

                // Present P1, Absent Form Time, Present P2
                $query->orWhere(function ($q) {
                    $q->whereNotIn('slot_4', ['T', '?'])
                        ->whereIn('slot_3', ['?', 'T', 'L'])
                        ->whereNotIn('slot_2', ['T', '?']);
                });

                // ->orWhere(function ($q) {
                //     $q->whereNotIn('slot_6', ['T', '?'])
                //         ->whereIn('slot_4', ['?', 'T'])
                //         ->whereNotIn('slot_3', ['T', '?']);
                // })->orWhere(function ($q) {
                //     $q->whereNotIn('slot_7', ['T', '?'])
                //         ->whereIn('slot_6', ['?', 'T?'])
                //         ->whereNotIn('slot_4', ['T', '?']);
                // })->orWhere(function ($q) {
                //     $q->whereNotIn('slot_9', ['T', '?'])
                //         ->whereIn('slot_7', ['?', 'T?'])
                //         ->whereNotIn('slot_6', ['T', '?']);
                // })
            })
            ->orderBy('students.tutor')
            ->with('student')
            ->get();
    }

    public function loadP1Lates()
    {
        $this->p1Lates = Attendance::select('attendances.*')
            ->join('students', 'attendances.student_id', '=', 'students.student_id')
            ->where('students.tutor', '!=', 'AE')
            ->whereDate('attendances.date', $this->date)
            ->where(function ($query) {
                $query->whereIn('slot_2', ['L', '?', 'T']);
            })
            ->orderBy('students.tutor')
            ->with('student')
            ->get();
    }

    public function loadAttendances()
    {
        $this->attendances = Attendance::select('attendances.*')
            ->join('students', 'attendances.student_id', '=', 'students.student_id')
            ->where('students.tutor', '!=', 'AE')
            ->whereDate('date', $this->date)
            ->when($this->ignoreFullDay, function ($query) {
                $query->whereRaw("(LENGTH(codes) - LENGTH(REPLACE(codes, '.', '')) + LENGTH(codes) - LENGTH(REPLACE(codes, 'T', '')) + LENGTH(codes) - LENGTH(REPLACE(codes, '?', ''))) < LENGTH(codes)");
            })
            ->where(function ($query) {
                $query->when($this->tCount, function ($query) {
                    // $query->whereRaw("(LENGTH(codes) - LENGTH(REPLACE(codes, 'T', '')) + LENGTH(codes) - LENGTH(REPLACE(codes, '?', ''))) >= " . $this->tCount);
                    $query->whereRaw("(LENGTH(codes) - LENGTH(REPLACE(codes, 'T', '')) + LENGTH(codes) - LENGTH(REPLACE(codes, '?', '')) + LENGTH(codes) - LENGTH(REPLACE(codes, 'L', ''))) >= " . $this->tCount);
                });
                // ->when($this->lCount, function ($query) {
                //     $query->orWhereRaw("LENGTH(codes) - LENGTH(REPLACE(codes, 'L', '')) >= " . $this->lCount);
                // });
            })
            ->orderBy('students.tutor')
            ->with('student')
            ->get();

        $this->studentIDList = $this->attendances->pluck('student_id')->diff($this->excludedIds)->implode("\n");
    }


    public function updated($property)
    {
        $this->loadAttendances();
        $this->loadSkippedAttendances();
    }

    public function updatedDate()
    {
        $this->excludedIds = [];
    }

    public function render()
    {
        return view('livewire.attendance-list');
    }
}
