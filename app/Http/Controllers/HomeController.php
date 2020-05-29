<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\BackupRepository;
use App\Repositories\SourceRepository;
use App\Repositories\DiskRepository;

class HomeController extends Controller
{

    protected $backupRepository;
    protected $sourceRepository;
    protected $diskRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(BackupRepository $backupRepository, SourceRepository $sourceRepository, DiskRepository $diskRepository)
    {
        $this->backupRepository = $backupRepository;
        $this->sourceRepository = $sourceRepository;
        $this->diskRepository   = $diskRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $countSource = $this->sourceRepository->getModel()->count();
        $countDisk   = $this->diskRepository->getModel()->count();
        $countBackup = $this->backupRepository->getModel()->count();

        return view('home', compact('countBackup', 'countDisk', 'countSource'));
    }
}
