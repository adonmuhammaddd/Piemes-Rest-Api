<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewDokumenTemuanRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("
            CREATE VIEW view_dokumen_temuan_records 
            AS
            SELECT
                tbl_dokumen_temuan.*,
                tbl_jenis_dokumen_temuan.jenisDokumenTemuan,
                tbl_dokumen.tipeDokumenId,
                tbl_dokumen.noDokumenTemuan,
                tbl_dokumen.uraianTemuan,
                tbl_dokumen.kodeRekomendasi,
                tbl_dokumen.kodeRingkasanTindakLanjut,
                tbl_dokumen.ringkasanTindakLanjut,
                tbl_dokumen.statusTindakLanjut,
                tbl_dokumen.tindakLanjut,
                tbl_dokumen.subNomorRekomendasi,
                tbl_dokumen.nomorHeader,
                tbl_dokumen.titleHeader,
                tbl_dokumen.satkerId
            FROM
                tbl_dokumen_temuan
                JOIN tbl_jenis_dokumen_temuan ON tbl_dokumen_temuan.jenisDokumenTemuanId = tbl_jenis_dokumen_temuan.id
                JOIN tbl_dokumen ON tbl_dokumen_temuan.dokumenId = tbl_dokumen.id
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('view_dokumen_temuan_records');
    }
}
