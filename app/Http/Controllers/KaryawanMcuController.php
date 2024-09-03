<?php

namespace App\Http\Controllers;

use App\Models\KaryawanMcu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KaryawanMcuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 10;
        $query = KaryawanMcu::with('karyawan');
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->whereHas('karyawan', function($q) use ($searchTerm) {
                $q->where('nama_karyawan', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('no_badge', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $karyawans = $query->latest()->paginate($limit);
        $no = $karyawans->firstItem();
        foreach ($karyawans as $index => $item) {
            $item->no = $no;
            $item->usia = $item->karyawan->usia;
            $no++;
        }

        $response = [
            'success' => true,
            'message' => 'List karyawans',
            'data' => [
                'karyawans' => $karyawans->items(),
                'pagination' => [
                    'total' => $karyawans->total(),
                    'per_page' => $karyawans->perPage(),
                    'current_page' => $karyawans->currentPage(),
                    'last_page' => $karyawans->lastPage(),
                    'from' => $karyawans->firstItem(),
                    'to' => $karyawans->lastItem(),
                ]
            ]
        ];
        return response()->json($response, 200);
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
        $validate = $request->validate([
            'id_karyawan' => 'required|unique:karyawans,no_badge',
            'riwayat_kesehatan' => 'required',
            'riwayat_konsumsi_obat' => 'required',
            'score_kardiovaskular_jakarta' => 'required',
            'tahun_mcu' => 'required|integer',
            'file_mcu' => 'required|mimes:pdf|max:2048'
        ], [
            'id_karyawan.required' => 'ID karyawan tidak boleh kosong.',
            'riwayat_kesehatan.required' => 'Riwayat kesehatan wajib diisi.',
            'riwayat_konsumsi_obat.required' => 'Riwayat konsumsi obat wajib diisi.',
            'score_kardiovaskular_jakarta.required' => 'score kardiovaskular wajib diisi.',
            'tahun_mcu.required' => 'Tahun MCU wajib diisi.',
            'tahun_mcu.integer' => 'Nomor HP/WA harus berupa angka.',
            'file_mcu.required' => 'Foto wajib diunggah.',
            'file_mcu.mimes' => 'file harus berupa file dengan format: pdf.',
            'file_mcu.max' => 'Ukuran file maksimal adalah 2MB.',
        ]);

        $fileMcu = $request->file('file_mcu');
        $path = $fileMcu->store('file-mcu', 'public');

        $validate['file_mcu'] = $path;

        $karyawanMcu = KaryawanMcu::create($validate);
        $response = [
            'success' => true,
            'message' => 'Karyawan created',
            'data' => $karyawanMcu
        ];
        return response()->json($response, 200, [
            'Content-Type' => 'application/json;charset=UTF-8',
            'Charset' => 'utf-8'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\KaryawanMcu  $karyawanMcu
     * @return \Illuminate\Http\Response
     */
    public function show(KaryawanMcu $karyawanMcu)
    {
        $response = [
            'success' => true,
            'message' => 'Data Mcu Karyawan',
            'data' => $karyawanMcu
        ];
        return response()->json($response, 200, [
            'Content-Type' => 'application/json;charset=UTF-8',
            'Charset' => 'utf-8'
        ]);
    }

    public function showByKaryawan($karyawanMcu)
    {
        $karyawanMcuData = KaryawanMcu::where('id_karyawan', $karyawanMcu)->get();
        $response = [
            'success' => true,
            'message' => 'Data Mcu Karyawan',
            'data' => $karyawanMcuData
        ];
        return response()->json($response, 200, [
            'Content-Type' => 'application/json;charset=UTF-8',
            'Charset' => 'utf-8'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\KaryawanMcu  $karyawanMcu
     * @return \Illuminate\Http\Response
     */
    public function edit(KaryawanMcu $karyawanMcu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\KaryawanMcu  $karyawanMcu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KaryawanMcu $karyawanMcu)
    {
        $karyawanMcu->riwayat_kesehatan = $request->riwayat_kesehatan;
        $karyawanMcu->riwayat_konsumsi_obat = $request->riwayat_konsumsi_obat;
        $karyawanMcu->score_kardiovaskular_jakarta = $request->score_kardiovaskular_jakarta;
        $karyawanMcu->tahun_mcu = $request->tahun_mcu;

        if ($request->hasFile('file_mcu')) {
            $validate = $request->validate([
                'file_mcu' => 'required|mimes:pdf|max:2048'
            ], [
                'file_mcu.required' => 'Foto wajib diunggah.',
                'file_mcu.mimes' => 'file harus berupa file dengan format: pdf.',
                'file_mcu.max' => 'Ukuran file maksimal adalah 2MB.',
            ]);
            if ($karyawanMcu->file_mcu) {
                Storage::delete('public/' . $karyawanMcu->file_mcu);
            }
            $fileMCU = $request->file('file_mcu');
            $path = $fileMCU->store('file-mcu', 'public');
            $karyawanMcu->file_mcu = $path;
        }

        $karyawanMcu->save();
        $response = [
            'success' => true,
            'message' => 'Data MCU updated',
            'data' => $request->riwayat_kesehatan
        ];
        return response()->json($response, 200, [
            'Content-Type' => 'application/json;charset=UTF-8',
            'Charset' => 'utf-8'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\KaryawanMcu  $karyawanMcu
     * @return \Illuminate\Http\Response
     */
    public function destroy(KaryawanMcu $karyawanMcu)
    {
        if ($karyawanMcu->file_mcu) {
            Storage::delete('public/' . $karyawanMcu->file_mcu);
        }
        $karyawanMcu->delete();
        $response = [
            'success' => true,
            'message' => 'data MCU deleted',
        ];
        return response()->json($response, 200, [
            'Content-Type' => 'application/json;charset=UTF-8',
            'Charset' => 'utf-8'
        ]);
    }
}
