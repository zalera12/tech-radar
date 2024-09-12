<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\DashboardController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/index', [DashboardController::class, 'index']);
    Route::get('/auth-profile', [DashboardController::class, 'profile']);
    Route::get('/auth-profile-settings', [DashboardController::class, 'profileSettings']);
    Route::post('/auth-profile/edit/{user}', [DashboardController::class, 'profileEdit']);
    Route::post('/companies/add', [DashboardController::class, 'addCompanies']);
       Route::post('/companies/users/join', [DashboardController::class, 'joinCompanyUser'])
            ->name('company-users.join');
    
    // // Rute yang memerlukan izin spesifik
    Route::middleware(['check.permission'])->group(function () {
        // Permission: Edit Company
        Route::post('/companies/edit/{company}', [DashboardController::class, 'editCompanies'])
            ->middleware('check.permission:Edit Company')->name('companies.edit');
        // Permission: Delete Company
        Route::post('/companies/delete/{company}', [DashboardController::class, 'deleteCompanies'])
            ->middleware('check.permission:Delete Company')->name('companies.delete');
        // Permission: Read Company
        Route::get('/companies/main/{company}', [CompaniesController::class, 'mainCompanies'])
            ->middleware('check.permission:Read Company Profile')->name('companies.main');

        // Permission: Manage Company Users
        Route::get('/companies/users/{company}', [CompaniesController::class, 'usersCompanies'])
            ->middleware('check.permission:Read Company User')->name('companies.users');
        Route::post('/companies/user/add', [CompaniesController::class, 'addUser'])
            ->middleware('check.permission:Add Company User')->name('company.addUser');
        Route::post('/companies/users/edit', [CompaniesController::class, 'updateRoleUser'])
            ->middleware('check.permission:Edit Company User')->name('company-users.update');
     
        Route::post('/companies/roles/delete', [CompaniesController::class, 'destroyUserCompanies'])
            ->middleware('check.permission:Delete Company User')->name('companies.roles.delete');

        // Permission: Manage Company Roles
        Route::get('/companies/roles/{company}', [CompaniesController::class, 'rolesCompanies'])
            ->middleware('check.permission:Read Company Role')->name('companies.roles');
        Route::post('/companies/roles/add', [CompaniesController::class, 'addRolesCompanies'])
            ->middleware('check.permission:Add Company Role')->name('companies.roles.add');
        Route::post('/companies/roles/edit', [CompaniesController::class, 'editRolesCompanies'])
            ->middleware('check.permission:Edit Company Role')->name('companies.roles.edit');
        Route::delete('/companies/roles/delete', [CompaniesController::class, 'deleteRolesCompanies'])
            ->middleware('check.permission:Delete Company Role')->name('roles.delete');

        // Permission: Manage Company Categories
        Route::get('/companies/categories/{company}', [CompaniesController::class, 'categoriesCompanies'])
            ->middleware('check.permission:Read Company Category')->name('categories.index');
        Route::post('/companies/categories/{company}/add', [CompaniesController::class, 'addCategoryCompanies'])
            ->middleware('check.permission:Add Company Category')->name('categories.add');
        Route::post('/companies/categories/edit', [CompaniesController::class, 'editCategoryCompanies'])
            ->middleware('check.permission:Edit Company Category')->name('categories.edit');
        Route::post('/companies/categories/delete', [CompaniesController::class, 'deleteCategoryCompanies'])
            ->middleware('check.permission:Delete Company Category')->name('categories.delete');

        // Permission: Manage Company Technologies
        Route::get('/companies/technologies/{company}', [CompaniesController::class, 'technologiesCompanies'])
            ->middleware('check.permission:Read Company Technology')->name('technologies.companies');
        Route::post('/companies/technologies/add', [CompaniesController::class, 'addTechnologiesCompanies'])
            ->middleware('check.permission:Add Company Technology')->name('technologies.add');
        Route::get('/technologies/{id}', [CompaniesController::class, 'showTechnologiesCompanies'])
            ->middleware('check.permission:Read Company Technology')->name('technologies.show');
        Route::post('/companies/technologies/edit', [CompaniesController::class, 'updateTechnologiesCompanies'])
            ->middleware('check.permission:Edit Company Technology')->name('technologies.update');
        Route::post('/companies/technologies/delete', [CompaniesController::class, 'deleteTechnologiesCompanies'])
            ->middleware('check.permission:Delete Company Technology')->name('technologies.delete');

        // Permission: Manage Permissions
        Route::get('/companies/permissions/{company}', [CompaniesController::class, 'permissionsCompanies'])
            ->middleware('check.permission:Manage User Permission')->name('companies.permissions');
        Route::post('/toggle-role-permission', [CompaniesController::class, 'toggleRolePermission'])
            ->middleware('check.permission:Manage User Permission')->name('roles.togglePermission');

        // Permission: Manage Pending Members
        Route::get('/companies/pendingMember/{company}', [CompaniesController::class, 'pendingMemberCompanies'])
            ->middleware('check.permission:Read Pending Company User')->name('companies.pendingMembers');
        Route::put('/companies/pendingMember/update/{member}', [CompaniesController::class, 'updatePendingMember'])
            ->middleware('check.permission:Update Company User')->name('companies.pendingMember.update');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Route di luar grup middleware
Route::get('/testApi', function () {
    $categories = Category::where('company_id', '01J736609SA0X6W6EHSQZXWMV9')->get();
    return view('apiTest', ['categories' => $categories]);
})->name('apiTest');

Route::get('/testHome', function () {
    return view('testHome');
})->name('testHome');

Route::get('/loginAccount', function () {
    return Socialite::driver('google')->redirect();
})->name('loginAccount');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
