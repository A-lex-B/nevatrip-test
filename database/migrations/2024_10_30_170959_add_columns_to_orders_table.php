<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('ticket_concessionary_price')->nullable()->after('ticket_kid_quantity');
            $table->integer('ticket_concessionary_quantity')->nullable()->after('ticket_concessionary_price');
            $table->integer('ticket_group_price')->nullable()->after('ticket_concessionary_quantity');
            $table->integer('ticket_group_quantity')->nullable()->after('ticket_group_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('ticket_group_quantity');
            $table->dropColumn('ticket_group_price');
            $table->dropColumn('ticket_concessionary_quantity');
            $table->dropColumn('ticket_concessionary_price');
        });
    }
};
