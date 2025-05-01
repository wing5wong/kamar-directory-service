<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            for ($i = 1; $i <= 10; $i++) {
                $table->char("slot_$i", 1)
                    ->virtualAs("SUBSTRING(codes, $i, 1)")
                    ->nullable();
            }
        });
    }
};
