<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    BancoController,
    EntidadController,
    LoteController,
    RemateController,
    FacturaController,
    ConcafactvenController,
    ReporteController,
    DatosController,
    UserController,
    AuthController,
    RoleController,
    PermissionController,
    CobranzaIntegradaController
};


Route::prefix('cobranza')->group(function () {
    Route::get('/token', [CobranzaIntegradaController::class, 'obtenerToken']);
    Route::get('/status', [CobranzaIntegradaController::class, 'verificarConexion']);
    Route::post('/publicar-deuda', [CobranzaIntegradaController::class, 'publicarDeuda']);
    Route::get('/estado-deuda/{nroSeguimiento}/{nroConvenio}', [CobranzaIntegradaController::class, 'consultarEstadoDeuda']);
    Route::get('/listar-publicaciones/{nroSeguimiento}/{nroConvenio}', [CobranzaIntegradaController::class, 'listarPublicaciones']);
    Route::get('/pagos/{nroConvenio}/{codMoneda}/{fecha}/{nroSeguimiento}', [CobranzaIntegradaController::class, 'consultarPagos']);
    Route::get('/deuda/qr/{nroSeguimiento}/{nroConvenio}', [CobranzaIntegradaController::class, 'consultarDeudaQR']);

});
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('api.auth.login');
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/password/reset', [UserController::class, 'sendPasswordResetLink']);
    Route::post('/password/reset/{token}', [UserController::class, 'resetPassword']);

});

Route::prefix('roles')->group(function () {
    Route::get('/', [RoleController::class, 'index']);
    Route::post('/', [RoleController::class, 'store']);
    Route::get('/{id}', [RoleController::class, 'show']);
    Route::put('/{id}', [RoleController::class, 'update']);
    Route::delete('/{id}', [RoleController::class, 'destroy']);
    Route::get('/{roleName}/permissions', [RoleController::class, 'getPermissionsByRole']);
    Route::post('/{roleName}/permissions', [RoleController::class, 'assignPermissions']);
});
Route::get('/role', [RoleController::class, 'getRoles']);

Route::prefix('permisos')->group(function () {
    Route::get('/', [PermissionController::class, 'index']);
    Route::post('/', [PermissionController::class, 'store']);
});

Route::middleware(['role_or_permission:admin|ver,custom_guard'])->get('/middleware-test', function () {
    return response()->json(['message' => 'Middleware funcionando correctamente']);
});

Route::get('/connected-users', [AuthController::class, 'getConnectedUsers']);

Route::middleware('web')->get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
Route::middleware(['role_or_permission:admin|ver'])->get('/test', function () {
    return response()->json(['message' => 'Middleware funcionando']);
});

Route::middleware(['web'])->group(function () {
    Route::get('/auth/google', [UserController::class, 'redirectToGoogle']);
    Route::get('/auth/google/callback', [UserController::class, 'handleGoogleCallback']);
});

Route::middleware('auth:sanctum')->group(function () {

});

Route::get('/test-factura', function () {
    return view('factura');
});

Route::resource('users', UserController::class)->except(['create', 'edit']);
Route::get('/userlevels', [UserController::class, 'getUserLevels']);

Route::controller(BancoController::class)->prefix('bancos')->group(function () {
    Route::get('/', 'index');
    Route::get('/{codnum}', 'show');
    Route::post('/', 'store');
    Route::put('/{codnum}', 'update');
    Route::delete('/{codnum}', 'destroy');
});

Route::controller(EntidadController::class)->prefix('entidades')->group(function () {
    Route::get('/', 'index');
    Route::post('/create', 'store');
    Route::put('/{codnum}', 'update');
    Route::delete('/{codnum}', 'destroy');
    Route::get('/{codnum}', 'showByCodnum');
    Route::post('/searchConstancia', 'searchConstancia');
    Route::put('/{codnum}/estado', 'updateEstado');
});

Route::controller(LoteController::class)->prefix('lotes')->group(function () {
    Route::get('/', 'index');
    Route::get('/{codnum}', 'show');
    Route::post('/', 'store');
    Route::put('/{codnum}/estado', 'updateEstado');
    Route::put('/{codnum}', 'update');
    Route::delete('/{codnum}', 'destroy');
});

Route::controller(RemateController::class)->prefix('remates')->group(function () {
    Route::get('/', 'index');
    Route::get('/{ncomp}', 'show');
    Route::get('/{codnum}/lotes', 'obtenerLotes');
    Route::post('/', 'store');
    Route::put('/{codnum}', 'update');
    Route::delete('/{codnum}', 'destroy');
});

Route::controller(ConcafactvenController::class)->prefix('conceptos')->group(function () {
    Route::get('/', 'index');
    Route::get('/impuesto/1', 'conceptosFc');
    Route::post('/', 'store');
    Route::put('/{codnum}', 'update');
    Route::delete('/{codnum}', 'destroy');
});

Route::controller(FacturaController::class)->prefix('factura')->group(function () {
    Route::post('/lotes', 'facturarLotes');
    Route::post('/conceptos', 'facturarConceptos');
});

Route::controller(DatosController::class)->prefix('datos')->group(function () {
    Route::get('/paises', 'getPaises');
    Route::get('/provincias', 'getProvincias');
    Route::get('/localidades', 'getLocalidades');
    Route::get('/tipos-entidad', 'getTiposEntidad');
    Route::get('/tipos-iva', 'getTiposIVA');
    Route::get('/tipos-industria', 'getTiposIndustria');
});

Route::prefix('reporte')->group(function () {
    Route::post('/generar-factura', [ReporteController::class, 'index']);
    Route::post('/generateFactura', [ReporteController::class, 'generateFactura']);
});