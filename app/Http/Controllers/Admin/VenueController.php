<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Exception;
use Illuminate\Http\Request;

class VenueController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$search = $request->search;
		$venues = Venue::paginate(10);
		if (isset($search)) {
			$venues = Venue::where('name', 'LIKE', '%'.$search.'%')
				->orWhere('location', 'LIKE', '%'.$search.'%')
				->paginate(10);
		}
		return inertia('Admin/VenuesPage', [
			'venues' => $venues,
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		return inertia('Admin/Venues/CreateVenuePage');
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		$request->validate([
			'name' => ['required', 'string', 'max:250'],
			'location' => ['required', 'string', 'max:250'],
		]);

		try {
			Venue::create([
				'name' => $request->name,
				'location' => $request->location,
			]);

			return back()->with('success', 'Venue created successfully');
		} catch (Exception $e) {
			return back()->withErrors(['error' => $e->getMessage()]);
		}
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
		try {
			$venue = Venue::findOrFail($id);
			return inertia('Admin/Venues/EditVenuePage', [
				'venue' => $venue,
			]);
		} catch (Exception $e) {
			return back()->withErrors(['error' => $e->getMessage()]);
		}
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, string $id)
	{
		$venue = Venue::findOrFail($id);

		$request->validate([
			'name' => ['required', 'string', 'max:250'],
			'location' => ['required', 'string', 'max:250'],
		]);

		try {
			$venue->name = $request->name;
			$venue->location = $request->location;
			$venue->save();

			return back()->with('success', 'Venue updated successfully');
		} catch (Exception $e) {
			return back()->withErrors(['error' => $e->getMessage()]);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		try {
			$venue = Venue::findOrFail($id);
			$venue->delete();

			return back()->with('success', 'Venue deleted successfully.');
		} catch (Exception $e) {
			return back()->withErrors(['error' => 'Exception error']);
		}
	}
}


