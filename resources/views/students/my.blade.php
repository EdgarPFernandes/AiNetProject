<x-layouts.main-content title="Students"
                        heading="My Students"
                        subheading="List of students I am teaching.">
    @if($students->isNotEmpty())                        
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl ">
        <div class="flex justify-start ">
            <div class="my-4 p-6 ">
                <div class="my-4 font-base text-sm text-gray-700 dark:text-gray-300">
                    <x-students.table :students="$students"
                                      :showCourse="true"
                                      :showView="true"
                                      :showEdit="true"
                                      :showDelete="true"
                    />
                </div>
                <div class="mt-4">
                    {{ $students->links() }}
                </div>                
            </div>
        </div>
    </div>
    @endif
</x-layouts.main-content>
