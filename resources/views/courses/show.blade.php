<x-layouts.main-content :title="$course->name"
                        heading="View Course"
                        :subheading="$course->name">
    <div class="flex flex-col space-y-6">
        <div class="max-full">
            <section>
                <div class="static sm:absolute -top-2 right-0 flex flex-wrap justify-start sm:justify-end items-center gap-4">
                    @can('create', App\Models\Course::class)
                        <flux:button variant="primary" href="{{ route('courses.create') }}">New</flux:button>
                    @endcan
                    @can('update', $course)
                        <flux:button href="{{ route('courses.edit', ['course' => $course]) }}">Edit</flux:button>
                    @endcan
                    @can('delete', $course)
                        <form method="POST" action="{{ route('courses.destroy', ['course' => $course]) }}">
                            @csrf
                            @method('DELETE')
                            <flux:button variant="danger" type="submit">Delete</flux:button>
                        </form>
                    @endcan
                </div>
                <div class="mt-6 space-y-4">
                    @include('courses.partials.fields', ['mode' => 'show'])
                </div>
                @can('viewCurriculum', App\Models\Course::class)
                <h3 class="pt-16 pb-4 text-lg font-medium text-gray-900
                           dark:text-gray-100">
                    Curriculum
                </h3>
                <x-courses.curriculum :disciplines="$course->disciplines"
                                      :showView="true"
                                      class="pt-4"
                />
                @endcan
            </section>
        </div>
    </div>
</x-layouts.main-content>
