<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. php artisan migrate:refresh --path=/database/migrations/2023_02_05_042750_create_accounts_table.php
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('user_id')->default('');
            $table->string('merchant_account_id')->default('');
            $table->string('account_number');
            $table->string('account_name')->default('');
            $table->string('bank_code')->default('');
            $table->string('currency');
            $table->string('type');
            $table->string('bank');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
};
