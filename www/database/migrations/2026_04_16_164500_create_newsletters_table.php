<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletters', function (Blueprint $table) {
            $table->id();
            $table->string('subject'); // Assunto do E-mail
            $table->string('title')->nullable(); // Titulo interno
            $table->text('body')->nullable(); // Saudação
            $table->enum('status', ['draft', 'sending', 'completed'])->default('draft');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        // Tabela Pivô (As 5 melhores matérias do mês)
        Schema::create('newsletter_news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('newsletter_id')->constrained()->cascadeOnDelete();
            $table->foreignId('news_id')->constrained()->cascadeOnDelete();
            $table->integer('order_index')->default(0); // Ordenação física na revista digital
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_news');
        Schema::dropIfExists('newsletters');
    }
};
