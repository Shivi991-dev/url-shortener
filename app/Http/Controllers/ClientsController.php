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

        $companies = auth()->user()->hasRole('super admin') ? Companies::all() : null;
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

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required'],
            'role' => 'required|exists:roles,name',
        ];

        if (auth()->user()->hasRole('super admin')) {
            $rules['company'] = 'required|exists:companies,id';
        }

        $request->validate($rules);

        $companyId = auth()->user()->hasRole('super admin') ? (int) $request->company : (int) auth()->user()->companies_id;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'companies_id' => $companyId,
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->route('clients.index')->with('success', 'Client created successfully');
    }

    public function show(User $client)
    {
        $this->checkRoleBasedAuthorization(['super admin', 'admin']);

        if(!auth()->user()->hasRole('super admin')) {
            if($client->companies_id != auth()->user()->companies_id) {
                abort(403);
            }
        }
        return view('superadmin.clients.show', compact('client'));
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

        if(!auth()->user()->hasRole('super admin')) {
            if($client->companies_id != auth()->user()->companies_id) {
                abort(403);
            }
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($client->id)],
            'password' => ['nullable'],
            'role' => 'required|exists:roles,name',
        ];

        if (auth()->user()->hasRole('super admin')) {
            $rules['company'] = 'required|exists:companies,id';
        }

        $request->validate($rules);

        $companyId = auth()->user()->hasRole('super admin') ? (int) $request->company : (int) auth()->user()->companies_id;
        
        $client->update([
            'name' => $request->name,
            'email' => $request->email,
            'companies_id' => $companyId,
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

        if(auth()->user()->hasRole('super admin')) {
            $client->delete();
        } else {
            if($client->companies_id != auth()->user()->companies_id) {
                abort(403);
            }
            $client->delete();
        }
        
        return redirect()->route('clients.index')->with('success', 'Client deleted successfully');
    }
}
