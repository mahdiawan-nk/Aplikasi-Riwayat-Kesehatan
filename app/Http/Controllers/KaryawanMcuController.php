<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\KaryawanMcu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\MedicalCondition;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

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
        $query = KaryawanMcu::with(['karyawan', 'medicalCondition:id,name', 'statusFitToWork:id,name_status']);
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->whereHas('karyawan', function ($q) use ($searchTerm) {
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
                'data' => $karyawans->items(),
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
            'file_mcu' => 'required|mimes:pdf|max:2048',
            'hasil_mcu' =>  'required',
            'fitwork_condition' => 'required',
            // 'medical_condition'=>'required|array|min:1',
            // 'medical_condition.*'=>'exists:medical_conditions,id'
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
            'hasil_mcu.required' => 'Hasil MCU wajib diisi.',
            // 'fitwork_condition.required' => 'Fitwork condition wajib diisi.',
            // 'medical_condition.required' => 'Medical condition wajib diisi.'
        ]);

        $fileMcu = $request->file('file_mcu');
        $path = $fileMcu->store('file-mcu', 'public');

        $validate['file_mcu'] = $path;
        $validate['status_fit_to_work'] = $request->fitwork_condition;

        $karyawanMcu = KaryawanMcu::create($validate);

        $karyawanMcu->medicalCondition()->attach($request->medical_condition);

        $response = [
            'success' => true,
            'message' => 'Karyawan created',
            'data' => $validate
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
        $dataMedical = $karyawanMcu->medicalCondition()->pluck('id_medical_condition')->toArray();
        $karyawanMcu->medical_condition = $dataMedical;
        $response = [
            'success' => true,
            'message' => 'Data Mcu Karyawan',
            'data' => $karyawanMcu,
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
        $karyawanMcu->status_fit_to_work = $request->fitwork_condition;
        $karyawanMcu->hasil_mcu = $request->hasil_mcu;

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
        $karyawanMcu->medicalCondition()->sync($request->medical_condition);
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
        $karyawanMcu->medicalCondition()->detach();
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

    public function exportExcel(Request $request)
    {
        // $years = KaryawanMcu::distinct()
        //     ->orderBy('tahun_mcu', 'asc')
        //     ->pluck('tahun_mcu');
        // $datasMCU = $this->getDataMcu();
        // $mcuData = [];
        // foreach ($datasMCU as $item) {
        //     foreach ($years as $year) {
        //         if (isset($item->mcu->$year)) {  // Akses sebagai objek
        //         $mcuData[] = $item->mcu->$year['status_fit_to_work']; // Akses data MCU per tahun
        //         }
        //     }
        // }
        // return response()->json($mcuData);
        // return;
        $requestYear = $request->input('year');
        // Create new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Query the medical conditions from the database
        $medicalConditions = MedicalCondition::all()->pluck('name')->toArray();

        // Define the static headers
        $mainHeaders = [
            'Card ID',
            'Full Name',
            'Status Fit To Work',
            'Hasil Medical Check Up',
            'Kardiovaskuler',
        ];
        $years = KaryawanMcu::distinct()
            ->orderBy('tahun_mcu', 'asc')
            ->pluck('tahun_mcu');
        // $years = $this->getLastFiveYears($requestYear);

        // Subheaders for years
        $subHeaders = [
            '', // Card ID
            '', // Full Name
        ];

        // Add subheaders dynamically for each section (Status Fit To Work, Hasil Medical Check Up, Kardiovaskuler)
        foreach ($years as $year) {
            $subHeaders[] = $year; // Status Fit To Work for each year
        }

        foreach ($years as $year) {
            $subHeaders[] = $year; // Hasil Medical Check Up for each year
        }

        foreach ($years as $year) {
            $subHeaders[] = $year; // Kardiovaskuler for each year
        }

        // Add subheaders for medical conditions
        foreach ($medicalConditions as $condition) {
            foreach ($years as $year) {
                $subHeaders[] = $year; // Tahun untuk setiap medical condition
            }
        }
        // Set main headers
        $sheet->fromArray($mainHeaders, null, 'A1');

        // Set sub-headers
        $sheet->fromArray($subHeaders, null, 'A2');

        $jmlYears = count($years);
        $addings = $jmlYears - 1;
        $FitWorkStart = 3;
        $FitWorkEnds = count($years) > 1 ? $FitWorkStart + $addings  : $FitWorkStart;
        $McuStart = $FitWorkEnds + 1;
        $McuEnds = $FitWorkEnds + $jmlYears;
        $KardioStart = $McuEnds + 1;
        $KardioEnds = $McuEnds + $jmlYears;

        // Calculate column end for Fit Work
        $startColFitWork = chr(64 + 3);
        $endColFitWork = chr(64 + $FitWorkEnds);

        // Calculate column range for MCU
        $startColMCU = chr(64 + $McuStart);
        $endColMCU = chr(64 + $McuEnds);

        // Calculate column range for Kardiovascular
        $startColKardio = chr(64 + $KardioStart);
        $endColKardio = chr(64 + $KardioEnds);

        $sheet->setCellValue('C1', 'Status Fit To Work');
        $sheet->setCellValue($startColMCU . '1', 'Hasil MCU');
        $sheet->setCellValue($startColKardio . '1', 'Kardiovaskuler');
        $no = 1;
        foreach ($medicalConditions as $condition) {
            // Calculate the start column for this condition
            $column = $this->getExcelColumnName($KardioEnds + 1 + (($no - 1) * $jmlYears)); // Based on $no and years count

            // Calculate the end column based on the number of years for this condition
            $columnnext = $this->getExcelColumnName($KardioEnds + ($no * $jmlYears));

            // Set cell value in the header row and merge cells across the years for the condition
            $sheet->setCellValue($column[0] . '1', $condition);
            $sheet->mergeCells($column[0] . '1:' . $columnnext[0] . '1');

            $no++; // Increment for the next condition
        }
        $sheet->mergeCells("A1:A2");
        $sheet->mergeCells("B1:B2");
        $sheet->mergeCells($startColFitWork . "1:" . $endColFitWork . "1"); // Status Fit To Work
        $sheet->mergeCells($startColMCU . "1:" . $endColMCU . "1"); // Hasil Medical Check Up
        $sheet->mergeCells($startColKardio . "1:" . $endColKardio . "1"); // Kardiovaskuler

        $row = 3; // Baris mulai data karyawan
        $datasMCU = $this->getDataMcu(); // Data hasil query
        $startColumn = 'C';
        foreach ($datasMCU as $item) {
            $sheet->setCellValue('A' . $row, $item->no_badge);
            $sheet->setCellValue('B' . $row, $item->nama_karyawan);

            $column = $startColumn;
            $fitWork =[];
            $hasilMcu =[];
            $skj=[];
            // Isi data MCU berdasarkan tahun yang tersedia
            foreach ($years as $year) {
                if (isset($item->mcu->$year)) {  // Akses sebagai objek
                    $fitWork[]= $item->mcu->$year['status_fit_to_work']; // Akses data MCU per tahun
                    $hasilMcu[]= strip_tags($item->mcu->$year['hasil_mcu']); // Akses data MCU per tahun
                    $skj[] = $item->mcu->$year['skj'];
                }
            }
            $sheet->fromArray($fitWork, null, $startColFitWork . $row);
            $sheet->fromArray($hasilMcu, null, $startColMCU . $row);
            $sheet->fromArray($skj, null, $startColKardio . $row);
            $row++;
        }


        // Apply some basic styling to the header
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '2')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'BDD7EE', // Light blue background color
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // Set the row height for the header
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getRowDimension(2)->setRowHeight(20);

        // Set column widths to auto-fit the content
        foreach (range('A', $sheet->getHighestColumn()) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $exportPath = storage_path('app/public/exports/');
        if (!file_exists($exportPath)) {
            mkdir($exportPath, 0755, true); // Create the directory with proper permissions
        }

        // Save the file to the server and return as download
        $writer = new Xlsx($spreadsheet);
        $fileName = 'medical_checkup_header_' . time() . '.xlsx';
        $filePath = 'exports/' . $fileName;
        $writer->save(storage_path('app/public/' . $filePath));

        return response()->download(storage_path('app/public/' . $filePath))->deleteFileAfterSend(true);
    }
    function getExcelColumnName($index)
    {
        $letters = '';
        $newIndex = $index;
        while ($index > 0) {
            $index--; // Adjust for 1-based index
            $letters = chr(65 + ($index % 26)) . $letters;
            $index = intval($index / 26);
        }
        return [$letters, $newIndex];
    }

    private function getLastFiveYears($requestYear = null)
    {
        // Use the provided year or default to the current year
        $year = $requestYear ? \Carbon\Carbon::createFromFormat('Y', $requestYear)->year : \Carbon\Carbon::now()->year;

        $years = [];
        for ($i = 0; $i < 5; $i++) {
            $years[] = $year;
            $year--;
        }
        sort($years);
        return $years;
    }

    private function getDataMcu()
    {
        $years = KaryawanMcu::distinct()
            ->orderBy('tahun_mcu', 'asc')
            ->pluck('tahun_mcu');

        $karyawanData = Karyawan::select('id', 'nama_karyawan', 'no_badge')->get();

        $dataMcu = [];

        foreach ($karyawanData as $karyawan) {
            $mcu = KaryawanMcu::with(['statusFitToWork:id,name_status', 'medicalCondition:id,name'])
                ->where('id_karyawan', $karyawan->id)
                ->whereIn('tahun_mcu', $years)
                ->get();

            $mcus = [];
            $mdcu = [];

            foreach ($mcu as $item) {
                foreach ($item->medicalCondition as $items) {
                    $mdcu[$items->id] = [
                        'name' => $items->name,
                    ];
                }

                $mcus[$item->tahun_mcu] = [
                    'skj' => $item->score_kardiovaskular_jakarta,
                    'hasil_mcu' => $item->hasil_mcu,
                    'status_fit_to_work' => $item->statusFitToWork->name_status,
                    'medical_condition' => (object)$mdcu // Convert medical condition to object
                ];
            }

            $dataMcu[] = (object)[
                'no_badge' => $karyawan->no_badge,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'mcu' => (object)$mcus // Convert MCU data to object
            ];
        }

        return (object)$dataMcu; // Return the data as object
    }
}
