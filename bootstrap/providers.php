<?php

use App\Providers\AppServiceProvider;
use Illuminate\Validation\Rules\Password;

Password::defaults(function () {
    return Password::min(8);
});

return [
    AppServiceProvider::class,
];
