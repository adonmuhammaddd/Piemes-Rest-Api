<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TindakLanjut;
use App\Dokumen;
use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Kpa;
use App\Ppk;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TindakLanjutController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwtmiddleware');
    }
    
    public function index()
    {
        $dokumenTindakLanjut = DB::table('tbl_dokumen')
            ->join('tbl_dokumen_temuan', 'tbl_dokumen.dokumenTemuanId', '=', 'tbl_dokumen_temuan.id')
            ->select('tbl_dokumen.id', 'tbl_dokumen.tipeDokumenId', 'tbl_dokumen.noUraianTemuan', 'tbl_dokumen.uraianTemuan', 'tbl_dokumen.kodeRekomendasi', 'tbl_dokumen.kodeRingkasanTindakLanjut', 'tbl_dokumen.statusTindakLanjut', 'tbl_dokumen.tglTindakLanjut', 'tbl_dokumen.subNomorRekomendasi', 'tbl_dokumen.nomorHeader', 'tbl_dokumen.titleHeader', 'tbl_dokumen.satkerId', 'tbl_dokumen.ppkId', 'tbl_dokumen.dokumenTemuanId', 'tbl_dokumen.isDeleted', 'tbl_dokumen_temuan.noLHA', 'tbl_dokumen_temuan.tglTerimaDokumenTemuan', 'tbl_dokumen.nomorDraft', 'tbl_dokumen.isRevisi')
            ->get();

        $newDokumen = [];
        $deleteTersedia = [];
        $kr = [];
        $tampungKelompokTL = [];
        $anjay = [];
        $bos = [];

        $kodeRekomendasi = DB::table('tbl_dokumen')
            ->select('kodeRekomendasi')
            ->get();

        for ($i = 0; $i < count($dokumenTindakLanjut); $i++)
        {
            $kr[] = $dokumenTindakLanjut[$i]->kodeRekomendasi;
        }
        
        $kamu = array_unique($kr);

        foreach ($kamu as $key => $value) {
            $anjay[] = $value; //this will print the indexes of the array
        }

        for ($i = 0; $i < count($anjay); $i++)
        {
            array_push($tampungKelompokTL, [
                'kodeRekomendasi' => $anjay[$i],
                'dokSerupa' => []
            ]);
            for ($j = 0; $j < $dokumenTindakLanjut->count(); $j++)
            {
                if ($dokumenTindakLanjut[$j]->kodeRekomendasi == $anjay[$i])
                {
                    array_push($tampungKelompokTL[$i]['dokSerupa'], $dokumenTindakLanjut[$j]);
                }
            }
        }

        for ($i = 0; $i < count($tampungKelompokTL); $i++)
        {
            for ($j = 0; $j < count($tampungKelompokTL[$i]['dokSerupa']); $j++)
            {
                for ($k = 0; $k < $dokumenTindakLanjut->count(); $k++)
                {
                    if (count($tampungKelompokTL[$i]['dokSerupa']) > 1)
                    {
                        if($dokumenTindakLanjut[$k]->kodeRekomendasi == $tampungKelompokTL[$i]['dokSerupa'][$j]->kodeRekomendasi)
                        {
                            if($dokumenTindakLanjut[$k]->statusTindakLanjut == 'Tersedia' && $dokumenTindakLanjut[$k]->tglTindakLanjut == null)
                            {
                                $bos[] = $dokumenTindakLanjut[$k];
                                DB::table('tbl_dokumen')
                                    ->where('id', $dokumenTindakLanjut[$k]->id)
                                    ->update(['isDeleted' => 1]);
                            }
                        }
                    }
                }
            }
        }
        
        if (Auth::user()->roleId > 30)
        {
            $result = DB::table('tbl_dokumen')
                ->join('tbl_satker', 'tbl_dokumen.satkerId', '=', 'tbl_satker.id')
                ->join('tbl_ppk', 'tbl_dokumen.ppkId', '=', 'tbl_ppk.id')
                ->join('tbl_dokumen_temuan', 'tbl_dokumen.dokumenTemuanId', '=', 'tbl_dokumen_temuan.id')
                ->select('tbl_dokumen.id', 'tbl_dokumen.tipeDokumenId', 'tbl_dokumen.noUraianTemuan', 'tbl_dokumen.uraianTemuan', 'tbl_dokumen.kodeRekomendasi', 'tbl_dokumen.kodeRingkasanTindakLanjut', 'tbl_dokumen.statusTindakLanjut', 'tbl_dokumen.subNomorRekomendasi', 'tbl_dokumen.nomorHeader', 'tbl_dokumen.titleHeader', 'tbl_dokumen.satkerId', 'tbl_satker.namaSatker', 'tbl_dokumen.ppkId', 'tbl_ppk.namaPpk', 'tbl_dokumen.dokumenTemuanId', 'tbl_dokumen.isDeleted', 'tbl_dokumen_temuan.noLHA', 'tbl_dokumen_temuan.tglTerimaDokumenTemuan', 'tbl_dokumen.nomorDraft', 'tbl_dokumen.tglTindakLanjut', 'tbl_dokumen.isRevisi')
                ->where('tbl_dokumen.isDeleted', 0)
                ->get();
        }
        else
        {
            $result = DB::table('tbl_dokumen')
                ->join('tbl_satker', 'tbl_dokumen.satkerId', '=', 'tbl_satker.id')
                ->join('tbl_ppk', 'tbl_dokumen.ppkId', '=', 'tbl_ppk.id')
                ->join('tbl_dokumen_temuan', 'tbl_dokumen.dokumenTemuanId', '=', 'tbl_dokumen_temuan.id')
                ->select('tbl_dokumen.id', 'tbl_dokumen.tipeDokumenId', 'tbl_dokumen.noUraianTemuan', 'tbl_dokumen.uraianTemuan', 'tbl_dokumen.kodeRekomendasi', 'tbl_dokumen.kodeRingkasanTindakLanjut', 'tbl_dokumen.statusTindakLanjut', 'tbl_dokumen.subNomorRekomendasi', 'tbl_dokumen.nomorHeader', 'tbl_dokumen.titleHeader', 'tbl_dokumen.satkerId', 'tbl_satker.namaSatker', 'tbl_dokumen.ppkId', 'tbl_dokumen.dokumenTindakLanjut',  'tbl_ppk.namaPpk', 'tbl_dokumen.dokumenTemuanId', 'tbl_dokumen.isDeleted', 'tbl_dokumen_temuan.noLHA', 'tbl_dokumen_temuan.tglTerimaDokumenTemuan', 'tbl_dokumen.nomorDraft', 'tbl_dokumen.tglTindakLanjut', 'tbl_dokumen.isRevisi')
                ->where('tbl_dokumen.satkerId', Auth::user()->satkerId)
                ->where('tbl_dokumen.isDeleted', 0)
                ->get();
        }

        $key = [];
        // $result = [];

        // foreach($dataTindakLanjut as $k => $val) 
        // { 
        //     $val->uraianTemuan = strip_tags($val->uraianTemuan);
        //     if ($val->statusTindakLanjut == 'Tersedia')
        //     {
        //         $val->isEdit = 1;
        //     }
        //     else
        //     {
        //         $val->isEdit = 0;
        //     }

        //     if (Auth::user()->roleId < 50)
        //     {
        //         if ($val->isRevisi == 0 && $val->nomorDraft == null)
        //         {
        //             $val->keterangan = 'Dokumen Baru perlu ditindak lanjuti';
        //         }
        //         else if ($val->nomorDraft != null && $val->isRevisi == 0 && $val->statusTindakLanjut == 'Dalam Proses')
        //         {
        //             $val->keterangan = 'Menunggu Respon';
        //         }
        //         else if ($val->nomorDraft != null && $val->isRevisi == 1 && $val->statusTindakLanjut == 'Dalam Proses')
        //         {
        //             $val->keterangan = 'Dokumen Perlu direvisi';
        //         }
        //         else if ($val->statusTindakLanjut == 'Selesai')
        //         {
        //             $val->keterangan = 'Dokumen Tuntas';
        //         }
        //     }
        //     else
        //     {
        //         if ($val->isRevisi == 0 && $val->nomorDraft == null)
        //         {
        //             $val->keterangan = 'Dokumen Baru perlu ditindak lanjuti';
        //         }
        //         else if ($val->nomorDraft != null && $val->isRevisi == 1 && $val->statusTindakLanjut == 'Dalam Proses')
        //         {
        //             $val->keterangan = 'Dokumen Perlu direvisi';
        //         }
        //         else if ($val->statusTindakLanjut == 'Selesai')
        //         {
        //             $val->keterangan = 'Dokumen Tuntas';
        //         }
        //         else if ($val->nomorDraft != null && $val->isRevisi == 0 && $val->statusTindakLanjut == 'Dalam Proses')
        //         {
        //             $val->keterangan = 'Menunggu Respon';
        //             // array_push($key, $dataTindakLanjut);
        //             // unset($dokumenTindakLanjut[$k]);
        //         }
        //     }
        // }
        for($i = 0; $i < $result->count(); $i++)
        {
            $result[$i]->uraianTemuan = strip_tags($result[$i]->uraianTemuan);
            if ($result[$i]->statusTindakLanjut == 'Tersedia')
            {
                $result[$i]->isEdit = 1;
            }
            else
            {
                $result[$i]->isEdit = 0;
            }

            if (Auth::user()->roleId < 40)
            {
                if ($result[$i]->isRevisi == 0 && $result[$i]->nomorDraft == null)
                {
                    $result[$i]->keterangan = 'Dokumen Baru perlu ditindak lanjuti';
                }
                else if ($result[$i]->nomorDraft != null && $result[$i]->isRevisi == 0 && $result[$i]->statusTindakLanjut == 'Dalam Proses')
                {
                    $result[$i]->keterangan = 'Menunggu Respon';
                }
                else if ($result[$i]->nomorDraft != null && $result[$i]->isRevisi == 1 && $result[$i]->statusTindakLanjut == 'Dalam Proses')
                {
                    $result[$i]->keterangan = 'Dokumen Perlu direvisi';
                }
                else if ($result[$i]->statusTindakLanjut == 'Selesai')
                {
                    $result[$i]->keterangan = 'Dokumen Tuntas';
                }
            }
            else
            {
                if ($result[$i]->isRevisi == 0 && $result[$i]->nomorDraft == null)
                {
                    $result[$i]->keterangan = 'Dokumen Baru perlu ditindak lanjuti';
                }
                else if ($result[$i]->nomorDraft != null && $result[$i]->isRevisi == 1 && $result[$i]->statusTindakLanjut == 'Dalam Proses')
                {
                    $result[$i]->keterangan = 'Dokumen Perlu direvisi';
                }
                else if ($result[$i]->statusTindakLanjut == 'Selesai')
                {
                    $result[$i]->keterangan = 'Dokumen Tuntas';
                }
                else if ($result[$i]->nomorDraft != null && $result[$i]->isRevisi == 0 && $result[$i]->statusTindakLanjut == 'Dalam Proses')
                {
                    $result[$i]->keterangan = 'Menunggu Respon';
                }
            }
        }

        // if (Auth::user()->roleId > 40)
        // {
        //     $result = $dataTindakLanjut;
        //     $newResult = array_diff($result, $key);
        // }
        // else
        // {
        //     $result = $dataTindakLanjut;
        // }

        return response()->json([
            'message' => 'Data Tindak Lanjut',
            'isSuperAdmin' => Auth::user()->roleId > 30 ? true : false,
            'result' => $result
        ], 200);
    }

    public function store(Request $request)
    {
        $checkDokumen = DB::table('tbl_dokumen')
            ->where('uniqueColumn', $request->_uniqueColumn)
            ->latest('created_at')
            ->first();
        
        $updatePrevious = Dokumen::find($request->id);
        $updatePrevious->isEdit = 0;
        $updatePrevious->save();

        $nomorDraft = $updatePrevious->nomorDraft + 1;

        if ($request->hasFile('dokumenTindakLanjut'))
        {
            $fileNameWithExt = $request->file('dokumenTindakLanjut')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('dokumenTindakLanjut')->getClientOriginalExtension();
            $fileNameToStore = $fileName.'_'.time().'.'.$extension;
            $path = $request->file('dokumenTindakLanjut')->storeAs('public/dokumen/', $fileNameToStore);
        }
        else
        {
            $fileNameToStore = null;
        }

        $tindakLanjut = [
            'dokumenTemuanId' => (int)$request->_dokumenTemuanId,
            'noUraianTemuan' => $request->_noUraianTemuan,
            'uraianTemuan' => $request->_uraianTemuan,
            'rekomendasi' => $request->_rekomendasi,
            'kodeRekomendasi' => $request->_kodeRekomendasi,
            'kodeRingkasanTindakLanjut' => $request->_kodeRingkasanTindakLanjut,
            'ringkasanTindakLanjut' => $request->_ringkasanTindakLanjut,
            'statusTindakLanjut' => $request->_statusTindakLanjut,
            'tindakLanjut' => $request->_tindakLanjut,
            'nomorHeader' => $request->_nomorHeader,
            'titleHeader' => $request->_titleHeader,
            'satkerId' => (int)Auth::user()->satkerId,
            'ppkId' => (int)$request->_ppkId,
            'tglTindakLanjut' => date('Y-m-d'),
            'userId' => (int)Auth::user()->id,
            'nomorDraft' => $nomorDraft,
            'dokumenTindakLanjut' => $fileNameToStore,
            'uniqueColumn' => $request->_uniqueColumn,
            'isEdit' => 1
        ];

        Dokumen::insert($tindakLanjut);
        $theId = DB::table('tbl_dokumen')->latest('created_at')->first();
        // $resultTindakLanjut = DB::table('tbl_dokumen')->where('id', $theId->id)->first();
        // aa

        $theSatker = DB::table('tbl_satker')->where('id', $tindakLanjut['satkerId'])->first();
        $thePpk = DB::table('tbl_ppk')->where('id', $tindakLanjut['ppkId'])->first();

        $time = strtotime($tindakLanjut['tglTindakLanjut']);

        $newformat = date('d-m-Y',$time);

        $tindakLanjut['tglTindakLanjut'] = $newformat;
        $tindakLanjut['id'] = $theId->id;
        $tindakLanjut['namaSatker'] = $theSatker->namaSatker;
        $tindakLanjut['namaPpk'] = $thePpk->namaPpk;

        $getAllTindakLanjut = DB::table('tbl_dokumen')
            ->where('dokumenTemuanId', $request->_dokumenTemuanId)
            ->get();

        $tindakLanjutAll = [];

        for($i = 0; $i < count($getAllTindakLanjut); $i++)
        {
            $time = strtotime($getAllTindakLanjut[$j]->tglTindakLanjut);

            $newformat = date('d-m-Y',$time);

            $tindakLanjutAll[] = [
                'id' => (int)$getAllTindakLanjut[$i]->id,
                'dokumenTemuanId' => (int)$getAllTindakLanjut[$i]->dokumenTemuanId,
                'noUraianTemuan' => $getAllTindakLanjut[$i]->noUraianTemuan,
                'uraianTemuan' => $getAllTindakLanjut[$i]->uraianTemuan,
                'rekomendasi' => $getAllTindakLanjut[$i]->rekomendasi,
                'kodeRekomendasi' => $getAllTindakLanjut[$i]->kodeRekomendasi,
                'kodeRingkasanTindakLanjut' => $getAllTindakLanjut[$i]->kodeRingkasanTindakLanjut,
                'ringkasanTindakLanjut' => $getAllTindakLanjut[$i]->ringkasanTindakLanjut,
                'statusTindakLanjut' => $getAllTindakLanjut[$i]->statusTindakLanjut,
                'tindakLanjut' => $getAllTindakLanjut[$i]->tindakLanjut,
                'nomorHeader' => $getAllTindakLanjut[$i]->nomorHeader,
                'titleHeader' => $getAllTindakLanjut[$i]->titleHeader,
                'satkerId' => (int)$getAllTindakLanjut[$i]->satkerId,
                'ppkId' => (int)$getAllTindakLanjut[$i]->ppkId,
                'tglTindakLanjut' => $newformat,
                'userId' => (int)$getAllTindakLanjut[$i]->userId,
                'nomorDraft' => $getAllTindakLanjut[$i]->nomorDraft,
                'dokumenTindakLanjut' => $getAllTindakLanjut[$i]->dokumenTindakLanjut,
                'uniqueColumn' => $getAllTindakLanjut[$i]->uniqueColumn,
                'isEdit' => $getAllTindakLanjut[$i]->isEdit
            ];

            // $theSatker = DB::table('tbl_satker')->where('id', $resultTindakLanjut[$i]->satkerId)->first();
            // $thePpk = DB::table('tbl_ppk')->where('id', $resultTindakLanjut[$i]->ppkId)->first();
    
            // $tindakLanjutAll['namaSatker'] = $theSatker->namaSatker;
            // $tindakLanjutAll['namaPpk'] = $thePpk->namaPpk;
        }
        
        for($i = 0; $i < count($tindakLanjutAll); $i++)
        {
            $theSatker = DB::table('tbl_satker')->where('id', $tindakLanjutAll[$i]['satkerId'])->first();
            $thePpk = DB::table('tbl_ppk')->where('id', $tindakLanjutAll[$i]['ppkId'])->first();
    
            $tindakLanjutAll[$i]['namaSatker'] = $theSatker->namaSatker;
            $tindakLanjutAll[$i]['namaPpk'] = $thePpk->namaPpk;
        }
        
        return response()->json([
            'message' => 'Berhasil menambahkan data',
            'result' => $tindakLanjut,
            'resultAll' => $tindakLanjutAll
        ], 200);

        // $namaSatker = DB::table('tbl_satker')->where('satkerId', Auth::user()->satkerId)->first();
        // $dokumenTemuan = DB::table('tbl_dokumen_temuan')->where('id', $request->_dokumenTemuanId)->first();

        // $title = 'Halo, Admin';
        // $description = 'Sepertinya ada dokumen tindak lanjut yang perlu direspon.';
        // $content = 'Dokumen Tindak lanjut dari satker'.$namaSatker->namaSatker.'dengan Nomor <b>'.$dokumenTemuan->noLHA.' '.$dokumenTemuan->tglLHA.'</b> dimohon untuk segera direspon';
        // $footer = 'dimohon untuk tidak membalas pesan ini, ini adalah pesan otomatis untuk pengingat dokumen tindak lanjut yang harus direspon';

        // $sendmail = Mail::to('adonmuhammaddd@gmail.com')->send(new SendMail($title, $description, $content, $footer));
        // if (empty($sendmail)) {
        //     return response()->json([
        //         'mailMessage' => 'Mail Sent Sucessfully',
        //         'message' => 'Berhasil menambahkan data',
        //         'result' => $result
        //     ], 200);
        // }else{
        //     return response()->json(['message' => 'Mail Sent fail'], 400);
        // }

        // \LogActivity::addToLog(Auth::user()->nama.' dari Satker '.Auth::user()->satkerId.' telah menambah data Dokumen baru '.$request->tipeDokumenId);
        
        // return response()->json([

        // ], 200);
    }

    public function gridView()
    {
        $result = DB::table('tbl_dokumen')
            ->join('tbl_dokumen_temuan', 'tbl_dokumen.dokumenTemuanId', '=', 'tbl_dokumen_temuan.id')
            ->select('tbl_dokumen.id', 'tbl_dokumen.tipeDokumenId', 'tbl_dokumen.noUraianTemuan', 'tbl_dokumen.uraianTemuan', 'tbl_dokumen.kodeRekomendasi', 'tbl_dokumen.kodeRingkasanTindakLanjut', 'tbl_dokumen.statusTindakLanjut', 'tbl_dokumen.subNomorRekomendasi', 'tbl_dokumen.nomorHeader', 'tbl_dokumen.titleHeader', 'tbl_dokumen.satkerId', 'tbl_dokumen.ppkId', 'tbl_dokumen.dokumenTemuanId', 'tbl_dokumen_temuan.noLHA', 'tbl_dokumen_temuan.tglTerimaDokumenTemuan', 'tbl_dokumen.nomorDraft', 'tbl_dokumen.isRevisi')
            ->get();
            
        for($i = 0; $i < $result->count(); $i++)
        {
            $result[$i]->uraianTemuan = strip_tags($result[$i]->uraianTemuan);
            if ($result[$i]->statusTindakLanjut == 'Tersedia')
            {
                $result[$i]->isEdit = 1;
            }
            else
            {
                $result[$i]->isEdit = 0;
            }
            
            if ($result[$i]->isRevisi == 0 && $result[$i]->nomorDraft == null)
            {
                $result[$i]->keterangan = 'Dokumen Baru perlu ditindak lanjuti';
            }
            else if ($result[$i]->nomorDraft != null && $result[$i]->isRevisi == 0 && $result[$i]->statusTindakLanjut == 'Dalam Proses')
            {
                $result[$i]->keterangan = 'Menunggu Respon';
            }
            else if ($result[$i]->nomorDraft != null && $result[$i]->isRevisi == 1 && $result[$i]->statusTindakLanjut == 'Dalam Proses')
            {
                $result[$i]->keterangan = 'Dokumen Perlu direvisi';
            }
            else if ($result[$i]->statusTindakLanjut == 'Selesai')
            {
                $result[$i]->keterangan = 'Dokumen Tuntas';
            }
        }

        return response()->json(compact('result'), 200);
    }

    public function detailTindakLanjut($id = null)
    {
        if ($id == null)
        {
            $theId = DB::table('tbl_dokumen')->latest('id')->first();
        }
        else
        {
            $theId = DB::table('tbl_dokumen')->latest('id')->first();
        }

        $result = DB::table('tbl_dokumen')
            ->join('tbl_satker', 'tbl_dokumen.satkerId', '=', 'tbl_satker.id')
            ->join('tbl_ppk', 'tbl_dokumen.ppkId', '=', 'tbl_ppk.id')
            ->join('tbl_dokumen_temuan', 'tbl_dokumen.dokumenTemuanId', '=', 'tbl_dokumen_temuan.id')
            ->select('tbl_dokumen.id', 'tbl_dokumen.tipeDokumenId', 'tbl_dokumen.noUraianTemuan', 'tbl_dokumen.uraianTemuan', 'tbl_dokumen.kodeRekomendasi', 'tbl_dokumen.kodeRingkasanTindakLanjut', 'tbl_dokumen.statusTindakLanjut', 'tbl_dokumen.subNomorRekomendasi', 'tbl_dokumen.nomorHeader', 'tbl_dokumen.titleHeader', 'tbl_dokumen.satkerId', 'tbl_dokumen.ppkId', 'tbl_dokumen.dokumenTemuanId', 'tbl_dokumen_temuan.noLHA', 'tbl_dokumen.tglTindakLanjut', 'tbl_dokumen_temuan.tglTerimaDokumenTemuan', 'tbl_dokumen.nomorDraft', 'tbl_dokumen.isRevisi', 'tbl_satker.namaSatker', 'tbl_ppk.namaPpk')
            ->where('tbl_dokumen.id', $theId->id)
            ->first();
    
        $time = strtotime($result->tglTindakLanjut);

        $newformat = date('d-m-Y',$time);

        $result->tglTindakLanjut = $newformat;
        $result->uraianTemuan = strip_tags($result->uraianTemuan);
        // if ($result->statusTindakLanjut == 'Tersedia')
        // {
        //     $result->isEdit = 1;
        // }
        // else
        // {
        //     $result->isEdit = 0;
        // }
        
        // if ($result->isRevisi == 0 && $result->nomorDraft == null)
        // {
        //     $result->keterangan = 'Dokumen Baru perlu ditindak lanjuti';
        // }
        // else if ($result->nomorDraft != null && $result->isRevisi == 0 && $result->statusTindakLanjut == 'Dalam Proses')
        // {
        //     $result->keterangan = 'Menunggu Respon';
        // }
        // else if ($result->nomorDraft != null && $result->isRevisi == 1 && $result->statusTindakLanjut == 'Dalam Proses')
        // {
        //     $result->keterangan = 'Dokumen Perlu direvisi';
        // }
        // else if ($result->statusTindakLanjut == 'Selesai')
        // {
        //     $result->keterangan = 'Dokumen Tuntas';
        // }

        return response()->json(compact('result'), 200);
    }

    public function show($id, $temuan_id)
    {
        $dataTemuan = DB::table('tbl_dokumen_temuan')
            ->where('id', $temuan_id)
            ->first();

        $dataDokumen = DB::table('tbl_dokumen')
            ->join('tbl_satker', 'tbl_dokumen.satkerId', '=', 'tbl_satker.id')
            ->join('tbl_ppk', 'tbl_dokumen.ppkId', '=', 'tbl_ppk.id')
            ->select('tbl_dokumen.*', 'tbl_satker.namaSatker', 'tbl_ppk.namaPpk')
            ->where('tbl_dokumen.id', $id)
            ->first();

        $result = [
            'dataDokumenTemuan' => $dataTemuan,
            'dataDokumen' => $dataDokumen
        ];

        return response()->json(compact('result'), 200);
    }

    function createRespon(Request $request, $id)
    {
        $result = TindakLanjut::find($id);
        $result->tglResponTindakLanjut = $request->tglResponTindakLanjut;
        $result->responTindakLanjut = $request->responTindakLanjut;
        $result->isRevisi = $request->isRevisi;
        $result->save();
            
        // \LogActivity::addToLog(Auth::user()->nama.' dari cabang '.Auth::user()->satkerId.' telah mengubah data Temuan');

        return response()->json([
            'message' => 'Berhasil me-respon Data Tindak Lanjut',
            'result' => $result
        ], 200);
    }

    function update(Request $request, $id)
    {
        $tindakLanjut = Dokumen::find($id);

        if ($request->hasFile('dokumenTindakLanjut'))
        {
            $oldFile = public_path("public/dokumen/{$tindakLanjut->dokumenTindakLanjut}");
            if (File::exists($oldFile)) { // unlink or remove previous image from folder
                unlink($oldFile);
            }
            $fileNameWithExt = $request->file('dokumenTindakLanjut')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('dokumenTindakLanjut')->getClientOriginalExtension();
            $fileNameToStore = $fileName.'_'.time().'.'.$extension;
            $path = $request->file('dokumenTindakLanjut')->storeAs('public/dokumen/', $fileNameToStore);
        }
        else
        {
            $fileNameToStore = $tindakLanjut->dokumenTindakLanjut;
        }

        $updateDokumen = Dokumen::find($id);
        $updateDokumen->satkerId = (int)$request->_satkerId;
        $updateDokumen->dokumenTemuanId = (int)$request->dokumenTemuanId;
        $updateDokumen->noUraianTemuan = $request->_noUraianTemuan;
        $updateDokumen->uraianTemuan = $request->_uraianTemuan;
        $updateDokumen->rekomendasi = $request->_rekomendasi;
        $updateDokumen->kodeRekomendasi = $request->_kodeRekomendasi;
        $updateDokumen->kodeRingkasanTindakLanjut = $request->_kodeRingkasanTindakLanjut;
        $updateDokumen->ringkasanTindakLanjut = $request->_ringkasanTindakLanjut;
        $updateDokumen->nomorHeader = $request->_nomorHeader;
        $updateDokumen->titleHeader = $request->_titleHeader;
        $updateDokumen->tglTindakLanjut = date('Y-m-d');
        $updateDokumen->statusTindakLanjut = $request->_statusTindakLanjut;
        $updateDokumen->tindakLanjut = $request->_tindakLanjut;
        $updateDokumen->ppkId = (int)$request->_ppkId;
        $updateDokumen->userId = (int)Auth::user()->id;
        $updateDokumen->dokumenTindakLanjut = $fileNameToStore;
        $updateDokumen->save();

        $result = $updateDokumen;

        $theSatker = DB::table('tbl_satker')->where('id', $updateDokumen->satkerId)->first();
        $thePpk = DB::table('tbl_ppk')->where('id', $updateDokumen->ppkId)->first();

        
        $time = strtotime($updateDokumen['tglTindakLanjut']);

        $newformat = date('d-m-Y',$time);

        $updateDokumen['tglTindakLanjut'] = $newformat;
        $result['namaSatker'] = $theSatker->namaSatker;
        $result['namaPpk'] = $thePpk->namaPpk;
            
        // \LogActivity::addToLog(Auth::user()->nama.' dari cabang '.Auth::user()->satkerId.' telah mengubah data Temuan');

        return response()->json([
            'message' => 'Berhasil update data',
            'result' => $result
        ], 200);
    }

    public function destroy($id)
    {
        $result = TindakLanjut::find($id);
        $result->isDeleted = '1';
        $result->save();

        // \LogActivity::addToLog(Auth::user()->nama.' dari cabang '.Auth::user()->satkerId.' telah menghapus data Temuan');

        return response()->json([
            'message' => 'Berhasil menghapus data',
            'result' => $result
        ], 200);
    }
    
    function sendEmailNeedRespon($satker, $noLHA, $tglLHA)
    {
        
    }
}
