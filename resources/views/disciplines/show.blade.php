<x-layouts.main-content :title="$discipline->name"
                        heading="View Discipline"
                        :subheading="$discipline->name">
    <div class="flex flex-col space-y-6">
        <div class="max-full">
            <section>
                <div class="static sm:absolute -top-2 right-0 flex flex-wrap justify-start sm:justify-end items-center gap-4">
                    @can('create', App\Models\Discipline::class)
                        <flux:button variant="primary" href="{{ route('disciplines.create') }}">New</flux:button>
                    @endcan
                    @can('update', $discipline)
                        <flux:button href="{{ route('disciplines.edit', ['discipline' => $discipline]) }}">Edit</flux:button>
                    @endcan
                    @can('delete', $discipline)
                        <form method="POST" action="{{ route('disciplines.destroy', ['discipline' => $discipline]) }}">
                            @csrf
                            @method('DELETE')
                            <flux:button variant="danger" type="submit">Delete</flux:button>
                        </form>
                    @endcan

                    @can('use-cart')
                        <form method="POST" action="{{ route('cart.add', ['discipline' => $discipline]) }}">
                            @csrf
                            <flux:button variant="primary" type="submit">Add to cart</flux:button>
                        </form>
                    @endcan

                </div>
                <div class="mt-6 space-y-4">
                    @include('disciplines.partials.fields', ['mode' => 'show'])
                </div>
                @can('viewAny', App\Models\Teacher::class)
                    <h3 class="pt-16 pb-4 text-2xl font-medium text-gray-900 dark:text-gray-100">
                        Teachers
                    </h3>
                    <x-teachers.table :teachers="$discipline->teachers"
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
