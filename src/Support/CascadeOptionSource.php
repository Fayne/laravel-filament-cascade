<?php

namespace Fayne\Cascade\Support;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

final class CascadeOptionSource
{
    public function __construct(private array $options)
    {
        if (count(array_intersect(array_keys($options), ['options', 'fromFile', 'fromDirectory', 'fromUrl'])) !== 1) {
            throw new InvalidArgumentException(__('filament-cascade::cascade.source'));
        }
        foreach (['fromFile' => 'files', 'fromDirectory' => 'directories', 'fromUrl' => 'urls'] as $key => $type) {
            if (isset($options[$key]) && ! in_array($options[$key], config('filament-cascade.sources.' . $type, []), true)) {
                throw new InvalidArgumentException(__('filament-cascade::cascade.source_denied'));
            }
        }
    }

    public function levels(array $path, bool $strict = false): array
    {
        if (! array_is_list($path) || count($path) > 64) {
            throw new InvalidArgumentException(__('filament-cascade::cascade.depth'));
        }
        $remote = isset($this->options['fromDirectory']) || isset($this->options['fromUrl']);
        $choices = $this->root();
        $levels = [];
        $prefix = [];
        foreach ([...$path, null] as $value) {
            if ($choices === []) {
                if ($value !== null && $strict) {
                    throw new InvalidArgumentException(__('filament-cascade::cascade.stale'));
                }break;
            }
            $levels[] = $choices;
            if ($value === null) {
                break;
            }
            $selected = null;
            foreach ($choices as $choice) {
                if (is_scalar($value) && (string) $choice['value'] === (string) $value) {
                    $selected = $choice;
                    break;
                }
            }
            if (! $selected) {
                if ($strict) {
                    throw new InvalidArgumentException(__('filament-cascade::cascade.invalid_parent'));
                }break;
            }
            $prefix[] = $value;
            if ($selected['leaf'] ?? false) {
                $choices = [];

                continue;
            }
            $choices = $remote ? $this->load($prefix) : ($selected['children'] ?? []);
        }

        return $levels;
    }

    private function root(): array
    {
        if (isset($this->options['options'])) {
            $data = $this->options['options'];

            return is_string($data) ? json_decode($data, true, flags: JSON_THROW_ON_ERROR) : $data;
        }
        if (isset($this->options['fromFile'])) {
            return json_decode(file_get_contents($this->options['fromFile']), true, flags: JSON_THROW_ON_ERROR);
        }

        return $this->load([]);
    }

    private function load(array $path): array
    {
        $parent = $path === [] ? null : $path[array_key_last($path)];
        if (isset($this->options['fromUrl'])) {
            $data = Http::timeout(5)->withoutRedirecting()->get($this->options['fromUrl'], ['parent' => $parent, 'values' => $path])->throw()->json();
            if (! is_array($data)) {
                throw new InvalidArgumentException(__('filament-cascade::cascade.response_invalid'));
            }

            return $data;
        }
        if (isset($this->options['fromDirectory'])) {
            $name = $parent === null ? 'root' : (string) $parent;
            if (! preg_match('/^[a-zA-Z0-9_-]+$/D', $name)) {
                throw new InvalidArgumentException(__('filament-cascade::cascade.node_invalid'));
            }
            $base = realpath($this->options['fromDirectory']);
            $file = realpath($this->options['fromDirectory'] . '/' . $name . '.json');
            if ($file === false) {
                return [];
            }if (! $base || ! str_starts_with($file, $base . DIRECTORY_SEPARATOR)) {
                throw new InvalidArgumentException(__('filament-cascade::cascade.directory_invalid'));
            }

            return json_decode(file_get_contents($file), true, flags: JSON_THROW_ON_ERROR);
        }
        throw new InvalidArgumentException(__('filament-cascade::cascade.source'));
    }
}
