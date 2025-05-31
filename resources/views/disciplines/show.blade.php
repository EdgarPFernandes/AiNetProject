<x-layouts.main-content :title="$discipline->name"
                        heading="View Discipline"
                        :subheading="$discipline->name">
    <div class="flex flex-col space-y-6">
        <div class="max-full">
            <section>
                <div class="static sm:absolute -top-2 right-0 flex flex-wrap justify-start sm:justify-end items-center gap-4">
                    <flux:button variant="primary" href="{{ route('disciplines.create', ['discipline' => $discipline]) }}">New</flux:button>
                    <flux:button href="{{ route('disciplines.edit', ['discipline' => $discipline]) }}">Edit</flux:button>
                    <form method="POST" action="{{ route('disciplines.destroy', ['discipline' => $discipline]) }}">
                        @csrf
                        @method('DELETE')
                        <flux:button variant="danger" type="submit">Delete</flux:button>
                    </form>
                </div>
                <div class="mt-6 space-y-4">
                    @include('disciplines.partials.fields', ['mode' => 'show'])
                </div>
                <h3 class="pt-16 pb-4 text-2xl font-medium text-gray-900 dark:text-gray-100">
                    Teachers
                </h3>
                <x-teachers.table :teachers="$discipline->teachers"
                                  :showView="true"
                                  :showEdit="false"
                                  :showDelete="false"
                                  class="pt-4"
                />
            </section>
        </div>
    </div>
</x-layouts.main-content>
