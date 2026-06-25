<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class ProgramBaseController extends Controller
{
    protected function checkAuth()
    {
        if (!Auth::check()) {
            abort(redirect()->route('login'));
        }
    }

    protected function formatProgramName($program)
    {
        $name = str_replace('-', ' ', $program);
        if (in_array(strtolower($name), ['knmp', 'bins'])) {
            return strtoupper($name);
        }
        return ucwords($name);
    }
}
