<?php

namespace App\Http\Controllers;

use Request;

class ViewController extends Controller
{
    public function showView(Request $request, $view = 'system', $action = 'status')
    {
        $file = \File::get(storage_path('config/menu.json'));
        $menus = json_decode($file, true);

        return view("{$view}.{$action}", ['menus' => $menus, 'view' => $view, 'action' => $action]);
    }
}
