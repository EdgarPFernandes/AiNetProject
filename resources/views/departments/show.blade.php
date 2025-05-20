<x-layouts.main-content :title="$department->name"
                        heading="View Department"
                        :subheading="$department->name">
    <div class="flex flex-col space-y-6">
        <div class="max-full">
            <section>
                <div class="static sm:absolute -top-2 right-0 flex flex-wrap justify-start sm:justify-end items-center gap-4">
                    @can('create', App\Models\Department::class)
                        <flux:button variant="primary" href="{{ route('departments.create') }}">New</flux:button>
                    @endcan
                    @can('update', $department)
                        <flux:button href="{{ route('departments.edit', ['department' => $department]) }}">Edit</flux:button>
                    @endcan
                    @can('delete', $department)
                        <form method="POST" action="{{ route('departments.destroy', ['department' => $department]) }}">
                            @csrf
                            @method('DELETE')
                            <flux:button variant="danger" type="submit">Delete</flux:button>
                        </form>
                    @endcan
                </div>
                <div class="mt-6 space-y-4">
                    @include('departments.partials.fields', ['mode' => 'show'])
                </div>
                @can('viewAny', App\Models\Teacher::class)
                    <h3 class="pt-16 pb-4 text-2xl font-medium text-gray-900 dark:text-gray-100">
                        Teachers
                    </h3>
                    <x-teachers.table :teachers="$department->teachers"
                                    :showDepartment="false"
                                    :showView="true"
                                    :showEdit="false"
                                    :showDelete="false"
                                    class="pt-4"
                    />
                @endcan
            </section>
        </div>
    </div>
</x-layouts.main-content>
