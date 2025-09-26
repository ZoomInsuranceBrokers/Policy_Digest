<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'admin'])->group(function () {
    // Admin Claims Section
    Route::get('/admin/claims', [\App\Http\Controllers\Admin\ClaimsController::class, 'index'])->name('admin.claims');
    Route::get('/admin/claims/company/{company}', [\App\Http\Controllers\Admin\ClaimsController::class, 'companyClaims'])->name('admin.claims.company');
    Route::get('/admin/claims/company/{company}/export', [\App\Http\Controllers\Admin\ClaimsController::class, 'export'])->name('admin.claims.company.export');
    Route::post('/admin/portfolio/ajax-bulk-row/{companyId}', [\App\Http\Controllers\PortfolioController::class, 'ajaxBulkRow'])->name('admin.portfolio.ajax_bulk_row');
    Route::put('/admin/portfolio/endorsement/{endorsementId}', [\App\Http\Controllers\PortfolioController::class, 'updateEndorsement'])->name('admin.portfolio.update_endorsement');
    Route::delete('/admin/portfolio/endorsement/{endorsementId}', [\App\Http\Controllers\PortfolioController::class, 'deleteEndorsement'])->name('admin.portfolio.delete_endorsement');
    Route::post('/admin/portfolio/policy/{policyId}/upload-endorsement', [\App\Http\Controllers\PortfolioController::class, 'uploadEndorsementCopy'])->name('admin.portfolio.upload_endorsement');
    Route::post('/admin/portfolio/policy/{policyId}/upload-policy-copy', [\App\Http\Controllers\PortfolioController::class, 'uploadPolicyCopy'])->name('admin.portfolio.upload_policy_copy');
    Route::get('/admin/portfolio/policy/{policyId}/endorsements', [\App\Http\Controllers\PortfolioController::class, 'viewEndorsements'])->name('admin.portfolio.view_endorsements');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/company-master', [\App\Http\Controllers\CompanyMasterController::class, 'index'])->name('admin.company_master');
    Route::post('/admin/company-master', [\App\Http\Controllers\CompanyMasterController::class, 'store'])->name('admin.company_master.store');
    Route::delete('/admin/company-master/{id}', [\App\Http\Controllers\CompanyMasterController::class, 'destroy'])->name('admin.company_master.delete');
    Route::post('/admin/company-master/toggle/{id}', [\App\Http\Controllers\CompanyMasterController::class, 'toggleActive'])->name('admin.company_master.toggle');
    Route::get('/admin/portfolio', [\App\Http\Controllers\PortfolioController::class, 'index'])->name('admin.portfolio');
    Route::get('/admin/portfolio/policies/{companyId}', [\App\Http\Controllers\PortfolioController::class, 'showPolicies'])->name('admin.portfolio.policies');
    Route::get('/admin/portfolio/create-policy/{companyId}', [\App\Http\Controllers\PortfolioController::class, 'createPolicyForm'])->name('admin.portfolio.create_policy');
    Route::post('/admin/portfolio/create-policy/{companyId}', [\App\Http\Controllers\PortfolioController::class, 'storeSinglePolicy'])->name('admin.portfolio.store_policy');
    Route::post('/admin/portfolio/bulk-upload/{companyId}', [\App\Http\Controllers\PortfolioController::class, 'bulkUpload'])->name('admin.portfolio.bulk_upload');
    Route::get('/admin/company-master/edit/{id}', [\App\Http\Controllers\CompanyMasterController::class, 'edit'])->name('admin.company_master.edit');
    Route::post('/admin/company-master/update/{id}', [\App\Http\Controllers\CompanyMasterController::class, 'update'])->name('admin.company_master.update');
    Route::get('/admin//users', [App\Http\Controllers\UserController::class, 'index'])->name('admin.users');
    Route::post('/admin//users', [App\Http\Controllers\UserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin//users/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin//users/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('admin.users.destroy');
});

Route::middleware(['auth', 'user'])->prefix('user')->group(function () {
    Route::get('/dashboard', function() { return view('user.dashboard'); })->name('user.dashboard');
    Route::get('/portfolio', [PortfolioController::class, 'userPortfolio'])->name('user.portfolio');
    Route::get('/portfolio/policy/{policyId}/endorsements', [PortfolioController::class, 'userViewEndorsements'])->name('user.portfolio.endorsements');
    Route::get('/claims', [PortfolioController::class, 'userClaims'])->name('user.claims');
    Route::get('/claims/create/{type?}', [PortfolioController::class, 'createClaim'])->name('user.claims.create');
    Route::post('/claims/store', [PortfolioController::class, 'storeClaim'])->name('user.claims.store');
});
