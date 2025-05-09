<?php
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackOffice\UserController;

use App\Http\Controllers\BackOffice\AdminRoleController;
use App\Http\Controllers\BackOffice\CandidateController;
use App\Http\Controllers\Api\EmployerController;
use App\Http\Controllers\Api\ModuleController;




Route::group([
    'prefix'     => 'backoffice',
    'middleware' => ['auth:api', 'role:admin']
], function () {

       // Assign a single role
    Route::post('/users/{id}/assign-role', [AdminRoleController::class, 'assignRole']);
       // Remove a single role
    Route::post('/users/{id}/remove-role', [AdminRoleController::class, 'removeRole']);
       // Sync multiple roles at once
    Route::post('/users/{id}/sync-roles', [AdminRoleController::class, 'syncRoles']);

    // Users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // Offers
   

    Route::middleware('auth:api')->group(function () {
      Route::post('module',                    [ModuleController::class, 'index']);
      Route::post('module/save',               [ModuleController::class, 'store']);
      Route::get('module/{id}',                [ModuleController::class, 'show']);
      Route::post('module/{id}/update',        [ModuleController::class, 'update']);
      Route::delete('module/{id}',             [ModuleController::class, 'destroy']);
      Route::post('module/change_status/{id}', [ModuleController::class, 'changeStatus']);
  });
  

    // ... other back-office routes


    Route::middleware('auth:api')->apiResource('employers', EmployerController::class);




    

    Route::get('candidates', [CandidateController::class,'index']);

    Route::middleware(['auth:api','role:admin'])
     ->get('backoffice/candidates', [CandidateController::class, 'index']);



   

});
 
Route::post('/register', [AuthController::class, 'register']);




Route::post('/login', [AuthController::class, 'login'])
     ->name('login');





Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);


Route::middleware(['auth:api', 'role:admin'])->get('/test-admin', function () {
    return response()->json(['message' => 'Accès admin autorisé']);
});



// Example of a protected route that requires authentication (token must be passed in the Authorization header)
Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);

