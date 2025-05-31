<x-layouts.main-content :title="$teacher->name"
                        heading="View Teacher"
                        :subheading="$teacher->user->name">

<div class="flex flex-col space-y-6">
        <div class="max-full">
            <section>
                <div class="static sm:absolute -top-2 right-0 flex flex-wrap justify-start sm:justify-end items-center gap-4">
                    <flux:button variant="primary" href="{{ route('teachers.create', ['teacher' => $teacher]) }}">New</flux:button>
                    <flux:button href="{{ route('teachers.edit', ['teacher' => $teacher]) }}">Edit</flux:button>
                    <form method="POST" action="{{ route('teachers.destroy', ['teacher' => $teacher]) }}">
                        @csrf
                        @method('DELETE')
                        <flux:button variant="danger" type="submit">Delete</flux:button>
                    </form>
                </div>
                <div class="mt-6 space-y-4">
                    @include('teachers.partials.fields', ['mode' => 'show'])
                </div>
                <h3 class="pt-16 pb-4 text-2xl font-medium text-gray-900 dark:text-gray-100">
                    Disciplines
                </h3>
                <x-disciplines.table :disciplines="$teacher->disciplines"
                                     :showView="true"
                                     :showEdit="false"
                                     :showDelete="false"
                                     :showAddToCart="true"
                                     :showRemoveFromCart="false"
             
                                     class="pt-4"
                />
            </section>
        </div>
    </div>
</x-layouts.main-content>
