<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

use Encore\Admin\Grid\Column;
use Encore\Admin\Show;
use Encore\Admin\Admin;
use App\Admin\Extensions\Displayer\Money;
use App\Admin\Extensions\Displayer\StringMaxLength;
use App\Admin\Extensions\Show\Money as SMoney;
use App\Admin\Extensions\Show\Zoom;

Encore\Admin\Form::forget(['map', 'editor', 'DateMultiple']);

Column::extend('money', Money::class);
Column::extend('stringMaxLength', StringMaxLength::class);

Show::extend('money', SMoney::class);
Show::extend('zoom', Zoom::class);

Admin::js('/js/libs/zooming/zooming.min.js');




