<?php

namespace App\Http\Controllers\Knmp\Evaluasi;

use App\Http\Controllers\ProgramBaseController;
use Illuminate\Http\Request;

class KinerjaController extends ProgramBaseController
{
    public function index(Request $request, $program, $menu = null)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);
        
        return view('programs.knmp.evaluasi.index', [
            'activeModule' => 'Evaluasi',
            'activeProgram' => $activeProgram
        ]);
    }
}
