<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\Log;
use App\Models\Permission;
use App\Models\Role;
use App\Models\role_permissions;
use App\Models\Technology;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
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
                'categories' => Category::where('company_id',$company->id)->get(),
                'created_date' => Carbon::parse($company->created_at)->format('d F Y'),
                'companyMembers' => $companyUsers,
            ]);
        }
    }

    public function usersCompanies(Request $request, Company $company)
    {
        // Ambil user yang sedang login
        $user = auth()->user();
    
        // Ambil companies terkait user yang sedang login
        $companies = $user->companies;
    
        // Cari company yang spesifik, misalnya berdasarkan ID
        $companyId = $company->id;
        $company = $companies->firstWhere('id', $companyId);
    
        // Ambil roles yang terkait dengan perusahaan ini (melalui pivot company_users)
        $roles = Role::whereHas('companies', function ($query) use ($company) {
            $query->where('companies.id', $company->id);
        })->get();
    
        // Ambil nilai search dan role dari request
        $search = $request->input('search');
        $roleFilter = $request->input('role_id'); // Ganti ke 'role_id'
        $sort_order = $request->input('sort_order', 'terbaru'); // Default sort 'terbaru'
    
        // Buat query untuk mengambil user yang terkait dengan perusahaan ini melalui pivot company_users
        $usersQuery = $company->users()->withPivot('role_id', 'status');
    
        // Filter berdasarkan role (jika ada role yang dipilih)
        if ($roleFilter) {
            $usersQuery->wherePivot('role_id', $roleFilter);
        }
    
        // Jika ada input search, filter berdasarkan nama atau email
        if ($search) {
            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                      ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }
    
        // Terapkan logika sorting berdasarkan sort_order
        if ($sort_order === 'terbaru') {
            $usersQuery->orderBy('created_at', 'desc');
        } elseif ($sort_order === 'terlama') {
            $usersQuery->orderBy('created_at', 'asc');
        } elseif ($sort_order === 'A-Z') {
            $usersQuery->orderBy('name', 'asc');
        } elseif ($sort_order === 'Z-A') {
            $usersQuery->orderBy('name', 'desc');
        }
    
        // Paginate hasil query
        $companyUsers = $usersQuery->paginate(3);
    
        // Return ke view dengan data yang dibutuhkan
        return view('apps-crm-companies', [
            'user' => $user,
            'company' => $company,
            'companyUsers' => $companyUsers,
            'roles' => $roles,
        ]);
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
        $pendingMembersQuery = $company
            ->users()
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
            return redirect("/companies/users/$company->id?permission=Read Company User&idcp=$company->id");
        }

        // Cek apakah user sudah terdaftar di perusahaan ini
        if (
            $company
                ->users()
                ->where('user_id', $user->id)
                ->exists()
        ) {
            return redirect("/companies/users/$company->id?permission=Read Company User&idcp=$company->id");
        }

        // Tambahkan user ke perusahaan
        Log::create([
            'id' => Ulid::generate(),
            'company_id' => $request->company_id,
            'name' => $request->user,
            'description' => "Add Company User"
        ]);
    
        $company->users()->attach($user->id, ['id' => Ulid::generate(), 'role_id' => $request->role_id, 'status' => 'ACCEPTED']);

        return redirect("/companies/users/$company->id?permission=Read Company User&idcp=$company->id")->with('add_success', 'user berhasil ditambahkan ke perusahaan');
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

        Log::create([
            'id' => Ulid::generate(),
            'company_id' => $request->company_id,
            'name' => $request->user,
            'description' => "Update Pending Company User"
        ]);

        return redirect("/companies/pendingMember/$company->id?permission=Read Pending Company User&idcp=$company->id")->with('update_success', 'Pending member updated successfully');
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

        Log::create([
            'id' => Ulid::generate(),
            'company_id' => $request->company_id,
            'name' => $request->user,
            'description' => "Edit Company User"
        ]);

        return redirect("/companies/users/$company->id?permission=Read Company User&idcp=$company->id")->with('success', 'User role updated successfully');
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

        Log::create([
            'id' => Ulid::generate(),
            'company_id' => $request->company_id,
            'name' => $request->user,
            'description' => "Delete Company User"
        ]);

        // Menghapus user dari perusahaan (detach)
        $user->companies()->detach($company->id);

        // Redirect atau kembalikan response sukses
        return redirect("/companies/users/$company->id?permission=Read Company User&idcp=$company->id")->with('success', 'User successfully removed from the company.');
    }

    public function rolesCompanies(Company $company)
    {
        $user = auth()->user();

        // Ambil data request dari input
        $search = request('search');
        $sortOrder = request('sort_order', 'terbaru');

        // Ambil semua roles yang terkait dengan perusahaan tertentu
        $roles = Role::whereHas('companiesPermissions', function ($query) use ($company) {
            $query->where('companies.id', $company->id);
        });

        // Filter berdasarkan search jika ada input search
        if ($search) {
            $roles->where('name', 'like', '%' . $search . '%');
        }

        // Sorting berdasarkan input sort_order
        if ($sortOrder === 'terbaru') {
            $roles->orderBy('created_at', 'desc');
        } elseif ($sortOrder === 'terlama') {
            $roles->orderBy('created_at', 'asc');
        } elseif ($sortOrder === 'A-Z') {
            $roles->orderBy('name', 'asc');
        } elseif ($sortOrder === 'Z-A') {
            $roles->orderBy('name', 'desc');
        }

        // Pagination
        $roles = $roles->paginate(3); // Sesuaikan jumlah per halaman

        // Kirim data ke view
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
                return redirect("/companies/permissions/$companyId?permission=Read User permission&idcp=$companyId")->with('success', 'Permission disconnected successfully.');
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

        Log::create([
            'id' => Ulid::generate(),
            'company_id' => $request->company_id,
            'name' => $request->user,
            'description' => "Add Company Role"
        ]);

        // Redirect ke halaman permissions
        return redirect("/companies/permissions/$companyId?permission=Read User permission&idcp=$companyId")->with('success_create', 'Role berhasil ditambahkan. Silakan isi permission untuk role yang baru.');
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
        Log::create([
            'id' => Ulid::generate(),
            'company_id' => $request->company_id,
            'name' => $request->user,
            'description' => "Edit Company Role"
        ]);


        return redirect("/companies/roles/$request->company?permission=Read Company Role&idcp=$request->company")->with('success_update', 'Role updated successfully!');
    }

    public function deleteRolesCompanies(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        try {
            // Find role by ID and delete
            $role = Role::findOrFail($request->role_id);
            Log::create([
                'id' => Ulid::generate(),
                'company_id' => $request->company_id,
                'name' => $request->user,
                'description' => "Delete Company Role"
            ]);
            $role->delete();

            // Redirect back with success message
            return redirect()->back()->with('success_delete', 'Role deleted successfully!');
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect("/companies/roles/$request->company?permission=Read Company Role&idcp=$request->company")->with('error', 'Failed to delete role.');
        }
    }

    public function categoriesCompanies(Company $company)
    {
        $user = auth()->user();
         // Ambil data request dari input
         $search = request('search');
         $sortOrder = request('sort_order', 'terbaru');
 
         // Ambil semua roles yang terkait dengan perusahaan tertentu
         $categories = Category::where('company_id',$company->id);
 
         // Filter berdasarkan search jika ada input search
         if ($search) {
             $categories->where('name', 'like', '%' . $search . '%');
         }
 
         // Sorting berdasarkan input sort_order
         if ($sortOrder === 'terbaru') {
             $categories->orderBy('created_at', 'desc');
         } elseif ($sortOrder === 'terlama') {
             $categories->orderBy('created_at', 'asc');
         } elseif ($sortOrder === 'A-Z') {
             $categories->orderBy('name', 'asc');
         } elseif ($sortOrder === 'Z-A') {
             $categories->orderBy('name', 'desc');
         }
 
         // Pagination
         $categories = $categories->paginate(10); // Sesuaikan jumlah per halaman
 
        return view('apps-crm-categories', [
            'categories' => $categories,
            'user' => $user,
            'company' => $company,
        ]);
    }

    public function addCategoryCompanies(Request $request, Company $company)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
    
        // Buat kategori baru
        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'company_id' => $company->id
        ]);
    
        // Tentukan path untuk menyimpan file JSON di direktori storage/app/public/files
        $filePath = 'files/' .strtoupper($category->name). '.json';
    
        // Buat folder jika belum ada
        if (!Storage::disk('public')->exists('files')) {
            Storage::disk('public')->makeDirectory('files');
        }
    
        // Tulis data kosong ke file JSON
        Storage::disk('public')->put($filePath, json_encode([], JSON_PRETTY_PRINT));
    
        // Buat entri log untuk pencatatan aktivitas penambahan kategori
        Log::create([
            'id' => Ulid::generate(),
            'company_id' => $company->id,
            'name' => $request->user, // Pastikan ini adalah data yang benar untuk log
            'description' => "Add Category Technology"
        ]);
    
        // Redirect dengan pesan sukses
        return redirect("/companies/categories/$company->id?permission=Read Category Technology&idcp=$company->id")
            ->with('success_update', 'Category added successfully, and JSON file created.');
    }
    
    
    
    
    private function updateCategoryJson($categoryId)
    {
        // Ambil semua teknologi yang memiliki category_id sesuai
        $technologies = Technology::where('category_id', $categoryId)
            ->get(['name', 'ring', 'quadrant', 'is_new', 'description']);
    
        // Ubah format 'is_new' menjadi "TRUE"/"FALSE"
        $formattedTechnologies = $technologies->map(function ($tech) {
            return [
                'name' => $tech->name,
                'ring' => $tech->ring,
                'quadrant' => $tech->quadrant,
                'isNew' => $tech->is_new ? "TRUE" : "FALSE", // Mengubah ke "TRUE"/"FALSE"
                'description' => $tech->description ??  "-",
            ];
        });

        $category = Category::where('id',$categoryId)->get();
    
        // Tentukan path file JSON di direktori storage/app/public/files
        $filePath = 'files/' . strtoupper(Str::slug($category->name, '-')) . '.json'; // Pastikan tidak ada 'public/' di sini
    
        // Tulis data ke file JSON di storage menggunakan Storage facade
        Storage::disk('public')->put($filePath, json_encode($formattedTechnologies, JSON_PRETTY_PRINT));
    
        // Verifikasi apakah file berhasil ditulis
        if (Storage::disk('public')->exists($filePath)) {
            // Optional: log sukses atau lakukan tindakan lain
            // Log::info("File JSON berhasil disimpan di storage untuk category: $categoryId");
        } else {
            // Optional: log jika terjadi error
            // Log::error("Gagal menyimpan file JSON di storage untuk category: $categoryId");
        }
    }
    
    
    
    

    public function addTechnologiesCompanies(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quadrant' => 'required|string|in:Techniques,Platforms,Tools,Languages and Frameworks',
            'ring' => 'required|string|in:hold,adopt,assess,trial',
            'category_id' => 'required|exists:categories,id',
            'company_id' => 'required|exists:companies,id',
            'user_id' => 'required|exists:users,id',
        ]);
    
        // Generate ULID untuk id technology
        $validated['id'] = Ulid::generate();
    
        // Buat technology baru
        Technology::create($validated);
    
        // Update file JSON untuk kategori terkait
        $this->updateCategoryJson($validated['category_id']);
    
        // Buat log untuk penambahan teknologi
        Log::create([
            'id' => Ulid::generate(),
            'company_id' => $request->company_id,
            'name' => $request->user, // Pastikan ini adalah data yang benar untuk log
            'description' => "Add Technology"
        ]);
    
        // Redirect dengan pesan sukses
        return redirect("/companies/technologies/$request->company_id?permission=Read Technology&idcp=$request->company_id")->with('success', 'Technology updated successfully.');
    }
    
    

    public function updateTechnologiesCompanies(Request $request)
    {
        // Validasi input request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quadrant' => 'required|string|in:Techniques,Platforms,Tools,Languages and Frameworks',
            'ring' => 'required|string|in:hold,adopt,assess,trial',
            'category_id' => 'required|exists:categories,id',
            'company_id' => 'required|exists:companies,id',
            'user_id' => 'required|exists:users,id',
        ]);
    
        // Temukan teknologi berdasarkan id
        $technology = Technology::findOrFail($request->id);
    
        // Update teknologi dengan data yang telah divalidasi
        $technology->update($request->all());
    
        // Buat log untuk mencatat aktivitas update
        Log::create([
            'id' => Ulid::generate(),
            'company_id' => $request->company_id,
            'name' => $request->user, // Pastikan ini adalah data yang benar untuk log
            'description' => "Edit Technology"
        ]);
    
        // Update file JSON untuk kategori terkait
        $this->updateCategoryJson($technology->category_id);
    
        // Redirect ke halaman teknologi dengan pesan sukses
        return redirect("/companies/technologies/$request->company_id?permission=Read Technology&idcp=$request->company_id")->with('success', 'Technology updated successfully.');
    }
    
    

    public function deleteTechnologiesCompanies(Request $request)
    {
        // Temukan teknologi berdasarkan ID
        $technology = Technology::findOrFail($request->id);
    
        // Ambil category_id sebelum teknologi dihapus
        $categoryId = $technology->category_id;
    
        // Buat log untuk mencatat aktivitas penghapusan teknologi
        Log::create([
            'id' => Ulid::generate(),
            'company_id' => $request->company_id,
            'name' => $request->user, // Pastikan ini adalah data yang benar untuk log
            'description' => "Delete Technology"
        ]);
    
        // Hapus teknologi
        $technology->delete();
    
        // Update file JSON untuk kategori terkait setelah penghapusan
        $this->updateCategoryJson($categoryId);
    
        // Redirect ke halaman teknologi dengan pesan sukses
        return redirect("/companies/technologies/$request->company_id?permission=Read Technology&idcp=$request->company_id")->with('success', 'Technology updated successfully.');
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

        Log::create([
            'id' => Ulid::generate(),
            'company_id' => $request->company_id,
            'name' => $request->user,
            'description' => "Edit Category Technology"
        ]);
        return redirect("/companies/categories/$request->company_id?permission=Read Category Technology&idcp=$request->company_id")->with('success_update', 'Category updated successfully.');
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

        Log::create([
            'id' => Ulid::generate(),
            'company_id' => $request->company_id,
            'name' => $request->user,
            'description' => "Delete Category Technology"
        ]);

        return redirect("/companies/categories/$request->company_id?permission=Read Category Technology&idcp=$request->company_id")->with('success_delete', 'Category deleted successfully, and associated JSON file deleted.');
    }

    public function technologiesCompanies(Request $request, Company $company)
    {
        $user = auth()->user();
    
        // Ambil filter dari request
        $filterCategory = $request->input('filterCategory');
        $filterRing = $request->input('filterRing');
        $filterQuadrant = $request->input('filterQuadrant'); // Filter Quadrant
        $search = $request->input('search');
        $sortOrder = $request->input('sort_order'); // Ambil sorting dari request
    
        // Mulai query dengan memfilter berdasarkan company_id
        $query = Technology::with(['category', 'user'])
            ->where('company_id', $company->id);
    
        // Filter berdasarkan category_id jika ada
        if ($filterCategory) {
            $query->where('category_id', $filterCategory);
        }
    
        // Filter berdasarkan ring jika ada
        if ($filterRing) {
            $query->where('ring', $filterRing);
        }
    
        // Filter berdasarkan quadrant jika ada
        if ($filterQuadrant) {
            $query->where('quadrant', $filterQuadrant);
        }
    
        // Filter berdasarkan search jika ada
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }
    
        // Tambahkan logika sorting
        if ($sortOrder) {
            switch ($sortOrder) {
                case 'terbaru':
                    $query->orderBy('created_at', 'desc'); // Sort by newest
                    break;
                case 'terlama':
                    $query->orderBy('created_at', 'asc'); // Sort by oldest
                    break;
                case 'A-Z':
                    $query->orderBy('name', 'asc'); // Sort by name A-Z
                    break;
                case 'Z-A':
                    $query->orderBy('name', 'desc'); // Sort by name Z-A
                    break;
                default:
                    $query->orderBy('created_at', 'desc'); // Default sorting
            }
        } else {
            $query->orderBy('created_at', 'desc'); // Default sort by terbaru if no sort_order is set
        }
    
        // Paginasi dengan 10 data per halaman
        $technologies = $query->paginate(50);
    
        // Dapatkan semua kategori dari perusahaan
        $categories = $company->categories;
    
        // Kembalikan view dengan data yang diperlukan
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
