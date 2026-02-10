<x-filament::page>
    <x-filament::widgets
        :widgets="$this->getHeaderWidgets()"
        class="mb-6"
    />

    <x-filament::widgets
        :widgets="$this->getWidgets()"
    />
</x-filament::page>
