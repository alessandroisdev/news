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
        Schema::create('audience_metrics', function (Blueprint $table) {
            $table->id();
            
            // Relatório de IP/Dispositivo/Sessão. Se deletado, manter null para dados históricos abstratos
            $table->foreignId('visitor_profile_id')->nullable()->constrained('visitor_profiles')->nullOnDelete();
            
            // Polimórfico para News, Categoria, Colunista. Ex: trackable_id=5, trackable_type='App\Models\News'
            $table->morphs('trackable');
            
            // 'view' = Exibição de Página / 'click' = Botão clicado interno
            $table->string('type')->default('view'); 

            $table->timestamps();

            // Desempenho alto nas queries
            $table->index(['trackable_type', 'trackable_id', 'type']);
            $table->index('visitor_profile_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audience_metrics');
    }
};
