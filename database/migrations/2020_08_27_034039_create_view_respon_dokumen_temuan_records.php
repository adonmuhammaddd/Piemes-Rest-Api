<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewResponDokumenTemuanRecords extends Migration
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
                tbl_respon_dokumen_temuan.*,
                tbl_tindak_lanjut.tglTindakLanjut
            FROM
                tbl_respon_dokumen_temuan
                JOIN tbl_tindak_lanjut ON tbl_respon_dokumen_temuan.tindakLanjutId = tbl_tindak_lanjut.id
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('view_respon_dokumen_temuan_records');
    }
}
