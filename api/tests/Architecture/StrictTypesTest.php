<?php

declare(strict_types=1);

arch('strict types declared')
    ->expect('App')
    ->toUseStrictTypes();

arch('no debug helpers leaked into the code')
    ->expect(['dd', 'dump', 'ray', 'var_dump'])
    ->not->toBeUsed();

arch('domain code does not depend on http layer')
    ->expect('App\Domain')
    ->not->toUse('App\Http');

arch('domain code does not depend on console layer')
    ->expect('App\Domain')
    ->not->toUse('App\Console');

arch('form requests extend FormRequest')
    ->expect('App\Http\Requests')
    ->toExtend('Illuminate\Foundation\Http\FormRequest');

arch('api controllers extend the base Controller')
    ->expect('App\Http\Controllers\Api')
    ->toExtend('App\Http\Controllers\Controller');
