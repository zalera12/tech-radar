<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Company $company)
{
    // Ambil query log berdasarkan company
    $logsQuery = Log::where('company_id', $company->id);

    // Search (pencarian)
    if (request('search')) {
        $logsQuery->where(function ($query) {
            $query->where('name', 'like', '%' . request('search') . '%')
                  ->orWhere('description', 'like', '%' . request('search') . '%');
        });
    }

    // Filter (penyortiran)
    if (request('sort_order')) {
        switch (request('sort_order')) {
            case 'terbaru':
                $logsQuery->orderBy('created_at', 'desc');
                break;
            case 'terlama':
                $logsQuery->orderBy('created_at', 'asc');
                break;
            case 'A-Z':
                $logsQuery->orderBy('name', 'asc');
                break;
            case 'Z-A':
                $logsQuery->orderBy('name', 'desc');
                break;
        }
    }

    // Pagination dengan 10 item per halaman
    $logs = $logsQuery->latest()->paginate(50)->withQueryString();

    return view('apps-crm-log', [
        'user' => auth()->user(),
        'company' => $company,
        'logs' => $logs,
    ]);
}


    public function destroyLog(Request $request)
    {
        // Ambil log berdasarkan id yang diterima dari request
        $log = Log::where('id', $request->log_id)->first();

        if ($log) {
            // Hapus log
            $log->delete();

            // Redirect dengan pesan sukses
            return redirect("/companies/log/$request->company_id?permission=Read Change Log&idcp=$request->company_id")->with('success', 'Logs have been successfully deleted.');
        }

        // Redirect dengan pesan error jika log tidak ditemukan
        return redirect()->back()->with('error', 'Log Not Found.');
    }
}
