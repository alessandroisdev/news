<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            
            // Relacionamentos Fortes
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('news_id')->constrained()->cascadeOnDelete();
            
            // Hierarquia: Threading no padrão Facebook (Resposta de Resposta não precisa, mas Resposta direta precisa)
            $table->foreignId('parent_id')->nullable()->constrained('comments')->cascadeOnDelete();
            
            // O comentário em si
            $table->text('content');
            
            // Shadowban Defenses
            $table->boolean('is_shadowbanned')->default(false);
            $table->string('shadowban_reason')->nullable();
            
            // Admins podem banir um comentário mesmo q o robô não tenha pego
            $table->boolean('is_hidden')->default(false);

            $table->timestamps();
            $table->softDeletes();
            
            // Índices de otimização
            $table->index(['news_id', 'parent_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
