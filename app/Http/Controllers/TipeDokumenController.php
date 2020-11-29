<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipeDokumen;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TipeDokumenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => 'login']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = DB::table('tbl_tipe_dokumen')
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
            'tipeDokumen' => 'required|string|max:255'
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->errors()->toJson(), 400);
        }
        
        $tipeDokumen = $request->get('tipeDokumen');

        $tipeDokumenDb = TipeDokumen::query()
            ->where('tipeDokumen', $tipeDokumen)
            ->count();

        if ($tipeDokumenDb > 0)
        {
            return response()->json([
                'message' => 'Tipe Dokumen Sudah Ada',
                'result' => $tipeDokumen
            ], 400);
        }
        else
        {
            // \LogActivity::addToLog(Auth::user()->nama.' dari cabang '.Auth::user()->satkerId.' telah menambahkan data Jenis Dokumen Temuan '. $tipeDokumen);
            
            $result = tipeDokumen::create([
                'tipeDokumen' => $tipeDokumen
            ]);

            return response()->json(compact('result'), 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tipeDokumenDb = TipeDokumen::query()
            ->where('tipeDokumen', $tipeDokumen)
            ->count();
            
        if ($tipeDokumenDb > 0)
        {
            return response()->json([
                'message' => 'Tipe Dokumen Sudah Ada',
                'result' => $tipeDokumen
            ], 400);
        }
        else
        {
            $result = TipeDokumen::find($id);
            $result->tipeDokumen = $request->tipeDokumen;
            $result->save();

            \LogActivity::addToLog(Auth::user()->nama.' dari satker '.Auth::user()->satkerId.' telah mengubah data Jenis Dokumen Temuan '. $request->tipeDokumen);
            return response()->json(compact('result'), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = TipeDokumen::find($id);
        $result->isDeleted = 1;
        $result->save();

        \LogActivity::addToLog(Auth::user()->nama.' dari cabang '.Auth::user()->cabang.' telah menghapus data Jenis Dokumen Temuan '. $result->tipeDokumen);
        return response()->json(compact('result'), 200);
    }
}
