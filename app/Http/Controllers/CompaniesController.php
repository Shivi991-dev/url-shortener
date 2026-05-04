<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Companies;
use App\Traits\CheckRoleBasedAuthorization;

class CompaniesController extends Controller
{
    use CheckRoleBasedAuthorization;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkRoleBasedAuthorization(['super admin']);

        $companies = Companies::withCount('users')->get();
        return view('superadmin.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkRoleBasedAuthorization(['super admin']);

        return view('superadmin.companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkRoleBasedAuthorization(['super admin']);

        $request->validate(['name' => 'required|string|max:255']);

        Companies::create(['name' => $request->name]);

        return redirect()->route('companies.index')->with('success', 'Company created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Companies $company)
    {
        $this->checkRoleBasedAuthorization(['super admin']);

        return view('superadmin.companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Companies $company)
    {
        $this->checkRoleBasedAuthorization(['super admin']);

        return view('superadmin.companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Companies $company)
    {
        $this->checkRoleBasedAuthorization(['super admin']);

        $request->validate(['name' => 'required|string|max:255']);
        $company->update(['name' => $request->name]);
        return redirect()->route('companies.index')->with('success', 'Company updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Companies $company)
    {
        $this->checkRoleBasedAuthorization(['super admin']);

        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company deleted successfully');
    }
}
