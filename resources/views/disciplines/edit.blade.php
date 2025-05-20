<x-layouts.main-content :title="$discipline->name"
                        heading="Edit Discipline"
                        :subheading="$discipline->name">
    <div class="flex flex-col space-y-6">
        <div class="max-full">
            <section>
                <div class="static sm:absolute -top-2 right-0 flex flex-wrap justify-start sm:justify-end items-center gap-4">
                    @can('create', App\Models\Discipline::class)
                        <flux:button variant="primary" href="{{ route('disciplines.create') }}">New</flux:button>
                    @endcan
                    @can('view', $discipline)
                        <flux:button href="{{ route('disciplines.show', ['discipline' => $discipline]) }}">View</flux:button>
                    @endcan
                    @can('delete', $discipline)
                        <form method="POST" action="{{ route('disciplines.destroy', ['discipline' => $discipline]) }}">
                            @csrf
                            @method('DELETE')
                            <flux:button variant="danger" type="submit">Delete</flux:button>
                        </form>
                    @endcan
                </div>
                <form method="POST" action="{{ route('disciplines.update', ['discipline' => $discipline]) }}">
                    @csrf
                    @method('PUT')
                    <div class="mt-6 space-y-4">
                        @include('disciplines.partials.fields', ['mode' => 'edit'])
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
