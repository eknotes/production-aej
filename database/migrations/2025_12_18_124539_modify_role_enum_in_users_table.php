<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Menambahkan 'super_admin' ke dalam enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'leader', 'manager') NOT NULL DEFAULT 'leader'");
    }

    public function down()
    {
        // Rollback (Hati-hati jika ada data super_admin)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'leader', 'manager') NOT NULL DEFAULT 'leader'");
    }
};