<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;

class SystemUpdateController extends Controller
{
    protected $targetBranch;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Obtiene la rama objetivo de actualización. Si no está forzada en .env (APP_UPDATE_BRANCH),
     * detecta automáticamente la rama activa actual (ej: develop o main).
     */
    protected function getTargetBranch()
    {
        $configured = env('APP_UPDATE_BRANCH');
        if (!empty($configured)) {
            return $configured;
        }

        $branchRes = $this->runProcess(['git', 'rev-parse', '--abbrev-ref', 'HEAD']);
        if ($branchRes['exitCode'] === 0 && !empty($branchRes['output'])) {
            return $branchRes['output'];
        }

        return 'main';
    }

    /**
     * Valida permisos administrativos para acceder al actualizador
     */
    protected function authorizeAdmin()
    {
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

        if (Gate::has('haveaccess')) {
            if (!Gate::allows('haveaccess', 'boton.sistema')) {
                abort(403, 'No tiene permisos para gestionar actualizaciones.');
            }
        }
    }

    /**
     * Ejecuta un comando en consola mediante Symfony Process
     */
    protected function runProcess(array $cmd, $timeout = 60)
    {
        $process = new Process($cmd, base_path());
        $process->setTimeout($timeout);
        $process->run();

        return [
            'exitCode' => $process->getExitCode(),
            'output' => trim($process->getOutput()),
            'error' => trim($process->getErrorOutput()),
        ];
    }

    /**
     * Panel principal de actualizaciones
     */
    public function index()
    {
        $this->authorizeAdmin();

        $branchRes = $this->runProcess(['git', 'rev-parse', '--abbrev-ref', 'HEAD']);
        $currentBranch = $branchRes['exitCode'] === 0 ? $branchRes['output'] : 'desconocida';

        $commitRes = $this->runProcess(['git', 'rev-parse', '--short', 'HEAD']);
        $currentCommit = $commitRes['exitCode'] === 0 ? $commitRes['output'] : 'N/A';

        $dateRes = $this->runProcess(['git', 'log', '-1', '--pretty=format:%cd', '--date=relative']);
        $commitDate = $dateRes['exitCode'] === 0 ? $dateRes['output'] : 'N/A';

        $msgRes = $this->runProcess(['git', 'log', '-1', '--pretty=format:%s']);
        $commitMessage = $msgRes['exitCode'] === 0 ? $msgRes['output'] : 'N/A';

        // Historial de actualizaciones registradas
        $history = [];
        $logPath = storage_path('logs/updates.log');
        if (file_exists($logPath)) {
            $lines = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $history = array_reverse(array_slice($lines, -15));
        }

        $targetBranch = $this->getTargetBranch();
        $title = 'Actualizaciones del Sistema';

        return view('sistema.update.index', compact(
            'title',
            'currentBranch',
            'currentCommit',
            'commitDate',
            'commitMessage',
            'targetBranch',
            'history'
        ));
    }

