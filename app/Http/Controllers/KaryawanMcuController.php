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

    // public function exportExcel(Request $request)
    // {
    //     $requestYear = $request->input('year');
    //     // Create new Spreadsheet
    //     $spreadsheet = new Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();

    //     // Query the medical conditions from the database
    //     $medicalConditions = MedicalCondition::all()->pluck('name')->toArray();

    //     // Define the static headers
    //     $mainHeaders = [
    //         'Card ID',
    //         'Full Name',
    //         'Status Fit To Work',
    //         'Hasil Medical Check Up',
    //         'Kardiovaskuler',
    //     ];
    //     $years = KaryawanMcu::distinct()
    //         ->orderBy('tahun_mcu', 'asc')
    //         ->pluck('tahun_mcu');
    //     // $years = $this->getLastFiveYears($requestYear);

    //     // Subheaders for years
    //     $subHeaders = [
    //         '', // Card ID
    //         '', // Full Name
    //     ];

    //     // Add subheaders dynamically for each section (Status Fit To Work, Hasil Medical Check Up, Kardiovaskuler)
    //     foreach ($years as $year) {
    //         $subHeaders[] = $year; // Status Fit To Work for each year
    //     }

    //     foreach ($years as $year) {
    //         $subHeaders[] = $year; // Hasil Medical Check Up for each year
    //     }

    //     foreach ($years as $year) {
    //         $subHeaders[] = $year; // Kardiovaskuler for each year
    //     }

    //     // Add subheaders for medical conditions
    //     foreach ($medicalConditions as $condition) {
    //         foreach ($years as $year) {
    //             $subHeaders[] = $year; // Tahun untuk setiap medical condition
    //         }
    //     }
    //     // Set main headers
    //     $sheet->fromArray($mainHeaders, null, 'A1');

    //     // Set sub-headers
    //     $sheet->fromArray($subHeaders, null, 'A2');

    //     $jmlYears = count($years);
    //     $addings = $jmlYears - 1;
    //     $FitWorkStart = 3;
    //     $FitWorkEnds = count($years) > 1 ? $FitWorkStart + $addings  : $FitWorkStart;
    //     $McuStart = $FitWorkEnds + 1;
    //     $McuEnds = $FitWorkEnds + $jmlYears;
    //     $KardioStart = $McuEnds + 1;
    //     $KardioEnds = $McuEnds + $jmlYears;

    //     // Calculate column end for Fit Work
    //     $startColFitWork = chr(64 + 3);
    //     $endColFitWork = chr(64 + $FitWorkEnds);

    //     // Calculate column range for MCU
    //     $startColMCU = chr(64 + $McuStart);
    //     $endColMCU = chr(64 + $McuEnds);

    //     // Calculate column range for Kardiovascular
    //     $startColKardio = chr(64 + $KardioStart);
    //     $endColKardio = chr(64 + $KardioEnds);

    //     $sheet->setCellValue('C1', 'Status Fit To Work');
    //     $sheet->setCellValue($startColMCU . '1', 'Hasil MCU');
    //     $sheet->setCellValue($startColKardio . '1', 'Kardiovaskuler');
    //     $no = 1;
    //     foreach ($medicalConditions as $condition) {
    //         // Calculate the start column for this condition
    //         $column = $this->getExcelColumnName($KardioEnds + 1 + (($no - 1) * $jmlYears)); // Based on $no and years count

    //         // Calculate the end column based on the number of years for this condition
    //         $columnnext = $this->getExcelColumnName($KardioEnds + ($no * $jmlYears));

    //         // Set cell value in the header row and merge cells across the years for the condition
    //         $sheet->setCellValue($column[0] . '1', $condition);
    //         $sheet->mergeCells($column[0] . '1:' . $columnnext[0] . '1');

    //         $no++; // Increment for the next condition
    //     }
    //     $sheet->mergeCells("A1:A2");
    //     $sheet->mergeCells("B1:B2");
    //     $sheet->mergeCells($startColFitWork . "1:" . $endColFitWork . "1"); // Status Fit To Work
    //     $sheet->mergeCells($startColMCU . "1:" . $endColMCU . "1"); // Hasil Medical Check Up
    //     $sheet->mergeCells($startColKardio . "1:" . $endColKardio . "1"); // Kardiovaskuler

    //     $row = 3; // Baris mulai data karyawan
    //     $datasMCU = $this->getDataMcu(); // Data hasil query
    //     $startColumn = 'C';
    //     foreach ($datasMCU as $item) {
    //         $sheet->setCellValue('A' . $row, $item->no_badge);
    //         $sheet->setCellValue('B' . $row, $item->nama_karyawan);

    //         $column = $startColumn;
    //         $fitWork = [];
    //         $hasilMcu = [];
    //         $skj = [];
    //         $medcond = [];
    //         // Isi data MCU berdasarkan tahun yang tersedia
    //         foreach ($years as $year) {
    //             if (isset($item->mcu->$year)) {  // Akses sebagai objek
    //                 $fitWork[] = $item->mcu->$year['status_fit_to_work']; // Akses data MCU per tahun
    //                 $hasilMcu[] = strip_tags($item->mcu->$year['hasil_mcu']); // Akses data MCU per tahun
    //                 $skj[] = $item->mcu->$year['skj'];
    //                 // Collect medical conditions
    //                 foreach ($item->mcu->$year['medical_condition'] as $condition) {
    //                     $medcond[] = $condition['name']; // Assuming each condition has a 'name'
    //                 }
    //             }
    //         }
    //         $sheet->fromArray($fitWork, null, $startColFitWork . $row);
    //         $sheet->fromArray($hasilMcu, null, $startColMCU . $row);
    //         $sheet->fromArray($skj, null, $startColKardio . $row);
    //         $noc = 1;
    //         foreach ($years as $year) {
    //             if (isset($item->mcu->$year)) { // Check if data exists for the year
    //                 foreach ($medicalConditions as $condition) {
    //                     // Calculate the start column for this condition
    //                     $column = $this->getExcelColumnName($KardioEnds + 1 + (($noc - 1) * $jmlYears)); // Based on $noc and years count

    //                     // Insert sample data if the condition exists for that year
    //                     if (isset($medcond[$year])) {
    //                         // Add all conditions for that year
    //                         foreach ($medcond[$year] as $cond) {
    //                             $sheet->setCellValue($column[0] . $row, "Sample Data: $cond in $year");
    //                             $column[0]++; // Move to the next column for the next condition
    //                         }
    //                     }
    //                 }
    //                 $noc++;
    //             }
    //         }
    //         $row++;
    //     }


    //     // Apply some basic styling to the header
    //     $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '2')->applyFromArray([
    //         'font' => [
    //             'bold' => true,
    //         ],
    //         'alignment' => [
    //             'horizontal' => Alignment::HORIZONTAL_CENTER,
    //             'vertical' => Alignment::VERTICAL_CENTER,
    //         ],
    //         'fill' => [
    //             'fillType' => Fill::FILL_SOLID,
    //             'startColor' => [
    //                 'rgb' => 'BDD7EE', // Light blue background color
    //             ],
    //         ],
    //         'borders' => [
    //             'allBorders' => [
    //                 'borderStyle' => Border::BORDER_THIN,
    //                 'color' => ['argb' => '000000'],
    //             ],
    //         ],
    //     ]);

    //     // Set the row height for the header
    //     $sheet->getRowDimension(1)->setRowHeight(25);
    //     $sheet->getRowDimension(2)->setRowHeight(20);

    //     // Set column widths to auto-fit the content
    //     foreach (range('A', $sheet->getHighestColumn()) as $column) {
    //         $sheet->getColumnDimension($column)->setAutoSize(true);
    //     }

    //     $exportPath = storage_path('app/public/exports/');
    //     if (!file_exists($exportPath)) {
    //         mkdir($exportPath, 0755, true); // Create the directory with proper permissions
    //     }

    //     // Save the file to the server and return as download
    //     $writer = new Xlsx($spreadsheet);
    //     $fileName = 'medical_checkup_header_' . time() . '.xlsx';
    //     $filePath = 'exports/' . $fileName;
    //     $writer->save(storage_path('app/public/' . $filePath));

    //     return response()->download(storage_path('app/public/' . $filePath))->deleteFileAfterSend(true);
    // }

    public function exportExcel(Request $request)
    {
        // $requestYear = $request->input('year');
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

        $startYear = $request->input('start_year', date('Y') - 1); // Default tahun sebelumnya jika tidak ada input
        $endYear = $request->input('end_year', date('Y')); // Default tahun saat ini jika tidak ada input

        // Query untuk mengambil data MCU berdasarkan range tahun
        $years = KaryawanMcu::whereBetween('tahun_mcu', [$startYear, $endYear])
            ->distinct()
            ->orderBy('tahun_mcu', 'asc')
            ->pluck('tahun_mcu');

        // Cek hasil
        if ($years->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No data found for the given year range'], 200);
        }
        // $years = KaryawanMcu::distinct()
        //     ->orderBy('tahun_mcu', 'asc')
        //     ->pluck('tahun_mcu');

        // Subheaders for years
        $subHeaders = [
            '', // Card ID
            '', // Full Name
        ];

        foreach ($years as $year) {
            $subHeaders[] = $year; // Status Fit To Work for each year
        }

        foreach ($years as $year) {
            $subHeaders[] = $year; // Hasil Medical Check Up for each year
        }

        foreach ($years as $year) {
            $subHeaders[] = $year; // Kardiovaskuler for each year
        }

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
        $FitWorkStart = 3;
        $FitWorkEnds = $FitWorkStart + $jmlYears - 1;
        $McuStart = $FitWorkEnds + 1;
        $McuEnds = $McuStart + $jmlYears - 1;
        $KardioStart = $McuEnds + 1;
        $KardioEnds = $KardioStart + $jmlYears - 1;

        // Set headers for each section
        $sheet->setCellValue('C1', 'Status Fit To Work');
        $sheet->setCellValue($this->getExcelColumnName($McuStart)[0] . '1', 'Hasil MCU');
        $sheet->setCellValue($this->getExcelColumnName($KardioStart)[0] . '1', 'Kardiovaskuler');

        $no = 1;
        foreach ($medicalConditions as $condition) {
            // Calculate the start column for this condition
            $columnStart = $this->getExcelColumnName($KardioEnds + 1 + (($no - 1) * $jmlYears)); // Based on $no and years count

            // Set cell value in the header row and merge cells across the years for the condition
            $columnEnd = $this->getExcelColumnName($KardioEnds + ($no * $jmlYears));
            $sheet->setCellValue($columnStart[0] . '1', $condition);
            $sheet->mergeCells($columnStart[0] . '1:' . $columnEnd[0] . '1');

            $no++; // Increment for the next condition
        }

        // Merge static headers
        $sheet->mergeCells("A1:A2");
        $sheet->mergeCells("B1:B2");
        $sheet->mergeCells($this->getExcelColumnName($FitWorkStart)[0] . "1:" . $this->getExcelColumnName($FitWorkEnds)[0] . "1"); // Status Fit To Work
        $sheet->mergeCells($this->getExcelColumnName($McuStart)[0] . "1:" . $this->getExcelColumnName($McuEnds)[0] . "1"); // Hasil Medical Check Up
        $sheet->mergeCells($this->getExcelColumnName($KardioStart)[0] . "1:" . $this->getExcelColumnName($KardioEnds)[0] . "1"); // Kardiovaskuler

        $row = 3; // Baris mulai data karyawan
        $datasMCU = $this->getDataMcu(); // Data hasil query

        foreach ($datasMCU as $item) {
            // Set No Badge dan Nama Karyawan
            $sheet->setCellValue('A' . $row, $item->no_badge);
            $sheet->setCellValue('B' . $row, $item->nama_karyawan);

            $fitWork = [];
            $hasilMcu = [];
            $skj = [];
            $medcondPerYear = array_fill(0, $jmlYears, []);

            // Proses data MCU berdasarkan tahun yang tersedia
            foreach ($years as $index => $year) {
                if (isset($item->mcu->$year)) {
                    // Isi data fit to work, hasil MCU, dan SKJ
                    $fitWork[] = $item->mcu->$year->status_fit_to_work;
                    $hasilMcu[] = strip_tags($item->mcu->$year->hasil_mcu);
                    $skj[] = $item->mcu->$year->skj;

                    // Proses Medical Condition
                    if (isset($item->mcu->$year->medical_condition)) {
                        foreach ($item->mcu->$year->medical_condition as $id => $condition) {
                            // Simpan medical condition sesuai dengan tahun dan id kondisi
                            $medcondPerYear[$index][$id] = "✓";
                        }
                    }
                } else {
                    // Jika data tahun tidak ada, isi dengan string kosong
                    $fitWork[] = '';
                    $hasilMcu[] = '';
                    $skj[] = '';
                }
            }

            // Menulis data MCU ke sheet
            $sheet->fromArray($fitWork, null, $this->getExcelColumnName($FitWorkStart)[0] . $row);
            $sheet->fromArray($hasilMcu, null, $this->getExcelColumnName($McuStart)[0] . $row);
            $sheet->fromArray($skj, null, $this->getExcelColumnName($KardioStart)[0] . $row);

            // Memasukkan data Medical Condition ke kolom yang sesuai
            foreach ($medcondPerYear as $yearIndex => $conditionsPerYear) {
                $conditionColumnStart = $this->getExcelColumnName($KardioEnds + 1 + ($yearIndex * $jmlYears));

                foreach ($conditionsPerYear as $id => $condition) {
                    // Tentukan kolom awal untuk medical condition ini dan sesuai dengan tahun yang benar
                    $conditionColumn = $this->getExcelColumnName($KardioEnds + 1 + ($id - 1) * $jmlYears + $yearIndex);
                    $sheet->setCellValue($conditionColumn[0] . $row, $condition);
                }
            }

            $row++; // Pindah ke baris berikutnya untuk data karyawan lainnya
        }

        $jmlKaryawans = Karyawan::all();
        // Apply styling and formatting
        $this->applyStyling($sheet, $jmlYears, $jmlKaryawans);

        // Save the file
        return $this->saveExcelFile($spreadsheet, $startYear, $endYear);
    }

    private function applyStyling($sheet, $jmlYears = 1, $jmlKaryawans = [])
    {
        // Style for header (rows 1 and 2)
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '2')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => [
                'wrapText' => true,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'BDD7EE'],
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
        $sheet->getRowDimension(2)->setRowHeight(70);

        // Apply specific styling for columns starting from row 3
        $sheet->getColumnDimension('D')->setWidth(55); // Set height for row 3

        // Column A styling
        $sheet->getColumnDimension('A')->setWidth(11);

        $sheet->getRowDimension(3)->setRowHeight(120);
        $sheet->getStyle('A3')->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_LEFT,
            ],
        ]);

        // Column B styling
        $sheet->getColumnDimension('B')->setWidth(35);
        // Column C styling form Fit to Work
        $startCol = 3;
        $ColumnFitToWork = $this->LettersInArray($jmlYears, $startCol);

        // Atur lebar kolom pertama (23 untuk setiap huruf di $letters)
        foreach ($ColumnFitToWork as $value) {
            $sheet->getColumnDimension($value)->setWidth(23);
        }
        $newYears = ($jmlYears == 1)
            ? $jmlYears
            : (($jmlYears == $startCol)
                ? $jmlYears + 3
                : ($jmlYears > $startCol
                    ? $jmlYears * 2
                    : $jmlYears + 2));
        $nextColumn = array_key_last($ColumnFitToWork) + 1;
        $ColumnRiwayatKesehatan = $this->LettersInArray($newYears, $nextColumn);

        foreach ($ColumnRiwayatKesehatan as $values) {
            $sheet->getColumnDimension($values)->setWidth(45);
        }

        $newYearss = ($jmlYears == 1)
            ? $jmlYears
            : (($jmlYears == $startCol)
                ? $newYears + 3
                : ($jmlYears > $startCol
                    ? $newYears + $jmlYears
                    : $newYears + 2));
        $nextColumnToNext = array_key_last($ColumnRiwayatKesehatan) + 1;
        $ColumnKardioVaskular = $this->LettersInArray($newYearss, $nextColumnToNext);
        foreach ($ColumnKardioVaskular as $values) {
            $sheet->getColumnDimension($values)->setWidth(15);
        }
        $startColumnMedical = array_key_last($ColumnKardioVaskular) + 1;
        $DataMedical = MedicalCondition::count();
        $jmlColumnMedical = $DataMedical * $jmlYears;
        $ArrayColumnMedical = [];
        for ($a = 1; $a <= $jmlColumnMedical; $a++) {
            $ArrayColumnMedical[$this->getExcelColumnName($a+$startColumnMedical-1)[1]]=$this->getExcelColumnName($a+$startColumnMedical-1)[0];
        }
        $startRow = 3;
        foreach ($jmlKaryawans as $key => $value) {
            $sheet->getRowDimension($startRow)->setRowHeight(120);
            $sheet->getStyle('A' . $startRow . ':B' . $startRow)->applyFromArray([
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ]);
            $sheet->getStyle(reset($ColumnFitToWork) . $startRow . ":" . end($ColumnFitToWork) . $startRow)->applyFromArray([
                'alignment' => [
                    'wrapText' => true,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ]);
            $sheet->getStyle(reset($ColumnRiwayatKesehatan) . $startRow . ":" . end($ColumnRiwayatKesehatan) . $startRow)->applyFromArray([
                'alignment' => [
                    'wrapText' => true,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ],

                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ]);
            $sheet->getStyle(reset($ColumnKardioVaskular) . $startRow . ":" . end($ColumnKardioVaskular) . $startRow)->applyFromArray([
                'alignment' => [
                    'wrapText' => true,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],

                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ]);
            $sheet->getStyle(reset($ArrayColumnMedical) . $startRow . ":" . end($ArrayColumnMedical) . $startRow)->applyFromArray([
                'alignment' => [
                    'wrapText' => true,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ]);
            $startRow++;
        }
    }

    function LettersInArray($jmlYears = 1, $startCol = 3)
    {
        $letters = [];
        if ($jmlYears == 1) {
            $letters[$this->getExcelColumnName($startCol)[1]] = $this->getExcelColumnName($startCol)[0];
        } else {
            if ($jmlYears < $startCol) {
                // Jika jmlYears lebih kecil dari years, lakukan perulangan decrement
                for ($year = $startCol + 1; $year >= $jmlYears + 1; $year--) {
                    $letters[$this->getExcelColumnName($year)[1]] = $this->getExcelColumnName($year)[0];
                }
                ksort($letters);
            } else {
                for ($year = $startCol; $year <= $jmlYears + 2; $year++) {
                    $letters[$this->getExcelColumnName($year)[1]] = $this->getExcelColumnName($year)[0];
                }
            }
        }


        return $letters;
    }

    private function saveExcelFile($spreadsheet, $start_year = null, $end_year = null)
    {
        $exportPath = storage_path('app/public/exports/');
        if (!file_exists($exportPath)) {
            mkdir($exportPath, 0755, true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'medical_checkup_' . $start_year . '_' . $end_year . '_' . time() . '.xlsx';
        $filePath = 'exports/' . $fileName;
        $writer->save(storage_path('app/public/' . $filePath));

        return response()->json(['success' => true, 'fileName' => $fileName, 'filePath' => $filePath]);
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

    public function getDataMcu()
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


            foreach ($mcu as $item) {
                $mdcu = [];
                foreach ($item->medicalCondition as $items) {
                    $mdcu[$items->id] = (object)[
                        'name' => $items->name,
                    ];
                }

                $mcus[$item->tahun_mcu] = (object)[
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
