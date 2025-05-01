<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Wing5wong\KamarDirectoryServices\Models\Attendance;

class AttendanceGraphs extends Component
{

    public $studentId = '';
    public $dateFrom;
    public $dateTo;

    public $attendances = '';
    public $ethnicity = '';
    public $gender = '';
    public $house = '';
    public $yearLevel = '';
    public $whanauOnly = false;

    public $slotSummaries;
    public $slotSummariesByGroup;
    public $datas;

    public $data = [];

    public array $chartData = [];
    public array $ethnicGroups = ['Total', 'European', 'Māori', 'MELAA', 'Asian', 'Pasifika'];
    public array $yearLevels = [9, 10, 11, 12, 13];
    public array $genders = ['M', 'F', 'D'];

    public function mount()
    {
        $slotKeys = [
            'slot_1_alt',
            'slot_2_alt',
            'slot_3_alt',
            'slot_4_alt',
            'slot_5_alt',
            'slot_6_alt',
            'slot_7_alt',
            'slot_8_alt',
            'slot_9_alt',
            'slot_10_alt'
        ];


        $colorMap = [
            '?' => 'gray',
            'J' => 'blue',
            'L' => 'orange',
            'P' => 'green',
            'U' => 'darkred',
            // add more codes as needed
        ];

        $result = [];

        foreach ($this->ethnicGroups as $group) {
            $result[$group] = [];

            foreach ($slotKeys as $slotIndex => $slotKey) {
                $query = Attendance::query()
                    ->select($slotKey, DB::raw('count(*) as total'))
                    ->whereYear('date', Carbon::now()->year);

                if ($group !== 'Total') {
                    $query->whereHas('student', function ($q) use ($group) {
                        $q->where('ethnicityL1', $group);
                    });
                }

                $items = $query->groupBy($slotKey)->get();

                foreach ($items as $item) {
                    $value = trim($item->$slotKey);
                    if ($value === '' || $value === '.') continue;

                    if (!isset($result[$group][$value])) {
                        $result[$group][$value] = array_fill(0, 10, 0);
                    }

                    $result[$group][$value][$slotIndex] = $item->total;
                }
            }
        }


        foreach ($this->yearLevels as $group) {
            $result[$group] = [];

            foreach ($slotKeys as $slotIndex => $slotKey) {
                $query = Attendance::query()
                    ->select($slotKey, DB::raw('count(*) as total'))
                    ->whereYear('date', Carbon::now()->year);

                $query->whereHas('student', function ($q) use ($group) {
                    $q->where('yearlevel', $group);
                });

                $items = $query->groupBy($slotKey)->get();

                foreach ($items as $item) {
                    $value = trim($item->$slotKey);
                    if ($value === '' || $value === '.') continue;

                    if (!isset($result[$group][$value])) {
                        $result[$group][$value] = array_fill(0, 10, 0);
                    }

                    $result[$group][$value][$slotIndex] = $item->total;
                }
            }
        }


        foreach ($this->genders as $group) {
            $result[$group] = [];

            foreach ($slotKeys as $slotIndex => $slotKey) {
                $query = Attendance::query()
                    ->select($slotKey, DB::raw('count(*) as total'))
                    ->whereYear('date', Carbon::now()->year);

                $query->whereHas('student', function ($q) use ($group) {
                    $q->where('gender', $group);
                });

                $items = $query->groupBy($slotKey)->get();

                foreach ($items as $item) {
                    $value = trim($item->$slotKey);
                    if ($value === '' || $value === '.') continue;

                    if (!isset($result[$group][$value])) {
                        $result[$group][$value] = array_fill(0, 10, 0);
                    }

                    $result[$group][$value][$slotIndex] = $item->total;
                }
            }
        }


        $formatted = [];

        foreach ($result as $group => $values) {
            $formatted[$group] = [];

            foreach ($values as $code => $slotTotals) {
                $formatted[$group][] = [
                    'label' => $code,
                    'data' => $slotTotals,
                    'backgroundColor' => $colorMap[$code] ?? 'black', // fallback color
                ];
            }
        }


        $this->chartData = $formatted;
        //dd($this->chartData['European']);
        //  dd($this->chartData);
    }

    public function render()
    {
        return view('livewire.attendance-graphs');
    }
}
