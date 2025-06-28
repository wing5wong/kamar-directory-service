<?php

namespace App\Livewire;

use Livewire\Component;
use Wing5wong\KamarDirectoryServices\Models\Student;

class StudentRecord extends Component
{
    public $studentId = 25302;
    public $student;

    public function mount()
    {
        $this->student = Student::with('attendances', 'pastoralRecords', 'recognitions')->where('student_id', $this->studentId)->first();
    }
    public function updated()
    {
        $this->student = Student::with('attendances', 'pastoralRecords', 'recognitions')->where('student_id', $this->studentId)->first();
    }

    public function render()
    {
        return view('livewire.student-record');
    }
}
