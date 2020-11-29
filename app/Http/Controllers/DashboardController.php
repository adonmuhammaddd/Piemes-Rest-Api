<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Ppk;
use App\Satker;
use App\Dokumen;
use App\DokTemuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwtmiddleware');
    }

    public function index()
    {
        $result = [];
        $satkerTemp = [];
        $dokumenTemp = [];
        $dataTemp = [];
            
        $countBpkTemp = [
            'tersedia' => 0,
            'dalamProses' => 0,
            'tuntas' => 0,
            'total' => 0
        ];

        $countInspektorat = [
            'tersedia' => 0,
            'dalamProses' => 0,
            'tuntas' => 0,
            'total' => 0
        ];

        $dokumenTemuan = DB::table('tbl_dokumen_temuan')
        ->where('isDeleted', 0)
        ->get();

        if (Auth::user()->roleId != 30)
        {

            $satker = DB::table('tbl_satker')
                ->where('isDeleted', 0)
                ->get();

            $dokumenTindakLanjut = DB::table('view_dashboard_records')
                ->select('statusTindakLanjut', 'satkerId', 'jenisDokumenTemuanId')
                ->where('isDeleted', 0)
                ->get();
            
            for ($i = 0; $i < count($satker); $i++)
            {
                $satkerTemp[] = [
                    'namaSatker' => $satker[$i]->namaSatker,
                    'satkerId' => $satker[$i]->id,
                    'bpk' => $countBpkTemp,
                    'inspektorat' => $countInspektorat,
                ];
            }

            for ($j = 0; $j < count($dokumenTindakLanjut); $j++)
            {
                if ($dokumenTindakLanjut[$j]->jenisDokumenTemuanId == 1)
                {
                    for ($k = 0; $k < count($satkerTemp); $k++)
                    {
                        if ($dokumenTindakLanjut[$j]->satkerId == $satkerTemp[$k]['satkerId'])
                        {
                            if ($dokumenTindakLanjut[$j]->statusTindakLanjut == 'Tersedia')
                            {
                                // $countTersedia++;
                                $satkerTemp[$k]['bpk']['tersedia']++;
                            }
                            else if ($dokumenTindakLanjut[$j]->statusTindakLanjut == 'Dalam Proses')
                            {
                                // $countDalamProses++;
                                $satkerTemp[$k]['bpk']['dalamProses']++;
                            }
                            else if ($dokumenTindakLanjut[$j]->statusTindakLanjut == 'Tuntas')
                            {
                                // $countSelesai++;
                                $satkerTemp[$k]['bpk']['tuntas']++;
                            }
                        }
                        $satkerTemp[$k]['bpk']['total'] = $satkerTemp[$k]['bpk']['tersedia']+$satkerTemp[$k]['bpk']['dalamProses']+$satkerTemp[$k]['bpk']['tuntas'];
                    }
                }
                else
                {
                    for ($k = 0; $k < count($satkerTemp); $k++)
                    {
                        if ($dokumenTindakLanjut[$j]->satkerId == $satkerTemp[$k]['satkerId'])
                        {
                            if ($dokumenTindakLanjut[$j]->statusTindakLanjut == 'Tersedia')
                            {
                                // $countTersedia++;
                                $satkerTemp[$k]['inspektorat']['tersedia']++;
                            }
                            else if ($dokumenTindakLanjut[$j]->statusTindakLanjut == 'Dalam Proses')
                            {
                                // $countDalamProses++;
                                $satkerTemp[$k]['inspektorat']['dalamProses']++;
                            }
                            else if ($dokumenTindakLanjut[$j]->statusTindakLanjut == 'Tuntas')
                            {
                                // $countSelesai++;
                                $satkerTemp[$k]['inspektorat']['tuntas']++;
                            }
                        }
                        $satkerTemp[$k]['inspektorat']['total'] = $satkerTemp[$k]['inspektorat']['tersedia']+$satkerTemp[$k]['inspektorat']['dalamProses']+$satkerTemp[$k]['inspektorat']['tuntas'];
                    }
                }
            }

            for ($i = 0; $i < count($satkerTemp); $i++)
            {
                $result[] = [
                    'namaSatker' => $satkerTemp[$i]['namaSatker'],
                    'satkerId' => $satkerTemp[$i]['satkerId'],
                    'bpk' => [
                        'tersedia' => $satkerTemp[$i]['bpk']['tersedia'],
                        'dalamProses' => $satkerTemp[$i]['bpk']['dalamProses'],
                        'tuntas' => $satkerTemp[$i]['bpk']['tuntas'],
                        'total' => $satkerTemp[$i]['bpk']['total']
                    ],
                    'inspektorat' => [
                        'tersedia' => $satkerTemp[$i]['inspektorat']['tersedia'],
                        'dalamProses' => $satkerTemp[$i]['inspektorat']['dalamProses'],
                        'tuntas' => $satkerTemp[$i]['inspektorat']['tuntas'],
                        'total' => $satkerTemp[$i]['inspektorat']['total']
                    ]
                    // 'Tersedia' => $satkerTemp[$i]['tersedia'] == 0 ? null : $satkerTemp[$i]['tersedia'],
                    // 'Dalam Proses' => $satkerTemp[$i]['dalamProses']  == 0 ? null : $satkerTemp[$i]['dalamProses'],
                    // 'Tuntas' => $satkerTemp[$i]['tuntas']  == 0 ? null : $satkerTemp[$i]['tuntas'],
                    // 'Total' => $satkerTemp[$i]['total']  == 0 ? null : $satkerTemp[$i]['total']
                ];
            }

            return response()->json([
                'message' => 'Dashboard Admin',
                'isDashboardSatker' => false,
                'result' => $result
                // 'totalTindakLanjut' => $dokumenTindakLanjut->count(),
                // 'totalTemuan' => $dokumenTemuan->count()
            ]);
        }
        else
        {
            $satker = DB::table('tbl_satker')
                ->select('namaSatker')
                ->where('id', Auth::user()->satkerId)
                ->first();

            $dokumenTindakLanjut = DB::table('view_dashboard_records')
                ->select('statusTindakLanjut', 'satkerId','jenisDokumenTemuanId')
                ->where('satkerId', Auth::user()->satkerId)
                ->where('isDeleted', 0)
                ->get();

            $countDokumenTindakLanjut = DB::table('tbl_dokumen')
                ->select('*')
                ->where('isDeleted', 0)
                ->groupBy('uniqueColumn')
                ->count();
            
            $satkerTemp[] = [
                'namaSatker' => $satker->namaSatker,
                'satkerId' => Auth::user()->satkerId,
                'bpk' => $countBpkTemp,
                'inspektorat' => $countInspektorat,
            ];

            for ($j = 0; $j < count($dokumenTindakLanjut); $j++)
            {
                if ($dokumenTindakLanjut[$j]->jenisDokumenTemuanId == 1)
                {
                    for ($k = 0; $k < count($satkerTemp); $k++)
                    {
                        if ($dokumenTindakLanjut[$j]->statusTindakLanjut == 'Tersedia')
                        {
                            // $countTersedia++;
                            $satkerTemp[$k]['bpk']['tersedia']++;
                        }
                        else if ($dokumenTindakLanjut[$j]->statusTindakLanjut == 'Dalam Proses')
                        {
                            // $countDalamProses++;
                            $satkerTemp[$k]['bpk']['dalamProses']++;
                        }
                        else if ($dokumenTindakLanjut[$j]->statusTindakLanjut == 'Tuntas')
                        {
                            // $countSelesai++;
                            $satkerTemp[$k]['bpk']['tuntas']++;
                        }
                        $satkerTemp[$k]['bpk']['total'] = $satkerTemp[$k]['bpk']['tersedia']+$satkerTemp[$k]['bpk']['dalamProses']+$satkerTemp[$k]['bpk']['tuntas'];
                    }
                }
                else
                {
                    for ($k = 0; $k < count($satkerTemp); $k++)
                    {
                        if ($dokumenTindakLanjut[$j]->statusTindakLanjut == 'Tersedia')
                        {
                            // $countTersedia++;
                            $satkerTemp[$k]['inspektorat']['tersedia']++;
                        }
                        else if ($dokumenTindakLanjut[$j]->statusTindakLanjut == 'Dalam Proses')
                        {
                            // $countDalamProses++;
                            $satkerTemp[$k]['inspektorat']['dalamProses']++;
                        }
                        else if ($dokumenTindakLanjut[$j]->statusTindakLanjut == 'Tuntas')
                        {
                            // $countSelesai++;
                            $satkerTemp[$k]['inspektorat']['tuntas']++;
                        }
                        $satkerTemp[$k]['inspektorat']['total'] = $satkerTemp[$k]['inspektorat']['tersedia']+$satkerTemp[$k]['inspektorat']['dalamProses']+$satkerTemp[$k]['inspektorat']['tuntas'];
                    }
                }
            }

            for ($i = 0; $i < count($satkerTemp); $i++)
            {
                $result[] = [
                    'namaSatker' => $satkerTemp[$i]['namaSatker'],
                    'satkerId' => $satkerTemp[$i]['satkerId'],
                    'bpk' => [
                        'tersedia' => $satkerTemp[$i]['bpk']['tersedia'],
                        'dalamProses' => $satkerTemp[$i]['bpk']['dalamProses'],
                        'tuntas' => $satkerTemp[$i]['bpk']['tuntas'],
                        'total' => $satkerTemp[$i]['bpk']['total']
                    ],
                    'inspektorat' => [
                        'tersedia' => $satkerTemp[$i]['inspektorat']['tersedia'],
                        'dalamProses' => $satkerTemp[$i]['inspektorat']['dalamProses'],
                        'tuntas' => $satkerTemp[$i]['inspektorat']['tuntas'],
                        'total' => $satkerTemp[$i]['inspektorat']['total']
                    ]
                    // 'Tersedia' => $satkerTemp[$i]['tersedia'] == 0 ? null : $satkerTemp[$i]['tersedia'],
                    // 'Dalam Proses' => $satkerTemp[$i]['dalamProses']  == 0 ? null : $satkerTemp[$i]['dalamProses'],
                    // 'Tuntas' => $satkerTemp[$i]['tuntas']  == 0 ? null : $satkerTemp[$i]['tuntas'],
                    // 'Total' => $satkerTemp[$i]['total']  == 0 ? null : $satkerTemp[$i]['total']
                ];
            }

            return response()->json([
                'message' => 'Dashboard Admin',
                'isDashboardSatker' => false,
                'result' => $result
            ]);
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
