<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\CheckRoleBasedAuthorization;
use App\Models\ShortUrls;
use Illuminate\Support\Str;
class ShortUrlsController extends Controller
{
    use CheckRoleBasedAuthorization;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkRoleBasedAuthorization(['super admin', 'admin', 'member']);

        if(auth()->user()->hasRole('super admin')) {
            $shortUrls = ShortUrls::with('company')->get();
        } elseif(auth()->user()->hasRole('admin')) {
            $shortUrls = ShortUrls::with('company')
            ->where('companies_id', auth()->user()->companies_id)
            ->get();
        } elseif(auth()->user()->hasRole('member')) {
            $shortUrls = auth()->user()->shortUrls()->with('company')->get();
        }

        return view('superadmin.short-urls.index', compact('shortUrls'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkRoleBasedAuthorization(['admin', 'member']);

        return view('superadmin.short-urls.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkRoleBasedAuthorization(['admin', 'member']);

        $request->validate([
            'url' => 'required|url',
        ]);

        $code = $this->generateShortCode();

        $shortUrl = ShortUrls::create([
            'url' => $request->url,
            'companies_id' => auth()->user()->companies_id,
            'user_id' => auth()->user()->id,
            'short_url' => $code,
        ]);

        return redirect()->route('short-urls.index')->with('success', 'Short URL created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShortUrls $shortUrl)
    {
        $this->checkRoleBasedAuthorization(['admin', 'member']);

        $user = auth()->user();
        if ($user->hasRole('admin')) {
            if ((int) $shortUrl->companies_id !== (int) $user->companies_id) {
                abort(403);
            }
        } else {
            if ((int) $shortUrl->user_id !== (int) $user->id) {
                abort(403);
            }
        }

        $shortUrl->delete();

        return redirect()->route('short-urls.index')->with('success', 'Short URL deleted successfully');
    }

    public function generateShortCode(): string
    {
        $this->checkRoleBasedAuthorization(['admin', 'member']);

        $code = Str::random(6);

        $shortCodeExists = ShortUrls::where('short_url', $code)->exists();

        if($shortCodeExists) {
            return false;
        }

        return $code;
    }

    // This function is used to redirect to a publicly accessible url without authentication
    public function redirectToPublicLongUrl(string $shortUrl)
    {
        $longUrl = ShortUrls::where('short_url', $shortUrl)->first();

        if(!$longUrl) {
            return abort(404);
        }

        $longUrl->hits++;
        $longUrl->save();

        return redirect($longUrl->url);
    }
}
