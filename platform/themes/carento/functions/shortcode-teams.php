<?php

use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Shortcode\Compilers\Shortcode as ShortcodeCompiler;
use Botble\Shortcode\Facades\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Team\Models\Team;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Arr;

app()->booted(function (): void {
    if (! is_plugin_active('team')) {
        return;
    }

    Shortcode::register('team', __('Team'), __('Team'), function (ShortcodeCompiler $shortcode): ?string {
        if (! $teamIds = Shortcode::fields()->getIds('team_ids', $shortcode)) {
            return null;
        }

        $teams = Team::query()
            ->with(['metadata', 'slugable'])
            ->wherePublished()
            ->whereIn('id', $teamIds)
            ->get();

        if ($teams->isEmpty()) {
            return null;
        }

        return Theme::partial('shortcodes.team.index', compact('shortcode', 'teams'));
    });

    Shortcode::setPreviewImage('team', Theme::asset()->url('images/ui-blocks/teams.png'));

    Shortcode::setAdminConfig('team', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
            )
            ->add(
                'subtitle',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Subtitle'))
            )
            ->add(
                'team_ids',
                SelectField::class,
                SelectFieldOption::make()
                    ->searchable()
                    ->multiple()
                    ->choices(Team::query()->wherePublished()->pluck('name', 'id')->all())
                    ->selected(explode(',', Arr::get($attributes, 'team_ids')))
                    ->label(__('Choose team member'))
            );
    });
});
