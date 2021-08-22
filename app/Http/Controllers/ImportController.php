<?php

namespace App\Http\Controllers;


use App\Imports\FieldOfficerImport;
use App\Imports\SuppliesImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function importFieldOfficer()
    {
        return view('admin.field_officers_resource.import');
    }
    public function storeFieldOfficer(Request $request)
    {
        $this->validate($request, [
            'import_file'  => 'required|mimes:xls,xlsx'
        ]);

        $filename = $request->file('import_file');

        Excel::import(new FieldOfficerImport, $filename);
        Session::flash('message', 'Excel uploaded successfully!');
        return redirect('field_officers');
    }

    public function importSupplies()
    {
        return view('admin.inventory-resource.import');
    }
    public function storeSupplies(Request $request)
    {
        $this->validate($request, [
            'import_file'  => 'required|mimes:xls,xlsx'
        ]);

        $filename = $request->file('import_file');

        Excel::import(new SuppliesImport, $filename);
        Session::flash('message', 'Excel uploaded successfully!');
        return redirect('inventory');
    }
}