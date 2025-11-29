<?php

namespace App\Http\Controllers;

use App\Models\Citizen;
use App\Models\State;
use App\Models\Block;
use App\Models\Panchayat;
use App\Models\Village;
use Illuminate\Http\Request;

class CitizenController extends Controller
{
    // Show all citizens
    public function index()
    {
        $citizens = Citizen::with(['village.panchayat.block.state'])
            ->orderBy('CitizenId', 'desc')
            ->get();
            
        return view('citizens.index', compact('citizens'));
    }

    // Show create form (Homepage)
    public function create()
    {
        $states = State::all();
        $allBlocks = Block::all();
        $allPanchayats = Panchayat::all();
        $allVillages = Village::all();
        
        return view('citizens.create', compact('states', 'allBlocks', 'allPanchayats', 'allVillages'));
    }

    // Store new citizen
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'gender' => 'required|in:Male,Female,Other',
            'phone' => 'required|string|max:15|unique:citizens,CitizenPhone',
            'email' => 'nullable|email|max:100|unique:citizens,CitizenEmail',
            'village_id' => 'required|exists:villages,VillageId'
        ]);

        Citizen::create([
            'CitizenName' => $validated['name'],
            'CitizenGender' => $validated['gender'],
            'CitizenPhone' => $validated['phone'],
            'CitizenEmail' => $validated['email'],
            'VillageId' => $validated['village_id']
        ]);

        return redirect()->route('citizens.index')
            ->with('success', 'Citizen registered successfully!');
    }

    // Show edit form - UPDATED: Simplified without API dependencies
    public function edit($id)
    {
        $citizen = Citizen::with(['village.panchayat.block.state'])->findOrFail($id);
        $states = State::all();
        
        // Preload all location data for JavaScript filtering
        $allBlocks = Block::all();
        $allPanchayats = Panchayat::all();
        $allVillages = Village::all();
        
        return view('citizens.edit', compact('citizen', 'states', 'allBlocks', 'allPanchayats', 'allVillages'));
    }

    // Update citizen
    public function update(Request $request, $id)
    {
        $citizen = Citizen::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'gender' => 'required|in:Male,Female,Other',
            'phone' => 'required|string|max:15|unique:citizens,CitizenPhone,' . $citizen->CitizenId . ',CitizenId',
            'email' => 'nullable|email|max:100|unique:citizens,CitizenEmail,' . $citizen->CitizenId . ',CitizenId',
            'village_id' => 'required|exists:villages,VillageId'
        ]);

        $citizen->update([
            'CitizenName' => $validated['name'],
            'CitizenGender' => $validated['gender'],
            'CitizenPhone' => $validated['phone'],
            'CitizenEmail' => $validated['email'],
            'VillageId' => $validated['village_id']
        ]);

        return redirect()->route('citizens.index')
            ->with('success', 'Citizen updated successfully!');
    }

    // Delete citizen
    public function destroy($id)
    {
        $citizen = Citizen::findOrFail($id);
        $citizen->delete();
        
        return redirect()->route('citizens.index')
            ->with('success', 'Citizen deleted successfully!');
    }
}