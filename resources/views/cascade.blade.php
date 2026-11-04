<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="{ state: $wire.$entangle(@js($getStatePath())).live }" style="{{ $isInline() ? 'display:flex;flex-wrap:wrap;gap:.75rem' : 'display:grid;gap:.75rem' }}">
        @foreach ($getLevels() as $index => $choices)
            <div wire:key="{{ $getStatePath() }}-level-{{ $index }}-{{ md5(json_encode($choices)) }}" x-data="{ search: '', choices: @js($choices) }">
                @if ($isSearchable()) <input type="search" x-model.debounce.150ms="search" placeholder="{{ __('filament-cascade::cascade.search') }}" aria-label="{{ __('filament-cascade::cascade.search_level', ['level' => $index + 1]) }}" /> @endif
                <select aria-label="{{ __('filament-cascade::cascade.select_level', ['level' => $index + 1]) }}" x-bind:disabled="@js($isDisabled())" x-on:change="state = [...(state || []).slice(0, {{ $index }}), $event.target.value]" style="min-width:10rem;padding:.5rem;border:1px solid #9ca3af;border-radius:.5rem">
                    <option value="" disabled x-bind:selected="(state || [])[{{ $index }}] === undefined">{{ __('filament-cascade::cascade.choose') }}</option>
                    <template x-for="option in choices.filter(item => String(item.label).toLocaleLowerCase().includes(search.toLocaleLowerCase()) || String(item.value) === String((state || [])[{{ $index }}]))" x-bind:key="option.value">
                        <option x-bind:value="option.value" x-bind:selected="String((state || [])[{{ $index }}]) === String(option.value)" x-text="option.label"></option>
                    </template>
                </select>
            </div>
        @endforeach
    </div>
</x-dynamic-component>
