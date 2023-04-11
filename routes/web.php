<?php

/////////////// INDICADORES MECATRONICA UTVT 2023. ///////////////

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreasController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CuentasController;
use App\Http\Controllers\EncryptController;
use App\Http\Controllers\TiposActividadesController;
use App\Http\Controllers\Sistema\Panel\PanelController;
use App\Http\Controllers\Sistema\TipoAreas\TipoAreasController;
use App\Http\Controllers\Graficas\GraficasPorTipoAreaController;
use App\Http\Controllers\Students\StudentsController;
use App\Http\Controllers\Asesorias\AsesoriasController;
use App\Http\Controllers\Tutorias\TutoriasController;
use App\Http\Controllers\ValoracionAE\ValoracionAEController;
use App\Http\Controllers\TrayectoriaC\TrayectoriaCController;

//////////// RUTAS PARA LA PARTE INICIAL DEL SISTEMA. ///////////////

// Código para borrar el caché del sistema. 
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return 'Application cache cleared';
});

// Ejecución de los recordatorios.
Route::get('/run-schedules', function() {
    $exitCode = Artisan::call('schedule:work');
    return 'Application run schedules, recordatorios:send.';
});

 // Eliminación del caché en las rutas.
 Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return 'Routes cache cleared';
});

// Eliminación del caché en la configuración.
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return 'Config cache cleared';
}); 

// Eliminación del caché en la vista.
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return 'View cache cleared';
});
Route::get('/storage', function() {
    $exitCode = Artisan::call('storage:link');
    return 'Storage created';
});
 

//////////////////// RUTAS DEL PANEL #1 /////////////////////////

Route::redirect('/', 'panel');
Auth::routes();
Route::middleware(['auth'])->group(function () {
    Route::get('/panel', [PanelController::class,'panel']);

//////////////////////////////////////////////  U S U A R I O S  ///////////////////////////////////////////////////////////////
   
    Route::resource('users', UsersController::class, ['names' => 'users']);
    
    Route::get('students/activar/{id}', [StudentsController::class,'activar'])->name('students.activar');
    Route::get('students/desactivar/{id}', [StudentsController::class,'desactivar'])->name('students.desactivar');
    Route::post('students/by_group', [StudentsController::class,'by_group'])->name('students.by_group');
    Route::resource('students', StudentsController::class, ['names' => 'students']);
    Route::post('asesorias/update_tipo', [AsesoriasController::class,'update_tipo'])->name('asesorias.update_tipo');
    Route::post('asesorias/update_obs', [AsesoriasController::class,'update_obs'])->name('asesorias.update_obs');
    Route::post('asesorias/update_evidencia', [AsesoriasController::class,'update_evidencia'])->name('asesorias.update_evidencia');
    Route::resource('asesorias', AsesoriasController::class, ['names' => 'asesorias']);
    Route::resource('trayectoriac', TrayectoriaCController::class, ['names' => 'trayectoriac']);
    Route::get('tutorias/delete/{id}', [TutoriasController::class,'delete'])->name('tutorias.delete');
    Route::resource('tutorias', TutoriasController::class, ['names' => 'tutorias']);
    Route::get('valoracion_ae/delete/{id}', [ValoracionAEController::class,'delete'])->name('valoracion_ae.delete');
    Route::resource('valoracion_ae', ValoracionAEController::class, ['names' => 'valoracion_ae']);
    // Ruta para editar el perfil.
    Route::get('editar-perfil', [CuentasController::class, 'editar_perfil'])->name('editar-perfil');
    Route::post('editar-perfil', [CuentasController::class, 'editar_perfil_post'])->name('editar-perfil.post');

    // Áreas.
    Route::resource('areas', AreasController::class, ['names' => 'areas']);

    // Ruta para el Administrador: Areas y Usuarios.
    Route::resource('admin/areas', AreasController::class, ['names' => 'areas']);
    Route::resource('admin/users', UsersController::class, ['names' => 'users']);

    // Ruta - Index.
    Route::get('hello', [EncryptController::class,'index']);

    //////////////////////////// RUTAS - GRÁFICAS POR ÁREA. /////////////////////////

    // Ruta - Estadistica inicial de gráficas por Área.
    Route::get('/dashboard/{user}',[GraficasPorTipoAreaController::class,'dashboard']);
    Route::post('/dashboard/{user}',[GraficasPorTipoAreaController::class,'getEstadisticasDeActividades']);

});


Route::get('php', function (){
    phpinfo();
});

Route::get('/inactivo' , function(){
    return view('auth.login_message');
})->name('inactivo');
