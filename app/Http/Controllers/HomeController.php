<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BackupService;
use App\Services\SourceService;
use App\Services\DiskService;

class HomeController extends Controller
{

    protected $backupService;
    protected $sourceService;
    protected $diskService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(BackupService $backupService, SourceService $sourceService, DiskService $diskService)
    {
        $this->backupService = $backupService;
        $this->sourceService = $sourceService;
        $this->diskService   = $diskService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $countSource = $this->sourceService->getModel()->count();
        $countDisk   = $this->diskService->getModel()->count();
        $countBackup = $this->backupService->getModel()->count();

        return view('home', compact('countBackup', 'countDisk', 'countSource'));
    }
}
