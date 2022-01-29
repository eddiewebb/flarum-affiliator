<?php

/*
 * This file is part of webbinaro/flarum-affiliator.
 *
 * Copyright (c) 2021 Eddie Webbinaro.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Webbinaro\Affiliator;

use Flarum\Extend;
use Webbinaro\Affiliator\PostAffiliatorSerializer;
return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less'),
    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/less/admin.less'),
    new Extend\Locales(__DIR__.'/locale'),
    (new Extend\ApiSerializer(PostSerializer::class)))
    ->attribute('content', function($serializer, $tag) {
        return $tag->is_article_series;
    }),
    ];