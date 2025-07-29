<?php

namespace Botble\CarRentals\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Contracts\HasTreeCategory as HasTreeCategoryContract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\Html;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\HasTreeCategory;
use Botble\CarRentals\Models\Concerns\HasActiveCarsRelation;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class CarCategory extends BaseModel implements HasTreeCategoryContract
{
    use HasTreeCategory;
    use HasActiveCarsRelation;

    protected $table = 'cr_car_categories';

    protected $fillable = [
        'name',
        'parent_id',
        'description',
        'status',
        'icon',
        'order',
        'is_featured',
        'is_default',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
        'description' => SafeContent::class,
        'is_default' => 'bool',
        'order' => 'int',
    ];

    protected static function booted(): void
    {
        static::deleted(function (CarCategory $category): void {
            $category->children()->each(fn (CarCategory $child) => $child->delete());

            $category->cars()->detach();
        });
    }

    public function cars(): BelongsToMany
    {
        return $this->belongsToMany(Car::class, 'cr_cars_categories', 'cr_car_category_id', 'cr_car_id');
    }

    public static function handleCommissionEachCategory(array $data): array
    {
        $commissions = [];
        CategoryCommission::query()->truncate();
        foreach ($data as $datum) {
            if (! $datum['categories']) {
                continue;
            }

            $categories = json_decode($datum['categories'], true);

            if (! is_array($categories) || ! count($categories)) {
                continue;
            }

            foreach ($categories as $category) {
                $commission = CategoryCommission::query()->firstOrNew([
                    'car_category_id' => $category['id'],
                ]);

                if (! $commission) {
                    continue;
                }

                $commission->commission_percentage = $datum['commission_fee'];
                $commission->save();
                $commissions[] = $commission;
            }
        }

        return $commissions;
    }

    public static function getCommissionEachCategory(): array
    {
        $commissions = CategoryCommission::query()->with(['category'])->get();
        $data = [];
        foreach ($commissions as $commission) {
            if (! $commission->category) {
                continue;
            }

            $data[$commission->commission_percentage]['commission_fee'] = $commission->commission_percentage;
            $data[$commission->commission_percentage]['categories'][] = [
                'id' => $commission->car_category_id,
                'value' => $commission->category->name,
            ];
        }

        return $data;
    }

    public function parent(): BelongsTo
    {
        return $this
            ->belongsTo(CarCategory::class, 'parent_id')
            ->whereNot('parent_id', $this->getKey())
            ->withDefault();
    }

    public function children(): HasMany
    {
        return $this
            ->hasMany(CarCategory::class, 'parent_id')
            ->whereNot('id', $this->getKey());
    }

    protected function parents(): Attribute
    {
        return Attribute::get(function (): Collection {
            $parents = collect();

            $parent = $this->parent;

            while ($parent->id) {
                $parents->push($parent);
                $parent = $parent->parent;
            }

            return $parents;
        });
    }

    protected function badgeWithCount(): Attribute
    {
        return Attribute::get(function (): HtmlString {
            return Html::tag('span', sprintf('(%s)', $this->cars_count), [
                'data-bs-toggle' => 'tooltip',
                'data-bs-original-title' => trans(
                    'plugins/car-rentals::car-rentals.attribute.category.forms.total_cars',
                    ['total' => $this->cars_count]
                ),
            ]);
        });
    }

    public function activeChildren(): HasMany
    {
        return $this
            ->children()
            ->wherePublished()
            ->with(['slugable', 'activeChildren']);
    }
}
