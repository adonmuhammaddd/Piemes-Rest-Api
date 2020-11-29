<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use App\DokApproval;
use App\TindakLanjut;
use App\Helpers\RefreshToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTExceptions;
use JWTAUth;

class ApprovalDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $result = DB::table('view_approval_record')
            ->select('view_approval_record.*')
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
            'idTindakLanjut' => 'required|numeric',
            'noDokumenApv' => 'required',
            'namaDokumenApv' => 'required',
            'tglApproval' => 'required',
            'catatan' => 'required',
            'fileDokumen' => 'required|max:10000'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        if ($request->hasFile('fileDokumen'))
        {
            $fileNameWithExt = $request->file('fileDokumen')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('fileDokumen')->getClientOriginalExtension();
            $fileNameToStore = str_slug($fileName).'_'.time().'.'.$extension;
            $path = $request->file('fileDokumen')->storeAs('public/dokumenapproval', $fileNameToStore);
        }
        else
        {
            $fileNameToStore = 'nofile.pdf';
        }

        $result = DokApproval::create([
            'idUser' => Auth::user()->id,
            'idTindakLanjut' => $request->idTindakLanjut,
            'noDokumen' => $request->noDokumenApv,
            'namaDokumen' => $request->namaDokumenApv,
            'tglApproval' => $request->tglApproval,
            'fileDokumen' => $fileNameToStore,
            'catatan' => $request->catatan,
        ]);
        if ($result)
        {
            \LogActivity::addToLog(Auth::user()->nama.' dari cabang '.Auth::user()->cabang.' telah menambah data Approval baru'. $request->namaDokumenApv);
            return response()->json(compact('result'), 200);
        }
        else
        {
            return response()->json('Gagal menyimpan data', 204);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $validator = Validator::make($request->all(), [
            'idTindakLanjut' => 'required|numeric',
            'tglApproval' => 'required',
            'noDokumenApv' => 'required',
            'namaDokumenApv' => 'required',
            'catatan' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $approvalDocument = DokApproval::find($id);

        if ($request->hasFile('fileDokumen'))
        {
            $oldFile = public_path("storage/dokumenapproval/{$approvalDocument->fileDokumen}");
            if (File::exists($oldFile)) { // unlink or remove previous image from folder
                unlink($oldFile);
            }

            $fileNameWithExt = $request->file('fileDokumen')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('fileDokumen')->getClientOriginalExtension();
            $fileNameToStore = $fileName.'_'.time().'.'.$extension;
            $path = $request->file('fileDokumen')->storeAs('public/dokumenapproval/', $fileNameToStore);
        }
        else
        {
            $fileNameToStore = $approvalDocument->fileDokumen;
        }

        $result = DokApproval::find($id);
        $result->idTindakLanjut = $request->idTindakLanjut;
        $result->noDokumen = $request->noDokumenApv;
        $result->namaDokumen = $request->namaDokumenApv;
        $result->tglApproval = $request->tglApproval;
        $result->catatan = $request->catatan;
        $result->fileDokumen = $fileNameToStore;
        $result->save();
            
        \LogActivity::addToLog(Auth::user()->nama.' dari cabang '.Auth::user()->cabang.' telah mengubah data Approval'. $request->namaDokumenApv);

        
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
        $result = DokApproval::find($id);
        $result->isDeleted = '1';
        $result->save();

        \LogActivity::addToLog(Auth::user()->nama.' dari cabang '.Auth::user()->cabang.' telah menghapus data Approval'. $result->namaDokumen);
        
        return response()->json(compact('result'), 200);
    }

    // protected function checkToken()
    // {
    //     try {

    //         if (! $user = JWTAuth::parseToken()->authenticate()) {
    //             return response()->json(['user_not_found'], 404);
    //         }

    //     } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

    //         return response()->json(['token_expired'], $e->getStatusCode(), 404);

    //     } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

    //         return response()->json(['token_invalid'], $e->getStatusCode(), 404);

    //     } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

    //         return response()->json(['token_absent'], $e->getStatusCode(), 404);

    //     }
    // }
}
