<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class InterfaceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bindRepositories();
        $this->bindInterfaces();
    }

    private function bindRepositories()
    {
        $arquivosRepository = Storage::disk('repositories')->allFiles();

        $arquivosRepository = collect($arquivosRepository)->chunk(2);

        foreach ($arquivosRepository as $duplaArquivos) {
            $interface = $this->nomeArquivoParaClasse($duplaArquivos->last());
            $concrete = $this->nomeArquivoParaClasse($duplaArquivos->first());
            
            $this->app->bind($interface, $concrete);
        }
    }

    private function nomeArquivoParaClasse(string $nomeArquivo)
    {
        $nomeArquivo = str_replace(['/', '.php'], ['\\', ''], $nomeArquivo);
        return "Repositories\\$nomeArquivo";
    }

    private function bindInterfaces()
    {
        foreach (config('interface') as $interface => $concrete) {
            $this->app->bind($interface, $concrete);
        }
    }
}
