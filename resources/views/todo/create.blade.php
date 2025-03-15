
<x-app-layout>
    <x-slot nam name="header">
        <h2 class="text-x1 font-semibold leading-tight text-gray-880 dark: text-gray-200">
            {{ _('Todo') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto max-w-7x1 sm: px-6 1g: px-8">
            <div class="overflow-hidden bg-white shadow-sm dark: bg-gray-800 sm: rounded-lg">
                <div class="p-6 text-xl text-gray-900 dark: text-gray-100">
                    {{ _('Create Todo Page') }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>