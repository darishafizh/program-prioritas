<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProgramController extends Controller
{
    protected function checkAuth()
    {
        if (!session('logged_in')) {
            abort(redirect('/login'));
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

    public function dashboard($program, $menu = null)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);
        $progKey = strtolower($program);
        
        if (in_array($progKey, ['knmp', 'bins'])) {
            return view("programs.{$progKey}.dashboard.index", [
                'activeModule' => 'Dashboard',
                'activeProgram' => $activeProgram
            ]);
        }
        
        return view('programs.default.dashboard.index', [
            'activeModule' => 'Dashboard',
            'activeProgram' => $activeProgram
        ]);
    }

    public function master(Request $request, $program, $menu = null)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);
        $progKey = strtolower($program);
        
        if ($progKey === 'knmp') {
            if ($menu === 'komponen') {
                return view('programs.knmp.master.komponen', ['activeModule' => 'Master Data', 'activeProgram' => $activeProgram]);
            }
            if ($menu === 'vendor') {
                return view('programs.knmp.master.vendor', ['activeModule' => 'Master Data', 'activeProgram' => $activeProgram]);
            }
            
            return view('programs.knmp.master.index', [
                'activeModule' => 'Master Data',
                'activeProgram' => $activeProgram,
                'stage' => $request->query('stage', 'pengajuan')
            ]);
        }
        
        if ($progKey === 'bins') {
            return view('programs.bins.master.index', [
                'activeModule' => 'Master Data',
                'activeProgram' => $activeProgram,
                'type' => $request->query('type', 'petak')
            ]);
        }
        
        return view('programs.default.master.index', [
            'activeModule' => 'Master Data',
            'activeProgram' => $activeProgram
        ]);
    }

    public function operasional(Request $request, $program, $menu = null)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);
        $progKey = strtolower($program);
        
        if ($progKey === 'knmp') {
            if ($menu === 'kendala') {
                return view('programs.knmp.operasional.kendala', ['activeModule' => 'Operasional', 'activeProgram' => $activeProgram]);
            }
            if ($menu === 'pencairan') {
                return view('programs.knmp.operasional.pencairan', ['activeModule' => 'Operasional', 'activeProgram' => $activeProgram]);
            }
            
            return view('programs.knmp.operasional.index', [
                'activeModule' => 'Operasional',
                'activeProgram' => $activeProgram,
                'stage' => $request->query('stage', 'usulan')
            ]);
        }
        
        if ($progKey === 'bins') {
            return view('programs.bins.operasional.index', [
                'activeModule' => 'Operasional',
                'activeProgram' => $activeProgram
            ]);
        }
        
        return view('programs.default.operasional.index', [
            'activeModule' => 'Operasional',
            'activeProgram' => $activeProgram
        ]);
    }

    public function evaluasi($program, $menu = null)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);
        $progKey = strtolower($program);
        
        if (in_array($progKey, ['knmp', 'bins'])) {
            return view("programs.{$progKey}.evaluasi.index", [
                'activeModule' => 'Evaluasi',
                'activeProgram' => $activeProgram
            ]);
        }
        
        return view('programs.default.evaluasi.index', [
            'activeModule' => 'Evaluasi',
            'activeProgram' => $activeProgram
        ]);
    }
}
