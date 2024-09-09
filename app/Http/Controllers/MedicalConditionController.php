<?php

namespace App\Http\Controllers;

use App\Models\MedicalCondition;
use Illuminate\Http\Request;

class MedicalConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medicalConditions = MedicalCondition::latest()->get();
        return response()->json([
            'success' => 'OK',
            'message' => 'Found ' . count($medicalConditions) . ' medical conditions',
            'data' => $medicalConditions
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
     * @param  \App\Models\MedicalCondition  $medicalCondition
     * @return \Illuminate\Http\Response
     */
    public function show(MedicalCondition $medicalCondition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MedicalCondition  $medicalCondition
     * @return \Illuminate\Http\Response
     */
    public function edit(MedicalCondition $medicalCondition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MedicalCondition  $medicalCondition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MedicalCondition $medicalCondition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MedicalCondition  $medicalCondition
     * @return \Illuminate\Http\Response
     */
    public function destroy(MedicalCondition $medicalCondition)
    {
        //
    }
}
