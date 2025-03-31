<?php

namespace App\Http\Controllers;


use Illuminate\View\View;

class BaseInfoController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();
        $passport = $user->passport;
        $contracts = $user->contracts;

        return view('base-info.index', compact(['contracts', 'passport']));
    }
}
