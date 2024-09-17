<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Log;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Uid\Ulid;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->input('search');
        $sortOrder = $request->input('sort_order');
    
        $companies = $user->companies()
            ->wherePivot('status', 'Accepted')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($sortOrder, function ($query, $sortOrder) {
                if ($sortOrder === 'A-Z') {
                    $query->orderBy('name', 'asc');
                } elseif ($sortOrder === 'Z-A') {
                    $query->orderBy('name', 'desc');
                } elseif ($sortOrder === 'terbaru') {
                    $query->orderBy('created_at', 'desc');
                } elseif ($sortOrder === 'terlama') {
                    $query->orderBy('created_at', 'asc');
                }
            })
            ->paginate(3);
    
        return view('index', [
            'dataCompanies' => $companies,
            'user' => $user,
            'search' => $search,
            'sortOrder' => $sortOrder,
        ]);
    }    
    public function profile()
    {
        return view('pages-profile', [
            'user' => auth()->user(),
        ]);
    }

    public function profileSettings()
    {
        return view('pages-profile-settings', [
            'user' => auth()->user(),
        ]);
    }

    public function profileEdit(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required',
            'photo' => 'nullable|image|file|max:1024', // Nullable untuk memungkinkan tanpa upload gambar
        ]);
    
        // Cek apakah user mengupload gambar baru
        if ($request->hasFile('photo')) {
            // Hapus gambar lama jika ada
            if ($user->photo && Storage::exists($user->photo)) {
                Storage::delete($user->photo);
            }
    
            // Simpan gambar baru
            $validated['photo'] = $request->file('photo')->store('folder_images');
        } else {
            // Jika tidak ada gambar baru yang diupload, tetap gunakan gambar lama
            $validated['photo'] = $request->input('old_photo');
        }
    
        // Update user dengan data yang telah divalidasi
        $user->update($validated);
    
        // Redirect kembali dengan pesan sukses
        return redirect('/auth-profile')->with('success_update', 'Data berhasil diubah');
    }
    
    public function addCompanies(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Proses upload gambar jika ada
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('folder_images');
        } else {
            $imagePath = null;
        }

        $code = $this->generateUniqueCode();
        $id = Ulid::generate();

        // Simpan data perusahaan
        $company = Company::create([
            'id' => $id,
            'name' => $validatedData['name'],
            'code' => $code,
            'description' => $validatedData['description'] ?? null,
            'image' => $imagePath,
        ]);

        // Mendapatkan role dan permission IDs
        $ownerRole = Role::where('name', 'OWNER')->first();
        $pendingMemberRole = Role::where('name', 'Pending Member')->first();
        $guestRole = Role::where('name', 'GUEST')->first();
        $permissions = Permission::pluck('id')->toArray();
        $waitingPermission = Permission::where('name', 'Waiting')->first();
        $readCompanyProfilePermission = Permission::where('name', 'Read Company Profile')->first();

        // Attach OWNER role dengan semua permission IDs
        foreach ($permissions as $permission) {
            $company->roles()->attach($ownerRole->id, [
                'permission_id' => $permission,
            ]);
        }

        // Attach Pending Member role dengan Waiting permission
        $company->roles()->attach($pendingMemberRole->id, [
            'permission_id' => $waitingPermission->id,
        ]);

        // Attach GUEST role dengan Read Company Profile permission
        $company->roles()->attach($guestRole->id, [
            'permission_id' => $readCompanyProfilePermission->id,
        ]);

        // Memberikan user yang membuat perusahaan role OWNER
        $user = User::find(auth()->user()->id);
        $user->companies()->attach($company->id, [
            'role_id' => $ownerRole->id,
            'id' => Ulid::generate(),
            'status' => 'ACCEPTED',
        ]);

        return redirect('/')->with('add_success', 'Perusahaan Berhasil Ditambahkan!');
    }

    public function editCompanies(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Ambil data company yang akan di-edit
        $company = Company::findOrFail($id);
    
        // Cek apakah user mengupload gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($company->image && Storage::exists($company->image)) {
                Storage::delete($company->image);
            }
    
            // Simpan gambar baru
            $path = $request->file('image')->store('folder_images', 'public');
            $company->image = $path;
        }
    
        // Update nama dan deskripsi
        $company->name = $request->input('name');
        $company->description = $request->input('description');
    
        // Simpan perubahan
        $company->save();

        Log::create([
            'id' => Ulid::generate(),
            'company_id' => $request->id,
            'name' => $request->user,
            'description' => "Edit Company"
        ]);
    
        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success_update', 'Company updated successfully!');
    }
    
    public function deleteCompanies(Request $request, Company $company)
    {
        // Optional: Cek otorisasi jika perlu
        // $this->authorize('delete', $company);
        // Hapus gambar terkait jika ada
        if ($company->image && Storage::exists($company->image)) {
            Storage::delete($company->image); // Hapus gambar dari storage
        }

        Log::create([
            'id' => Ulid::generate(),
            'company_id' => $company->id,
            'name' => $request->user,
            'description' => "Delete Company"
        ]);
    
        // Hapus company dari database
        $company->delete();
    
        // Redirect ke halaman utama dengan pesan sukses
        return redirect('/')->with('success_delete', 'Company deleted successfully.');
    }
    
    
    private function generateUniqueCode()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';
        $code = substr(str_shuffle($characters), 0, 6);

        // Ensure code is unique
        while (Company::where('code', $code)->exists()) {
            $code = substr(str_shuffle($characters), 0, 6);
        }

        return $code;
    }

    public function joinCompanyUser(Request $request)
    {
        // Validasi input
        $request->validate([
            'company_code' => 'required|string|max:255|exists:companies,code',
        ]);

        // Step 1: Cari company berdasarkan code
        $company = Company::where('code', $request->company_code)->first();

        // Step 2: Ambil ID dari user yang sedang login
        $user = Auth::user();

        // Step 3: Cek apakah user sudah ada di company tersebut
        $existingCompanyUser = $user
            ->companies()
            ->where('company_id', $company->id)
            ->first();

        // Cek jika user sudah ada dengan status ACCEPTED
        if ($existingCompanyUser && $existingCompanyUser->pivot->status === 'ACCEPTED') {
            return redirect()->back()->with('error', 'Anda sudah bergabung dengan company ini.');
        }

        // Cek jika user sudah ada dengan status WAITING
        if ($existingCompanyUser && $existingCompanyUser->pivot->status === 'WAITING') {
            return redirect()->back()->with('error', 'Anda sudah mengirimkan permintaan bergabung. Tunggu persetujuan dari owner.');
        }

        // Step 4: Ambil ID role dengan nama "Pending Member"
        $pendingRole = Role::where('name', 'Pending Member')->first();

        // Cek apakah role ada
        if (!$pendingRole) {
            return redirect()
                ->back()
                ->withErrors(['message' => 'Role "Pending Member" tidak ditemukan.']);
        }

        // Step 5: Assign data ke tabel pivot company_users dengan status WAITING
        $user->companies()->attach($company->id, [
            'id' => Ulid::generate(),
            'role_id' => $pendingRole->id,
            'status' => 'WAITING',
        ]);

        // Redirect dengan pesan sukses
        return redirect('/index')->with('success', 'Anda telah berhasil mengirim permintaan untuk bergabung dengan perusahaan.');
    }
}
