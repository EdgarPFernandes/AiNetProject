{{-- <div {{ $attributes->merge(['class' => '']) }}> --}}
<div {{ $attributes }}>
    <form method="GET" action="{{ $filterAction }}">
        <div class="flex justify-between space-x-3">
            <div class="grow flex flex-col space-y-2">
                <div>
                    <flux:select name="department" label="Department">
                        @foreach ($departments as $value => $description)
                            <flux:select.option value="{{ $value }}" :selected="$department === $value">{{ $description }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
                <div>
                    <flux:input name="name" label="Name" class="grow" value="{{ $name }}"/>
                </div>
            </div>
            <div class="grow-0 flex flex-col space-y-3 justify-start">
                <div class="pt-6">
                    <flux:button variant="primary" type="submit">Filter</flux:button>
                </div>
                <div>
                    <flux:button :href="$resetUrl">Cancel</flux:button>
                </div>
            </div>
        </div>
    </form>
</div>
