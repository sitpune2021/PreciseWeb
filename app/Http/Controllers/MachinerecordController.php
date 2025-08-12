<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Machinerecord;
class MachinerecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function AddMachinerecord()
    {
        return view('Machinerecord.add');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
   public function ViewMachinerecord()
    {
        $record = Machinerecord::all();
        return view('Machinerecord.view', compact('record'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
