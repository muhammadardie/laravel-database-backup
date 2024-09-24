<?php

namespace App\Http\Controllers;

use App\Repositories\{ BackupHistoryRepository, DatabaseSourceRepository, StorageRepository };

class HomeController extends Controller
{

    protected $history;
    protected $source;
    protected $storage;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(BackupHistoryRepository $history, DatabaseSourceRepository $source, StorageRepository $storage)
    {
        $this->history = $history;
        $this->source = $source;
        $this->storage   = $storage;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $countSource = $this->source->count();
        $countDisk   = $this->storage->count();
        $countBackup = $this->history->count();

        return view('home', compact('countBackup', 'countDisk', 'countSource'));
    }
}
