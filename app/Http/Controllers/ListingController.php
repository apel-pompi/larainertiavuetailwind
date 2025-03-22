<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


class ListingController extends Controller
{
    public $user;

    public function __construct()
    {
        new Middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // if (is_null($this->user) || !$this->user->can('listing.index')) {
        //     abort(403, 'Sorry !! You are Unauthorized person !');
        // }

       
        $listings = Listing::with('user')
            ->filter(request(['search', 'user_id', 'tag']))
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return Inertia::render('Listing/Index', [
            'listings' => $listings,
            'searchTerm' => $request->search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Listing/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $newTags = explode(',' ,  $request->tags);
        // $newTags = array_map('trim', $newTags);
        // $newTags = array_filter($newTags);
        // $newTags = array_unique($newTags);
        // $newTags = implode(',', $newTags);

        $fields = $request->validate([
            'title' => ['required', 'max:255'],
            'desc' => ['required'],
            'tags' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'link' => ['nullable', 'url'],
            'image' => ['nullable', 'file', 'max:3072', 'mimes:jpeg,jpg,png,webp']
        ]);

        if ($request->hasFile('image')) {
            $fields['image'] = Storage::disk('public')->put('images/lisiting', $request->image);
        }
        $fields['tags'] = implode(',', array_unique(array_filter(array_map('trim', explode(',',  $request->tags)))));

        $request->user()->listings()->create($fields);

        return redirect()->route('dashboard')->with('status', 'Listing created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Listing $listing)
    {
        return Inertia::render('Listing/Show', [
            'listing' => $listing,
            'user' => $listing->user->only(['name', 'id'])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Listing $listing)
    {
        return Inertia::render('Listing/Edit',[
            'listing' => $listing,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Listing $listing)
    {
        $fields = $request->validate([
            'title' => ['required', 'max:255'],
            'desc' => ['required'],
            'tags' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'link' => ['nullable', 'url'],
            'image' => ['nullable', 'file', 'max:3072', 'mimes:jpeg,jpg,png,webp']
        ]);

        if ($request->hasFile('image')) {
            if($listing->image){
                Storage::disk('public')->delete($listing->image);
            }
            $fields['image'] = Storage::disk('public')->put('images/lisiting', $request->image);
        }else{
            $fields['image'] =$listing->image;
        }

        $fields['tags'] = implode(',', array_unique(array_filter(array_map('trim', explode(',',  $request->tags)))));

        $listing->update($fields);

        return redirect()->route('dashboard')->with('status', 'Listing updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Listing $listing)
    {
        if($listing->image){
            Storage::disk('public')->delete($listing->image);
        }
        $listing->delete();
        return redirect()->route('dashboard')->with('status', 'Listing delete successfully.');
    }
}
