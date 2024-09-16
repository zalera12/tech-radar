<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\Permission;
use App\Models\Role;
use App\Models\role_permissions;
use App\Models\Technology;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Uid\Ulid;

class CompaniesController extends Controller
{
    public function mainCompanies(Company $company)
    {
        // Ambil user yang sedang login
        $user = auth()->user();

        // Ambil companies terkait user tersebut
        $companies = $user->companies;

        // Cari company yang spesifik, misalnya berdasarkan ID
        $companyId = $company->id; // Ganti dengan ID company yang ingin kamu akses
        $company = $companies->firstWhere('id', $companyId);

        // Cek apakah company ditemukan dan ada pivot-nya
        if ($company && $company->pivot) {
            // Akses role_id dari data pivot
            $roleId = $company->pivot->role_id;
            $userrole = Role::where('id', $roleId)->first();
        } else {
            // Company tidak ditemukan atau relasi pivot tidak ada
            echo 'Company atau relasi pivot tidak ditemukan.';
        }

        $companyUsers = [];
        // Periksa apakah company ditemukan
        if ($company) {
            // Loop melalui setiap user yang terkait dengan perusahaan
            foreach ($company->users as $user) {
                // Ambil role ID dari pivot
                $roleId = $user->pivot->role_id;

                // Ambil data role berdasarkan role ID
                $role = Role::find($roleId);

                // Tambahkan data user dan role ke dalam array
                $companyUsers[] = [
                    'user' => $user,
                    'role' => $role,
                ];
            }
            return view('apps-projects-overview', [
                'company' => $company,
                'user' => $user,
                'role' => $userrole,
                'created_date' => Carbon::parse($company->created_at)->format('d F Y'),
                'companyMembers' => $companyUsers,
            ]);
        }
    }

    public function usersCompanies(Company $company)
    {
        // Ambil user yang sedang login
        $user = auth()->user();

        // Ambil companies terkait user tersebut
        $companies = $user->companies;

        // Cari company yang spesifik, misalnya berdasarkan ID
        $companyId = $company->id; // Ganti dengan ID company yang ingin kamu akses
        $company = $companies->firstWhere('id', $companyId);

        $roles = Role::whereHas('companiesPermissions', function ($query) use ($company) {
            $query->where('companies.id', $company->id);
        })->get();

        $companyUsers = [];
        // Periksa apakah company ditemukan
        if ($company) {
            // Loop melalui setiap user yang terkait dengan perusahaan
            foreach ($company->users as $user) {
                // Ambil role ID dari pivot
                $roleId = $user->pivot->role_id;

                // Ambil data role berdasarkan role ID
                $role = Role::find($roleId);

                // Tambahkan data user dan role ke dalam array
                $companyUsers[] = [
                    'user' => $user,
                    'role' => $role,
                ];
            }

            return view('apps-crm-companies', [
                'user' => auth()->user(),
                'company' => $company,
                'companyUsers' => $companyUsers,
                'roles' => $roles,
            ]);
        }
    }

    public function pendingMemberCompanies(Request $request, Company $company)
    {
        $user = auth()->user();
        $companies = $user->companies;
        $company = $companies->firstWhere('id', $company->id);
        $roles = Role::whereHas('companiesPermissions', function ($query) use ($company) {
            $query->where('companies.id', $company->id);
        })->get();
    
        // Ambil nilai search dari request
        $search = $request->input('search');
    
        // Ambil nilai sort_order dari request, default ke 'terbaru'
        $sort_order = $request->input('sort_order', 'terbaru');
    
        // Buat query untuk pending members
        $pendingMembersQuery = $company->users()
            ->wherePivot('status', 'WAITING') // Hanya ambil pengguna dengan status WAITING
            ->when($search, function ($query) use ($search) {
                // Filter berdasarkan nama jika ada input search
                $query->where('name', 'LIKE', '%' . $search . '%');
            });
    
        // Terapkan logika sorting berdasarkan sort_order
        if ($sort_order === 'terbaru') {
            $pendingMembersQuery->orderBy('created_at', 'desc');
        } elseif ($sort_order === 'terlama') {
            $pendingMembersQuery->orderBy('created_at', 'asc');
        } elseif ($sort_order === 'A-Z') {
            $pendingMembersQuery->orderBy('name', 'asc');
        } elseif ($sort_order === 'Z-A') {
            $pendingMembersQuery->orderBy('name', 'desc');
        }
    
        // Paginate hasil query
        $pendingMembers = $pendingMembersQuery->paginate(1);
    
        // Kembalikan ke view dengan data yang dibutuhkan
        return view('apps-crm-pending-members', [
            'user' => $user,
            'company' => $company,
            'pendingMembers' => $pendingMembers,
            'roles' => $roles,
            'sort_order' => $sort_order,
        ]);
    }
    
    
    
  
    
    

