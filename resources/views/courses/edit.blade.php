<x-layouts.main-content :title="$course->name"
                        heading="Edit course"
                        :subheading="$course->name">
    <div class="flex flex-col space-y-6">
        <div class="max-full">
            <section>
                <div class="static sm:absolute -top-2 right-0 flex flex-wrap justify-start sm:justify-end items-center gap-4">
                    <flux:button variant="primary" href="{{ route('courses.create', ['course' => $course]) }}">New</flux:button>
                    <flux:button href="{{ route('courses.show', ['course' => $course]) }}">View</flux:button>
                    <form method="POST" action="{{ route('courses.destroy', ['course' => $course]) }}">
                        @csrf
                        @method('DELETE')
                        <flux:button variant="danger" type="submit">Delete</flux:button>
                    </form>
                </div>
                <form method="POST" action="{{ route('courses.update', ['course' => $course]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mt-6 space-y-4">
                        @include('courses.partials.fields', ['mode' => 'edit'])
                    </div>
                    <div class="flex mt-6">
                        <flux:button variant="primary" type="submit"  class="uppercase">Save</flux:button>
                        <flux:button class="uppercase ms-4" href="{{ url()->full() }}">Cancel</flux:button>
                    </div>
                </form>
            </section>
        </div>
    </div>
    <form class="hidden" id="form_to_delete_course_image"
        method="POST" 
        action="{{ route('courses.image.destroy', ['course' => $course]) }}">
        @csrf
        @method('DELETE')
    </form>    
</x-layouts.main-content>