    /**
     * Endpoint AJAX para consultar si hay actualizaciones disponibles en GitHub
     */
    public function check(Request $request)
    {
        $this->authorizeAdmin();

        $targetBranch = $this->getTargetBranch();

        // 1. Fetch de GitHub
        $fetch = $this->runProcess(['git', 'fetch', 'origin']);
        if ($fetch['exitCode'] !== 0) {
            return response()->json([
                'success' => false,
                'has_updates' => false,
                'commits_behind' => 0,
                'current_commit' => 'N/A',
                'branch' => $targetBranch,
                'pending_commits' => [],
                'message' => 'Error al conectar con GitHub: ' . ($fetch['error'] ?: $fetch['output']),
            ], 200);
        }

        $localCommit = $this->runProcess(['git', 'rev-parse', '--short', 'HEAD'])['output'] ?? '';
        $remoteCommit = $this->runProcess(['git', 'rev-parse', '--short', "origin/{$targetBranch}"])['output'] ?? '';

        // Cantidad de commits pendientes
        $countRes = $this->runProcess(['git', 'rev-list', '--count', "HEAD..origin/{$targetBranch}"]);
        $count = $countRes['exitCode'] === 0 ? (int)$countRes['output'] : 0;

        $pendingCommits = [];
        if ($count > 0) {
            $logRes = $this->runProcess([
                'git', 'log', "HEAD..origin/{$targetBranch}",
                '--pretty=format:%h|%s|%cr|%an',
                '-n', '15'
            ]);

            if (!empty($logRes['output'])) {
                $lines = explode("\n", $logRes['output']);
                foreach ($lines as $line) {
                    $parts = explode('|', $line, 4);
                    if (count($parts) === 4) {
                        $pendingCommits[] = [
                            'hash' => $parts[0],
                            'subject' => $parts[1],
                            'date' => $parts[2],
                            'author' => $parts[3],
                        ];
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'has_updates' => $count > 0,
            'commits_behind' => $count,
            'current_commit' => $localCommit,
            'remote_commit' => $remoteCommit,
            'branch' => $targetBranch,
            'pending_commits' => $pendingCommits,
            'message' => $count > 0
                ? "Se encontraron {$count} actualización(es) pendiente(s)."
                : "El sistema ya se encuentra en la versión más reciente.",
        ]);
    }

    /**
     * Endpoint AJAX para aplicar la actualización en 5 pasos seguros
     */
    public function apply(Request $request)
    {
        $this->authorizeAdmin();

        $lockKey = 'system_update_in_progress';
        $lock = Cache::lock($lockKey, 600);

        if (!$lock->get()) {
            return response()->json([
                'success' => false,
                'message' => 'Ya hay una actualización en progreso. Por favor espere.',
            ], 423);
        }

        $steps = [];
        $targetBranch = $this->getTargetBranch();
        $operator = Auth::user()->name ?? 'Administrador';

        try {
            // Paso 1: Respaldo preventivo de base de datos
            $backupDir = storage_path('app/backups');
            if (!is_dir($backupDir)) {
                @mkdir($backupDir, 0755, true);
            }
            $backupFile = $backupDir . '/backup_pre_update_' . date('Ymd_His') . '.sql';

            $dbHost = env('DB_HOST', '127.0.0.1');
            $dbName = env('DB_DATABASE', 'hosteria-villasol');
            $dbUser = env('DB_USERNAME', 'root');
            $dbPass = env('DB_PASSWORD', '');

            $dumpCmd = ['mysqldump', "-h{$dbHost}", "-u{$dbUser}"];
            if (!empty($dbPass)) {
                $dumpCmd[] = "-p{$dbPass}";
            }
            $dumpCmd[] = $dbName;

            $dumpProc = new Process($dumpCmd);
            $dumpProc->setTimeout(120);
            $dumpProc->run();

            if ($dumpProc->getExitCode() === 0 && !empty($dumpProc->getOutput())) {
                file_put_contents($backupFile, $dumpProc->getOutput());
                $steps[] = ['step' => 'Respaldo de Base de Datos', 'status' => 'OK', 'detail' => basename($backupFile)];
            } else {
                // Alternativa: Si mysqldump no está en PATH, ejecutar backup de spatie si está configurado
                try {
                    Artisan::call('backup:run', ['--only-db' => true]);
                    $steps[] = ['step' => 'Respaldo de Base de Datos', 'status' => 'OK', 'detail' => 'Laravel Backup ejecutado'];
                } catch (\Throwable $e) {
                    $steps[] = ['step' => 'Respaldo de Base de Datos', 'status' => 'WARNING', 'detail' => 'Continuando sin respaldo: ' . $e->getMessage()];
                }
            }

            // Paso 2: Guardar cambios locales temporales (git stash)
            $stash = $this->runProcess(['git', 'stash', 'push', '-u', '-m', 'Auto-stash pre update ' . date('Y-m-d H:i:s')]);
            $steps[] = ['step' => 'Resguardo de Cambios Locales (Git Stash)', 'status' => 'OK', 'detail' => $stash['output'] ?: 'Árbol limpio'];

            // Paso 3: Descarga de código (git fetch y pull / reset seguro)
            $this->runProcess(['git', 'fetch', 'origin', $targetBranch], 120);
            $pull = $this->runProcess(['git', 'pull', 'origin', $targetBranch], 120);
            if ($pull['exitCode'] !== 0) {
                // Si git pull tiene algún tropiezo con archivos locales, forzar sincronización limpia
                $reset = $this->runProcess(['git', 'reset', '--hard', "origin/{$targetBranch}"], 60);
                if ($reset['exitCode'] !== 0) {
                    throw new \Exception('Fallo al ejecutar git pull: ' . ($pull['error'] ?: $pull['output']));
                }
                $pull['output'] = 'Sincronizado limpiamente con origin/' . $targetBranch;
            }
            $steps[] = ['step' => "Descarga de Código (Git origin/{$targetBranch})", 'status' => 'OK', 'detail' => $pull['output']];

            // Paso 4: Migraciones de base de datos
            Artisan::call('migrate', ['--force' => true]);
            $migrateOutput = trim(Artisan::output());
            $steps[] = ['step' => 'Migraciones de Base de Datos', 'status' => 'OK', 'detail' => $migrateOutput ?: 'Sin migraciones pendientes'];

            // Paso 5: Limpieza integral de caché
            Artisan::call('optimize:clear');
            $steps[] = ['step' => 'Limpieza de Caché de Laravel', 'status' => 'OK', 'detail' => 'Vistas, rutas y config renovadas'];

            // Registro histórico
            $newCommit = $this->runProcess(['git', 'rev-parse', '--short', 'HEAD'])['output'] ?? '';
            $logEntry = sprintf(
                "[%s] Actualizado a commit %s por %s (Rama: %s)\n",
                date('Y-m-d H:i:s'),
                $newCommit,
                $operator,
                $targetBranch
            );
            @file_put_contents(storage_path('logs/updates.log'), $logEntry, FILE_APPEND);

            return response()->json([
                'success' => true,
                'new_commit' => $newCommit,
                'steps' => $steps,
                'message' => '¡Sistema actualizado con éxito a la versión ' . $newCommit . '!',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'steps' => $steps,
                'message' => 'Error durante la actualización: ' . $e->getMessage(),
            ], 500);
        } finally {
            $lock->release();
        }
    }
}
