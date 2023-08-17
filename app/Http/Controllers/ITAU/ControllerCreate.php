<?php

namespace App\Http\Controllers\ITAU;

use App\Http\Controllers\ClassGlobais\ControllerMaster;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ControllerCreate extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)

    {
        return  ControllerMaster::GetCreate($request->cobranca_id);
    }
}
