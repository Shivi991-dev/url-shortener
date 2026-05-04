<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Models\Companies;
use App\Traits\CheckRoleBasedAuthorization;

class ClientsController extends Controller
{
    use CheckRoleBasedAuthorization;
    
    public function index()
    {
        $this->checkRoleBasedAuthorization(['super admin', 'admin']);

        if(auth()->user()->hasRole('super admin')) {
            $clients = User::role('admin')->with('roles')->with('company')->get();
        } elseif(auth()->user()->hasRole('admin')) {
            $clients = User::role(['admin', 'member'])->with('company')->where('companies_id', auth()->user()->companies_id)->get();
        }

        return view('superadmin.clients.index', compact('clients'));
    }

    public function create()
    {
        $this->checkRoleBasedAuthorization(['super admin', 'admin']);

        $companies = Companies::all();
        if(auth()->user()->hasRole('super admin')) {
            $roles = ['admin'];
        } elseif(auth()->user()->hasRole('admin')) {
            $roles = ['admin', 'member'];
        }
        return view('superadmin.clients.create', compact('companies', 'roles'));
    }

    public function store(Request $request)
    {
        $this->checkRoleBasedAuthorization(['super admin', 'admin']);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required'],
            'role' => 'required|exists:roles,name',
            'company' => 'required|exists:companies,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'companies_id' => (int) $request->company,
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->route('clients.index')->with('success', 'Client created successfully');
    }

    public function show(User $client)
    {
        $this->checkRoleBasedAuthorization(['super admin', 'admin']);

        if (! $client->hasRole('admin')) {
            abort(404);
        }

        $companies = Companies::all();
        return view('superadmin.clients.show', compact('client', 'companies'));
    }

    public function edit(User $client)
    {
        $this->checkRoleBasedAuthorization(['super admin', 'admin']);

        $companies = Companies::all();

        if(auth()->user()->hasRole('super admin')) {
            $roles = ['admin'];
        } elseif(auth()->user()->hasRole('admin')) {
            $roles = ['admin', 'member'];
        }

        return view('superadmin.clients.edit', compact('client', 'companies', 'roles'));
    }

    public function update(Request $request, User $client)
    {
        $this->checkRoleBasedAuthorization(['super admin', 'admin']);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($client->id)],
            'password' => ['nullable'],
            'role' => 'required|exists:roles,name',
            'company' => 'required|exists:companies,id',
        ]);
        
        $client->update([
            'name' => $request->name,
            'email' => $request->email,
            'companies_id' => (int) $request->company,
        ]);
        
        if ($request->filled('password')) {
            $client->password = $request->password;
        }

        $client->syncRoles([$request->role]);

        return redirect()->route('clients.index')->with('success', 'Client updated successfully');
    }

    public function destroy(User $client)
    {
        $this->checkRoleBasedAuthorization(['super admin', 'admin']);

        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully');
    }
}
