<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\whquestacg\Enemigo;

class ImportEnemies extends Command
{
    protected $signature = 'whq:import-enemies';
    protected $description = 'Import WHQ enemies from fixed CSV in public folder';

    public function handle()
    {
        $filename = 'whq_enemies.csv';
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

        // Validar columnas mínimas
        if (!in_array('Titulo', $header)) {
            $this->error("La columna 'Titulo' es obligatoria.");
            return Command::FAILURE;
        }

        $rows = file($path);
        $total = count($rows) - 1;

        $this->info("Iniciando importación de {$total} enemigos...");
        $this->output->progressStart($total);

        $insertados = 0;
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

                // Validar titulo obligatorio
                if (empty(trim($data['Titulo'] ?? ''))) {
                    $this->warn("Fila {$lineNumber}: Titulo vacío.");
                    $errores++;
                    continue;
                }

                // Validaciones numéricas según migración
                $copias = $this->validateUnsignedInt($data['Copias'] ?? 1, 0, 255, 'Copias', $lineNumber);
                $nivel = $this->validateUnsignedInt($data['Nivel'] ?? null, 0, 255, 'Nivel', $lineNumber);
                $resistencia = $this->validateUnsignedInt($data['Resistencia'] ?? null, 0, 255, 'Resistencia', $lineNumber);
                $vida = $this->validateUnsignedInt($data['Vida'] ?? null, 0, 255, 'Vida', $lineNumber);
                $ataque = $this->validateUnsignedInt($data['Ataque'] ?? null, 0, 255, 'Ataque', $lineNumber);

                if ($this->hasValidationError) {
                    $errores++;
                    $this->hasValidationError = false;
                    continue;
                }

                Enemigo::create([
                    'user_id'     => $userId,
                    'titulo'      => trim($data['Titulo']),
                    'copias'      => $copias ?? 1,
                    'familia'     => $data['Familia'] ?? null,
                    'tipo'        => $data['Tipo'] ?? null,
                    'nivel'       => $nivel,
                    'resistencia' => $resistencia,
                    'vida'        => $vida,
                    'ataque'      => $ataque,
                    'efecto1'     => $data['Efecto1'] ?? null,
                    'efecto2'     => $data['Efecto2'] ?? null,
                    'efecto3'     => $data['Efecto3'] ?? null,
                    'accion1'     => $data['Accion1'] ?? null,
                    'accion2'     => $data['Accion2'] ?? null,
                    'accion3'     => $data['Accion3'] ?? null,
                    'nemesis'     => $data['Nemesis'] ?? null,
                    'flavor'      => $data['Flavor'] ?? null,
                    'imagen'      => $data['Imagen'] ?? null,
                ]);

                $insertados++;
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
        $this->info("Insertados: {$insertados}");
        $this->info("Errores: {$errores}");

        return Command::SUCCESS;
    }

    private bool $hasValidationError = false;

    private function validateUnsignedInt($value, $min, $max, $field, $line)
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (!is_numeric($value) || $value < $min || $value > $max) {
            $this->warn("Fila {$line}: {$field} inválido ({$value}).");
            $this->hasValidationError = true;
            return null;
        }

        return (int) $value;
    }
}