<?php

namespace App\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Wing5wong\KamarDirectoryServices\Models\Pastoral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Wing5wong\KamarDirectoryServices\Models\Student;

class PastoralGraphs extends Component
{
    private $pastorals;
    public $allPastorals;
    public $datas = [];

    public int $topX = 10;
    public $categories = [];
    public $types = ['C', 'D'];

    public $reason = [
        'Cellphone Use'
    ];

    private $resetableKeys = [
        'motivation',
        'consequence',
        'location',
        'teacher',
        'ethnicity',
        'tutor',
        'house',
        'yearLevel',
        'day',
        'week',
        'month',
        'studentId',
        'studentName',
        'period',
        'gender'
    ];

    public $whanauClasses = ["AWAGAM", "MAUHN", "MOAPT", "WHETI"];

    public $motivation = '';
    public $consequence = '';
    public $location = '';
    public $teacher = '';
    public $ethnicity = '';
    public $tutor = '';
    public $house = '';
    public $yearLevel = '';
    public $day = '';
    public $week = '';
    public $month = '';
    public $studentId = '';
    public $studentName = '';
    public $period = '';
    public $gender = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $whanauOnly = false;

    private $schoolPeriodOrder = [
        'BEFORE SCHOOL',
        'PERIOD 1',
        'FT',
        'PERIOD 2',
        '1ST BREAK',
        'PERIOD 3',
        'PERIOD 4',
        '2ND BREAK',
        'PERIOD 5',
        'AFTER SCHOOL',
        'OUT OF SCHOOL HOURS'
    ];



    public function mount(Request $request)
    {
        $this->dateFrom = Carbon::now()->startOfYear()->toDateString();
        $this->dateTo = Carbon::now()->toDateString();

        $this->allPastorals = Pastoral::query()
            ->whereHas('student')
            ->whereYear('dateevent', Carbon::now()->year)
            ->get();

        $this->categories['allTypes'] = $this->allPastorals->pluck('type')
            ->unique()
            ->filter(function ($type) {
                return in_array($type, ['C', 'D']);
            })
            ->map(function ($item) {
                $mappings = [
                    'C' => 'Minors',
                    'D' => 'Majors'
                ];
                return [
                    'code' => $item,
                    'type' => $mappings[$item]
                ];
            })
            ->toArray();

        $onlyCDPastorals = $this->allPastorals
            ->filter(function ($pastoral) {
                return in_array($pastoral['type'], ['C', 'D']);
            });

        $this->categories['months'] = $onlyCDPastorals
            ->sortBy('monthOfYear')
            ->pluck('monthOfYearEnglish')
            ->unique()
            ->toArray();

        $this->categories['periods'] = $onlyCDPastorals
            ->sortBy(function ($item) {
                return array_search($item->period, $this->schoolPeriodOrder);
            })
            ->pluck('period')
            ->unique()
            ->toArray();

        $this->categories['days'] = $onlyCDPastorals
            ->sortBy(function ($item) {
                return $item->dayOfWeek;
            })
            ->pluck('dayOfWeekEnglish')
            ->unique()
            ->toArray();

        collect(
            [
                'reasons' => 'reason',
                'motivations' => 'motivation',
                'consequences' => 'action1',
                'locations' => 'location',
                'teachers' => 'teacher',
                'ethnicities' => 'student.ethnicityL1',
                'tutors' => 'student.tutor',
                'houses' => 'student.house',
                'yearLevels' => 'student.yearlevel',
                'weeks' => 'weekOfYear',
                'names' => 'student.fullName',
                'genders' => 'student.gender'
            ]
        )->each(function ($item, $key) use ($onlyCDPastorals) {
            $this->categories[$key] = $onlyCDPastorals
                ->pluck($item)
                ->unique()
                ->sort()
                ->toArray();
        });

        $this->reloadData();
    }

    public function render(Request $request)
    {
        return view('livewire.pastoral-graphs');
    }

