<x-layouts.main-content :title="'Curriculum of ' . $course->fullName"
                        :heading="$course->fullName"
                        :subheading="'Curriculum of ' . $course->fullName">
    <div class="flex flex-col">
        <x-courses.curriculum :disciplines="$course->disciplines"
                              :showView="true"
                              class="pt-4"
        />
    </div>
</x-layouts.main-content>
