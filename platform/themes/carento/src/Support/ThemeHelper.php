<?php

namespace Theme\Carento\Support;

use Botble\Base\Models\BaseQueryBuilder;
use Botble\Blog\Models\Post;
use Illuminate\Support\Collection;

class ThemeHelper
{
    public static function getBlogPosts(array $categoryIds = [], ?string $type = null, int $limit = 6): Collection
    {
        if (! is_plugin_active('blog') || ! $categoryIds) {
            return collect();
        }

        $query = Post::query()
            ->whereHas('categories', fn (BaseQueryBuilder $query) => $query->whereIn('id', $categoryIds));

        if ($type) {
            $query = match ($type) {
                'popular' => $query->orderByDesc('views'),
                'featured' => $query->where('is_featured', 1),
                default => $query->orderByDesc('created_at'),
            };
        }

        return $query
            ->wherePublished()
            ->limit($limit)
            ->get();
    }

    public static function isShowPostMeta(string $kind, string $type, bool $default = false): bool
    {
        $kind = in_array($kind, ['list', 'detail']) ? $kind : 'list';
        $option = theme_option("blog_post_{$kind}_meta_display", null);

        return $option !== null
            ? in_array($type, json_decode($option, true))
            : $default;
    }

    public static function getPlayVideoIconBase64(): string
    {
        $iconPath = theme_path('carento/public/images/icons/play.svg');

        if (! file_exists($iconPath)) {
            return '';
        }

        $imageBase64 = base64_encode(file_get_contents($iconPath));

        return base64_encode(str_replace('__color__', theme_option('primary_color', '#70f46d'), base64_decode($imageBase64)));
    }
}