    public function resetFields()
    {
        collect($this->resetableKeys)
            ->each(function ($key) {
                $this->{$key} = '';
            });

        $this->types = ['C', 'D'];
        $this->reason = ['Cellphone Use'];
        $this->whanauOnly = false;
        $this->topX = 10;
        $this->dateFrom = Carbon::now()->startOfYear()->toDateString();
        $this->dateTo = Carbon::now()->toDateString();

        $this->reloadData();
    }


    public function reloadData()
    {
        $this->pastorals = Cache::remember('pastoralData2', 0, function () {
            return Pastoral::query()
                ->whereHas('student')
                ->whereYear('dateevent', Carbon::now()->year)
                ->when($this->whanauOnly, function ($query) {
                    return $query->whereHas('student', function ($q) {
                        $q->whereIn('tutor', $this->whanauClasses);
                    });
                })
                ->when($this->dateFrom, function ($query) {
                    $query->whereDate('dateevent', '>=', $this->dateFrom);
                })
                ->when($this->dateTo, function ($query) {
                    $query->whereDate('dateevent', '<=', $this->dateTo);
                })
                ->when($this->types, function ($query) {
                    return $query->whereIn('type', $this->types);
                }, function ($query) {
                    return $query->whereIn('type', ['C', 'D']);
                })
                ->when(!empty($this->reason), function ($query) {
                    return $query
                        ->whereNotIn('reason', $this->reason);
                    //   ->where('reason', 'LIKE', '%' . $this->reason . '%');
                })
                ->when($this->motivation, function ($query) {
                    return $query->where('motivation', 'LIKE', '%' . $this->motivation . '%');
                })
                ->when($this->consequence, function ($query) {
                    return $query->where('action1', 'LIKE', '%' . $this->consequence . '%');
                })
                ->when($this->ethnicity, function ($query) {
                    return $query->whereHas('student', function ($q) {
                        $q->where('ethnicityL1', $this->ethnicity);
                    });
                })
                ->when($this->location, function ($query) {
                    return $query->where('location', $this->location);
                })
                ->when($this->teacher, function ($query) {
                    return $query->where('teacher', $this->teacher);
                })
                ->when($this->tutor, function ($query) {
                    return $query->whereHas('student', function ($q) {
                        $q->where('tutor', 'LIKE', '%' . $this->tutor . '%');
                    });
                })
                ->when($this->house, function ($query) {
                    return $query->whereHas('student', function ($q) {
                        $q->where('house', 'LIKE', '%' . $this->house . '%');
                    });
                })
                ->when($this->yearLevel, function ($query) {
                    return $query->whereHas('student', function ($q) {
                        $q->where('yearlevel', $this->yearLevel);
                    });
                })
                ->when($this->studentId, function ($query) {
                    $query->where('student_id', $this->studentId);
                })
                ->when($this->studentName, function ($query) {
                    $query->whereHas('student', function ($q) {
                        $q->where('firstName', 'LIKE',  $this->studentName . '%')
                            ->orWhere('lastName', 'LIKE',  $this->studentName . '%');
                    });
                })
                ->when($this->gender, function ($query) {
                    return $query->whereHas('student', function ($q) {
                        $q->where('gender', $this->gender);
                    });
                })
                ->get();
        });

        $this->pastorals = $this->pastorals->when($this->period, function ($collection) {
            return $collection->filter(function ($item) {
                return $item->period === $this->period;
            });
        });

        $this->pastorals = $this->pastorals->when($this->day, function ($collection) {
            return $collection->filter(function ($item) {
                return $item->dayOfWeekEnglish === $this->day;
            });
        });

        $this->pastorals = $this->pastorals->when($this->week, function ($collection) {
            return $collection->filter(function ($item) {
                return $item->weekOfYear === (int)$this->week;
            });
        });

        $this->pastorals = $this->pastorals->when($this->month, function ($collection) {
            return $collection->filter(function ($item) {
                return $item->monthOfYearEnglish === $this->month;
            });
        });




        $typeData = $this->pastorals->map(function ($item) {
            $mappings = [
                'C' => 'Minors',
                'D' => 'Majors'
            ];
            $item['type'] = $mappings[$item['type']];
            return $item;
        });
        $typeData = $typeData->groupBy('type');

        $this->datas['allTypes'] = [
            'label' => 'Type',
            'data' => $typeData->map->count()->sortDesc()->toArray(),
            'borderWidth' => 1,
            'backgroundColor' => $this->getBackgroundColors($typeData)
        ];

        $houseData = $this->pastorals->sortBy('student.house')->groupBy('student.house');
        $this->datas['houses'] = [
            'label' => 'House',
            'data' => $houseData->map->count()->toArray(),
            'borderWidth' => 1,
            'backgroundColor' => $this->getBackgroundColors($houseData)
        ];

        $ethnicityData = $this->pastorals->groupBy('student.ethnicityL1');
        $this->datas['ethnicities'] = [
            'label' => 'Ethnicity',
            'data' => $ethnicityData->map->count()->toArray(),
            'borderWidth' => 1,
            'backgroundColor' => $this->getBackgroundColors($ethnicityData)
        ];

        $genderData = $this->pastorals->groupBy('student.gender')
            ->sortDesc();
        $this->datas['genders'] = [
            'label' => 'Gender',
            'data' => $genderData->map->count()->toArray(),
            'borderWidth' => 1,
            'backgroundColor' => $this->getBackgroundColors($genderData)
        ];

        $tutorData = $this->pastorals->groupBy('student.tutor')
            ->sortDesc();
        $this->datas['tutors'] = [
            'label' => 'Tutor',
            'data' => $tutorData->map->count()->toArray(),
            'borderWidth' => 1,
            'backgroundColor' => $this->getBackgroundColors($tutorData)
        ];


        $studentNameData = $this->pastorals->groupBy(function ($item) {
            return $item->student->student_id . ' - ' . $item->student->fullName;
        })->sortByDesc(function ($items) {
            return $items->count();
        });;

        $this->datas['names'] = [
            'label' => 'Tutor',
            'data' => $studentNameData->map->count()->take($this->topX)->toArray(),
            'borderWidth' => 1,
            'backgroundColor' => $this->getBackgroundColorsFromValue($studentNameData->map(function ($pastoral) {
                return $pastoral->first()->student->house;
            }))
        ];

        // background colour by student house
        // $this->datas['names'] = $this->pastorals->groupBy(function ($item) {
        //     return $item->student->student_id . ' - ' . $item->student->fullName;
        // })->map->count()->sortDesc()->take($this->topX)->toArray();



        $this->datas['reasons'] = $this->pastorals->groupBy('reason')->map->count()->sortDesc()->toArray();
        $this->datas['motivations'] = $this->pastorals->groupBy('motivation')->map->count()->sortDesc()->toArray();
        $this->datas['consequences'] = $this->pastorals->groupBy('action1')->map->count()->sortDesc()->toArray();
        $this->datas['locations'] = $this->pastorals->groupBy('location')->map->count()->sortDesc()->toArray();
        $this->datas['teachers'] = $this->pastorals->groupBy('teacher')->map->count()->sortDesc()->toArray();

        // $this->datas['tutors'] = $this->pastorals->sortBy('student.tutor')->groupBy('student.tutor')->map->count()->sortDesc()->take(14)->toArray();
        $this->datas['yearLevels'] = $this->pastorals->groupBy('student.yearlevel')->map->count()->toArray();

        $this->datas['days'] = $this->pastorals->sortBy('dayOfWeek')->groupBy('dayOfWeekEnglish')->map->count()->toArray();
        $this->datas['weeks'] = $this->pastorals->sortBy('weekOfYear')->groupBy('weekOfYear')->map->count()->toArray();
        $this->datas['months'] = $this->pastorals->sortBy('monthOfYear')->groupBy('monthOfYearEnglish')->map->count()->toArray();


        $this->datas['periods'] = $this->pastorals
            ->reject(function ($item) {
                return $item->timeevent === null;
            })
            ->sortBy(function ($item) {
                return array_search($item->period, $this->schoolPeriodOrder);
            })->groupBy('period')->map->count()->toArray();

        $this->dispatch('dataUpdate', $this->datas);
    }

