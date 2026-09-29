<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('unit_price', 12, 2)->default(0)->after('quantity');
        });

        foreach (\DB::table('order_items')->get() as $item) {
            $price = \DB::table('books')->where('id', $item->book_id)->value('price') ?? 0;
            \DB::table('order_items')->where('id', $item->id)->update(['unit_price' => $price]);
        }
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('unit_price');
        });
    }
};
