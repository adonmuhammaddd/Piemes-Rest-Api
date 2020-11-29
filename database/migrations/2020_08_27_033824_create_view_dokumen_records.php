<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewDokumenRecords extends Migration
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
                tbl_dokumen.*,
                tbl_tipe_dokumen.tipeDokumen
            FROM
            tbl_dokumen
                JOIN tbl_tipe_dokumen ON tbl_dokumen.tipeDokumenId = tbl_tipe_dokumen.id
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('view_dokumen_records');
    }
}
