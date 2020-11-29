<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Dokumen;
use JWTAUth;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use Mavinoo\LaravelBatch\LaravelBatchFacade;

class DokumenController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwtmiddleware');
    }

    public function index()
    {
        if (Auth::user()->roleId == 50 || Auth::user()->roleId == 40)
        {
            $result = DB::table('view_dokumen_records')
                ->select('view_dokumen_records.*')
                ->where('isDeleted', 0)
                ->get();
        }
        else
        {
            $result = DB::table('view_dokumen_records')
                ->select('view_dokumen_records.*')
                ->where('satkerId', Auth::user()->satkerId)
                ->where('isDeleted', 0)
                ->get();
        }

        return response()->json(compact('result'), 200);
    }

    public function testMail()
    {
        $users = DB::table('tbl_user')
            ->select('email', 'nama')
            ->get();

        $theEmail = [];
        $theName = [];
        for ($i = 0; $i < $users->count(); $i++)
        {
            array_push($theEmail, $users[$i]->email);
            array_push($theName, $users[$i]->nama);
        }

        $emailSent = $this->sendEmailTemuanBaru($theEmail);
        if ($emailSent) {
            return response()->json(['message' => 'Mail Sent Sucessfully'], 200);
        }else{
            return response()->json(['message' => 'Mail Sent fail'], 400);
        }
    }

    public function store(Request $request)
    {

        if ($request->hasFile('fileDokumen'))
        {
            $fileNameWithExt = $request->file('fileDokumen')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('fileDokumen')->getClientOriginalExtension();
            $fileNameToStore = $fileName.'_'.time().'.'.$extension;
            $path = $request->file('fileDokumen')->storeAs('public/dokumentemuan/', $fileNameToStore);
        }
        else
        {
            $fileNameToStore = null;
        }

        $digits = 11;
        $randomNum;
        $nomorDraft = 1;

        $result = [];
        for($i = 0; $i < count($request->all()); $i++) {
            $randomNum = 'TL'.rand(pow(10, $digits-1), pow(10, $digits)-1);
            $data = [
                $dokumenTemuanId = $request[$i]['dokumenTemuanId'],
                $noUraianTemuan = $request[$i]['noUraianTemuan'],
                $uraianTemuan = $request[$i]['uraianTemuan'],
                $rekomendasi = $request[$i]['rekomendasi'],
                $kodeRekomendasi = $request[$i]['kodeRekomendasi'],
                $kodeRingkasanTindakLanjut = $request[$i]['kodeRingkasanTindakLanjut'],
                $ringkasanTindakLanjut = $request[$i]['ringkasanTindakLanjut'],
                $statusTindakLanjut = $request[$i]['statusTindakLanjut'],
                $tindakLanjut = $request[$i]['tindakLanjut'],
                $subNomorRekomendasi = $request[$i]['subNomorRekomendasi'],
                $nomorHeader = $request[$i]['nomorHeader'],
                $titleHeader = $request[$i]['titleHeader'],
                $satkerId = Auth::user()->satkerId,
                $ppkId = $request[$i]['ppkId'],
                $userId = Auth::user()->id,
                $nomorDraft,
                $dokumenTindakLanjut = $fileNameToStore,
                $uniqueColumn = $randomNum
            ];

            $insert[] = [
                'dokumenTemuanId' => $dokumenTemuanId,
                'noUraianTemuan' => $noUraianTemuan,
                'uraianTemuan' => $uraianTemuan,
                'rekomendasi' => $rekomendasi,
                'kodeRekomendasi' => $kodeRekomendasi,
                'kodeRingkasanTindakLanjut' => $kodeRingkasanTindakLanjut,
                'ringkasanTindakLanjut' => $ringkasanTindakLanjut,
                'statusTindakLanjut' => $statusTindakLanjut,
                'tindakLanjut' => $tindakLanjut,
                'subNomorRekomendasi' => $subNomorRekomendasi,
                'nomorHeader' => $nomorHeader,
                'titleHeader' => $titleHeader,
                'satkerId' => $satkerId,
                'ppkId' => $ppkId,
                'userId' => $userId,
                'nomorDraft' => $nomorDraft,
                'dokumenTindakLanjut' => $fileNameToStore,
                'uniqueColumn' => $randomNum,
                'isEdit' => 1
            ];
        }

        $insertDokumen = Dokumen::insert($insert);

        $dokumenUniqueTemp = [];        
        for ($i = 0; $i < count($insert); $i++)
        {
            $uniqueTemp = [
                "uniqueColumn" => $insert[$i]['uniqueColumn']
            ];

            $data = $uniqueTemp;
            array_push($dokumenUniqueTemp, $data);
        }

        $getDokumen = DB::table('tbl_dokumen')
            ->whereIn('uniqueColumn', $dokumenUniqueTemp)
            ->get();

        
        for($i = 0; $i < count($getDokumen); $i++)
        {
            $theSatker = DB::table('tbl_satker')->where('id', $getDokumen[$i]->satkerId)->first();
            $thePpk = DB::table('tbl_ppk')->where('id', $getDokumen[$i]->ppkId)->first();
    
            $getDokumen[$i]->namaSatker = $theSatker->namaSatker;
            $getDokumen[$i]->namaPpk = $thePpk->namaPpk;
        }
        // \LogActivity::addToLog(Auth::user()->nama.' dari Satker '.Auth::user()->satkerId.' telah menambah data Dokumen baru '.$request->tipeDokumenId);
         
        $emails = DB::table('tbl_user')
            ->select('email', 'email2', 'email3')
            ->where('id', Auth::user()->id)
            ->first();

        $myEmails = [];
        $emailNull = [];

        foreach($emails as $index => $value)
        {
            if ($value == null)
            {
                $emailNull[] = $value;
            }
            else
            {
                $myEmails[] = $value;
            }
        }

        $emailSent;
        
        for ($i = 0; $i < count($insert); $i++)
        {
            $emailSent = $this->sendEmailTemuanBaru($myEmails, $insert[$i]['kodeRekomendasi'], $insert[$i]['nomorHeader']);
        }

        if ($emailSent)
        {
            return response()->json([
                'message' => 'Berhasil menambahkan data',
                'mailMessage' => 'Mail Sent Sucessfully',
                'result' => $getDokumen
            ], 200);
        }
        else
        {
            return response()->json(['message' => 'Mail Sent fail'], 400);
        }
    // }
    }

    public function show($id)
    {
        $result = Dokumen::where('id', $id)->get();

        return response()->json(compact('result'), 200);
    }

    public function update(Request $request)
    {
        // $dokumenUnique = $request->dokumenTemuanId.'_'.$request->kodeRekomendasi.'_'.$request->noUraianTemuan.'_'.$request->satkerId.'_'.$request->ppkId;

        $dokumenIdTemp = [];        
        for ($i = 0; $i < count($request->all()); $i++)
        {
            $idTemp = [
                "id" => $request[$i]['id']
            ];

            $data = $idTemp;
            array_push($dokumenIdTemp, $data);
        }

        $checkDokumen = DB::table('tbl_dokumen')
            ->whereIn('id', $dokumenIdTemp)
            ->get();

        if ($checkDokumen->count() > 0)
        {
            $dokumenInstance = new Dokumen;
            $result = [];
            for ($i = 0; $i < count($request->all()); $i++)
            {
                $dokumen = [
                    "id" => $request[$i]['id'],
                    "tipeDokumenId" => $request[$i]['tipeDokumenId'],
                    "noUraianTemuan" => $request[$i]['noUraianTemuan'],
                    "uraianTemuan" => $request[$i]['uraianTemuan'],
                    "kodeRekomendasi" => $request[$i]['kodeRekomendasi'],
                    "kodeRingkasanTindakLanjut" => $request[$i]['kodeRingkasanTindakLanjut'],
                    "statusTindakLanjut" => $request[$i]['statusTindakLanjut'],
                    "subNomorRekomendasi" => $request[$i]['subNomorRekomendasi'],
                    "nomorHeader" => $request[$i]['nomorHeader'],
                    "titleHeader" => $request[$i]['titleHeader'],
                    "satkerId" => $request[$i]['satkerId'],
                    "ppkId" => $request[$i]['ppkId'],
                    "dokumenTemuanId" => $request[$i]['dokumenTemuanId'],
                    "rekomendasi" => $request[$i]['rekomendasi'],
                    "tindakLanjut" => $request[$i]['tindakLanjut'],
                    "dokumenTindakLanjut" => $request[$i]['dokumenTindakLanjut']
                ] ;
    
                $data = $dokumen;
                array_push($result, $data);
            }
            $index = 'id';
    
            \Batch::update($dokumenInstance, $result, $index);
                
            // \LogActivity::addToLog(Auth::user()->nama.' dari satker '.Auth::user()->satkerId.' telah mengubah data Dokumen '.$kodeTemuan);
        }
        else
        {
            if ($request->hasFile('fileDokumen'))
            {
                $fileNameWithExt = $request->file('fileDokumen')->getClientOriginalName();
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('fileDokumen')->getClientOriginalExtension();
                $fileNameToStore = $fileName.'_'.time().'.'.$extension;
                $path = $request->file('fileDokumen')->storeAs('public/dokumentemuan/', $fileNameToStore);
            }
            else
            {
                $fileNameToStore = null;
            }

            $result = [];
            for($i = 0; $i < count($request->all()); $i++) {
                $data = [
                    $dokumenTemuanId = $request[$i]['dokumenTemuanId'],
                    $tipeDokumenId = $request[$i]['tipeDokumenId'],
                    $noUraianTemuan = $request[$i]['noUraianTemuan'],
                    $uraianTemuan = $request[$i]['uraianTemuan'],
                    $rekomendasi = $request[$i]['rekomendasi'],
                    $kodeRekomendasi = $request[$i]['kodeRekomendasi'],
                    $kodeRingkasanTindakLanjut = $request[$i]['kodeRingkasanTindakLanjut'],
                    $ringkasanTindakLanjut = $request[$i]['ringkasanTindakLanjut'],
                    $statusTindakLanjut = $request[$i]['statusTindakLanjut'],
                    $tindakLanjut = $request[$i]['tindakLanjut'],
                    $subNomorRekomendasi = $request[$i]['subNomorRekomendasi'],
                    $nomorHeader = $request[$i]['nomorHeader'],
                    $titleHeader = $request[$i]['titleHeader'],
                    $satkerId = $request[$i]['satkerId'],
                    $ppkId = $request[$i]['ppkId']
                ];

                $result[] = [
                    'dokumenTemuanId' => $dokumenTemuanId,
                    'tipeDokumenId' => $tipeDokumenId,
                    'noUraianTemuan' => $noUraianTemuan,
                    'uraianTemuan' => $uraianTemuan,
                    'rekomendasi' => $rekomendasi,
                    'kodeRekomendasi' => $kodeRekomendasi,
                    'kodeRingkasanTindakLanjut' => $kodeRingkasanTindakLanjut,
                    'ringkasanTindakLanjut' => $ringkasanTindakLanjut,
                    'statusTindakLanjut' => $statusTindakLanjut,
                    'tindakLanjut' => $tindakLanjut,
                    'subNomorRekomendasi' => $subNomorRekomendasi,
                    'nomorHeader' => $nomorHeader,
                    'titleHeader' => $titleHeader,
                    'satkerId' => $satkerId,
                    'ppkId' => $ppkId
                ];
            }

            Dokumen::insert($result);
        }

        return response()->json([
            'message' => 'Berhasil update data',
            'result' => $result
        ], 200);
    }

    public function destroy($id)
    {
        $result = Dokumen::find($id);
        $result->isDeleted = '1';
        $result->save();

        // \LogActivity::addToLog(Auth::user()->nama.' dari cabang '.Auth::user()->satkerId.' telah menghapus data Dokumen '.$result->kodeTemuan);

        return response()->json([
            'message' => 'Berhasil menghapus data',
            'result' => $result
        ], 200);
    }

    public function sendEmailTemuanBaru($emails, $kodeRekomendasi, $nomorHeader)
    {
        $title = 'Halo .';
        $description = 'Ada dokumen temuan baru yang perlu ditindaklanjuti.';
        $content = 'Dokumen Tindak lanjut yang kami serahkan pada tanggal <b>'.$nomorHeader.'</b> dengan Nomor <b>'.$kodeRekomendasi.'</b> dimohon untuk segera diselesaikan dengan menggunakan aplikasi MAD (Monitoring Approval Dokumen) ya, Tetap semangat <b>Terima kasih</b>';
        $footer = 'dimohon untuk tidak membalas pesan ini, ini adalah pesan otomatis untuk pengingat dokumen temuan yang harus ditindaklanjuti';

        $sendmail = Mail::to($emails)->send(new SendMail($title, $description, $content, $footer));

        if (empty($sendmail)) {
            return response()->json(['message' => 'Mail Sent Sucessfully'], 200);
        }else{
            return response()->json(['message' => 'Mail Sent fail'], 400);
        }
    }

    public function sendEmailTindakLanjut($email, $satker, $tglTerimaDokumen, $noLHA, $tglLHA, $eselon)
    {
        $title = 'Halo, Satker '.$satker.'.';
        $description = 'Sepertinya ada dokumen tindak lanjut yang belum kamu selesaikan.';
        $content = 'Dokumen Tindak lanjut yang kami '.$eselon.' serahkan pada tanggal <b>'.$tglTerimaDokumen.'</b> dengan Nomor <b>'.$noLHA.$tglLHA.'</b> dimohon untuk segera diselesaikan dengan menggunakan aplikasi MAD (Monitoring Approval Dokumen) ya, Tetap semangat <b>Terima kasih</b>';
        $footer = 'dimohon untuk tidak membalas pesan ini, ini adalah pesan otomatis untuk pengingat dokumen temuan yang harus ditindaklanjuti';

        $sendmail = Mail::to($email)->send(new SendMail($title, $description, $content, $footer));
        if (empty($sendmail)) {
            return response()->json(['message' => 'Mail Sent Sucessfully'], 200);
        }else{
            return response()->json(['message' => 'Mail Sent fail'], 400);
        }
    }

    public function sendEmailResponded($email, $satker, $tglTerimaDokumen, $noLHA, $tglLHA, $eselon)
    {
        $title = 'Halo, Satker '.$satker.'.';
        $description = 'Sepertinya ada dokumen tindak lanjut yang belum kamu selesaikan.';
        $content = 'Dokumen Tindak lanjut yang kami '.$eselon.' serahkan pada tanggal <b>'.$tglTerimaDokumen.'</b> dengan Nomor <b>'.$noLHA.$tglLHA.'</b> dimohon untuk segera diselesaikan dengan menggunakan aplikasi MAD (Monitoring Approval Dokumen) ya, Tetap semangat <b>Terima kasih</b>';
        $footer = 'dimohon untuk tidak membalas pesan ini, ini adalah pesan otomatis untuk pengingat dokumen temuan yang harus ditindaklanjuti';

        $sendmail = Mail::to($email)->send(new SendMail($title, $description, $content, $footer));
        if (empty($sendmail)) {
            return response()->json(['message' => 'Mail Sent Sucessfully'], 200);
        }else{
            return response()->json(['message' => 'Mail Sent fail'], 400);
        }
    }
}
