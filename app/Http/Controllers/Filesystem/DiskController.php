<?php

namespace App\Http\Controllers\Filesystem;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\DiskRepository;
use App\Http\Requests\DiskRequest;

class DiskController extends Controller
{
    protected $diskRepo;
    
    public function __construct(DiskRepository $diskRepo)
    {
        $this->diskRepo = $diskRepo;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view ('filesystem.disk_index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('filesystem.disk_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DiskRequest $request)
    {
        $store = $this->diskRepo->storeDisk($request);

        return redirect()->route('disk.index')->with(\Helper::alertStatus('store', $store));
    }

    /**
     * Display the specified resource.
     *
     * @param  int 
     * @return \Illuminate\Http\Response
     */
    public function show($diskId)
    {
        $disk = $this->diskRepo->show($diskId);

        return view('filesystem.disk_show', compact('disk'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int
     * @return \Illuminate\Http\Response
     */
    public function edit($diskId)
    {
        $disk = $this->diskRepo->show($diskId);

        return view('filesystem.disk_edit', compact('disk'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int 
     * @return \Illuminate\Http\Response
     */
    public function update(DiskRequest $request, $diskId)
    {
        $update = $this->diskRepo->updateDisk($request, $diskId);

        return redirect()->route('disk.index')->with(\Helper::alertStatus('update', $update));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $diskId
     * @return \Illuminate\Http\Response
     */
    public function destroy($diskId)
    {
       $deleteImage = $this->diskRepo->deleteFiles('disk', $this->diskRepo->show($disk)->photo);

       return $this->diskRepo->delete($disk);
    }

    /**
    * Showing list disk by datatable
    * @param $request ajax
    * @return json
    */
    public function ajaxDatatable(Request $request)
    {
        $route = 'disk'; // if routing and table have different name

        return $this->diskRepo->makeDatatable($request, $route);
    }
}
