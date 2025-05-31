<x-layouts.main-content :title="$course->name"
                        heading="View Course"
                        :subheading="$course->name">
    <div class="flex flex-col space-y-6">
        <div class="max-full">
            <section>
                <div class="static sm:absolute -top-2 right-0 flex flex-wrap justify-start sm:justify-end items-center gap-4">
                    <flux:button variant="primary" href="{{ route('courses.create', ['course' => $course]) }}">New</flux:button>
                    <flux:button href="{{ route('courses.edit', ['course' => $course]) }}">Edit</flux:button>
                    <form method="POST" action="{{ route('courses.destroy', ['course' => $course]) }}">
                        @csrf
                        @method('DELETE')
                        <flux:button variant="danger" type="submit">Delete</flux:button>
                    </form>
                </div>
                <div class="mt-6 space-y-4">
                    @include('courses.partials.fields', ['mode' => 'show'])
                </div>
                <h3 class="pt-16 pb-4 text-lg font-medium text-gray-900
                           dark:text-gray-100">
                    Curriculum
                </h3>
                <x-courses.curriculum :disciplines="$course->disciplines"
                                      :showView="true"
                                      class="pt-4"
                />
            </section>
        </div>
    </div>
</x-layouts.main-content>
