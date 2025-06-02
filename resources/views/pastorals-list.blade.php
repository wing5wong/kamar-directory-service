<table border=1>
    @foreach ($pastorals as $pastoral)
        <tr>
            <td>{{ $pastoral->student_id }}</td>
            <td>{{ $pastoral->dateevent }}</td>
            <td>{{ $pastoral->type }}</td>
            <td>{{ $pastoral->reason }}</td>
            <td>{{ $pastoral->student->fullName }}</td>
        </tr>
    @endforeach
</table>