    public function addUser(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'role_id' => 'required|exists:roles,id',
            'company_id' => 'required|exists:companies,id',
        ]);

        $user = User::where('email', $request->email)->first();
        $company = Company::find($request->company_id);

        // Cek apakah user ada
        if (!$user) {
            return back();
        }

        // Cek apakah user sudah terdaftar di perusahaan ini
        if (
            $company
                ->users()
                ->where('user_id', $user->id)
                ->exists()
        ) {
            return back();
        }

        // Tambahkan user ke perusahaan
        $company->users()->attach($user->id, ['id' => Ulid::generate(), 'role_id' => $request->role_id, 'status' => 'ACCEPTED']);

        return back();
    }

    public function updatePendingMember(Request $request, $memberId)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:ACCEPTED,WAITING',
        ]);

        // Ambil pending member dan update role serta status
        $member = User::findOrFail($memberId);
        $company = Company::where('id', $request->company_id)->first();

        // Update role dan status
        $company->users()->updateExistingPivot($member->id, [
            'role_id' => $request->role_id,
            'status' => $request->status,
        ]);

        return redirect('/companies/pendingMember/' . $company->id)->with('success', 'Pending member updated successfully');
    }

    public function updateRoleUser(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        // Ambil user dan update role pada pivot table
        $user = User::findOrFail($request->id);
        $company = Company::where('id', $request->company_id)->first(); // Misal perusahaan terkait didapat dari user yang login

        // Update role di pivot table
        $company->users()->updateExistingPivot($user->id, [
            'role_id' => $request->role_id,
        ]);

        return redirect('/companies/users/' . $company->id)->with('success', 'User role updated successfully');
    }

    public function destroyUserCompanies(Request $request)
    {
        // Validasi input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'company_id' => 'required|exists:companies,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $company = Company::findOrFail($request->company_id);

        // Menghapus user dari perusahaan (detach)
        $user->companies()->detach($company->id);

        // Redirect atau kembalikan response sukses
        return redirect()->back()->with('success', 'User successfully removed from the company.');
    }

    public function rolesCompanies(Company $company)
    {
        $user = auth()->user();
        // Ambil roles yang terkait dengan perusahaan tertentu
        $roles = Role::whereHas('companiesPermissions', function ($query) use ($company) {
            $query->where('companies.id', $company->id);
        })->get();

        return view('apps-crm-roles', [
            'company' => $company,
            'roles' => $roles,
            'user' => $user,
        ]);
    }

    public function permissionsCompanies(Company $company)
    {
        $user = auth()->user();

        // Ambil semua roles yang terkait dengan perusahaan tersebut
        $roles = Role::whereHas('companiesPermissions', function ($query) use ($company) {
            $query->where('companies.id', $company->id);
        })->get();

        // Ambil semua permissions
        $permissions = Permission::all();

        // Ambil semua role_permissions yang terkait dengan company
        $rolePermissions = role_permissions::where('company_id', $company->id)->get();

        return view('apps-crm-permissions', [
            'roles' => $roles,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
            'user' => $user,
            'company' => $company,
        ]);
    }

    public function toggleRolePermission(Request $request)
    {
        $roleId = $request->input('role_id');
        $permissionId = $request->input('permission_id');
        $companyId = $request->input('company_id');
        $isConnected = $request->input('is_connected');

        try {
            if ($isConnected == 2) {
                role_permissions::create([
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                    'company_id' => $companyId,
                ]);
                return redirect()->back()->with('success', 'Permission connected successfully.');
            } else {
                role_permissions::where('role_id', $roleId)->where('permission_id', $permissionId)->where('company_id', $companyId)->delete();
                return redirect()->back()->with('success', 'Permission disconnected successfully.');
            }
        } catch (\Exception $e) {
            dd($e->getMessage()); // Ini akan menampilkan pesan error langsung
        }
    }

    public function addRolesCompanies(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        // Simpan data role ke table roles
        $role = Role::create([
            'id' => Ulid::generate(),
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Asumsikan terdapat company_id yang dikirim dari form
        $companyId = $request->company_id;
        $permissionId = Permission::where('name', 'Read Company Profile')->first()->id;
        // Simpan data ke table pivot role_permissions
        $role->companiesPermissions()->attach($companyId, [
            'permission_id' => $permissionId, // Kosongkan permission_id dulu
        ]);

        // Redirect ke halaman permissions
        return redirect('/companies/permissions/' . $companyId)->with('success_create', 'Role berhasil ditambahkan. Silakan isi permission untuk role yang baru.');
    }

    public function editRolesCompanies(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $roleId = $request->role_id;

        // Update role with new data
        Role::where('id', $roleId)->update($validated);

        // Redirect back with success message

        return redirect()->back()->with('success_update', 'Role updated successfully!');
    }

    public function deleteRolesCompanies(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        try {
            // Find role by ID and delete
            $role = Role::findOrFail($request->role_id);
            $role->delete();

            // Redirect back with success message
            return redirect()->back()->with('success_delete', 'Role deleted successfully!');
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->back()->with('error', 'Failed to delete role.');
        }
    }

    public function categoriesCompanies(Company $company)
    {
        $user = auth()->user();
        $categories = Category::where('company_id', $company->id)->get();

        return view('apps-crm-categories', [
            'categories' => $categories,
            'user' => $user,
            'company' => $company,
        ]);
    }

    public function addCategoryCompanies(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'company_id' => $company->id,
        ]);

        // Tentukan path untuk menyimpan file JSON
        $filePath = public_path('files/' . $category->id . '.json');

        // Buat folder jika belum ada
        if (!File::exists(public_path('files'))) {
            File::makeDirectory(public_path('files'), 0755, true);
        }

        // Tulis data kosong ke file JSON
        File::put($filePath, json_encode([], JSON_PRETTY_PRINT));

        return redirect()
            ->route('categories.index', $company->id)
            ->with('success_update', 'Category added successfully, and JSON file created.');
    }
    // Fungsi untuk memperbarui file JSON berdasarkan id_category
    private function updateCategoryJson($categoryId)
    {
        // Ambil semua teknologi yang memiliki category_id sesuai
        $technologies = Technology::where('category_id', $categoryId)->get(['name', 'ring', 'quadrant', 'is_new', 'description']);

        // Ubah format 'is_new' menjadi true/false
        $formattedTechnologies = $technologies->map(function ($tech) {
            return [
                'name' => $tech->name,
                'ring' => $tech->ring,
                'quadrant' => $tech->quadrant,
                'is_new' => $tech->is_new ? true : false,
                'description' => $tech->description,
            ];
        });

        // Tentukan path file JSON berdasarkan id_category
        $filePath = public_path('files/' . $categoryId . '.json');

        // Tulis data ke file JSON
        File::put($filePath, json_encode($formattedTechnologies, JSON_PRETTY_PRINT));
    }

    public function addTechnologiesCompanies(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quadrant' => 'required|string|in:Techniques,Platforms,Tools,Language and Framework',
            'ring' => 'required|string|in:HOLD,ADOPT,ASSESS,TRIAL',
            'category_id' => 'required|exists:categories,id',
            'company_id' => 'required|exists:companies,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $validated['id'] = Ulid::generate();

        // Buat technology baru
        Technology::create($validated);

        // Update file JSON untuk kategori terkait
        $this->updateCategoryJson($validated['category_id']);

        return redirect('/companies/technologies/' . $request->company_id)->with('success', 'Technology added successfully.');
    }

    public function updateTechnologiesCompanies(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quadrant' => 'required|string|in:Techniques,Platforms,Tools,Language and Framework',
            'ring' => 'required|string|in:HOLD,ADOPT,ASSESS,TRIAL',
            'category_id' => 'required|exists:categories,id',
            'company_id' => 'required|exists:companies,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $technology = Technology::findOrFail($request->id);

        $technology->update($request->all());

        // Update file JSON untuk kategori terkait
        $this->updateCategoryJson($technology->category_id);

        return redirect('/companies/technologies/' . $request->company_id)->with('success', 'Technology updated successfully.');
    }

    public function deleteTechnologiesCompanies(Request $request)
    {
        $technology = Technology::findOrFail($request->id);
        $categoryId = $technology->category_id;
        $technology->delete();

        // Update file JSON untuk kategori terkait
        $this->updateCategoryJson($categoryId);

        return redirect('/companies/technologies/' . $request->company_id)->with('success', 'Technology deleted successfully.');
    }

    public function editCategoryCompanies(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Category::findOrFail($request->category_id);
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return redirect('/companies/categories/' . $request->company_id)->with('success_update', 'Category updated successfully.');
    }

    public function deleteCategoryCompanies(Request $request)
    {
        // Temukan kategori berdasarkan ID yang diterima dari request
        $category = Category::findOrFail($request->category_id);

        // Tentukan path file JSON yang akan dihapus
        $filePath = public_path('files/' . $category->id . '.json');
        // Hapus kategori dari database
        $category->delete();

        // Periksa apakah file JSON ada, jika ya maka hapus
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        return redirect()->back()->with('success_delete', 'Category deleted successfully, and associated JSON file deleted.');
    }

    public function technologiesCompanies(Company $company)
    {
        $user = auth()->user();
        $technologies = Technology::with(['category', 'user'])
            ->where('company_id', $company->id)
            ->get();
        $categories = $company->categories;

        return view('apps-crm-technologies', [
            'technologies' => $technologies,
            'user' => $user,
            'categories' => $categories,
            'company' => $company,
        ]);
    }

    // Menampilkan detail teknologi (Read detail)
    public function showTechnologiesCompanies($id)
    {
        $technology = Technology::with(['category', 'company', 'user'])->findOrFail($id);
        return view('technologies.show', compact('technology'));
    }
}
