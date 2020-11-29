<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\ResponDokumenTemuan;
use App\Dokumen;
use JWTAUth;

class ResponDokumenTemuanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = DB::table('view_respon_dokumen_temuan_records')
            ->select('view_respon_dokumen_temuan_records.*')
            ->where('isDeleted', 0)
            ->get();

            for ($i = 0; $i < $result->count(); $i++)
            {
                for($j = 0; $j < $status->count(); $j++)
                {
                    if ($result[$i]->id == $status[$j]->responDokumenTemuanId)
                    {
                        if ($status[$j]->statusTindakLanjut == 'Tersedia')
                        {
                            $result[$i]->isEdit = 1;
                        }
                        else
                        {
                            $result[$i]->isEdit = 0;
                        }
                    }
                }
            }

        return response()->json(compact('result'), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->hasFile('dokumenTindakLanjut'))
        {
            $fileNameWithExt = $request->file('dokumenTindakLanjut')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('dokumenTindakLanjut')->getClientOriginalExtension();
            $fileNameToStore = $fileName.'_'.time().'.'.$extension;
            $path = $request->file('dokumenTindakLanjut')->storeAs('public/dokumen_tindak_lanjut/', $fileNameToStore);
        }
        else
        {
            $fileNameToStore = 'nofile.pdf';
        }

        $result = [
            'dokumenTemuanId' => $request->dokumenTemuanId,
            'tipeDokumenId' => $request->tipeDokumenId,
            'noUraianTemuan' => $request->noUraianTemuan,
            'uraianTemuan' => $request->uraianTemuan,
            'rekomendasi' => $request->rekomendasi,
            'kodeRekomendasi' => $request->kodeRekomendasi,
            'kodeRingkasanTindakLanjut' => $request->kodeRingkasanTindakLanjut,
            'ringkasanTindakLanjut' => $request->ringkasanTindakLanjut,
            'statusTindakLanjut' => $request->statusTindakLanjut,
            'tindakLanjut' => $request->tindakLanjut,
            'subNomorRekomendasi' => $request->subNomorRekomendasi,
            'nomorHeader' => $request->nomorHeader,
            'titleHeader' => $request->titleHeader,
            'satkerId' => $request->satkerId,
            'ppkId' => $request->ppkId,
            'userId' => Auth::user()->id,
            'nomorDraft' => $request->nomorDraft,
            'dokumenTindakLanjut' => $request->dokumenTindakLanjut,
            'tglResponTindakLanjut' => $request->tglResponTindakLanjut,
            'responTindakLanjut' => $request->responTindakLanjut,
            'isRevisi' => $request->isRevisi,
            // 'uniqueColumn' => $uniqueColumn
        ];
        
        Dokumen::insert($result);

        // \LogActivity::addToLog(Auth::user()->nama.' dari Satker '.Auth::user()->satkerId.' telah merevisi data Tindak Lanjut '.$request->tindakLanjutId);

        return response()->json([
            'message' => 'Berhasil menambahkan data',
            'result' => $result
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tindakLanjutId' => 'required',
            'tglResponDokumenTemuan' => 'required',
            'dokumenId' => 'required|numeric',
            'responTindakLanjut' => 'required|numeric'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $result = ResponDokumenTemuan::find($id);
        $result->tindakLanjutId = $request->tindakLanjutId;
        $result->tglResponDokumenTemuan = $request->tglResponDokumenTemuan;
        $result->dokumenId = $request->dokumenId;
        $result->responTindakLanjut = $request->responTindakLanjut;
        $result->save();
            
        // \LogActivity::addToLog(Auth::user()->nama.' dari satker '.Auth::user()->satkerId.' telah mengubah data revisi tindak lanjut '.$result->tindakLanjutId);

        return response()->json([
            'message' => 'Berhasil update data',
            'result' => $result
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = ResponDokumenTemuan::find($id);
        $result->isDeleted = '1';
        $result->save();

        // \LogActivity::addToLog(Auth::user()->nama.' dari cabang '.Auth::user()->satkerId.' telah menghapus data revisi tindak lanjut '.$result->tindakLanjutId);

        return response()->json([
            'message' => 'Berhasil menghapus data',
            'result' => $result
        ], 200);
    }
}
