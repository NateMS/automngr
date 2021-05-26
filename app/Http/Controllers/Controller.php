<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getDirection(Request $request, $default = 'asc')
    {
        if ($request->has('direction')) {
            if (in_array($request->get('direction'), ['asc', 'desc'])) {
                return $request->get('direction');
            }
        }

        return $default;
    }
}
