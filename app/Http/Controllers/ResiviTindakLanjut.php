<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\RevisiTindakLanjut;
use JWTAUth;

class RevisiTindakLanjutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = DB::table('view_revisi_tindak_lanjut_records')
            ->select('view_revisi_tindak_lanjut_records.*')
            ->where('isDeleted', 0)
            ->get();

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
        $validator = Validator::make($request->all(), [
            'nomorDraft' => 'required|numeric',
            'tindakLanjutId' => 'required',
            'tglRevisiTindakLanjut' => 'required',
            'dokumenId' => 'required|numeric'
        ]);

        $result = RevisiTindakLanjut::create([
            'nomorDraft' => $request->nomorDraft,
            'tindakLanjutId' => $request->tindakLanjutId,
            'tglRevisiTindakLanjut' => $request->tglRevisiTindakLanjut,
            'dokumenId' => $request->dokumenId,
        ]);

        \LogActivity::addToLog(Auth::user()->nama.' dari Satker '.Auth::user()->satkerId.' telah merevisi data Tindak Lanjut '.$request->tindakLanjutId);
        
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
            'nomorDraft' => 'required|numeric',
            'tindakLanjutId' => 'required',
            'tglRevisiTindakLanjut' => 'required',
            'dokumenId' => 'required|numeric'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $result = RevisiTindakLanjut::find($id);
        $result->nomorDraft = $request->nomorDraft;
        $result->tindakLanjutId = $request->tindakLanjutId;
        $result->tglRevisiTindakLanjut = $request->tglRevisiTindakLanjut;
        $result->dokumenId = $request->dokumenId;
        $result->save();
            
        \LogActivity::addToLog(Auth::user()->nama.' dari satker '.Auth::user()->satkerId.' telah mengubah data revisi tindak lanjut '.$result->tindakLanjutId);

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
        $result = RevisiTindakLanjut::find($id);
        $result->isDeleted = '1';
        $result->save();

        \LogActivity::addToLog(Auth::user()->nama.' dari cabang '.Auth::user()->satkerId.' telah menghapus data revisi tindak lanjut '.$result->tindakLanjutId);

        return response()->json([
            'message' => 'Berhasil menghapus data',
            'result' => $result
        ], 200);
    }
}
