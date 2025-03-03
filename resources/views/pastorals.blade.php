Pastorals
@vite(['resources/css/app.css', 'resources/js/app.js'])

<body class="bg-fixed">

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>


    <div className="p-6 max-w-6xl mx-auto">
        <div className="mb-4">
            <form action="{{ route('pastorals.filter') }}" method="POST" class="flex flex-wrap gap-y-8 gap-x-4">
                @csrf
                <fieldset
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <input type="checkbox" name="type[]" value="C"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    C
                    <input type="checkbox" name="type[]" value="D"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    D
                </fieldset>
                <label for="reason">reason</label>
                <select
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    name="reason" id="">
                    <option value="">All</option>
                    @foreach ($pastorals->groupBy('reason')->keys() as $reason)
                        <option value="{{ $reason }}"> {{ $reason }}</option>
                    @endforeach
                </select>
                <label for="motivation">motivation</label>
                <select
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    name="motivation" id="">
                    <option value="">All</option>
                    @foreach ($pastorals->groupBy('motivation')->keys() as $motivation)
                        <option value="{{ $motivation }}"> {{ $motivation }}</option>
                    @endforeach
                </select>
                <label for="consequence">consequence</label>
                <select
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    name="consequence" id="">
                    <option value="">All</option>
                    @foreach ($pastorals->groupBy('action1')->keys() as $consequence)
                        <option value="{{ $consequence }}"> {{ $consequence }}</option>
                    @endforeach
                </select>
                <label for="location">location</label>
                <select
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    name="location" id="">
                    <option value="">All</option>
                    @foreach ($pastorals->groupBy('location')->keys() as $location)
                        <option value="{{ $location }}"> {{ $location }}</option>
                    @endforeach
                </select>
                <label for="teacher">teacher</label>
                <select
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    name="teacher" id="">
                    <option value="">All</option>
                    @foreach ($pastorals->groupBy('teacher')->keys() as $teacher)
                        <option value="{{ $teacher }}"> {{ $teacher }}</option>
                    @endforeach
                </select>
                <label for="ethnicity">ethnicity</label>
                <select
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    name="ethnicity" id="">
                    <option value="">All</option>
                    @foreach ($pastorals->groupBy('student.ethnicityL1')->keys() as $ethnicity)
                        <option value="{{ $ethnicity }}"> {{ $ethnicity }}</option>
                    @endforeach
                </select>
                <label for="tutor">tutor</label>
                <select
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    name="tutor" id="">
                    <option value="">All</option>
                    @foreach ($pastorals->groupBy('student.tutor')->keys() as $tutor)
                        <option value="{{ $tutor }}"> {{ $tutor }}</option>
                    @endforeach
                </select>
                <label for="house">house</label>
                <select
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    name="house" id="">
                    <option value="">All</option>
                    @foreach ($pastorals->groupBy('student.house')->keys() as $house)
                        <option value="{{ $house }}"> {{ $house }}</option>
                    @endforeach
                </select>
                <label for="year">year</label>
                <select
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    name="year" id="">
                    <option value="">All</option>
                    @foreach ($pastorals->groupBy('student.yearlevel')->keys() as $yearlevel)
                        <option value="{{ $yearlevel }}"> {{ $yearlevel }}</option>
                    @endforeach
                </select>


                {{-- TODO: --}}
                <label for="day">day</label>
                <select
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    name="day" id="">
                    <option value="">All</option>
                    @foreach ($pastorals->groupBy('dayOfWeek')->keys() as $dayOfWeek)
                        <option value="{{ $dayOfWeek }}"> {{ $dayOfWeek }}</option>
                    @endforeach
                </select>

                {{-- TODO: --}}
                <input type="date" name="dateFrom"
                    class="bg-gray-50 border border-gray-300 text-gray-900
                    text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700
                    dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500
                    dark:focus:border-blue-500">
                <input type="date" name="dateTo"
                    class="bg-gray-50 border border-gray-300 text-gray-900
                    text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700
                    dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500
                    dark:focus:border-blue-500">


                <input type="reset" value="clear">
                <input type="submit" value="filter">
            </form>

        </div>

    </div>





    <div class="charts grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <div class="chart-container bg-white rounded-lg shadow-md p-4">
            <h2>By type</h2>
            <canvas id="myChart"></canvas>
        </div>

        <div class="chart-container bg-white rounded-lg shadow-md p-4">
            <h2>By reason</h2>
            <canvas id="myChart2"></canvas>
        </div>

        <div class="chart-container bg-white rounded-lg shadow-md p-4">
            <h2>By Motivation</h2>
            <canvas id="myChart3"></canvas>
        </div>

        <div class="chart-container bg-white rounded-lg shadow-md p-4">
            <h2>By Consequence</h2>
            <canvas id="myChart4"></canvas>
        </div>

        <div class="chart-container bg-white rounded-lg shadow-md p-4">
            <h2>By Where</h2>
            <canvas id="myChart5"></canvas>
        </div>

        <div class="chart-container bg-white rounded-lg shadow-md p-4">
            <h2>By Teacher</h2>
            <canvas id="myChart6"></canvas>
        </div>

        <div class="chart-container bg-white rounded-lg shadow-md p-4">
            <h2>Ethnicity</h2>
            <canvas id="myChart7"></canvas>
        </div>

        <div class="chart-container bg-white rounded-lg shadow-md p-4">
            <h2>Tutor</h2>
            <canvas id="myChart8"></canvas>
        </div>

        <div class="chart-container bg-white rounded-lg shadow-md p-4">
            <h2>House</h2>
            <canvas id="myChart9"></canvas>
        </div>

        <div class="chart-container bg-white rounded-lg shadow-md p-4">
            <h2>Year Level</h2>
            <canvas id="myChart10"></canvas>
        </div>


        <div class="chart-container bg-white rounded-lg shadow-md p-4">
            <h2>Day</h2>
            <canvas id="myChart11"></canvas>
        </div>

        <div class="chart-container bg-white rounded-lg shadow-md p-4">
            <h2>Week</h2>
            <canvas id="myChart12"></canvas>
        </div>

        <div class="chart-container bg-white rounded-lg shadow-md p-4">
            <h2>Month</h2>
            <canvas id="myChart13"></canvas>
        </div>

        <div class="chart-container bg-white rounded-lg shadow-md p-4">
            <h2>Top 10 Students</h2>
            <canvas id="myChart14"></canvas>
        </div>

        <div class="chart-container bg-white rounded-lg shadow-md p-4">
            <h2>Period</h2>
            <canvas id="myChart15"></canvas>
        </div>
    </div>


    <script>
        const ctx = document.getElementById('myChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                datasets: [{
                    label: 'Type',
                    data: {!! json_encode($pastorals->groupBy('type')->map->count()->sortDesc()->toArray()) !!},
                    borderWidth: 1
                }]
            }
        });

        const ctx2 = document.getElementById('myChart2');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                datasets: [{
                    label: 'Reason',
                    data: {!! json_encode($pastorals->groupBy('reason')->map->count()->sortDesc()->toArray()) !!},
                    borderWidth: 1
                }]
            }
        });

        const ctx3 = document.getElementById('myChart3');
        new Chart(ctx3, {
            type: 'bar',
            data: {
                datasets: [{
                    label: 'Motivation',
                    data: {!! json_encode($pastorals->groupBy('motivation')->map->count()->sortDesc()->toArray()) !!},
                    borderWidth: 1
                }]
            }
        });


        const ctx4 = document.getElementById('myChart4');
        new Chart(ctx4, {
            type: 'bar',
            data: {
                datasets: [{
                    label: 'Consequence',
                    data: {!! json_encode($pastorals->groupBy('action1')->map->count()->sortDesc()->toArray()) !!},
                    borderWidth: 1
                }]
            }
        });


        const ctx5 = document.getElementById('myChart5');
        new Chart(ctx5, {
            type: 'bar',
            data: {
                datasets: [{
                    label: 'Where',
                    data: {!! json_encode($pastorals->groupBy('location')->map->count()->sortDesc()->toArray()) !!},
                    borderWidth: 1
                }]
            }
        });


        const ctx6 = document.getElementById('myChart6');
        new Chart(ctx6, {
            type: 'bar',
            data: {
                datasets: [{
                    label: 'Teacher',
                    data: {!! json_encode($pastorals->groupBy('teacher')->map->count()->sortDesc()->toArray()) !!},
                    borderWidth: 1
                }]
            }
        });



        const ctx7 = document.getElementById('myChart7');
        new Chart(ctx7, {
            type: 'bar',
            data: {
                datasets: [{
                    data: {!! json_encode($pastorals->groupBy('student.ethnicityL1')->map->count()->sortDesc()->toArray()) !!},
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false

                    }
                }
            }
        });



        const ctx8 = document.getElementById('myChart8');
        new Chart(ctx8, {
            type: 'bar',
            data: {
                datasets: [{
                    label: 'Tutor',
                    data: {!! json_encode(
                        $pastorals->sortBy('student.tutor')->groupBy('student.tutor')->map->count()->sortDesc()->toArray(),
                    ) !!},
                    borderWidth: 1
                }]
            }
        });


        const ctx9 = document.getElementById('myChart9');
        new Chart(ctx9, {
            type: 'bar',
            data: {
                datasets: [{
                    label: 'House',
                    data: {!! json_encode($pastorals->sortBy('student.house')->groupBy('student.house')->map->count()->toArray()) !!},
                    borderWidth: 1,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                    ],
                }]
            }
        });


        const ctx10 = document.getElementById('myChart10');
        new Chart(ctx10, {
            type: 'bar',
            data: {
                datasets: [{
                    label: 'Year Level',
                    data: {!! json_encode($pastorals->groupBy('student.yearlevel')->map->count()->toArray()) !!},
                    borderWidth: 1
                }]
            }
        });


        const ctx11 = document.getElementById('myChart11');
        new Chart(ctx11, {
            type: 'bar',
            data: {
                datasets: [{
                    label: 'Day',
                    data: {!! json_encode($pastorals->sortBy('dayOfWeek')->groupBy('dayOfWeekEnglish')->map->count()->toArray()) !!},
                    borderWidth: 1
                }]
            }
        });




        const ctx12 = document.getElementById('myChart12');
        new Chart(ctx12, {
            type: 'bar',
            data: {
                datasets: [{
                    label: 'Week',
                    data: {!! json_encode($pastorals->sortBy('weekOfYear')->groupBy('weekOfYear')->map->count()->toArray()) !!},
                    borderWidth: 1
                }]
            }
        });



        const ctx13 = document.getElementById('myChart13');
        new Chart(ctx13, {
            type: 'bar',
            data: {
                datasets: [{
                    label: 'Month',
                    data: {!! json_encode($pastorals->sortBy('monthOfYear')->groupBy('monthOfYearEnglish')->map->count()->toArray()) !!},
                    borderWidth: 1
                }]
            }
        });


        const ctx14 = document.getElementById('myChart14');
        new Chart(ctx14, {
            type: 'bar',
            data: {
                datasets: [{
                    label: 'by Name',
                    data: {!! json_encode(
                        $pastorals->sortBy('monthOfYear')->groupBy(function ($item) {
                                return $item->student->student_id . ' - ' . $item->student->fullName;
                            })->map->count()->sortDesc()->take(10)->toArray(),
                    ) !!},
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        ticks: {
                            display: true,
                            autoSkip: false
                        }
                    }
                }
            }
        });



        const ctx15 = document.getElementById('myChart15');
        new Chart(ctx15, {
            type: 'bar',
            data: {
                datasets: [{
                    label: "Period",
                    data: {!! json_encode(
                        $pastorals->reject(function ($item) {
                                return $item->timeevent === null;
                            })->sortBy('timeevent')->groupBy('period')->map->count()->sortDesc()->toArray(),
                    ) !!},
                    borderWidth: 1
                }]
            }
        });
    </script>

    @php

        // print_r($pastorals->toArray());
    @endphp

    {{--
@foreach ($pastorals->groupBy('student_id') as $student_id => $pastoralEntries)
    <h2>{{ $student_id }}</h2>
    <ul>
        @foreach ($pastoralEntries as $ent)
            <li>{{ $ent->reason }}</li>
            <li>{{ $ent->action1 }}</li>
        @endforeach
    </ul>
@endforeach --}}

</body>
