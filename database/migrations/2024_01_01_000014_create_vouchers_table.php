<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();                   
            $table->enum('type', ['percent', 'nominal']);             
            $table->unsignedInteger('value');                         
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('quota');                        
            $table->unsignedInteger('used_count')->default(0);      
            $table->boolean('is_active')->default(true);               
            $table->timestamps();

            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
