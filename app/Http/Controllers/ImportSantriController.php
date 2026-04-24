<?php

namespace App\Http\Controllers;

use App\Exports\TemplateSantriExport;
use App\Imports\SantriImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportSantriController extends Controller
{
    public function index()
    {
        return view('santri.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new SantriImport, $request->file('file'));

            return redirect()
                ->route('santri.index')
                ->with('success', 'Data santri berhasil diimport.');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('error', 'Import gagal: ' . $th->getMessage());
        }
    }

    public function template()
    {
        return Excel::download(new TemplateSantriExport, 'template-import-santri.xlsx');
    }
}