    public function updatedTypes()
    {
        $this->reloadData();
        $this->categories['reasons'] = $this->allPastorals
            ->filter(function ($pastoral) {
                return in_array($pastoral['type'], $this->types);
            })
            ->pluck('reason')
            ->unique()
            ->sort()
            ->toArray();
    }

    public function updated()
    {
        $this->reloadData();
    }

    private function getBackgroundColors($pastorals)
    {
        $colorMapping = [
            'C' => 'rgba(255, 205, 86, 1)',
            'D' => '#d90000',
            'MINORS' => 'rgba(255, 205, 86, 1)',
            'MAJORS' => '#d90000',

            'AWA' => 'rgba(54, 162, 235, 1)',
            'MAU' => 'rgba(255, 99, 132, 1)',
            'MOA' => 'rgba(255, 205, 86, 1)',
            'WHE' => 'rgba(75, 192, 192, 1)',

            'EUROPEAN' => 'rgba(152,100,214,1)',
            'MĀORI' => 'rgba(255, 99, 132, 1)',
            'MELAA' => 'rgba(75, 192, 192, 1)',
            'PASIFIKA' => 'rgba(54, 162, 235, 1)',
            'ASIAN' => 'rgba(255, 205, 86, 1)',

            'M' => 'rgba(54, 162, 235, 1)',
            'F' => 'rgba(255, 99, 132, 1)',
        ];

        return array_map(function ($type) use ($colorMapping) {
            if (isset($colorMapping[mb_strtoupper($type)])) return $colorMapping[mb_strtoupper($type)]; // Default to black if no color is defined
            if (isset($colorMapping[mb_strtoupper(substr($type, 0, 3))])) return $colorMapping[mb_strtoupper(substr($type, 0, 3))]; // Default to black if no color is defined
        }, $pastorals->keys()->toArray());
    }

