<?php

namespace App\Http\Controllers\DefaultProgram;

use App\Http\Controllers\ProgramBaseController;
use Illuminate\Http\Request;

class MasterController extends ProgramBaseController
{
    public function index($program, $menu = null)
    {
        $this->checkAuth();
        
        if (auth()->user()->isUserDaerah() && $menu !== 'calon-lokasi' && $menu !== null) {
            abort(403, 'Anda hanya memiliki akses ke menu Calon Lokasi.');
        }

        $activeProgram = $this->formatProgramName($program);
        
        return view('programs.default.master.index', [
            'activeModule' => 'Master Data',
            'activeProgram' => $activeProgram
        ]);
    }
}
