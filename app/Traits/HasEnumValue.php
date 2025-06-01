<?php

namespace App\Traits;

trait HasEnumValue
{
    public static function getValues(string|array|null $values = null): array
    {
        $cases = $values ? array_filter(
            array: self::cases(),
            callback: function ($case) use ($values) {
                return is_array($values)
                    ? in_array($case["value"], $values)
                    : ($case["value"] == $values);
            },
        ) : self::cases();

        return array_column($cases, "value");
    }
}
