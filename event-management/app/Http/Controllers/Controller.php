<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * BaseController class that all other controllers extend. It provides common functionality for handling HTTP requests and responses, as well as authorization checks.
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests;
}
