<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'User/SavePackage',
        'User/Pay/transaction/initialize',
        'User/Prompt',
        'User/SaveSessionData',
        'User/GenerateImage',
        'User/GenerateTopic',
        'Admin/GetUserPackageInfo',
        'products/api/add',
        'products/api/update'
    ];
}
