<x-layouts.main-content :title="$department->name"
                        heading="Edit Department"
                        :subheading="$department->name">
    <div class="flex flex-col space-y-6">
        <div class="max-full">
            <section>
                <div class="static sm:absolute -top-2 right-0 flex flex-wrap justify-start sm:justify-end items-center gap-4">
                    @can('create', App\Models\Department::class)
                        <flux:button variant="primary" href="{{ route('departments.create') }}">New</flux:button>
                    @endcan
                    @can('view', $department)
                        <flux:button href="{{ route('departments.show', ['department' => $department]) }}">View</flux:button>
                    @endcan
                    @can('delete', $department)
                        <form method="POST" action="{{ route('departments.destroy', ['department' => $department]) }}">
                            @csrf
                            @method('DELETE')
                            <flux:button variant="danger" type="submit">Delete</flux:button>
                        </form>
                    @endcan
                </div>
                <form method="POST" action="{{ route('departments.update', ['department' => $department]) }}">
                    @csrf
                    @method('PUT')
                    <div class="mt-6 space-y-4">
                        @include('departments.partials.fields', ['mode' => 'edit'])
                    </div>
                    <div class="flex mt-6">
                        <flux:button variant="primary" type="submit"  class="uppercase">Save</flux:button>
                        <flux:button class="uppercase ms-4" href="{{ url()->full() }}">Cancel</flux:button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-layouts.main-content>
