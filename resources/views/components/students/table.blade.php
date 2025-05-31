<div>
    <table class="table-auto border-collapse">
        <thead>
        <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
            <th class="px-2 py-2 text-right">Number</th>
            <th class="px-2 py-2 text-left">Name</th>
            @if($showCourse)
                <th class="px-2 py-2 text-left  hidden md:table-cell">Course</th>
            @endif

            <th class="px-2 py-2 text-left hidden md:table-cell">Email</th>
            @if($showView)
                <th></th>
            @endif
            @if($showEdit)
                <th></th>
            @endif
            @if($showDelete)
                <th></th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach ($students as $student)
            <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                <td class="px-2 py-2 text-right">{{ $student->number }}</td>
                <td class="px-2 py-2 text-left">{{ $student->user->name }}</td>
                @if($showCourse)
                    <td class="px-2 py-2 text-left  hidden md:table-cell">{{ $student?->courseRef?->name }}</td>
                @endif
                <td class="px-2 py-2 text-left hidden md:table-cell">{{ $student->user->email }}</td>
                @if($showView)
                    <td class="ps-2 px-0.5">
                        <a href="{{ route('students.show', ['student' => $student]) }}">
                            <flux:icon.eye class="size-5 hover:text-green-600" />
                        </a>
                    </td>
                @endif
                @if($showEdit)
                    <td class="px-0.5">
                        <a href="{{ route('students.edit', ['student' => $student]) }}">
                            <flux:icon.pencil-square class="size-5 hover:text-blue-600" />
                        </a>
                    </td>
                @endif
                @if($showDelete)
                    <td class="px-0.5">
                        <form method="POST" action="{{ route('students.destroy', ['student' => $student]) }}" class="flex items-center">
                            @csrf
                            @method('DELETE')
                            <button type="submit">
                                <flux:icon.trash class="size-5 hover:text-red-600" />
                            </button>
                        </form>
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
