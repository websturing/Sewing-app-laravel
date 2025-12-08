<?php

namespace App\DTOs;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

abstract class BaseData
{
    /**
     * Convert snake_case to camelCase for array keys
     */
    protected static function camelKeys(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            $camelKey = Str::camel($key);
            $result[$camelKey] = $value;
        }
        return $result;
    }

    /**
     * Convert camelCase to snake_case for database
     */
    protected function snakeKeys(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            $snakeKey = Str::snake($key);
            $result[$snakeKey] = $value;
        }
        return $result;
    }

    /**
     * Factory dari array biasa (otomatis convert ke camelCase)
     */
    public static function fromArray(array $data): static
    {
        return new static(...static::camelKeys($data));
    }

    /**
     * Factory langsung dari FormRequest (otomatis convert ke camelCase)
     */
    public static function fromRequest(Request $request): static
    {
        return static::fromArray($request->validated());
    }

    /**
     * Factory dari query parameters (untuk GET requests)
     */
    public static function fromQuery(Request $request): static
    {
        $data = $request->query();

        // Convert string booleans to actual booleans
        foreach ($data as $key => $value) {
            if ($value === 'true') {
                $data[$key] = true;
            } elseif ($value === 'false') {
                $data[$key] = false;
            } elseif (is_numeric($value)) {
                $data[$key] = (int) $value;
            }
        }

        return static::fromArray($data);
    }

    /**
     * Konversi balik ke array (otomatis convert ke snake_case untuk database)
     */
    public function toArray(): array
    {
        return $this->snakeKeys(get_object_vars($this));
    }

    /**
     * Konversi ke array tapi tetap camelCase (untuk API response)
     */
    public function toCamelArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * Ambil hanya sebagian property tertentu (return camelCase)
     */
    public function only(array $keys): array
    {
        // Convert keys to camelCase jika diperlukan
        $camelKeys = array_map(fn($key) => Str::camel($key), $keys);
        return Arr::only($this->toCamelArray(), $camelKeys);
    }

    /**
     * Ambil semua kecuali property tertentu (return camelCase)
     */
    public function except(array $keys): array
    {
        // Convert keys to camelCase jika diperlukan
        $camelKeys = array_map(fn($key) => Str::camel($key), $keys);
        return Arr::except($this->toCamelArray(), $camelKeys);
    }
}
