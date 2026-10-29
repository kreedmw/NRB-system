<?php
declare(strict_types=1);

namespace App\Security;

final class InputSanitizer
{
    public static function string(mixed $value): string
    {
        return trim(strip_tags((string) $value));
    }

    public static function email(mixed $value): string
    {
        return strtolower(trim((string) $value));
    }

    public static function int(mixed $value): int
    {
        return (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }

    public static function array(array $values): array
    {
        foreach ($values as $key => $value) {
            if (is_string($value)) {
                $values[$key] = self::string($value);
            }
        }

        return $values;
    }
}
