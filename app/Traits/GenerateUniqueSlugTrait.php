<?php
declare(strict_types=1);

namespace App\Traits;
 
use Illuminate\Support\Str;

trait GenerateUniqueSlugTrait
{
    public static function bootGenerateUniqueSlugTrait(): void
    {
        static::saving(function ($model) {
            var_dump($model);
            if (!$model->post_slug) {
                $model->post_slug = $model->generateUniqueSlug(Str::slug($model->post_title));
            }
            // $post_slug = $model->post_slug;
            // $model->post_slug = $model->generateUniqueSlug($post_slug);
        });
    }

    public function generateUniqueSlug(string $post_slug): string
    {
        // Check if the slug already has a number at the end
        $originalSlug =  ($post_slug);
        $slugNumber = null;

        if (preg_match('/-(\d+)$/', $post_slug, $matches)) {
            $slugNumber = $matches[1];
            $post_slug = Str::replaceLast("-$slugNumber", '', $post_slug);
        }

        // Check if the modified slug already exists in the table
        $existingSlugs = $this->getExistingSlugs($post_slug, $this->getTable());

        if (!in_array($post_slug, $existingSlugs)) {
            // Slug is unique, no need to append numbers
            return $post_slug . ($slugNumber ? "-$slugNumber" : '');
        }

        // Increment the number until a unique slug is found
        $i = $slugNumber ? ($slugNumber + 1) : 1;
        $uniqueSlugFound = false;

        while (!$uniqueSlugFound) {
            $newSlug = $post_slug . '-' . $i;

            if (!in_array($newSlug, $existingSlugs)) {
                // Unique slug found
                return $newSlug;
            }

            $i++;
        }

        // Fallback: return the original slug with a random number appended
        return $originalSlug . '-' . mt_rand(1000, 9999);
    }

    private function getExistingSlugs(string $post_slug, string $table): array
    {
        return $this->where('post_slug', 'LIKE', $post_slug . '%')
            ->where('id', '!=', $this->id ?? null) // Exclude the current model's ID
            ->pluck('post_slug')
            ->toArray();
    }
}