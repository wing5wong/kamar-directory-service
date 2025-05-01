<div
    class="border bg-white border-gray-200 print:p-0 print:m-0 print:w-screen print:h-screen print:break-after-always flex flex-row h-screen overflow-hidden">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
    <style>
        h2 {
            font-weight: bold;
            font-size: 12px
        }
    </style>
    {{-- grid grid-cols-1 gap-6 md:grid-cols-4 lg:grid-cols-3 xl:grid-cols-9  --}}

    {{--



<input type="date" name="dateFrom"
class="bg-gray-50 border border-gray-300 text-gray-900
text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700
dark:border-gray-600 dark:placeholder-gray-400 dark:text-slate-900 dark:focus:ring-blue-500
dark:focus:border-blue-500">

<input type="date" name="dateTo"
class="bg-gray-50 border border-gray-300 text-gray-900
text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700
dark:border-gray-600 dark:placeholder-gray-400 dark:text-slate-900 dark:focus:ring-blue-500
dark:focus:border-blue-500">


<input type="reset" value="clear">
<input type="submit" value="filter"> --}}
    {{-- </form> --}}





    <div id="sidebar" style="transition: margin .3s"
        class="ml-0 w-0 w-100 grow-0 shrink-0 print:hidden bg-slate-100 p-2 mb-4 grid grid-cols-12 gap-2 auto-rows-max h-screen overflow-y-scroll">


        <div wire:click.prevent="resetFields"
            class="col-span-12 flex justify-center items-center bg-slate-950 text-white py-1">
            reset</div>


        <div class="flex-row col-span-12">
            <label class="text-slate-900" for="">Types</label>
            <fieldset
                class="flex mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 flex justify-around">
                @foreach ($categories['allTypes'] as $type)
                    <label for="" class="text-sm font-medium flex align-center justify-center gap-4">
                        <span>({{ $type['code'] }}) {{ $type['type'] }}</span>
                        <input type="checkbox" value="{{ $type['code'] }}" wire:model.live="types"
                            class="block rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                    </label>
                @endforeach
            </fieldset>
        </div>


        <div class="flex flex-col col-span-6">
            <label for="dateFrom" class="text-sm font-medium text-slate-900">From</label>
            <input type="date" wire:model.live="dateFrom"
                class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
        </div>

        <div class="flex flex-col col-span-6">
            <label for="dateTo" class="text-sm font-medium text-slate-900">To</label>
            <input type="date" wire:model.live="dateTo"
                class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
        </div>

        <div class="flex flex-col col-span-6">
            <label class="text-sm font-medium text-slate-900" for="name">Student ID</label>
            <input type="text" wire:model.live.debounce.1000ms="studentId"
                class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
        </div>

        <div class="flex flex-col col-span-6">
            <label class="text-sm font-medium text-slate-900" for="name">Name</label>
            <input type="text" wire:model.live.debounce.1000ms="studentName"
                class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
        </div>


        <div class="flex flex-col col-span-6">
            <label for="house" class="text-sm font-medium text-slate-900">House</label>
            <select
                class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                name="house" id="" wire:model.live="house">
                <option value="">All</option>
                @foreach ($categories['houses'] as $house)
                    <option value="{{ $house }}">{{ $house }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-col col-span-6">
            <div for="tutor" class="text-sm font-medium text-slate-900 flex flex-row justify-between">
                Tutor
                <label for="whanauClassToggle"
                    class="text-sm font-medium text-slate-900 flex flex-row border-1 border-slate-400 rounded px-1">
                    Whanau Only
                    <input id="whanauClassToggle" type="checkbox" wire:click='toggleWhanauClassesOnly' class="mx-1">
                </label>
            </div>

            <select
                class="disabled:opacity-10 mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                name="tutor" id="" wire:model.live="tutor" @disabled($whanauOnly)>
                <option value="">All</option>
                @foreach ($categories['tutors'] as $tutor)
                    <option value="{{ $tutor }}">{{ $tutor }}</option>
                @endforeach
            </select>
        </div>


        <div class="flex flex-col col-span-4">
            <label for="gender" class="text-sm font-medium text-slate-900">Gender</label>
            <select
                class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                name="gender" id="" wire:model.live="gender">
                <option value="">All</option>
                @foreach ($categories['genders'] as $gender)
                    <option value="{{ $gender }}"> {{ $gender }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col col-span-4">
            <label for="yearLevel" class="text-sm font-medium text-slate-900">Year Level</label>
            <select
                class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                name="yearLevel" id="" wire:model.live="yearLevel">
                <option value="">All</option>
                @foreach ($categories['yearLevels'] as $yearLevel)
                    <option value="{{ $yearLevel }}"> {{ $yearLevel }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-col col-span-4">
            <label for="ethnicity" class="text-sm font-medium text-slate-900">Ethnicity</label>
            <select
                class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                name="ethnicity" id="" wire:model.live="ethnicity">
                <option value=''>All</option>
                @foreach ($categories['ethnicities'] as $ethnicity)
                    <option value="{{ $ethnicity }}">{{ $ethnicity }}</option>
                @endforeach
            </select>
        </div>


        <div class="flex flex-col col-span-6">
            <label for="teacher" class="text-sm font-medium text-slate-900">By Teacher</label>
            <select
                class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                name="teacher" id="" wire:model.live="teacher">
                <option value=''>All</option>
                @foreach ($categories['teachers'] as $teacher)
                    <option value="{{ $teacher }}">{{ $teacher }}</option>
                @endforeach
            </select>
        </div>


        <div class="flex flex-col col-span-6">
            <label for="where" class="text-sm font-medium text-slate-900">Location</label>
            <select
                class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                name="where" id="" wire:model.live="location">
                <option value=''>All</option>
                @foreach ($categories['locations'] as $location)
                    <option value="{{ $location }}">{{ $location }}</option>
                @endforeach
            </select>
        </div>


        <div class="flex flex-col col-span-6">
            <label for="motivation" class="text-sm font-medium text-slate-900">Motivation</label>
            <select
                class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                name="motivation" id="" wire:model.live="motivation">
                <option value=''>All</option>
                @foreach ($categories['motivations'] as $motivation)
                    <option value="{{ $motivation }}">{{ $motivation }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col col-span-6">
            <label for="consequence" class="text-sm font-medium text-slate-900">Consequence</label>
            <select
                class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                name="consequence" id="" wire:model.live="consequence">
                <option value=''>All</option>
                @foreach ($categories['consequences'] as $consequence)
                    <option value="{{ $consequence }}">{{ $consequence }}</option>
                @endforeach
            </select>
        </div>



        <div class="flex flex-col col-span-3">
            <label for="period" class="text-sm font-medium text-slate-900">Period</label>
            <select
                class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                name="period" id="" wire:model.live="period">
                <option value="">All</option>
                @foreach ($categories['periods'] as $period)
                    <option value="{{ $period }}"> {{ $period }}</option>
                @endforeach
            </select>
        </div>



        <div class="flex flex-col col-span-3">
            <label for="day" class="text-sm font-medium text-slate-900">Day</label>
            <select
                class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                name="day" id="" wire:model.live="day">
                <option value="">All</option>
                @foreach ($categories['days'] as $day)
                    <option value="{{ $day }}"> {{ $day }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col col-span-3">
            <label for="week" class="text-sm font-medium text-slate-900">Week /Year</label>
            <select
                class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                name="week" id="" wire:model.live="week">
                <option value="">All</option>
                @foreach ($categories['weeks'] as $week)
                    <option value="{{ $week }}"> {{ $week }}</option>
                @endforeach
            </select>
        </div>


        <div class="flex flex-col col-span-3">
            <label for="month" class="text-sm font-medium text-slate-900">Month</label>
            <select
                class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                name="month" id="" wire:model.live="month">
                <option value="">All</option>
                @foreach ($categories['months'] as $month)
                    <option value="{{ $month }}"> {{ $month }}</option>
                @endforeach
            </select>
        </div>



        <div class="flex flex-col col-span-12">
            <label for="reason" class="text-sm font-medium text-slate-900">Ignore reason</label>
            {{-- <select
                class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                name="reason" id="" wire:model.live="reason" multiple>
                <option value=''>All</option>
                @foreach ($categories['reasons'] as $reason)
                    <option value="{{ $reason }}"> {{ $reason }}</option>
                @endforeach
            </select> --}}

            <fieldset
                class="flex gap-4 flex-row justify-start flex-wrap mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 flex justify-around">
                @foreach ($categories['reasons'] as $reason)
                    <label for="reason-{{ $reason }}"
                        class="text-sm font-medium flex items-center gap-2 w-4/10">
                        <input id="reason-{{ $reason }}" type="checkbox" value="{{ $reason }}"
                            wire:model.live.debounce.1500ms="reason"
                            class="block rounded-md px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                        <span>{{ $reason }}</span>
                    </label>
                @endforeach
            </fieldset>
        </div>


    </div>

    <div style="position: relative"
        class="flex-auto charts grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-3 gap-2 auto-rows-max  h-screen overflow-y-scroll">
        <button id="sidebarToggle"
            class="absolute -left-2 top-1/7 bg-red-500 rounded-full rounded-l-none p-2 border-1 border-l-0">
            hide</button>
        <div class="chart-container bg-white shadow-md p-2">
            <h2>By type</h2>
            <canvas id="myChart"></canvas>
        </div>

        <div class="chart-container bg-white shadow-md p-2">
            <h2>By reason</h2>
            <canvas id="myChart2"></canvas>
        </div>

        <div class="chart-container bg-white shadow-md p-2">
            <h2>By Where</h2>
            <canvas id="myChart5"></canvas>
        </div>


        <div class="chart-container bg-white shadow-md p-2">
            <h2>By Motivation</h2>
            <canvas id="myChart3"></canvas>
        </div>

        <div class="chart-container bg-white shadow-md p-2">
            <h2>By Consequence</h2>
            <canvas id="myChart4"></canvas>
        </div>

        <div class="chart-container bg-white shadow-md p-2">
            <h2>Period</h2>
            <canvas id="myChart15"></canvas>
        </div>

        <div class="chart-container bg-white shadow-md p-2">
            <h2>Day</h2>
            <canvas id="myChart11"></canvas>
        </div>

        <div class="chart-container bg-white shadow-md p-2">
            <h2>Week</h2>
            <canvas id="myChart12"></canvas>
        </div>

        <div class="chart-container bg-white shadow-md p-2">
            <h2>Month</h2>
            <canvas id="myChart13"></canvas>
        </div>



        <div class="chart-container bg-white shadow-md p-2">
            <h2>Ethnicity</h2>
            <canvas id="myChart7"></canvas>
        </div>

        <div class="chart-container bg-white shadow-md p-2">
            <h2>Year Level</h2>
            <canvas id="myChart10"></canvas>
        </div>


        <div class="chart-container bg-white shadow-md p-2">
            <h2>Gender</h2>
            <canvas id="myChart16"></canvas>
        </div>

        <div class="chart-container bg-white shadow-md p-2">
            <h2>By Teacher</h2>
            <canvas id="myChart6"></canvas>
        </div>

        <div class="chart-container bg-white shadow-md p-2">
            <h2>House</h2>
            <canvas id="myChart9"></canvas>
        </div>


        <div class="chart-container bg-white shadow-md p-2">
            <h2>Top <span wire:text="topX"></span> Students</h2>
            <div class="flex flex-col col-span-6">
                <select
                    class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 print:hidden"
                    name="topX" id="" wire:model.live="topX">
                    @foreach ([5, 10, 25, 50] as $topX)
                        <option value="{{ $topX }}">{{ $topX }}</option>
                    @endforeach
                </select>
            </div>
            <canvas id="myChart14"></canvas>
        </div>


        <div class="chart-container bg-white shadow-md p-2">
            <h2>Tutor</h2>
            <canvas id="myChart8"></canvas>
        </div>


    </div>

    @script
        <script>
            Chart.defaults.global = {
                responsive: true,
                maintainAspectRatio: true,

            };
            Chart.defaults.font.size = 10
            Chart.defaults.font.lineHeight = 1
            Chart.defaults.font.weight = 'bold'

            const ctx = document.getElementById('myChart');
            const c1 = new Chart(ctx, {
                type: 'bar',
                data: {
                    datasets: [{
                        label: 'Type',
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


            const ctx2 = document.getElementById('myChart2');
            const c2 = new Chart(ctx2, {
                type: 'bar',
                data: {
                    datasets: [{
                        label: 'Reason',
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                minRotation: 60, // This will rotate the labels to 90 degrees
                            }
                        }
                    }
                }
            });

            const ctx3 = document.getElementById('myChart3');
            const c3 = new Chart(ctx3, {
                type: 'bar',
                data: {
                    datasets: [{
                        label: 'Motivation',
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                minRotation: 60, // This will rotate the labels to 90 degrees
                            }
                        }
                    }
                }
            });


            const ctx4 = document.getElementById('myChart4');
            const c4 = new Chart(ctx4, {
                type: 'bar',
                data: {
                    datasets: [{
                        label: 'Consequence',
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                minRotation: 60, // This will rotate the labels to 90 degrees
                            }
                        }
                    }
                }
            });






            const ctx5 = document.getElementById('myChart5');
            const c5 = new Chart(ctx5, {
                type: 'bar',
                data: {
                    datasets: [{
                        label: 'Where',
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                minRotation: 60, // This will rotate the labels to 90 degrees
                            }
                        }
                    }
                }
            });


            const ctx6 = document.getElementById('myChart6');
            const c6 = new Chart(ctx6, {
                type: 'bar',
                data: {
                    datasets: [{
                        label: 'Teacher',
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                minRotation: 60, // This will rotate the labels to 90 degrees
                            }
                        }
                    }
                }
            });



            const ctx7 = document.getElementById('myChart7');
            const c7 = new Chart(ctx7, {
                type: 'bar',
                data: {
                    datasets: [{
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
            const c8 = new Chart(ctx8, {
                type: 'bar',
                data: {
                    datasets: [{
                        label: 'Tutor',
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

            const ctx9 = document.getElementById('myChart9');
            const c9 = new Chart(ctx9, {
                type: 'bar',
                options: {
                    plugins: {
                        legend: {
                            display: false

                        }
                    }
                }
            });


            const ctx10 = document.getElementById('myChart10');
            const c10 = new Chart(ctx10, {
                type: 'bar',
                data: {
                    datasets: [{
                        label: 'Year Level',
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


            const ctx11 = document.getElementById('myChart11');
            const c11 = new Chart(ctx11, {
                type: 'bar',
                data: {
                    datasets: [{
                        label: 'Day',
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

            const ctx12 = document.getElementById('myChart12');
            const c12 = new Chart(ctx12, {
                type: 'bar',
                data: {
                    datasets: [{
                        label: 'Week',
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

            const ctx13 = document.getElementById('myChart13');
            const c13 = new Chart(ctx13, {
                type: 'bar',
                data: {
                    datasets: [{
                        label: 'Month',
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

            const ctx14 = document.getElementById('myChart14');
            const c14 = new Chart(ctx14, {
                type: 'bar',
                data: {
                    datasets: [{
                        label: 'by Name',
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
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });



            const ctx15 = document.getElementById('myChart15');
            const c15 = new Chart(ctx15, {
                type: 'bar',
                data: {
                    datasets: [{
                        label: "Period",
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                }
            });

            const ctx16 = document.getElementById('myChart16');
            const c16 = new Chart(ctx16, {
                type: 'bar',
                data: {
                    datasets: [{
                        label: "Gender",
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



            $wire.on('dataUpdate', data => {
                let chartDatas = {
                    //'allTypes': c1,
                    'reasons': c2,
                    'motivations': c3,
                    'consequences': c4,
                    'locations': c5,
                    'teachers': c6,
                    //'ethnicities': c7,
                    //'tutors': c8,
                    //'houses': c9,
                    'yearLevels': c10,
                    'days': c11,
                    'weeks': c12,
                    'months': c13,
                    //'names': c14,
                    'periods': c15,
                    //'genders': c16
                };

                Object.keys(chartDatas).forEach(key => {
                    chartDatas[key].data.datasets[0].data = data[0][key];
                });

                Object.values(chartDatas).forEach(c => {
                    c.update();
                });


                let moreDatas = {
                    'allTypes': c1,
                    'ethnicities': c7,
                    'houses': c9,
                    'genders': c16,
                    'tutors': c8,
                    'names': c14
                }
                Object.keys(moreDatas).forEach(key => {
                    moreDatas[key].data.datasets = [data[0][key]];
                    moreDatas[key].update()

                });
            });
        </script>
    @endscript

    <script>
        document.getElementById("sidebarToggle").addEventListener("click", function() {
            document.getElementById("sidebar").classList.toggle("-ml-100");
            document.getElementById("sidebar").classList.toggle("ml-0");
        });
    </script>
</div>
