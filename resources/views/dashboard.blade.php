<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-middle">
           <h4 class="font-semibold">
                {{ __('Dashboard') }}
            </h4>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Sistema de gerenciamento de tarefas!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
