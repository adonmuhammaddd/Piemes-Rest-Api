<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Ppk;
use App\News;
use App\Satker;
use App\Dokumen;
use App\DokTemuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwtmiddleware', ['except' => ['downloadImage']]);
    }
    
    function filterSatker(Request $request)
    {
        if ($request->has('namaSatker'))
        {
            $result = Satker::query()
                ->where('namaSatker', 'LIKE', '%'.$request->namaSatker.'%')
                ->get();

            return response()->json(compact('result'), 200);
        }
        else
        {
            $result = Satker::all();
            
            return response()->json(compact('result'), 200);
        }
    }
    
    function filterNews(Request $request)
    {
        if ($request->has('title'))
        {
            $result = News::query()
                ->where('title', 'LIKE', '%'.$request->title.'%')
                ->get();

            return response()->json(compact('result'), 200);
        }
        else if ($request->has('isActive'))
        {
            $result = Satker::query()
                ->where('isActive', 'LIKE', '%'.$request->isActive.'%')
                ->get();

            return response()->json(compact('result'), 200);
        }
        else if ($request->has('title') && $request->has('isActive'))
        {
            $result = Satker::query()
                ->where('title', 'LIKE', '%'.$request->title.'%')
                ->where('isActive', 'LIKE', '%'.$request->isActive.'%')
                ->get();

            return response()->json(compact('result'), 200);
        }
        else
        {
            $result = Satker::all();
            
            return response()->json(compact('result'), 200);
        }
    }
    
    function filterPpk(Request $request)
    {
        if ($request->has('namaPpk'))
        {
            $result = PPK::query()
                ->where('namaPpk', 'LIKE', '%'.$request->namaPpk.'%')
                ->get();
                
            return response()->json(compact('result'), 200);
        }
        else if ($request->has('satkerId'))
        {
            $result = PPK::query()
                ->where('satkerId', 'LIKE', '%'.$request->satkerId.'%')
                ->get();
                
            return response()->json(compact('result'), 200);
        }
        else if ($request->has('namaPpk', 'satkerId'))
        {
            $result = PPK::query()
                ->where('namaPpk', 'LIKE', '%'.$request->namaPpk.'%')
                ->where('satkerId', 'LIKE', '%'.$request->satkerId.'%')
                ->get();
                
            return response()->json(compact('result'), 200);
        }
        else
        {
            $result = PPK::query()
                ->where('isDeleted', 0)
                ->get();
                
            return response()->json(compact('result'), 200);
        }
    }
    
    function filterTindakLanjut(Request $request)
    {
        if (Auth::user()->satkerId == null)
        {
            if ($request->has('noLHA'))
            {
                $result = Dokumen::query()
                    ->where('noLHA', 'LIKE', '%'.$request->noLHA.'%')
                    ->get();
                
                return response()->json(compact('result'), 200);
            }
            else if ($request->has('satkerId'))
            {
                $result = Dokumen::query()
                    ->where('satkerId', 'LIKE', '%'.$request->satkerId.'%')
                    ->get();
                
                return response()->json(compact('result'), 200);
            }
            else if ($request->has('tglTerimaDokumenTemuan'))
            {
                $doktemuan = DokTemuan::query()
                    ->where('tglTerimaDokumenTemuan', 'LIKE', '%'.$request->tglTerimaDokumenTemuan.'%')
                    ->get();
    
                $result = Dokumen::query()
                    ->where('dokumenTemuanId', $doktemuan->id)
                    ->get();
                
                return response()->json(compact('result'), 200);
            }
            else if ($request->has('statusTindakLanjut'))
            {
                $result = Dokumen::query()
                    ->where('statusTindakLanjut', 'LIKE', '%'.$request->statusTindakLanjut.'%')
                    ->get();
                
                return response()->json(compact('result'), 200);
            }
            else if ($request->has('titleHeader'))
            {
                $result = Dokumen::query()
                    ->where('titleHeader', 'LIKE', '%'.$request->titleHeader.'%')
                    ->get();
                
                return response()->json(compact('result'), 200);
            }
            else if ($request->has('noLHA', 'satkerId'))
            {
                $result = Dokumen::query()
                    ->where('nama', 'LIKE', '%'.$request->nama.'%')
                    ->where('satkerId', 'LIKE', '%'.$request->satkerId.'%')
                    ->get();
    
                return response()->json(compact('result'), 200);
            }
            else if ($request->has('noLHA', 'satkerId' ,'tglTerimaDokumenTemuan'))
            {
                $doktemuan = DokTemuan::query()
                    ->where('tglTerimaDokumenTemuan', 'LIKE', '%'.$request->tglTerimaDokumenTemuan.'%')
                    ->get();
    
                $result = Dokumen::query()
                    ->where('nama', 'LIKE', '%'.$request->nama.'%')
                    ->where('satkerId', 'LIKE', '%'.$request->satkerId.'%')
                    ->where('dokumenTemuanId', $doktemuan->id)
                    ->get();
    
                return response()->json(compact('result'), 200);
            }
            else if ($request->has('noLHA', 'satkerId', 'tglTerimaDokumenTemuan', 'statusTindakLanjut'))
            {
                $doktemuan = DokTemuan::query()
                    ->where('tglTerimaDokumenTemuan', 'LIKE', '%'.$request->tglTerimaDokumenTemuan.'%')
                    ->get();
    
                $result = Dokumen::query()
                    ->where('nama', 'LIKE', '%'.$request->nama.'%')
                    ->where('satkerId', 'LIKE', '%'.$request->satkerId.'%')
                    ->where('dokumenTemuanId', $doktemuan->id)
                    ->where('statusTindakLanjut', 'LIKE', '%'.$request->statusTindakLanjut.'%')
                    ->get();
    
                return response()->json(compact('result'), 200);
            }
            else if ($request->has('noLHA', 'satkerId', 'tglTerimaDokumenTemuan', 'statusTindakLanjut', 'titleHeader'))
            {
                $doktemuan = DokTemuan::query()
                    ->where('tglTerimaDokumenTemuan', 'LIKE', '%'.$request->tglTerimaDokumenTemuan.'%')
                    ->get();
    
                $result = Dokumen::query()
                    ->where('nama', 'LIKE', '%'.$request->nama.'%')
                    ->where('satkerId', 'LIKE', '%'.$request->satkerId.'%')
                    ->where('dokumenTemuanId', $doktemuan->id)
                    ->where('statusTindakLanjut', 'LIKE', '%'.$request->statusTindakLanjut.'%')
                    ->where('titleHeader', 'LIKE', '%'.$request->titleHeader.'%')
                    ->get();
    
                return response()->json(compact('result'), 200);
            }
            else
            {
                $result = Dokumen::all();
                
                return response()->json(compact('result'), 200);
            }
        }
        else
        {
            if ($request->has('noLHA'))
            {
                $result = Dokumen::query()
                    ->where('noLHA', 'LIKE', '%'.$request->noLHA.'%')
                    ->where('satkerId', 'LIKE', '%'.Auth::user()->satkerId.'%')
                    ->get();
                
                return response()->json(compact('result'), 200);
            }
            else if ($request->has('tglTerimaDokumenTemuan'))
            {
                $doktemuan = DokTemuan::query()
                    ->where('tglTerimaDokumenTemuan', 'LIKE', '%'.$request->tglTerimaDokumenTemuan.'%')
                    ->get();
    
                $result = Dokumen::query()
                    ->where('dokumenTemuanId', $doktemuan->id)
                    ->get();
                
                return response()->json(compact('result'), 200);
            }
            else if ($request->has('statusTindakLanjut'))
            {
                $result = Dokumen::query()
                    ->where('statusTindakLanjut', 'LIKE', '%'.$request->statusTindakLanjut.'%')
                    ->where('satkerId', 'LIKE', '%'.Auth::user()->satkerId.'%')
                    ->get();
                
                return response()->json(compact('result'), 200);
            }
            else if ($request->has('titleHeader'))
            {
                $result = Dokumen::query()
                    ->where('titleHeader', 'LIKE', '%'.$request->titleHeader.'%')
                    ->where('satkerId', 'LIKE', '%'.Auth::user()->satkerId.'%')
                    ->get();
                
                return response()->json(compact('result'), 200);
            }
            else if ($request->has('noLHA'))
            {
                $result = Dokumen::query()
                    ->where('nama', 'LIKE', '%'.$request->nama.'%')
                    ->where('satkerId', 'LIKE', '%'.Auth::user()->satkerId.'%')
                    ->get();
    
                return response()->json(compact('result'), 200);
            }
            else if ($request->has('noLHA','tglTerimaDokumenTemuan'))
            {
                $doktemuan = DokTemuan::query()
                    ->where('tglTerimaDokumenTemuan', 'LIKE', '%'.$request->tglTerimaDokumenTemuan.'%')
                    ->get();
    
                $result = Dokumen::query()
                    ->where('nama', 'LIKE', '%'.$request->nama.'%')
                    ->where('satkerId', 'LIKE', '%'.$request->satkerId.'%')
                    ->where('dokumenTemuanId', $doktemuan->id)
                    ->where('satkerId', 'LIKE', '%'.Auth::user()->satkerId.'%')
                    ->get();
    
                return response()->json(compact('result'), 200);
            }
            else if ($request->has('noLHA', 'tglTerimaDokumenTemuan', 'statusTindakLanjut'))
            {
                $doktemuan = DokTemuan::query()
                    ->where('tglTerimaDokumenTemuan', 'LIKE', '%'.$request->tglTerimaDokumenTemuan.'%')
                    ->get();
    
                $result = Dokumen::query()
                    ->where('nama', 'LIKE', '%'.$request->nama.'%')
                    ->where('dokumenTemuanId', $doktemuan->id)
                    ->where('statusTindakLanjut', 'LIKE', '%'.$request->statusTindakLanjut.'%')
                    ->where('satkerId', 'LIKE', '%'.Auth::user()->satkerId.'%')
                    ->get();
    
                return response()->json(compact('result'), 200);
            }
            else if ($request->has('noLHA', 'tglTerimaDokumenTemuan', 'statusTindakLanjut', 'titleHeader'))
            {
                $doktemuan = DokTemuan::query()
                    ->where('tglTerimaDokumenTemuan', 'LIKE', '%'.$request->tglTerimaDokumenTemuan.'%')
                    ->get();
    
                $result = Dokumen::query()
                    ->where('nama', 'LIKE', '%'.$request->nama.'%')
                    ->where('dokumenTemuanId', $doktemuan->id)
                    ->where('statusTindakLanjut', 'LIKE', '%'.$request->statusTindakLanjut.'%')
                    ->where('titleHeader', 'LIKE', '%'.$request->titleHeader.'%')
                    ->where('satkerId', 'LIKE', '%'.Auth::user()->satkerId.'%')
                    ->get();
    
                return response()->json(compact('result'), 200);
            }
            else
            {
                $result = Dokumen::all();
                return response()->json(compact('result'), 200);
            }
        }
    }
    
    function filterDokTemuan(Request $request)
    {
        if ($request->has('noLHA'))
        {
            $result = DokTemuan::query()
                ->where('noLHA', 'LIKE', '%'.$request->noLHA.'%')
                ->get();
            
            return response()->json(compact('result'), 200);
        }
        else if ($request->has('tglLHA'))
        {
            $result = DokTemuan::query()
                ->where('tglLHA', 'LIKE', '%'.$request->tglLHA.'%')
                ->get();
            
            return response()->json(compact('result'), 200);
        }
        else if ($request->has('namaKegiatan'))
        {
            $result = DokTemuan::query()
                ->where('namaKegiatan', 'LIKE', '%'.$request->namaKegiatan.'%')
                ->get();
            
            return response()->json(compact('result'), 200);
        }
        else if ($request->has('jenisDokumenTemuanId'))
        {
            $result = DokTemuan::query()
                ->where('jenisDokumenTemuanId', 'LIKE', '%'.$request->jenisDokumenTemuanId.'%')
                ->get();
            
            return response()->json(compact('result'), 200);
        }
        else if ($request->has('keadaanSdBulan'))
        {
            $result = Dokumen::query()
                ->where('keadaanSdBulan', 'LIKE', '%'.$request->keadaanSdBulan.'%')
                ->get();
            
            return response()->json(compact('result'), 200);
        }
        else if ($request->has('noLHA', 'tglLHA'))
        {
            $result = DokTemuan::query()
                ->where('noLHA', 'LIKE', '%'.$request->noLHA.'%')
                ->where('tglLHA', 'LIKE', '%'.$request->tglLHA.'%')
                ->get();

            return response()->json(compact('result'), 200);
        }
        else if ($request->has('noLHA', 'namaKegiatan'))
        {
            $result = DokTemuan::query()
                ->where('noLHA', 'LIKE', '%'.$request->noLHA.'%')
                ->where('namaKegiatan', 'LIKE', '%'.$request->namaKegiatan.'%')
                ->get();

            return response()->json(compact('result'), 200);
        }
        else if ($request->has('noLHA', 'jenisDokumenTemuanId'))
        {
            $result = DokTemuan::query()
                ->where('noLHA', 'LIKE', '%'.$request->noLHA.'%')
                ->where('jenisDokumenTemuanId', 'LIKE', '%'.$request->jenisDokumenTemuanId.'%')
                ->get();

            return response()->json(compact('result'), 200);
        }
        else if ($request->has('noLHA', 'keadaanSdBulan'))
        {
            $result = DokTemuan::query()
                ->where('noLHA', 'LIKE', '%'.$request->noLHA.'%')
                ->where('keadaanSdBulan', 'LIKE', '%'.$request->keadaanSdBulan.'%')
                ->get();

            return response()->json(compact('result'), 200);
        }
        else if ($request->has('tglLHA' ,'namaKegiatan'))
        {
            $result = DokTemuan::query()
                ->where('tglLHA', 'LIKE', '%'.$request->tglLHA.'%')
                ->where('namaKegiatan', 'LIKE', '%'.$request->namaKegiatan.'%')
                ->get();

            return response()->json(compact('result'), 200);
        }
        else if ($request->has('tglLHA', 'jenisDokumenTemuanId'))
        {
            $result = DokTemuan::query()
                ->where('tglLHA', 'LIKE', '%'.$request->tglLHA.'%')
                ->where('jenisDokumenTemuanId', 'LIKE', '%'.$request->jenisDokumenTemuanId.'%')
                ->get();

            return response()->json(compact('result'), 200);
        }
        else if ($request->has('tglLHA', 'keadaanSdBulan'))
        {
            $result = DokTemuan::query()
                ->where('tglLHA', 'LIKE', '%'.$request->tglLHA.'%')
                ->where('keadaanSdBulan', 'LIKE', '%'.$request->keadaanSdBulan.'%')
                ->get();

            return response()->json(compact('result'), 200);
        }
        else if ($request->has('namaKegiatan', 'jenisDokumenTemuanId'))
        {
            $result = DokTemuan::query()
                ->where('namaKegiatan', 'LIKE', '%'.$request->namaKegiatan.'%')
                ->where('jenisDokumenTemuanId', 'LIKE', '%'.$request->jenisDokumenTemuanId.'%')
                ->get();

            return response()->json(compact('result'), 200);
        }
        else if ($request->has('namaKegiatan', 'keadaanSdBulan'))
        {
            $result = DokTemuan::query()
                ->where('namaKegiatan', 'LIKE', '%'.$request->namaKegiatan.'%')
                ->where('keadaanSdBulan', 'LIKE', '%'.$request->keadaanSdBulan.'%')
                ->get();

            return response()->json(compact('result'), 200);
        }
        else if ($request->has('noLHA', 'tglLHA' ,'namaKegiatan'))
        {
            $result = DokTemuan::query()
                ->where('noLHA', 'LIKE', '%'.$request->noLHA.'%')
                ->where('tglLHA', 'LIKE', '%'.$request->tglLHA.'%')
                ->where('namaKegiatan', 'LIKE', '%'.$request->namaKegiatan.'%')
                ->get();

            return response()->json(compact('result'), 200);
        }
        else if ($request->has('noLHA', 'tglLHA', 'jenisDokumenTemuanId'))
        {
            $result = DokTemuan::query()
                ->where('noLHA', 'LIKE', '%'.$request->noLHA.'%')
                ->where('tglLHA', 'LIKE', '%'.$request->tglLHA.'%')
                ->where('jenisDokumenTemuanId', 'LIKE', '%'.$request->jenisDokumenTemuanId.'%')
                ->get();

            return response()->json(compact('result'), 200);
        }
        else if ($request->has('noLHA', 'tglLHA', 'keadaanSdBulan'))
        {
            $result = DokTemuan::query()
                ->where('noLHA', 'LIKE', '%'.$request->noLHA.'%')
                ->where('tglLHA', 'LIKE', '%'.$request->tglLHA.'%')
                ->where('keadaanSdBulan', 'LIKE', '%'.$request->keadaanSdBulan.'%')
                ->get();

            return response()->json(compact('result'), 200);
        }
        else if ($request->has('noLHA', 'tglLHA', 'namaKegiatan', 'jenisDokumenTemuanId'))
        {
            $result = DokTemuan::query()
                ->where('noLHA', 'LIKE', '%'.$request->noLHA.'%')
                ->where('tglLHA', 'LIKE', '%'.$request->tglLHA.'%')
                ->where('namaKegiatan', 'LIKE', '%'.$request->namaKegiatan.'%')
                ->where('jenisDokumenTemuanId', 'LIKE', '%'.$request->jenisDokumenTemuanId.'%')
                ->get();

            return response()->json(compact('result'), 200);
        }
        else if ($request->has('noLHA', 'tglLHA', 'namaKegiatan', 'jenisDokumenTemuanId', 'keadaanSdBulan'))
        {
            $result = DokTemuan::query()
                ->where('noLHA', 'LIKE', '%'.$request->noLHA.'%')
                ->where('tglLHA', 'LIKE', '%'.$request->tglLHA.'%')
                ->where('namaKegiatan', 'LIKE', '%'.$request->namaKegiatan.'%')
                ->where('jenisDokumenTemuanId', 'LIKE', '%'.$request->jenisDokumenTemuanId.'%')
                ->where('keadaanSdBulan', 'LIKE', '%'.$request->keadaanSdBulan.'%')
                ->get();

            return response()->json(compact('result'), 200);
        }
        else
        {
            $result = DokTemuan::all();
            
            return response()->json(compact('result'), 200);
        }
    }
    
    public function filterUser(Request $request)
    {
        if ($request->has('nama'))
        {
            $result = User::query()
                ->where('nama', 'LIKE', '%'.$request->nama.'%')
                ->get();
            
            return response()->json(compact('result'), 200);
        }
        else if ($request->has('satkerId'))
        {
            $result = User::query()
                ->where('satkerId', 'LIKE', '%'.$request->satkerId.'%')
                ->get();
            
            return response()->json(compact('result'), 200);
        }
        else if ($request->has('nama', 'satkerId'))
        {
            $result = User::query()
                ->where('nama', 'LIKE', '%'.$request->nama.'%')
                ->where('satkerId', 'LIKE', '%'.$request->satkerId.'%')
                ->get();

            return response()->json(compact('result'), 200);
        }
        else
        {
            $result = User::all();
            
            return response()->json(compact('result'), 200);
        }
    }

    function downloadFileTemuan(Request $request)
    {
        if (Storage::disk('public')->exists('dokumentemuan/'.$request->fileDokumen))
        {
            return response()->download(public_path('storage/dokumentemuan/'.$request->fileDokumen), $request->fileDokumen);
        }
        else
        {
            $result = 'File tidak ada';
            
            return response()->json(compact('result'), 404);
        }
    }

    function downloadFileTindakLanjut($dokumenTindakLanjut)
    {
        if (File::exists(public_path().'/dokumen/'.$dokumenTindakLanjut))
        {
            return response()->download(public_path('/dokumen/'.$dokumenTindakLanjut), $dokumenTindakLanjut);
        }
        else
        {
            $result = 'File tidak ada';
            return response()->json(compact('result'), 404);
        }
    }

    function downloadImage($image)
    {
        if (File::exists(public_path().'/images/'.$image))
        {
            return response()->download(public_path('/images/'.$image), $image);
        }
        else
        {
            $result = 'File tidak ada';
            return response()->json(compact('result'), 404);
        }
    }
}
