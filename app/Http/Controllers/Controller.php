<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

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
