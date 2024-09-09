<?php

namespace App\Http\Controllers;

use App\Models\StatusFitWork;
use Illuminate\Http\Request;

class StatusFittoworkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = StatusFitWork::latest()->get();
        return response()->json([
            'success' => 'OK',
            'message' => 'Found ' . count($data) . ' Fit Work conditions',
            'data' => $data
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\status_fittowork  $status_fittowork
     * @return \Illuminate\Http\Response
     */
    public function show(status_fittowork $status_fittowork)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\status_fittowork  $status_fittowork
     * @return \Illuminate\Http\Response
     */
    public function edit(status_fittowork $status_fittowork)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\status_fittowork  $status_fittowork
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, status_fittowork $status_fittowork)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\status_fittowork  $status_fittowork
     * @return \Illuminate\Http\Response
     */
    public function destroy(status_fittowork $status_fittowork)
    {
        //
    }
}
