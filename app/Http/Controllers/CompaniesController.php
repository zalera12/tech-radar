<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\Log;
use App\Models\Notification;
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
                'categories' => Category::where('company_id', $company->id)->get(),
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
        $roles = $company->roles->unique('id');

        // Ambil nilai search dan role dari request
        $search = $request->input('search');
        $roleFilter = $request->input('role_id'); // Ganti ke 'role_id'
        $sort_order = $request->input('sort_order', 'terbaru'); // Default sort 'terbaru'

        // Buat query untuk mengambil user yang terkait dengan perusahaan ini melalui pivot company_users
        $usersQuery = $company->users()->withPivot('role_id', 'status')->wherePivot('status', 'ACCEPTED');

        // Filter berdasarkan role (jika ada role yang dipilih)
        if ($roleFilter) {
            $usersQuery->wherePivot('role_id', $roleFilter);
        }

        // Jika ada input search, filter berdasarkan nama atau email
        if ($search) {
            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%')->orWhere('email', 'LIKE', '%' . $search . '%');
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
        $companyUsers = $usersQuery->paginate(50);

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
        $pendingMembers = $pendingMembersQuery->paginate(50);

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
        $role = Role::where('id', $request->role_id)->first();

        // Cek apakah user ada
        if (!$user) {
            return redirect("/companies/users/$company->id?permission=Read Company User&idcp=$company->id")->with('not_found', 'User not found!');
        }

        // Cek apakah user sudah terdaftar di perusahaan ini
        if (
            $company
                ->users()
                ->where('user_id', $user->id)
                ->exists()
        ) {
            return redirect("/companies/users/$company->id?permission=Read Company User&idcp=$company->id")->with('user_exists', 'The user is already a member of this company!');
        }

        // Tambahkan user ke perusahaan
        Log::create([
            'id' => Ulid::generate(),
            'company_id' => $request->company_id,
            'name' => $request->user,
            'description' => "Menambahkan pengguna bernama {$user->name} ke dalam perusahaan.",
        ]);

        $company->users()->attach($user->id, ['id' => Ulid::generate(), 'role_id' => $request->role_id, 'status' => 'ACCEPTED']);

        Notification::create([
            'id' => Ulid::generate(),
            'title' => 'Berhasil Bergabung dengan Perusahaan!',
            'message' => 'Selamat! Anda telah berhasil bergabung dengan ' . $company->name . ' sebagai ' . $role->name . ' melalui undangan. Selamat bekerja dan berkontribusi.',
            'user_id' => $user->id,
            'is_read' => false,
        ]);

        return redirect("/companies/users/$company->id?permission=Read Company User&idcp=$company->id")->with('add_success', 'User has been successfully added to the company.');
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
        $role = Role::where('id', $request->role_id)->first()->name;

        if ($request->status == 'ACCEPTED') {
            Notification::create([
                'id' => Ulid::generate(),
                'title' => 'Permintaan Bergabung Diterima!',
                'message' => 'Selamat! Permintaan Anda untuk bergabung dengan ' . $company->name . ' telah disetujui. Anda sekarang menjadi bagian dari perusahaan sebagai ' . $role . '.',
                'user_id' => $member->id,
                'is_read' => false,
            ]);
        }

        // Update role dan status
        $company->users()->updateExistingPivot($member->id, [
            'role_id' => $request->role_id,
            'status' => $request->status,
        ]);

        Log::create([
            'id' => Ulid::generate(),
            'company_id' => $request->company_id,
            'name' => $request->user,
            'description' => "User dengan nama {$request->user} telah menyetujui permintaan bergabung dari user bernama {$member->name} ke perusahaan",
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
        $role = Role::where('id', $request->role_id)->first();
        Log::create([
            'id' => Ulid::generate(),
            'company_id' => $request->company_id,
            'name' => $request->user,
            'description' => 'Mengubah Role user bernama ' . $user->name . ' Dari ' . $request->roleOld . ' menjadi ' . $role->name . '.',
        ]);
        // Update role di pivot table
        $company->users()->updateExistingPivot($user->id, [
            'role_id' => $request->role_id,
        ]);

        return redirect("/companies/users/$company->id?permission=Read Company User&idcp=$company->id")->with('success', 'User role updated successfully');
    }

    public function destroyUserCompanies(Request $request)
    {
        // Validasi input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'company_id' => 'required|exists:companies,id',
            'role' => 'required|string', // Validasi role juga
        ]);

        // Ambil user dan company
        $user = User::findOrFail($request->user_id);
        $company = Company::findOrFail($request->company_id);

        // Cek apakah user_id sama dengan user yang sedang login
        if (auth()->user()->id == $request->user_id) {
            return redirect("/companies/users/$company->id?permission=Read Company User&idcp=$company->id")->with('error', 'Failed to remove user. You cannot remove yourself from the company.');
        }

        // Cek jika role adalah OWNER
        if ($request->role == 'OWNER') {
            return redirect("/companies/users/$company->id?permission=Read Company User&idcp=$company->id")->with('error', 'Failed to remove user. You cannot remove the owner.');
        }

        // Log penghapusan user
        Log::create([
            'id' => Ulid::generate(),
            'company_id' => $request->company_id,
            'name' => $request->user,
            'description' => "Mengeluarkan user dengan nama {$user->name} dari perusahaan.",
        ]);

        Notification::create([
            'id' => Ulid::generate(),
            'title' => 'Anda Telah Dikeluarkan dari Perusahaan',
            'message' => 'Kami informasikan bahwa Anda telah dikeluarkan dari perusahaan ' . $company->name . '. Terima kasih atas kontribusi yang telah Anda berikan.',
            'user_id' => $user->id,
            'is_read' => false,
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
        $roles = $roles->paginate(50); // Sesuaikan jumlah per halaman

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
            'description' => 'Menambahkan role baru dengan nama ' . $request->name . '.',
        ]);

        // Redirect ke halaman permissions
        return redirect("/companies/permissions/$companyId?permission=Read User permission&idcp=$companyId")->with('success_create', 'Role has been successfully added. Please assign permissions for the newly created role');
    }

    public function editRolesCompanies(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $roleId = $request->role_id;
        $role = Role::where('id', $roleId)->first();
        // Update role with new data
        Role::where('id', $roleId)->update($validated);

        // Redirect back with success message
        Log::create([
            'id' => Ulid::generate(),
            'company_id' => $request->company_id,
            'name' => $request->user,
            'description' => "Mengubah role dari {$role->name} menjadi {$request->name}",
        ]);

        return redirect("/companies/roles/$request->company?permission=Read Company Role&idcp=$request->company")->with('success_update', 'Role updated successfully!');
    }

    public function deleteRolesCompanies(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        try {
            // Find role by ID
            $role = Role::findOrFail($request->role_id);

            // Cek apakah role adalah "owner"
            if (in_array($role->name, ['OWNER', 'Pending Member', 'GUEST'])) {
                // Redirect with error if the role is one of the restricted names
                return redirect("/companies/roles/$request->company?permission=Read Company Role&idcp=$request->company")->with('error', 'Failed to delete role. You cannot delete this role.');
            }

            // Log the deletion
            Log::create([
                'id' => Ulid::generate(),
                'company_id' => $request->company_id,
                'name' => $request->user,
                'description' => "Menghapus Role {$role->name}",
            ]);

            // Delete the role
            $role->delete();

            // Redirect back with success message
            return redirect()->back()->with('success_delete', 'Role deleted successfully!');
        } catch (\Exception $e) {
            // Redirect back with error message in case of failure
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
        $categories = Category::where('company_id', $company->id);

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
        $categories = $categories->paginate(50); // Sesuaikan jumlah per halaman

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
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:320',
        ]);

        // Buat kategori baru
        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'company_id' => $company->id,
        ]);

        // Tentukan path untuk menyimpan file JSON di direktori storage/app/public/files
        $filePath = 'files/' . strtoupper($category->name) . ' - ' . $company->name . '.json';

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
            'description' => 'Menambahkan category ' . $request->name . '.',
        ]);

        // Redirect dengan pesan sukses
        return redirect("/companies/categories/$company->id?permission=Read Category Technology&idcp=$company->id")->with('success_update', 'Category added successfully, and JSON file created.');
    }

    private function updateCategoryJson($categoryId, $companyId)
    {
        // Ambil semua teknologi yang memiliki category_id sesuai
        $technologies = Technology::where('category_id', $categoryId)->get(['name', 'ring', 'quadrant', 'is_new', 'description']);

        $companyName = Company::where('id', $companyId)->first()->name;

        $formattedTechnologies = $technologies->map(function ($tech) {
            return [
                'name' => $tech->name,
                'ring' => $tech->ring,
                'quadrant' => $tech->quadrant,
                'isNew' => $tech->is_new ? 'TRUE' : 'FALSE', // Mengubah ke "TRUE"/"FALSE"
                'description' => $tech->description ?? '-',
            ];
        });

        $category = Category::where('id', $categoryId)->first();

        // Tentukan path file JSON di direktori storage/app/public/files
        $filePath = 'files/' . strtoupper($category->name) . ' - ' . $companyName . '.json'; // Pastikan tidak ada 'public/' di sini

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

    private function removeTechnologyFromCategoryJson($technologyId, $categoryId, $companyId)
{
    // Ambil nama perusahaan
    $companyName = Company::where('id', $companyId)->first()->name;

    // Ambil semua teknologi yang memiliki category_id sesuai
    $technologies = Technology::where('category_id', $categoryId)->get(['id', 'name', 'ring', 'quadrant', 'is_new', 'description']);

    // Filter teknologi, hapus teknologi dengan ID yang diberikan
    $filteredTechnologies = $technologies->filter(function ($tech) use ($technologyId) {
        return $tech->id != $technologyId; // Hanya simpan teknologi yang bukan teknologi yang akan dihapus
    });

    // Format data teknologi yang tersisa
    $formattedTechnologies = $filteredTechnologies->map(function ($tech) {
        return [
            'name' => $tech->name,
            'ring' => $tech->ring,
            'quadrant' => $tech->quadrant,
            'isNew' => $tech->is_new ? 'TRUE' : 'FALSE', // Mengubah ke "TRUE"/"FALSE"
            'description' => $tech->description ?? '-',
        ];
    });

    // Ambil nama kategori
    $category = Category::where('id', $categoryId)->first();

    // Tentukan path file JSON di direktori storage/app/public/files
    $filePath = 'files/' . strtoupper($category->name) . ' - ' . $companyName . '.json';

    // Tulis data yang sudah diformat ke file JSON di storage menggunakan Storage facade
    Storage::disk('public')->put($filePath, json_encode($formattedTechnologies->toArray(), JSON_PRETTY_PRINT));

    // Verifikasi apakah file berhasil ditulis
    if (Storage::disk('public')->exists($filePath)) {
        // Optional: log sukses atau lakukan tindakan lain
        // Log::info("File JSON berhasil diperbarui di storage setelah menghapus teknologi: $technologyId");
    } else {
        // Optional: log jika terjadi error
        // Log::error("Gagal memperbarui file JSON setelah menghapus teknologi: $technologyId");
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
        $this->updateCategoryJson($validated['category_id'], $request->company_id);

        // Buat log untuk penambahan teknologi
        Log::create([
            'id' => Ulid::generate(),
            'company_id' => $request->company_id,
            'name' => $request->user, // Pastikan ini adalah data yang benar untuk log
            'description' => 'Menambahkan Technology ' . $request->name . '.',
        ]);

        // Redirect dengan pesan sukses
        return redirect("/companies/technologies/$request->company_id?permission=Read Technology&idcp=$request->company_id")->with('success', 'Technology added successfully.');
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
    
        // Simpan category_id lama sebelum update
        $oldCategoryId = $technology->category_id;
    
        // Update teknologi dengan data yang telah divalidasi
        $technology->update($request->all());
    
        // Buat log untuk mencatat aktivitas update
        Log::create([
            'id' => Ulid::generate(),
            'company_id' => $request->company_id,
            'name' => $request->user, // Pastikan ini adalah data yang benar untuk log
            'description' => 'Mengubah data technology ' . $request->name . '.',
        ]);
    
        // Jika category_id berubah, hapus data teknologi dari file JSON kategori lama
        if ($oldCategoryId != $technology->category_id) {
            $this->removeTechnologyFromCategoryJson($technology->id, $oldCategoryId, $request->company_id);
        }
    
        // Update file JSON untuk kategori terkait (kategori baru atau tetap sama)
        $this->updateCategoryJson($technology->category_id, $request->company_id);
    
        // Redirect ke halaman teknologi dengan pesan sukses
        return redirect("/companies/technologies/$request->company_id?permission=Read Technology&idcp=$request->company_id")
            ->with('success', 'Technology updated successfully.');
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
            'description' => 'Menghapus Technology'.$technology->name.'.',
        ]);

        // Hapus teknologi
        $technology->delete();

        // Update file JSON untuk kategori terkait setelah penghapusan
        $this->updateCategoryJson($categoryId, $request->company_id);

        // Redirect ke halaman teknologi dengan pesan sukses
        return redirect("/companies/technologies/$request->company_id?permission=Read Technology&idcp=$request->company_id")->with('success', 'Technology deleted successfully.');
    }

    public function editCategoryCompanies(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:320',
        ]);

        // Ambil data category berdasarkan id
        $category = Category::findOrFail($request->category_id);

        // Buat log perubahan sebelum update data
        Log::create([
            'id' => Ulid::generate(),
            'company_id' => $request->company_id,
            'name' => $request->user,
            'description' => 'Mengubah Data category ' . $category->name . '.',
        ]);

        // Simpan nama kategori lama untuk pengecekan nanti
        $oldCategoryName = $category->name;
        $company = Company::where('id', $request->company_id)->first();

        // Update category
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Cek jika nama category diubah
        if ($oldCategoryName != $request->name) {
            // Tentukan path file lama dan file baru

            $oldFilePath = 'files/' . strtoupper($oldCategoryName) . ' - ' . $company->name . '.json';
            $newFilePath = 'files/' . strtoupper($request->name) . ' - ' . $company->name . '.json';
            // Jika file JSON dengan nama lama ada, rename file tersebut
            if (Storage::disk('public')->exists($oldFilePath)) {
                Storage::disk('public')->move($oldFilePath, $newFilePath);
            }
        }

        // Redirect dengan pesan sukses
        return redirect("/companies/categories/$request->company_id?permission=Read Category Technology&idcp=$request->company_id")->with('success_update', 'Category updated successfully.');
    }

    public function deleteCategoryCompanies(Request $request)
    {
        // Temukan kategori berdasarkan ID yang diterima dari request
        $category = Category::findOrFail($request->category_id);
        $company = Company::where('id', $request->company_id)->first()->name;
        // Tentukan path file JSON yang akan dihapus di disk 'public'
        $filePath = 'files/' . strtoupper($category->name) . ' - ' . $company . '.json';

        // Buat log untuk pencatatan penghapusan kategori
        Log::create([
            'id' => Ulid::generate(),
            'company_id' => $request->company_id,
            'name' => $request->user,
            'description' => 'Menghapus Data Category ' . $category->name . '.',
        ]);

        // Hapus kategori dari database
        $category->delete();

        // Periksa apakah file JSON ada, jika ya maka hapus
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

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
        $query = Technology::with(['category', 'user'])->where('company_id', $company->id);

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
