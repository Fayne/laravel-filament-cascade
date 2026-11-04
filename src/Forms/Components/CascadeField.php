<?php

namespace Fayne\Cascade\Forms\Components;

use Closure;
use Fayne\Cascade\Support\CascadeOptionSource;
use Filament\Forms\Components\Field;

class CascadeField extends Field
{
    protected string $view = 'filament-cascade::cascade';

    protected array $cascadeOptions = [];

    public function cascadeOptions(array $options): static
    {
        new CascadeOptionSource($options);
        $this->cascadeOptions = $options;
        $this->rules([fn (CascadeField $component): Closure => function (string $attribute, mixed $value, Closure $fail) use ($component): void {
            if (! is_array($value ?? [])) {
                $fail(__('filament-cascade::cascade.path_array'));

                return;
            }
            try {
                (new CascadeOptionSource($component->cascadeOptions))->levels($value ?? [], true);
            } catch (\InvalidArgumentException $exception) {
                $fail($exception->getMessage());
            }
        }]);

        return $this;
    }

    public function hydrateState(?array &$hydratedDefaultState, bool $shouldCallHydrationHooks = true, bool $shouldApplyStateCasts = true, array &$appliedStateCastPaths = []): void
    {
        $value = $this->getRawState();
        if (is_string($value)) {
            $value = $value === '' ? [] : json_decode($value, true, flags: JSON_THROW_ON_ERROR);
        }$this->rawState($value ?? []);
        parent::hydrateState($hydratedDefaultState, $shouldCallHydrationHooks, $shouldApplyStateCasts, $appliedStateCastPaths);
        $value = $this->getRawState();
        if (is_string($value)) {
            $value = $value === '' ? [] : json_decode($value, true, flags: JSON_THROW_ON_ERROR);
        }$this->rawState($value ?? []);
    }

    public function getLevels(): array
    {
        $state = $this->getState();

        return (new CascadeOptionSource($this->cascadeOptions))->levels(is_array($state) ? $state : []);
    }

    public function isSearchable(): bool
    {
        return (bool) ($this->cascadeOptions['searchable'] ?? false);
    }

    public function isInline(): bool
    {
        return (bool) ($this->cascadeOptions['displayInline'] ?? false);
    }
}
