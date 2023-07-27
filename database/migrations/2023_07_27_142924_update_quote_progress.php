<?php

use Doctrine\DBAL\Types\Type;
use Illuminate\Database\DBAL\TimestampType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function __construct()
    {
        // ! This is to allow changing timestamps without forcing require dbal on non dev composer.
        Type::addType(
            'timestamp',
            TimestampType::class
        );
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quote_progresses', function (Blueprint $table) {
            $table->timestamp('expire_at')->useCurrent()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quote_progresses', function (Blueprint $table) {
            $table->timestamp('expire_at')->change();
        });
    }
};
