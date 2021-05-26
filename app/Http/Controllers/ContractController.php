<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Contract;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return [];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function buyContracts(Request $request)
    {
        return [];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sellContracts(Request $request)
    {
        return [];
    }

    public function show(Contract $contract)
    {
       return [];
    }

    public function print(Contract $contract)
    {
        return [];
    }
}
