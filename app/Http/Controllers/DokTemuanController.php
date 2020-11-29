<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\DokTemuan;
use App\Dokumen;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

class DokTemuanController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwtmiddleware');
    }

    public function index()
    {
        $result = DB::table('view_dokumen_temuan_records')
            ->where('isDeleted', 0)
            ->get();

        return response()->json(compact('result'), 200);
    }

    public function ajax_list($jenisDokumen)
    {
        $result = [];
        if ($jenisDokumen == 'bpk')
        {
            if (Auth::user()->roleId == 40 || Auth::user()->roleId == 50)
            {
                $dataTemuan = DB::table('tbl_dokumen_temuan')
                    ->where('isDeleted', 0)
                    ->where('jenisDokumenTemuanId', 1)
                    ->get();

                $idDataTemuan = DB::table('tbl_dokumen_temuan')
                    ->select('id')
                    ->where('isDeleted', 0)
                    ->where('jenisDokumenTemuanId', 1)
                    ->get();
            }
            else
            {
                $dataTemuan = DB::table('tbl_dokumen_temuan')
                    ->where('isDeleted', 0)
                    ->where('satkerId', Auth::user()->satkerId)
                    ->where('jenisDokumenTemuanId', 1)
                    ->get();

                $idDataTemuan = DB::table('tbl_dokumen_temuan')
                    ->select('id')
                    ->where('isDeleted', 0)
                    ->where('satkerId', Auth::user()->satkerId)
                    ->where('jenisDokumenTemuanId', 1)
                    ->get();
            }
        }
        else
        {
            if (Auth::user()->roleId == 40 || Auth::user()->roleId == 50)
            {
                $dataTemuan = DB::table('tbl_dokumen_temuan')
                    ->where('isDeleted', 0)
                    ->where('jenisDokumenTemuanId', 2)
                    ->get();

                $idDataTemuan = DB::table('tbl_dokumen_temuan')
                    ->select('id')
                    ->where('isDeleted', 0)
                    ->where('jenisDokumenTemuanId', 2)
                    ->get();
            }
            else
            {
                $dataTemuan = DB::table('tbl_dokumen_temuan')
                    ->where('isDeleted', 0)
                    ->where('satkerId', Auth::user()->satkerId)
                    ->where('jenisDokumenTemuanId', 2)
                    ->get();

                $idDataTemuan = DB::table('tbl_dokumen_temuan')
                    ->select('id')
                    ->where('isDeleted', 0)
                    ->where('satkerId', Auth::user()->satkerId)
                    ->where('jenisDokumenTemuanId', 2)
                    ->get();
            }
        }

        $ids = [];

        for ($i = 0; $i < count($idDataTemuan); $i++)
        {
            array_push($ids, $idDataTemuan[$i]->id);
        }

        $dokumen = Dokumen::whereIn('dokumenTemuanId', $ids)
            ->join('tbl_satker', 'tbl_dokumen.satkerId', '=', 'tbl_satker.id')
            ->join('tbl_ppk', 'tbl_dokumen.ppkId', '=', 'tbl_ppk.id')
            ->select('tbl_dokumen.id', 'tbl_dokumen.noUraianTemuan', 'tbl_dokumen.uraianTemuan', 'tbl_dokumen.kodeRekomendasi', 'tbl_dokumen.kodeRingkasanTindakLanjut', 'tbl_dokumen.statusTindakLanjut', 'tbl_dokumen.subNomorRekomendasi', 'tbl_dokumen.nomorHeader', 'tbl_dokumen.titleHeader', 'tbl_dokumen.satkerId', 'tbl_dokumen.ppkId', 'tbl_dokumen.dokumenTemuanId', 'tbl_dokumen.rekomendasi', 'tbl_dokumen.nomorDraft', 'tbl_dokumen.ringkasanTindakLanjut', 'tbl_dokumen.tindakLanjut', 'tbl_dokumen.dokumenTindakLanjut', 'tbl_dokumen.tglTindakLanjut', 'tbl_dokumen.uniqueColumn', 'tbl_dokumen.isEdit','tbl_satker.namaSatker', 'tbl_ppk.namaPpk')
            ->get();

        
        $result = [];
        $dataDokumen = [];
        for($i = 0; $i < count($dataTemuan); $i++) {

            $data = [
                $id = $dataTemuan[$i]->id,
                $jenisDokumenTemuanId = $dataTemuan[$i]->jenisDokumenTemuanId,
                $deadlineDokumenTemuan = $dataTemuan[$i]->deadlineDokumenTemuan,
                $tglTerimaDokumenTemuan = $dataTemuan[$i]->tglTerimaDokumenTemuan,
                $keadaanSdBulan = $dataTemuan[$i]->keadaanSdBulan,
                $namaKegiatan = $dataTemuan[$i]->namaKegiatan,
                $namaInstansi = $dataTemuan[$i]->namaInstansi,
                $unitKerjaEselon1 = $dataTemuan[$i]->unitKerjaEselon1,
                $satkerId = $dataTemuan[$i]->satkerId,
                $noLHA = $dataTemuan[$i]->noLHA,
                $tglLHA = $dataTemuan[$i]->tglLHA,
                $header = $dataTemuan[$i]->header,
                $footer = $dataTemuan[$i]->footer,
                $created_at = $dataTemuan[$i]->created_at,
                $updated_at = $dataTemuan[$i]->updated_at,
                $isDeleted = $dataTemuan[$i]->isDeleted
            ];
            for ($j = 0; $j < count($dokumen); $j++)
            {
                if ($dokumen[$j]->dokumenTemuanId == $dataTemuan[$i]->id)
                {
                    $time = strtotime($dokumen[$j]->tglTindakLanjut);

                    $newformat = date('d-m-Y',$time);

                    $dataDokumen[] = [
                        'id' => $dokumen[$j]->id,
                        'noUraianTemuan' => $dokumen[$j]->noUraianTemuan, 
                        'uraianTemuan' => $dokumen[$j]->uraianTemuan,
                        'kodeRekomendasi' => $dokumen[$j]->kodeRekomendasi, 
                        'kodeRingkasanTindakLanjut' => $dokumen[$j]->kodeRingkasanTindakLanjut,
                        'statusTindakLanjut' => $dokumen[$j]->statusTindakLanjut, 
                        'subNomorRekomendasi' => $dokumen[$j]->subNomorRekomendasi,
                        'nomorHeader' => $dokumen[$j]->nomorHeader, 
                        'titleHeader' => $dokumen[$j]->titleHeader,
                        'satkerId' => $dokumen[$j]->satkerId, 
                        'ppkId' => $dokumen[$j]->ppkId,
                        'nomorDraft' => $dokumen[$j]->nomorDraft, 
                        'dokumenTemuanId' => $dokumen[$j]->dokumenTemuanId, 
                        'dokumenTindakLanjut' => $dokumen[$j]->dokumenTindakLanjut, 
                        'namaSatker' => $dokumen[$j]->namaSatker,
                        'namaPpk' => $dokumen[$j]->namaPpk,
                        'isEdit' => $dokumen[$j]->isEdit,
                        'tglTindakLanjut' => $newformat
                    ];
                }
            }

            $result[] = [
                'id' => $id,
                'jenisDokumenTemuanId' => $jenisDokumenTemuanId,
                'deadlineDokumenTemuan' => $deadlineDokumenTemuan,
                'tglTerimaDokumenTemuan' => $tglTerimaDokumenTemuan,
                'keadaanSdBulan' => $keadaanSdBulan,
                'namaKegiatan' => $namaKegiatan,
                'namaInstansi' => $namaInstansi,
                'unitKerjaEselon1' => $unitKerjaEselon1,
                'satkerId' => $satkerId,
                'noLHA' => $noLHA,
                'tglLHA' => $tglLHA,
                'header' => $header,
                'footer' => $footer,
                'created_at' => $created_at,
                'updated_at' => $updated_at,
                'isDeleted' => $isDeleted,
                // 'resultDokumen' => ''
            ];
        }

        for ($i = 0; $i < count($dataDokumen); $i++)
        {
            for ($j = 0; $j < count($result); $j++)
            {
                if ($dataDokumen[$i]['dokumenTemuanId'] == $result[$j]['id'])
                {
                    $result[$j]['resultDokumen'][] = $dataDokumen[$i];
                }
            }
        }

        $dataDokumen = DB::table('tbl_dokumen')
            ->join('tbl_dokumen_temuan', 'tbl_dokumen.dokumenTemuanId', '=', 'tbl_dokumen_temuan.id')
            ->select('tbl_dokumen.statusTindakLanjut', 'tbl_dokumen.dokumenTemuanId', 'tbl_dokumen_temuan.id')
            ->get();

        $dataSatker = DB::table('tbl_satker')
            ->select('*')
            ->where('id', Auth::user()->satkerId)
            ->first();
            
            
        // for ($i = 0; $i < count($statusTersedia); $i++)
        // {
        //     $kr[] = $statusTersedia[$i]->dokumenTemuanId;
        // }

        // $kamu = array_unique($kr);
        
        // foreach ($kamu as $key => $value) {
        //     $anjay[] = $value; //this will print the indexes of the array
        // }

        // $tersedia = DB::table('tbl_dokumen')->whereIn('dokumenTemuanId', $anjay)->get();
        
        // for ($i = 0; $i < count($tersedia); $i++)
        // {
        //     if ($result->id == $anjay[$i])
        //     {
        //         array_push($tampungKelompokTL[$i]['dokSerupa'], $result[$j]);
        //     }
        // }

        // for ($i = 0; $i < $result->count(); $i++)
        // {
        //     for($j = 0; $j < $dataDokumen->count(); $j++)
        //     {
        //         if ($result[$i]->id == $dataDokumen[$j]->dokumenTemuanId && $dataDokumen[$j]->statusTindakLanjut == 'Tersedia' || $dataDokumen[$j]->statusTindakLanjut == 'Dalam Proses' || $dataDokumen[$j]->statusTindakLanjut == 'Selesai')
        //         {
        //             $result[$i]->isEdit = 1;
        //         }
        //         else if ($result[$i]->id == $dataDokumen[$j]->dokumenTemuanId && $dataDokumen[$j]->statusTindakLanjut == 'Selesai' || $dataDokumen[$j]->statusTindakLanjut == 'Dalam Proses')
        //         {
        //             $result[$i]->isEdit = 0;
        //         }
        //     }
        // }

        return response()->json([
            'message' => 'Data Temuan',
            'result' => $result,
            'dataSatker' => $dataSatker
        ], 200);
    }

    public function detailTemuan($id)
    {
        $result = DB::table('tbl_dokumen_temuan')
            ->where('id', $id)
            ->first();

        $status = DB::table('tbl_dokumen')
            ->join('tbl_dokumen_temuan', 'tbl_dokumen.dokumenTemuanId', '=', 'tbl_dokumen_temuan.id')
            ->select('statusTindakLanjut', 'dokumenTemuanId')
            ->where('tbl_dokumen.dokumenTemuanId', $id)
            ->get();

        for ($i = 0; $i < $status->count(); $i++)
        {
            if ($status[$i]->statusTindakLanjut == 'Tersedia')
            {
                $result->isEdit = 1;
            }
            else
            {
                $result->isEdit = 0;
            }
        }

        return response()->json([
            'message' => 'Data Temuan by Id',
            'result' => $result
        ], 200);
    }

    public function previewDokumen($id)
    {
        $dataTemuan = DB::table('view_dokumen_temuan_records')
            ->where('id', $id)
            ->first();

        $dokumen = Dokumen::where('dokumenTemuanId', $id)
            ->join('tbl_satker', 'tbl_dokumen.satkerId', '=', 'tbl_satker.id')
            ->join('tbl_ppk', 'tbl_dokumen.ppkId', '=', 'tbl_ppk.id')
            ->select('tbl_dokumen.*', 'tbl_satker.namaSatker', 'tbl_ppk.namaPpk')
            ->get();

        $dokumenUnique = $dokumen->unique('uniqueColumn');
        $dokumenDupes = $dokumen->diff($dokumenUnique);

        for ($i = 0; $i < $dokumenUnique->count(); $i++)
        {
            $dokumenUnique[$i]->uraianTemuan = strip_tags($dokumenUnique[$i]->uraianTemuan);
            for ($j = 0; $j < $dokumenDupes->count(); $j++)
            {
                if ($dokumenUnique[$i]->uniqueColumn == $dokumenDupes[$j]->uniqueColumn)
                {
                    if ($dokumenDupes[$j] > $dokumenUnique[$i])
                    {
                        $dokumenUnique[$i] = $dokumenDupes[$j];
                        $dokumenUnique[$i]->uraianTemuan = strip_tags($dokumenDupes[$j]->uraianTemuan);
                    }
                }
            }
        }

        $resultTemuan = [
            'id' => $id,
            'jenisDokumenTemuanId' => $dataTemuan->jenisDokumenTemuanId,
            'jenisDokumenTemuan' => $dataTemuan->jenisDokumenTemuan,
            'deadlineDokumenTemuan' => $dataTemuan->deadlineDokumenTemuan,
            'tglTerimaDokumenTemuan' => $dataTemuan->tglTerimaDokumenTemuan,
            'keadaanSdBulan' => $dataTemuan->keadaanSdBulan,
            'namaKegiatan' => $dataTemuan->namaKegiatan,
            'namaInstansi' => $dataTemuan->namaInstansi,
            'unitKerjaEselon1' => $dataTemuan->unitKerjaEselon1,
            'noLHA' => $dataTemuan->noLHA,
            'tglLHA' => $dataTemuan->tglLHA,
            'header' => $dataTemuan->header,
            'footer' => $dataTemuan->footer,
            'resultDokumen' => $dokumenUnique
        ];
            // \LogActivity::addToLog(Auth::user()->nama.' dari satker '.Auth::user()->satkerId.' telah menambah data Temuan baru');

        return response()->json([
            'message' => 'Preview Dokumen',
            'result' => $resultTemuan
        ], 200);
    }

    public function parentGridDetail($id)
    {
        $dataTemuan = DB::table('view_dokumen_temuan_records')
            ->where('id', $id)
            ->first();

        $dokumen = Dokumen::where('dokumenTemuanId', $id)
            ->join('tbl_satker', 'tbl_dokumen.satkerId', '=', 'tbl_satker.id')
            ->join('tbl_ppk', 'tbl_dokumen.ppkId', '=', 'tbl_ppk.id')
            ->select('tbl_dokumen.id', 'tbl_dokumen.noUraianTemuan', 'tbl_dokumen.uraianTemuan', 'tbl_dokumen.kodeRekomendasi', 'tbl_dokumen.kodeRingkasanTindakLanjut', 'tbl_dokumen.statusTindakLanjut', 'tbl_dokumen.subNomorRekomendasi', 'tbl_dokumen.nomorHeader', 'tbl_dokumen.titleHeader', 'tbl_dokumen.satkerId', 'tbl_dokumen.ppkId', 'tbl_dokumen.dokumenTemuanId', 'tbl_dokumen.rekomendasi', 'tbl_dokumen.ringkasanTindakLanjut', 'tbl_dokumen.tindakLanjut', 'tbl_dokumen.dokumenTindakLanjut', 'tbl_dokumen.tglTindakLanjut', 'tbl_dokumen.uniqueColumn', 'tbl_satker.namaSatker', 'tbl_ppk.namaPpk')
            ->where('tbl_dokumen.isDeleted', 0)
            ->get();

        // $dokumenUnique = $dokumen->unique('uniqueColumn');
        // $dokumenDupes = $dokumen->diff($dokumenUnique);

        // for ($i = 0; $i < $dokumenUnique->count(); $i++)
        // {
        //     $dokumenUnique[$i]->uraianTemuan = strip_tags($dokumenUnique[$i]->uraianTemuan);
        //     for ($j = 0; $j < $dokumenDupes->count(); $j++)
        //     {
        //         if ($dokumenUnique[$i]->uniqueColumn == $dokumenDupes[$j]->uniqueColumn)
        //         {
        //             if ($dokumenDupes[$j] > $dokumenUnique[$i])
        //             {
        //                 $dokumenUnique[$i] = $dokumenDupes[$j];
        //                 $dokumenUnique[$i]->uraianTemuan = strip_tags($dokumenDupes[$j]->uraianTemuan);
        //             }
        //         }
        //     }
        // }

        $resultTemuan = [
            'id' => $id,
            'jenisDokumenTemuanId' => $dataTemuan->jenisDokumenTemuanId,
            'jenisDokumenTemuan' => $dataTemuan->jenisDokumenTemuan,
            'deadlineDokumenTemuan' => $dataTemuan->deadlineDokumenTemuan,
            'tglTerimaDokumenTemuan' => $dataTemuan->tglTerimaDokumenTemuan,
            'keadaanSdBulan' => $dataTemuan->keadaanSdBulan,
            'namaKegiatan' => $dataTemuan->namaKegiatan,
            'namaInstansi' => $dataTemuan->namaInstansi,
            'unitKerjaEselon1' => $dataTemuan->unitKerjaEselon1,
            'noLHA' => $dataTemuan->noLHA,
            'tglLHA' => $dataTemuan->tglLHA,
            'header' => $dataTemuan->header,
            'footer' => $dataTemuan->footer,
            'resultDokumen' => $dokumen
        ];
            // \LogActivity::addToLog(Auth::user()->nama.' dari satker '.Auth::user()->satkerId.' telah menambah data Temuan baru');
        return response()->json([
            'message' => 'Data dokumen temuan',
            'result' => $resultTemuan
        ], 200);
    }

    public function show($id)
    {
        $dataTemuan = DB::table('view_dokumen_temuan_records')
            ->where('id', $id)
            ->first();

        $dokumen = Dokumen::where('dokumenTemuanId', $id)
            ->join('tbl_satker', 'tbl_dokumen.satkerId', '=', 'tbl_satker.id')
            ->join('tbl_ppk', 'tbl_dokumen.ppkId', '=', 'tbl_ppk.id')
            ->select('tbl_dokumen.id', 'tbl_dokumen.tipeDokumenId', 'tbl_dokumen.noUraianTemuan', 'tbl_dokumen.uraianTemuan', 'tbl_dokumen.kodeRekomendasi', 'tbl_dokumen.kodeRingkasanTindakLanjut', 'tbl_dokumen.statusTindakLanjut', 'tbl_dokumen.subNomorRekomendasi', 'tbl_dokumen.nomorHeader', 'tbl_dokumen.titleHeader', 'tbl_dokumen.satkerId', 'tbl_dokumen.ppkId', 'tbl_dokumen.dokumenTemuanId', 'tbl_dokumen.dokumenTindakLanjut', 'tbl_dokumen.tglTindakLanjut','tbl_dokumen.uniqueColumn','tbl_satker.namaSatker', 'tbl_ppk.namaPpk')
            ->get();

        $dokumenUnique = $dokumen->unique('kodeRekomendasi');
        $dokumenDupes = $dokumen->diff($dokumenUnique);

        for ($i = 0; $i < $dokumenUnique->count(); $i++)
        {
            $dokumenUnique[$i]->uraianTemuan = strip_tags($dokumenUnique[$i]->uraianTemuan);
            for ($j = 0; $j < $dokumenDupes->count(); $j++)
            {
                if ($dokumenUnique[$i]->kodeRekomendasi == $dokumenDupes[$j]->kodeRekomendasi)
                {
                    if ($dokumenDupes[$j] > $dokumenUnique[$i])
                    {
                        $dokumenUnique[$i] = $dokumenDupes[$j];
                        $dokumenUnique[$i]->uraianTemuan = strip_tags($dokumenDupes[$j]->uraianTemuan);
                    }
                }
            }
            if ($dokumenUnique[$i]->nomorDraft == null || $dokumenUnique[$i]->nomorDraft == '')
            {
                $dokumenUnique[$i]->nomorDraft = '-';
            }
        }

        $resultTemuan = [
            'id' => $id,
            'jenisDokumenTemuanId' => $dataTemuan->jenisDokumenTemuanId,
            'jenisDokumenTemuan' => $dataTemuan->jenisDokumenTemuan,
            'deadlineDokumenTemuan' => $dataTemuan->deadlineDokumenTemuan,
            'tglTerimaDokumenTemuan' => $dataTemuan->tglTerimaDokumenTemuan,
            'keadaanSdBulan' => $dataTemuan->keadaanSdBulan,
            'namaKegiatan' => $dataTemuan->namaKegiatan,
            'namaInstansi' => $dataTemuan->namaInstansi,
            'unitKerjaEselon1' => $dataTemuan->unitKerjaEselon1,
            'noLHA' => $dataTemuan->noLHA,
            'tglLHA' => $dataTemuan->tglLHA,
            'header' => $dataTemuan->header,
            'footer' => $dataTemuan->footer,
            'resultDokumen' => $dokumenUnique
        ];
            // \LogActivity::addToLog(Auth::user()->nama.' dari satker '.Auth::user()->satkerId.' telah menambah data Temuan baru');

        return response()->json([
            'message' => 'Data dokumen temuan',
            'result' => $resultTemuan
        ], 200);
    }

    public function detailTindakLanjut($id)
    {
        $dataTemuan = DB::table('view_dokumen_temuan_records')
            ->where('DId', $id)
            ->first();

        $dokumen = DB::table('tbl_dokumen')
            ->join('tbl_satker', 'tbl_dokumen.satkerId', '=', 'tbl_satker.id')
            ->join('tbl_ppk', 'tbl_dokumen.ppkId', '=', 'tbl_ppk.id')
            ->select('tbl_dokumen.*', 'tbl_satker.namaSatker', 'tbl_ppk.namaPpk')
            ->where('tbl_dokumen.id', $id)
            ->first();

        $resultTemuan = [
            'id' => $dataTemuan->id,
            'jenisDokumenTemuanId' => $dataTemuan->jenisDokumenTemuanId,
            'jenisDokumenTemuan' => $dataTemuan->jenisDokumenTemuan,
            'deadlineDokumenTemuan' => $dataTemuan->deadlineDokumenTemuan,
            'tglTerimaDokumenTemuan' => $dataTemuan->tglTerimaDokumenTemuan,
            'keadaanSdBulan' => $dataTemuan->keadaanSdBulan,
            'namaKegiatan' => $dataTemuan->namaKegiatan,
            'namaInstansi' => $dataTemuan->namaInstansi,
            'unitKerjaEselon1' => $dataTemuan->unitKerjaEselon1,
            'noLHA' => $dataTemuan->noLHA,
            'tglLHA' => $dataTemuan->tglLHA,
            'header' => $dataTemuan->header,
            'footer' => $dataTemuan->footer,
            'resultDokumen' => $dokumen
        ];
            // \LogActivity::addToLog(Auth::user()->nama.' dari satker '.Auth::user()->satkerId.' telah menambah data Temuan baru');

        return response()->json([
            'message' => 'Data Tindak Lanjut',
            'result' => $resultTemuan
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenisDokumenTemuanId' => 'required|numeric',
            'deadlineDokumenTemuan' => 'required',
            'tglTerimaDokumenTemuan' => 'required',
            // 'dokumenId' => 'required|numeric',
            'keadaanSdBulan' => 'required',
            'namaKegiatan' => 'required|string',
            'namaInstansi' => 'required|string',
            'unitKerjaEselon1' => 'required',
            'noLHA' => 'required',
            'tglLHA' => 'required',
            'header' => 'required',
            'footer' => 'required'
        ]);

        $result = DokTemuan::create([
            'jenisDokumenTemuanId' => $request->jenisDokumenTemuanId,
            'deadlineDokumenTemuan' => $request->deadlineDokumenTemuan,
            'tglTerimaDokumenTemuan' => $request->tglTerimaDokumenTemuan,
            'dokumenId' => $request->dokumenId,
            'keadaanSdBulan' => $request->keadaanSdBulan,
            'namaKegiatan' => $request->namaKegiatan,
            'namaInstansi' => $request->namaInstansi,
            'unitKerjaEselon1' => $request->unitKerjaEselon1,
            'satkerId' => Auth::user()->satkerId,
            'noLHA' => $request->noLHA,
            'tglLHA' => $request->tglLHA,
            'header' => $request->header,
            'footer' => $request->footer
        ]);

        // \LogActivity::addToLog(Auth::user()->nama.' dari satker '.Auth::user()->satkerId.' telah menambah data Temuan baru');
        
        return response()->json([
            'message' => 'Berhasil menambahkan data',
            'result' => $result
        ], 200);
    }

    function update(Request $request, $id)
    {
        // $validator = Validator::make($request->all(), [
        //     'jenisDokumenTemuanId' => 'required|numeric',
        //     'deadlineDokumenTemuan' => 'required',
        //     'tglTerimaDokumenTemuan' => 'required',
        //     'namaKegiatan' => 'required|string',
        //     'namaInstansi' => 'required|string',
        //     'unitKerjaEselon1' => 'required',
        //     'noLHA' => 'required',
        //     'tglLHA' => 'required',
        //     'header' => 'required'
        // ]);

        // if($validator->fails())
        // {
        //     return response()->json($validator->errors(), 400);
        // }

        $result = DokTemuan::find($id);
        $result->jenisDokumenTemuanId = $request->jenisDokumenTemuanId;
        $result->deadlineDokumenTemuan = $request->deadlineDokumenTemuan;
        $result->tglTerimaDokumenTemuan = $request->tglTerimaDokumenTemuan;
        $result->keadaanSdBulan = $request->keadaanSdBulan;
        $result->namaKegiatan = $request->namaKegiatan;
        $result->namaInstansi = $request->namaInstansi;
        $result->unitKerjaEselon1 = $request->unitKerjaEselon1;
        $result->satkerId = Auth::user()->satkerId;
        $result->noLHA = $request->noLHA;
        $result->tglLHA = $request->tglLHA;
        $result->header = $request->header;
        $result->footer = $request->footer;
        $result->save();
        
        $resultTindakLanjut = DB::table('tbl_dokumen')
            ->where('dokumenTemuanId', $id)
            ->get();
        // \LogActivity::addToLog(Auth::user()->nama.' dari satker '.Auth::user()->satkerId.' telah mengubah data Temuan');

        return response()->json([
            'message' => 'Berhasil update data',
            'result' => $result,
            'resultTL' => $resultTindakLanjut
        ], 200);
    }

    public function destroy($id)
    {
        // $doktemuan = DB::table('tbl_dokumen_temuan')
        //     ->where('id', $id)
        //     ->get();

        // $tindaklanjut = DB::table('tbl_dokumen')
        //     ->join('tbl_dokumen_temuan', 'tbl_dokumen.dokumenTemuanId', '=', 'tbl_dokumen_temuan.id')
        //     ->select('dokumenTemuanId', 'tindakLanjut')
        //     ->get();

        // if ($doktemuan->id == $tindaklanjut->dokumenTemuanId)
        // {
        //     if ($tindaklanjut->tindakLanjut == null || $tindaklanjut->tindakLanjut == '')
        //     {
        //         return response()->json([
        //             'message' => 'Tidak dapat menghapus data karna dokumen temuan sudah dalam proses',
        //             'result' => FALSE
        //         ], 400);
        //     }
        //     else
        //     {
        //         $result = DokTemuan::find($id);
        //         $result->isDeleted = '1';
        //         $result->save();

        //         // \LogActivity::addToLog(Auth::user()->nama.' dari cabang '.Auth::user()->satkerId.' telah menghapus data Temuan');

        //         return response()->json([
        //             'message' => 'Berhasil menghapus data',
        //             'result' => $result
        //         ], 200);
        //     }
        // }
        
        $DokTemuan = DokTemuan::find($id);
        $DokTemuan->isDeleted = '1';
        $DokTemuan->save();

        Dokumen::query()->where('dokumenTemuanId', $id)->update(array('isDeleted' => 1));

        $result = DokTemuan::query()
            ->where('id', $id)
            ->get();

        return response()->json([
            'message' => 'Berhasil menghapus data',
            'result' => $result
        ], 200);

    }
}
