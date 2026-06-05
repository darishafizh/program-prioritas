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

    public function dashboard($program)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);
        $progKey = strtolower($program);
        
        if (in_array($progKey, ['knmp', 'bins'])) {
            return view("programs.{$progKey}.dashboard", [
                'activeModule' => 'Dashboard',
                'activeProgram' => $activeProgram
            ]);
        }
        
        return view('programs.default.dashboard', [
            'activeModule' => 'Dashboard',
            'activeProgram' => $activeProgram
        ]);
    }

    public function master(Request $request, $program)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);
        $progKey = strtolower($program);
        
        if ($progKey === 'knmp') {
            return view('programs.knmp.master', [
                'activeModule' => 'Master Data',
                'activeProgram' => $activeProgram,
                'stage' => $request->query('stage', 'usulan')
            ]);
        }
        
        if ($progKey === 'bins') {
            return view('programs.bins.master', [
                'activeModule' => 'Master Data',
                'activeProgram' => $activeProgram,
                'type' => $request->query('type', 'petak')
            ]);
        }
        
        return view('programs.default.master', [
            'activeModule' => 'Master Data',
            'activeProgram' => $activeProgram
        ]);
    }

    public function operasional($program)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);
        $progKey = strtolower($program);
        
        if (in_array($progKey, ['knmp', 'bins'])) {
            return view("programs.{$progKey}.operasional", [
                'activeModule' => 'Operasional',
                'activeProgram' => $activeProgram
            ]);
        }
        
        return view('programs.default.operasional', [
            'activeModule' => 'Operasional',
            'activeProgram' => $activeProgram
        ]);
    }

    public function evaluasi($program)
    {
        $this->checkAuth();
        $activeProgram = $this->formatProgramName($program);
        $progKey = strtolower($program);
        
        if (in_array($progKey, ['knmp', 'bins'])) {
            return view("programs.{$progKey}.evaluasi", [
                'activeModule' => 'Evaluasi',
                'activeProgram' => $activeProgram
            ]);
        }
        
        return view('programs.default.evaluasi', [
            'activeModule' => 'Evaluasi',
            'activeProgram' => $activeProgram
        ]);
    }
}
