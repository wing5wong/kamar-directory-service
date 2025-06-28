<div>
    <style>
        table,
        tr,
        td {
            border: 1px solid;
            border-collapse: collapse;
            min-width: 1em;
            text-align: center;
            font-size: 10pt;
        }

        td.attendance-\.,
        td.attendance- {
            background: #ccc;
        }

        td.attendance-T,
        td.attendance-\? {
            background: #f34747;
        }

        td.attendance-M,
        td.attendance-J,
        td.attendance-D {
            background: #f34747;
        }

        td.attendance-Q,
        td.attendance-N {
            background: #91f2bd;
        }

        td.attendance-L {
            background: #88d7fc;
        }

        td.attendance-E {
            background: #fcd388;
        }
    </style>
    <input type="text" wire:model.live='studentId'>

    @if ($student)
        {{ $student->fullName }}

        <div style="display: flex; width: 100%;">
            <div style="flex: 1; margin: 2em">
                <h3>Attendance</h3>
                <table style="width: 100%; border: 1px solid;">
                    <tr>
                        <td>Week</td>
                        <td colspan="10">Monday</td>
                        <td colspan="10">Tuesday</td>
                        <td colspan="10">Wednesday</td>
                        <td colspan="10">Thursday</td>
                        <td colspan="10">Friday</td>
                    </tr>
                    <tr>
                        <td></td>
                        @for ($i = 1; $i <= 5; $i++)
                            @for ($j = 1; $j <= 10; $j++)
                                <td>{{ $j }}</td>
                            @endfor
                        @endfor
                    </tr>

                    @foreach ($student->attendances->sortBy(function ($att) {
            // Composite key: DESC week, ASC weekday
            return sprintf('%03d-%d', 999 - $att->date->weekOfYear, $att->date->dayOfWeekIso);
        })->groupBy(function ($att) {
            return $att->date->weekOfYear;
        }) as $week => $days)
                        <tr>
                            <td>{{ $week }} </td>
                            @foreach ($days as $attendance)
                                @for ($i = 1; $i <= 10; $i++)
                                    <td class="attendance-{{ $attendance->{'slot_' . $i} }}">
                                        {{ $attendance->{'slot_' . $i} }}</td>
                                @endfor
                            @endforeach
                        </tr>
                    @endforeach
                </table>
            </div>
            <div style="flex: 1; margin: 2em">
                <h3>Pastorals <small>{{ $student->pastoralRecords->count() }}
                        {{ Str::plural('record', $student->pastoralRecords->count()) }} </small></h3>
                <table style="width: 100%; border: 1px solid;">
                    @foreach ($student->pastoralRecords as $pastoralEntry)
                        <tr>
                            <td>{{ $pastoralEntry->dateevent }} </td>
                            <td>{{ $pastoralEntry->type }}</td>
                            <td>{{ $pastoralEntry->reason }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div style="flex: 1; margin: 2em">
                <h3>Recognitions <small>{{ $student->recognitions->count() }}
                        {{ Str::plural('record', $student->recognitions->count()) }} </small></h3>
                <table style="width: 100%; border: 1px solid;">
                    @foreach ($student->recognitions as $recognition)
                        <tr>
                            <td>{{ $recognition->date }} </td>
                            <td>{{ $recognition->subject }}</td>
                            <td>{{ $recognition->points }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td>Total:</td>
                        <td>{{ $student->recognitions->sum('points') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    @endif

</div>
