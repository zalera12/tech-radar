<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Http\Request;

class indexController extends Controller
{
    public function index(){
        return view('index2',[
            'companies' => Company::where('status', 'public')->latest()->limit(6)->get()
        ]);
    }

    public function Company(Request $request)
    {
        // Ambil query search dan filter dari request
        $search = $request->input('search');
        $filter = $request->input('filter');
    
        // Query dasar untuk perusahaan yang statusnya public
        $query = Company::where('status', 'public');
    
        // Jika ada search, tambahkan kondisi pencarian berdasarkan nama atau deskripsi
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
    
        // Tambahkan logika filter
        switch ($filter) {
            case 'terbaru':
                $query->latest(); // Urutkan berdasarkan yang terbaru
                break;
            case 'terlama':
                $query->oldest(); // Urutkan berdasarkan yang terlama
                break;
            case 'az':
                $query->orderBy('name', 'asc'); // Urutkan A-Z
                break;
            case 'za':
                $query->orderBy('name', 'desc'); // Urutkan Z-A
                break;
            default:
                $query->latest(); // Default ke yang terbaru
                break;
        }
    
        // Pagination
        $companies = $query->paginate(18)->appends($request->all());
    
        // Return view dengan data
        return view('allCompany', [
            'companies' => $companies
        ]);
    }

    public function detailCompany(Company $company){
        $categories = Category::where('company_id',$company->id);
        return view('company',[
            'company' => $company,
            'categories' => $categories->paginate(3)
        ]);
    }
    

    
}
