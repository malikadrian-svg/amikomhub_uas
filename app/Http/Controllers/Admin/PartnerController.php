<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter');

        $query = Partner::query();

        if ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }

        switch ($filter) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $partners = $query->paginate(4);

        return view('admin.partners.index', compact('partners', 'search', 'filter'));
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'required|url|max:255',
        ]);

        Partner::create($data);

        return redirect()->route('admin.partners.index')->with('success', 'Data Partner berhasil ditambahkan.');
    }

    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(\Illuminate\Http\Request $request, Partner $partner)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'required|url|max:255',
        ]);

        $partner->update($data);

        return redirect()->route('admin.partners.index')->with('success', 'Data Partner berhasil diperbarui.');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Data Partner berhasil dihapus.');
    }
}
