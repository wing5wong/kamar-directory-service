<div>
    <h1>Students with {{ $tCount }} truants, ? or L
        {{-- or {{ $lCount }} Lates --}}
    </h1>

    <style>
        .container {
            display: flex;
            flex-flow: row;
            width: 100%;
            gap: 2em
        }




        button {
            display: block;
            width: 100%
        }

        table {
            border-collapse: collapse;
        }


        td,
        th {
            padding: .3em 1em
        }

        input {
            font: 15px/24px "Lato", Arial, sans-serif;
            color: #333;
            box-sizing: border-box;
            letter-spacing: 1px;
        }

        .filters {
            display: flex;
            flex-flow: row wrap;
            gap: 2em
        }

        .field {
            display: flex;
            flex-direction: column
        }

        input[type="checkbox"] {
            width: 1.5em;
            height: 1.5em;
            margin: 0;
            border: 1px solid #222;
        }

        .house-Awa {
            background: #bde5f5;
        }

        .house-Maunga {
            background: #f5c1c1;
        }

        .house-Moana {
            background: #f5ecc1;
        }

        .house-Whenua {
            background: #c3f5c1
        }


        @media print {
            .right {
                display: none !important;
            }
        }
    </style>
    <div class="container">
        <div class="left">
            <div class="filters">
                <div class="field">
                    <span>Truants/?/L</span>
                    <input type="number" wire:model.live="tCount" min=0 max=6>
                </div>
                {{-- <div class="field">
                    <span>Lates</span>
                    <input type="number" wire:model.live="lCount" min=0 max=6>
                </div> --}}
                <div class="field">
                    <span>Date</span>
                    <input type="date" wire:model.live="date">
                </div>

                <div class="field">
                    <span>Ignore full day T / ?</span>
                    <input type="checkbox" wire:model.live="ignoreFullDay">
                </div>
            </div>
            <h3>{{ $attendances->count() }} students</h3>
            <hr>
            <table border=1>
                <tr>
                    {{-- <th>Date</th> --}}
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Codes</th>
                    {{-- <th>House</th> --}}
                    {{-- <th>Tutor</th> --}}
                    <th>Exclude</th>
                </tr>
                @foreach ($attendances->groupBy('student.house') as $house => $attendanceEntries)
                    @foreach ($attendanceEntries as $attendance)
                        <tr class="house-{{ $attendance->student->house }}">
                            {{-- <td>{{ $attendance->date }}</td> --}}
                            <td>{{ $attendance->student_id }}</td>
                            <td>{{ $attendance->student->fullName }}</td>
                            <td>{{ $attendance->codes }}</td>
                            {{-- <td>{{ $attendance->student->house }}</td> --}}
                            {{-- <td>{{ $attendance->student->tutor }}</td> --}}
                            <td><input type="checkbox" wire:model.live="excludedIds"
                                    value="{{ $attendance->student_id }}"></td>
                        </tr>
                    @endforeach
                @endforeach
            </table>

        </div>
        <div class="right">
            <div class="right">
                <h3>{{ $attendances->count() - count($excludedIds) }} Students</h3>
                <button id="copyButton">Copy IDs</button>
                <textarea width="20%" rows="20" id="out" readonly wire:model="studentIDList"></textarea>
            </div>
        </div>



        <div class="right">
            <h3>Form Time Late / Absent <small> ({{ $skippedAttendances->count() }} students)</small></h3>

            <table border=1>
                @foreach ($skippedAttendances->groupBy('student.house') as $house => $attendanceEntries)
                    @foreach ($attendanceEntries as $attendance)
                        <tr class="house-{{ $attendance->student->house }}">
                            <td>{{ $attendance->student_id }}</td>
                            <td>{{ $attendance->student->fullName }}</td>
                            <td>{{ $attendance->codes }}</td>
                            <td>{{ $attendance->student->tutor }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </table>
        </div>
        <div class="right">
            <h3>P1 Late / Absent <small> ({{ $p1Lates->count() }} students)</small></h3>
            <table border=1>
                @foreach ($p1Lates->groupBy('student.house') as $house => $attendanceEntries)
                    @foreach ($attendanceEntries as $attendance)
                        <tr class="house-{{ $attendance->student->house }}">
                            <td>{{ $attendance->student_id }}</td>
                            <td>{{ $attendance->student->fullName }}</td>
                            <td>{{ $attendance->codes }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </table>
        </div>
    </div>


    <script>
        const copyButton = document.querySelector('#copyButton');
        const out = document.querySelector('#out');

        if ('clipboard' in navigator) {
            copyButton.addEventListener('click', () => {
                navigator.clipboard.writeText(out.value)
                    .then(() => {
                        console.log('Text copied');
                    })
                    .catch((err) => console.error(err.name, err.message));
            });
        } else {
            copyButton.addEventListener('click', () => {
                const textArea = document.createElement('textarea');
                textArea.value = out.value;
                textArea.style.opacity = 0;
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    const success = document.execCommand('copy');
                    console.log(`Text copy was ${success ? 'successful' : 'unsuccessful'}.`);
                } catch (err) {
                    console.error(err.name, err.message);
                }
                document.body.removeChild(textArea);
            });
        }
    </script>
</div>
