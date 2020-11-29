<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewTindakLanjutRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("
            CREATE VIEW view_dokumen_records 
            AS
            SELECT
                tbl_tindak_lanjut.*,
                tbl_satker.namaSatker,
                tbl_dokumen_temuan.deadlineDokumenTemuan,
                tbl_dokumen_temuan.tglTerimaDokumenTemuan,
                tbl_kpa.namaKpa,
                tbl_ppk.namaPpk,
                tbl_user.nama,
                tbl_dokumen.statusTindakLanjut
            FROM
                tbl_tindak_lanjut
                JOIN tbl_satker ON tbl_tindak_lanjut.satkerId = tbl_satker.id
                JOIN tbl_dokumen_temuan ON tbl_tindak_lanjut.dokumenTemuanId = tbl_dokumen_temuan.id
                JOIN tbl_kpa ON tbl_tindak_lanjut.kpaId = tbl_kpa.id
                JOIN tbl_ppk ON tbl_tindak_lanjut.ppkId = tbl_ppk.id
                JOIN tbl_user ON tbl_tindak_lanjut.userId = tbl_user.id
                JOIN tbl_dokumen ON tbl_tindak_lanjut.dokumenId = tbl_dokumen.id 
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('view_tindak_lanjut_records');
    }
}