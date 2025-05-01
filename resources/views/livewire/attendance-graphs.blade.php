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

    <h2>Attendances</h2>




    <div id="sidebar" style="transition: margin .3s"
        class="ml-0 w-0 w-100 grow-0 shrink-0 print:hidden bg-slate-100 p-2 mb-4 grid grid-cols-12 gap-2 auto-rows-max h-screen overflow-y-scroll">


        <div wire:click.prevent="resetFields"
            class="col-span-12 flex justify-center items-center bg-slate-950 text-white py-1">
            reset</div>
        <div class="flex flex-col col-span-6">
            <label class="text-sm font-medium text-slate-900" for="name">Student ID</label>
            <input type="text" wire:model.live.debounce.1000ms="studentId"
                class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
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

    </div>

    <div style="position: relative"
        class="flex-auto charts grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-3 gap-2 auto-rows-max  h-screen overflow-y-scroll">
        <button id="sidebarToggle"
            class="absolute -left-2 top-1/7 bg-red-500 rounded-full rounded-l-none p-2 border-1 border-l-0">
            hide</button>


        <div class="chart-container bg-white shadow-md p-2">
            <h2>Total</h2>
            <canvas id="myChart6"></canvas>
        </div>

        <div class="chart-container bg-white shadow-md p-2">
            <h2>European</h2>
            <canvas id="myChart1"></canvas>
        </div>

        <div class="chart-container bg-white shadow-md p-2">
            <h2>Maori</h2>
            <canvas id="myChart2"></canvas>
        </div>

        <div class="chart-container bg-white shadow-md p-2">
            <h2>Pasifika</h2>
            <canvas id="myChart3"></canvas>
        </div>

        <div class="chart-container bg-white shadow-md p-2">
            <h2>MELAA</h2>
            <canvas id="myChart4"></canvas>
        </div>

        <div class="chart-container bg-white shadow-md p-2">
            <h2>Asian</h2>
            <canvas id="myChart5"></canvas>
        </div>

        <div class="chart-container bg-white shadow-md p-2">
            <h2>Year 9</h2>
            <canvas id="myChart9"></canvas>
        </div>
        <div class="chart-container bg-white shadow-md p-2">
            <h2>Year 10</h2>
            <canvas id="myChart10"></canvas>
        </div>
        <div class="chart-container bg-white shadow-md p-2">
            <h2>Year 11</h2>
            <canvas id="myChart11"></canvas>
        </div>
        <div class="chart-container bg-white shadow-md p-2">
            <h2>Year 12</h2>
            <canvas id="myChart12"></canvas>
        </div>
        <div class="chart-container bg-white shadow-md p-2">
            <h2>Year 13</h2>
            <canvas id="myChart13"></canvas>
        </div>


        <div class="chart-container bg-white shadow-md p-2">
            <h2>Male</h2>
            <canvas id="myChartM"></canvas>
        </div>
        <div class="chart-container bg-white shadow-md p-2">
            <h2>Female</h2>
            <canvas id="myChartF"></canvas>
        </div>


        <div class="container mt-5">
            {{-- <h2>Attendance Counts for Student: {{ $result->first()->student_id }}</h2> --}}
            <table class="table border border-black text-black text-center">
                <colgroup>
                    <col />
                    <col span="7" class="group-1 bg-green-400">
                    <col span="4" class="group-2 bg-indigo-400">
                    <col span="4" class="group-3 bg-rose-400">
                </colgroup>
                <thead>
                    <tr class="border border-black  bg-slate-300">
                        <th>Slot</th>
                        <th>P Count</th>
                        <th>L Count</th>
                        <th>A Count</th>
                        <th>V Count</th>
                        <th>N Count</th>
                        <th>D Count</th>
                        <th>Q Count</th>
                        <th>M Count</th>
                        <th>U Count</th>
                        <th>X Count</th>
                        <th>J Count</th>
                        <th>Question Count</th>
                        <th>T Count</th>
                        <th>G Count</th>
                        <th>E Count</th>
                    </tr>
                </thead>
                {{-- <tfoot>
                    <td></td>
                    <td colspan=7>{{ $totals['categories']['present'] }}</td>
                    <td colspan=4>{{ $totals['categories']['justified'] }}</td>
                    <td colspan=4>{{ $totals['categories']['unjustified'] }}</td>
                </tfoot>
                <tbody>
                    @foreach ($totals['slots'] as $slot => $codes)
                        <tr class="border border-black ">
                            <td>Slot {{ $slot }}</td>
                            @foreach ($codes as $code)
                                <td>{{ $code }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody> --}}
            </table>

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


            const ctx1 = document.getElementById("myChart1").getContext("2d");
            const ctx2 = document.getElementById("myChart2").getContext("2d");
            const ctx3 = document.getElementById("myChart3").getContext("2d");
            const ctx4 = document.getElementById("myChart4").getContext("2d");
            const ctx5 = document.getElementById("myChart5").getContext("2d");
            const ctx6 = document.getElementById("myChart6").getContext("2d");

            const ctxM = document.getElementById("myChartM").getContext("2d");
            const ctxF = document.getElementById("myChartF").getContext("2d");

            const ctx9 = document.getElementById("myChart9").getContext("2d");
            const ctx10 = document.getElementById("myChart10").getContext("2d");
            const ctx11 = document.getElementById("myChart11").getContext("2d");
            const ctx12 = document.getElementById("myChart12").getContext("2d");
            const ctx13 = document.getElementById("myChart13").getContext("2d");

            const c1 = new Chart(ctx1, {
                type: 'line', // or 'line', 'radar', etc.
                data: {
                    labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    datasets: @json($chartData['European'])
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Attendance by Slot'
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                        },
                        y: {
                            display: true,
                            type: 'logarithmic',
                        }
                    }
                }
            });

            const c2 = new Chart(ctx2, {
                type: 'line', // or 'line', 'radar', etc.
                data: {
                    labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 0],
                    datasets: @json($chartData['Māori'])
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Attendance by Slot'
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                        },
                        y: {
                            display: true,
                            type: 'logarithmic',
                        }
                    }
                }
            });

            const c3 = new Chart(ctx3, {
                type: 'line', // or 'line', 'radar', etc.
                data: {
                    labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 0],
                    datasets: @json($chartData['Pasifika'])
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Attendance by Slot'
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                        },
                        y: {
                            display: true,
                            type: 'logarithmic',
                        }
                    }
                }
            });

            const c4 = new Chart(ctx4, {
                type: 'line', // or 'line', 'radar', etc.
                data: {
                    labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 0],
                    datasets: @json($chartData['MELAA'])
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Attendance by Slot'
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                        },
                        y: {
                            display: true,
                            type: 'logarithmic',
                        }
                    }
                }
            });

            const c5 = new Chart(ctx5, {
                type: 'line', // or 'line', 'radar', etc.
                data: {
                    labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 0],
                    datasets: @json($chartData['Asian'])
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Attendance by Slot'
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                        },
                        y: {
                            display: true,
                            type: 'logarithmic',
                        }
                    }
                }
            });

            const c6 = new Chart(ctx6, {
                type: 'line', // or 'line', 'radar', etc.
                data: {
                    labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 0],
                    datasets: @json($chartData['Total'])
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Attendance by Slot'
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                        },
                        y: {
                            display: true,
                            type: 'logarithmic',
                        }
                    }
                }
            });

            const cM = new Chart(ctxM, {
                type: 'line',
                data: {
                    labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 0],
                    datasets: @json($chartData['M'])
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Attendance by Slot'
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                        },
                        y: {
                            display: true,
                            type: 'logarithmic',
                        }
                    }
                }
            });

            const cF = new Chart(ctxF, {
                type: 'line',
                data: {
                    labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 0],
                    datasets: @json($chartData['F'])
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Attendance by Slot'
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                        },
                        y: {
                            display: true,
                            type: 'logarithmic',
                        }
                    }
                }
            });

            const c9 = new Chart(ctx9, {
                type: 'line',
                data: {
                    labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 0],
                    datasets: @json($chartData[9])
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Attendance by Slot'
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                        },
                        y: {
                            display: true,
                            type: 'logarithmic',
                        }
                    }
                }
            });
            const c10 = new Chart(ctx10, {
                type: 'line',
                data: {
                    labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 0],
                    datasets: @json($chartData[10])
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Attendance by Slot'
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                        },
                        y: {
                            display: true,
                            type: 'logarithmic',
                        }
                    }
                }
            });
            const c11 = new Chart(ctx11, {
                type: 'line',
                data: {
                    labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 0],
                    datasets: @json($chartData[11])
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Attendance by Slot'
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                        },
                        y: {
                            display: true,
                            type: 'logarithmic',
                        }
                    }
                }
            });
            const c12 = new Chart(ctx12, {
                type: 'line',
                data: {
                    labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 0],
                    datasets: @json($chartData[12])
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Attendance by Slot'
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                        },
                        y: {
                            display: true,
                            type: 'logarithmic',
                        }
                    }
                }
            });
            const c13 = new Chart(ctx13, {
                type: 'line',
                data: {
                    labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 0],
                    datasets: @json($chartData[13])
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Attendance by Slot'
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                        },
                        y: {
                            display: true,
                            type: 'logarithmic',
                        }
                    }
                }
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
