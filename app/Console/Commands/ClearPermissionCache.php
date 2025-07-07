<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearPermissionCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpa o cache de permissões do Spatie Laravel Permission';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Limpando cache de permissões...');

        try {
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            $this->info('Cache de permissões limpo com sucesso!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Erro ao limpar cache de permissões: ' . $e->getMessage());
            return 1;
        }
    }
}
