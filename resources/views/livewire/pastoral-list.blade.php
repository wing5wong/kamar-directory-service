<div>

    <h1>Students with Major pastorals</h1>
    <div class="filters">
        <div class="field">
            <span>Date</span>
            <input type="date" wire:model.live="date"> {{ $this->carbonDate->dayName }}
        </div>
    </div>

    <hr>
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
            padding: .3em 1em;
        }

        tr>td:first-child,
        tr>td:last-child {

            text-align: center;
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
            <h3>Pastorals - {{ $pastorals->count() }} entries found</h3>
            <table border=1>
                <tr>
                    {{-- <th>Date</th> --}}
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Teacher</th>
                    {{-- <th>Type</th> --}}
                    <th>Reason</th>
                    <th>Action</th>
                    {{-- <th>House</th> --}}
                    <th>Exclude</th>
                </tr>
                @foreach ($pastorals->groupBy('student.house') as $house => $pastoralEntries)
                    @foreach ($pastoralEntries as $pastoral)
                        <tr class="house-{{ $pastoral->student->house }}">
                            {{-- <td>{{ $pastoral->dateevent->toDateString() }}</td> --}}
                            <td>{{ $pastoral->student_id }}</td>
                            <td>{{ $pastoral->student->fullName }}</td>
                            {{-- <td>{{ $pastoral->type }}</td> --}}
                            <td>{{ $pastoral->teacher }}</td>
                            <td>{{ $pastoral->reason }}</td>
                            <td>{{ $pastoral->action1 }}</td>
                            {{-- <td>{{ $pastoral->student->house }}</td> --}}

                            <td><input type="checkbox" wire:model.live="excludedIds" value="{{ $pastoral->student_id }}">
                            </td>

                        </tr>
                    @endforeach
                @endforeach
            </table>
        </div>

        <div class="right">
            <h3>{{ $this->uniqueEntries }} Students</h3>
            <button id="copyButton">Copy IDs</button>
            <textarea width="20%" rows="20" id="out" readonly wire:model="studentIDList"></textarea>

            <h3>Excluded actions</h3>
            <ul>
                @foreach ($this->excludedActions as $excludes)
                    <li>{{ $excludes }}</li>
                @endforeach
            </ul>
        </div>

        <div class="right">
            <h3>Students with 3+ Truant/? in last 3 days - {{ $manyLates->groupBy('student_id')->count() }} entries
                found</h3>
            <table border=1>
                @foreach ($manyLates->groupBy('student_id') as $id => $entries)
                    <tr>

                        @foreach ($entries as $entry)
                    <tr>
                        @if ($loop->first)
                            <td rowspan="{{ $entries->count() }}">{{ $entry->student->fullName }}</td>
                        @endif
                        <td>
                            {{ $entry->date }} - {{ $entry->codes }}
                        </td>
                    </tr>
                @endforeach
                </tr>
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
