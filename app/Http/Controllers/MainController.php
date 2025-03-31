<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\View\View;

class MainController extends Controller
{

    public function __invoke(): View
    {
        $banners = Banner::orderBy('order')->get();
        return view('main.index', compact('banners'));
    }
}
