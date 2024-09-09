<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Exports\KaryawanTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KaryawanImport;


class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 10;
        $query = Karyawan::query();
        if ($request->has('search')) {
            $query->where('no_badge', 'like', '%' . $request->search . '%');
            $query->orWhere('nama_karyawan', 'like', '%' . $request->search . '%');
            $query->orWhere('tempat_lahir', 'like', '%' . $request->search . '%');
            $query->orWhere('tgl_lahir', 'like', '%' . $request->search . '%');
            $query->orWhere('no_hp_wa', 'like', '%' . $request->search . '%');
            $query->orWhere('nama_istri_suami', 'like', '%' . $request->search . '%');
            $query->orWhere('no_hp_istri_suami', 'like', '%' . $request->search . '%');
        }

        $karyawans = $query->latest()->paginate($limit);
        $no = $karyawans->firstItem();
        foreach ($karyawans as $index => $item) {
            $item->no = $no;
            $item->usia = $item->usia;
            $no++;
        }

        $response = [
            'success' => true,
            'message' => 'List karyawans',
            'data' => [
                'data' => $karyawans->items(),
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
            'no_badge' => 'required|unique:karyawans,no_badge',
            'nama_karyawan' => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required',
            'no_hp_wa' => 'required|integer',
            'nama_istri_suami' => 'required',
            'no_hp_istri_suami' => 'required|integer',
            'foto' => 'required|mimes:jpg,png,jpeg|max:2048',
            'email' => 'required|email|unique:karyawans,email'
        ], [
            'no_badge.required' => 'Nomor badge wajib diisi.',
            'no_badge.unique' => 'Nomor badge sudah terdaftar.',
            'nama_karyawan.required' => 'Nama karyawan wajib diisi.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tgl_lahir.required' => 'Tanggal lahir wajib diisi.',
            'no_hp_wa.required' => 'Nomor HP/WA wajib diisi.',
            'no_hp_wa.integer' => 'Nomor HP/WA harus berupa angka.',
            'nama_istri_suami.required' => 'Nama istri/suami wajib diisi.',
            'no_hp_istri_suami.required' => 'Nomor HP istri/suami wajib diisi.',
            'no_hp_istri_suami.integer' => 'Nomor HP istri/suami harus berupa angka.',
            'foto.required' => 'Foto wajib diunggah.',
            'foto.mimes' => 'Foto harus berupa file dengan format: jpg, png, jpeg.',
            'foto.max' => 'Ukuran foto maksimal adalah 2MB.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.'
        ]);

        $fotoKaryawans = $request->file('foto');
        $path = $fotoKaryawans->store('foto-karyawans', 'public');

        $validate['foto'] = $path;
        $validate['password'] = Hash::make(date('dmY', strtotime($validate['tgl_lahir'])));

        $karyawan = Karyawan::create($validate);
        $response = [
            'success' => true,
            'message' => 'Karyawan created',
            'data' => $karyawan
        ];
        return response()->json($response, 200, [
            'Content-Type' => 'application/json;charset=UTF-8',
            'Charset' => 'utf-8'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Karyawan  $karyawan
     * @return \Illuminate\Http\Response
     */
    public function show(Karyawan $karyawan)
    {
        $response = [
            'success' => true,
            'message' => 'Data Karyawan',
            'data' => $karyawan
        ];
        return response()->json($response, 200, [
            'Content-Type' => 'application/json;charset=UTF-8',
            'Charset' => 'utf-8'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Karyawan  $karyawan
     * @return \Illuminate\Http\Response
     */
    public function edit(Karyawan $karyawan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Karyawan  $karyawan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Karyawan $karyawan)
    {


        $karyawan->no_badge = $request->no_badge;
        $karyawan->nama_karyawan = $request->nama_karyawan;
        $karyawan->tempat_lahir = $request->tempat_lahir;
        $karyawan->tgl_lahir = $request->tgl_lahir;
        $karyawan->no_hp_wa = $request->no_hp_wa;
        $karyawan->nama_istri_suami = $request->nama_istri_suami;
        $karyawan->no_hp_istri_suami = $request->no_hp_istri_suami;
        $karyawan->password = Hash::make(date('dmY', strtotime($request->tgl_lahir)));
        $karyawan->email = $request->email;

        if ($request->hasFile('foto')) {
            if ($karyawan->foto) {
                Storage::delete('public/' . $karyawan->foto);
            }
            $fotoKaryawans = $request->file('foto');
            $path = $fotoKaryawans->store('foto-karyawans', 'public');
            $karyawan->foto = $path;
        }

        $karyawan->save();
        $response = [
            'success' => true,
            'message' => 'Karyawan updated',
            'data' => $karyawan
        ];
        return response()->json($response, 200, [
            'Content-Type' => 'application/json;charset=UTF-8',
            'Charset' => 'utf-8'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Karyawan  $karyawan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Karyawan $karyawan)
    {

        try {
            // Panggil metode delete untuk menghapus data
            if ($karyawan->foto) {
                Storage::delete('public/' . $karyawan->foto);
            }
            $karyawan->delete();
            $response = [
                'success' => true,
                'message' => 'Karyawan deleted',
            ];
            return response()->json($response, 200, [
                'Content-Type' => 'application/json;charset=UTF-8',
                'Charset' => 'utf-8'
            ]);
        } catch (\Exception $e) {
            // Tangkap pengecualian jika terjadi kesalahan saat menghapus
            return response()->json([
                'status' => 'Gagal',
                'message' => 'Gagal menghapus data karyawan: ' . $e->getMessage()
            ], 500); // Kode status 500 untuk internal server error
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new KaryawanTemplateExport, 'karyawan_template.xlsx');
    }

    public function importKaryawan(Request $request)
    {
        // Validate that the request contains a file and that it is an Excel file
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            // Import the Excel file using the KaryawanImport class
            $data = Excel::import(new KaryawanImport, $request->file('excel_file'));

            // // Return a success message
            // return response()->json([
            //     'message' => 'File imported successfully',
            // ]);
            // $file = $request->file('excel_file');
            // $data = Excel::toArray(new KaryawanImport, $file);

            return response()->json([
                'message' => 'File imported successfully',
                'data'=>$data
            ]);
        } catch (\Exception $e) {
            // Handle any errors during the import process
            return response()->json([
                'message' => 'Error importing file: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function exportKaryawan(Request $request)
    {
        
    }
}
