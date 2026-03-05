<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\whquestacg\AccionEnemigo;

class ImportEnemyActions extends Command
{
    protected $signature = 'whq:import-enemy-actions';
    protected $description = 'Import WHQ enemy actions from fixed CSV in public folder';

    public function handle()
    {
        $filename = 'whq_enemyactions.csv';
        $path = public_path($filename);
        $userId = 1;

        if (!file_exists($path)) {
            $this->error("Archivo no encontrado en public/: {$filename}");
            return Command::FAILURE;
        }

        if (($handle = fopen($path, 'r')) === false) {
            $this->error("No se pudo abrir el archivo.");
            return Command::FAILURE;
        }

        $header = array_map('trim', fgetcsv($handle));

        if (!in_array('Nombre', $header)) {
            $this->error("La columna 'Nombre' es obligatoria.");
            return Command::FAILURE;
        }

        $rows = file($path);
        $total = count($rows) - 1;

        $this->info("Iniciando importación de {$total} acciones...");
        $this->output->progressStart($total);

        $insertadas = 0;
        $errores = 0;
        $lineNumber = 1;

        DB::beginTransaction();

        try {

            while (($row = fgetcsv($handle)) !== false) {

                $lineNumber++;
                $this->output->progressAdvance();

                if (empty(array_filter($row))) {
                    continue;
                }

                $data = array_combine($header, $row);

                if ($data === false) {
                    $this->warn("Fila {$lineNumber}: columnas inválidas.");
                    $errores++;
                    continue;
                }

                if (empty(trim($data['Nombre'] ?? ''))) {
                    $this->warn("Fila {$lineNumber}: Nombre vacío.");
                    $errores++;
                    continue;
                }

                AccionEnemigo::create([
                    'user_id'     => $userId,
                    'nombre'      => trim($data['Nombre']),
                    'descripcion' => $data['Texto'] ?? null,
                ]);

                $insertadas++;
            }

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();
            $this->error("\nError crítico: " . $e->getMessage());
            return Command::FAILURE;
        }

        fclose($handle);
        $this->output->progressFinish();

        $this->info("\nImportación finalizada.");
        $this->info("Insertadas: {$insertadas}");
        $this->info("Errores: {$errores}");

        return Command::SUCCESS;
    }
}