<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permissionName  Permission name passed from the route
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $permissionName = request('permission'); // Ambil permission dari query string

        // Ambil user yang sedang login
        $user = Auth::user();

        // Ambil company ID dari parameter route
        $companyId = request('idcp'); // Pastikan parameter ini ada di route

        // Jika companyId tidak ditemukan, redirect atau abort
        if (!$companyId) {
            return redirect()->back()->with('error', 'Company ID tidak ditemukan');
        }

        // Cek apakah user terkait dengan company tertentu melalui pivot company_user
        $userCompany = $user->companies()
                            ->where('company_id', $companyId)
                            ->first();

        if (!$userCompany) {
            return redirect()->back()->with('error', 'User tidak terkait dengan perusahaan ini');
        }

        $userRoleId = $userCompany->pivot->role_id ?? null;

        if (!$userRoleId) {
            return redirect()->back()->with('error', 'Role user tidak ditemukan untuk perusahaan ini');
        }

        // Dapatkan role dan permissions terkait
        $userRole = Role::with(['permissions' => function ($query) use ($companyId) {
            $query->wherePivot('company_id', $companyId); // Filter dengan company_id di pivot
        }])->find($userRoleId);

        if (!$userRole) {
            return redirect()->back()->with('error', 'Role user tidak ditemukan');
        }

        // Ambil permission names dari relasi permissions
        $permissions = $userRole->permissions->pluck('name'); // Menggunakan pluck untuk mengambil nama permission

        // Debugging
        // Cek apakah permission yang diperlukan ada
        if ($permissions->contains($permissionName)) {
            return $next($request);
        }

        return redirect()->back()->with('error', 'You do not have permission to perform this action.');
    }
}