    private function getBackgroundColorsFromValue($pastorals)
    {
        $colorMapping = [
            'C' => 'rgba(255, 205, 86, 1)',
            'D' => '#d90000',
            'MINORS' => 'rgba(255, 205, 86, 1)',
            'MAJORS' => '#d90000',

            'AWA' => 'rgba(54, 162, 235, 1)',
            'MAU' => 'rgba(255, 99, 132, 1)',
            'MOA' => 'rgba(255, 205, 86, 1)',
            'WHE' => 'rgba(75, 192, 192, 1)',

            'EUROPEAN' => 'rgba(152,100,214,1)',
            'MĀORI' => 'rgba(255, 99, 132, 1)',
            'MELAA' => 'rgba(75, 192, 192, 1)',
            'PASIFIKA' => 'rgba(54, 162, 235, 1)',
            'ASIAN' => 'rgba(255, 205, 86, 1)',

            'M' => 'rgba(54, 162, 235, 1)',
            'F' => 'rgba(255, 99, 132, 1)',
        ];

        return array_map(function ($type) use ($colorMapping) {
            if (isset($colorMapping[mb_strtoupper($type)])) return $colorMapping[mb_strtoupper($type)]; // Default to black if no color is defined
            if (isset($colorMapping[mb_strtoupper(substr($type, 0, 3))])) return $colorMapping[mb_strtoupper(substr($type, 0, 3))]; // Default to black if no color is defined
        }, $pastorals->values()->toArray());
    }

    public function toggleWhanauClassesOnly()
    {
        $this->tutor = '';
        $this->whanauOnly = ! $this->whanauOnly;
        $this->reloadData();
    }
}
