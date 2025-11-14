<?php

namespace App\Helpers;

class FileValidationHelper
{
    /**
     * Check if finfo extension is available
     */
    public static function hasFinfo(): bool
    {
        return class_exists('finfo') || function_exists('finfo_open');
    }

    /**
     * Get image validation rules with finfo fallback
     */
    public static function imageRules(int $maxSize = 2048, array $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif']): array
    {
        $rules = ['nullable', 'file', 'max:' . $maxSize];

        if (self::hasFinfo()) {
            $rules[] = 'image';
            $mimes = array_map(function ($ext) {
                return $ext === 'jpg' ? 'jpeg' : $ext;
            }, $allowedExtensions);
            $rules[] = 'mimes:' . implode(',', $mimes);
        } else {
            // Fallback to extension-based validation
            $rules[] = function ($attribute, $value, $fail) use ($allowedExtensions) {
                if ($value) {
                    $extension = strtolower($value->getClientOriginalExtension());
                    if (!in_array($extension, $allowedExtensions)) {
                        $fail("The {$attribute} must be an image file (" . implode(', ', $allowedExtensions) . ").");
                    }
                }
            };
        }

        return $rules;
    }

    /**
     * Get file validation rules with finfo fallback
     */
    public static function fileRules(int $maxSize = 2048, array $allowedExtensions = []): array
    {
        $rules = ['nullable', 'file', 'max:' . $maxSize];

        if (self::hasFinfo() && !empty($allowedExtensions)) {
            $rules[] = 'mimes:' . implode(',', $allowedExtensions);
        } elseif (!empty($allowedExtensions)) {
            // Fallback to extension-based validation
            $rules[] = function ($attribute, $value, $fail) use ($allowedExtensions) {
                if ($value) {
                    $extension = strtolower($value->getClientOriginalExtension());
                    if (!in_array($extension, $allowedExtensions)) {
                        $fail("The {$attribute} must be a file with extension: " . implode(', ', $allowedExtensions) . ".");
                    }
                }
            };
        }

        return $rules;
    }
}

