<?php

namespace App\Project;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class AdminClass
{
    public static $items;

    public function __construct(?array $array = null)
    {
        $array = $array ?: config('project.sidebar_nav');

        $this->setItems($array);
    }

    protected function setItems(array $array): void
    {
        self::$items = $this->mapItems($array);
    }

    protected function mapItems(array $array): array
    {
        $mapped = Arr::map($array, function (array $value) {
            $term = data_get($value, '4');
            $element = ['route' => $term];

            if (is_array($term)) {
                $element = ['submenu' => $this->mapSubmenu($term)];
            }

            $attr = ['slug', 'label', 'icon', 'group'];

            foreach ($attr as $index => $name) {
                $list[$name] = data_get($value, $index);
            }

            return $list + $element;
        });

        return $mapped;
    }

    protected function mapSubmenu(array $array): array
    {
        $mapped = Arr::map($array, function (array $value) {
            return [
                'label' => data_get($value, '0'),
                'route' => data_get($value, '1'),
            ];
        });

        return $mapped;
    }

    public static function getItems(): Collection
    {
        return collect(self::$items);
    }
}
