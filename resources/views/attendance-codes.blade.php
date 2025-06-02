<table border=1>
    @foreach ($attendances as $attendance)
        <tr>
            <td>{{ $attendance->student_id }}</td>
            <td>{{ $attendance->date }}</td>
            <td>{{ $attendance->codes }}</td>
            <td>{{ $attendance->student->fullName }}</td>
        </tr>
    @endforeach
</table>
