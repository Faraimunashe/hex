<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use App\Models\Module;
use App\Models\Venue;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExaminationController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$search = $request->search;
		$examinations = Examination::with(['module', 'venue'])->paginate(10);
		if (isset($search)) {
			$examinations = Examination::with(['module', 'venue'])
				->where('exam_date', 'LIKE', '%'.$search.'%')
				->orWhere('start_time', 'LIKE', '%'.$search.'%')
				->orWhere('end_time', 'LIKE', '%'.$search.'%')
				->orWhereHas('module', function ($q) use ($search) {
					$q->where('name', 'LIKE', '%'.$search.'%')
						->orWhere('code', 'LIKE', '%'.$search.'%');
				})
				->orWhereHas('venue', function ($q) use ($search) {
					$q->where('name', 'LIKE', '%'.$search.'%');
				})
				->paginate(10);
		}
		return inertia('Admin/ExaminationsPage', [
			'examinations' => $examinations,
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		$modules = Module::all();
		$venues = Venue::all();
		return inertia('Admin/Examinations/CreateExaminationPage', [
			'modules' => $modules,
			'venues' => $venues,
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		$request->validate([
			'module_id' => [
				'required', 'integer', 'exists:modules,id',
				Rule::unique('examinations')->where(function ($query) use ($request) {
					return $query->where('exam_date', $request->exam_date);
				}),
			],
			'venue_id' => ['required', 'integer', 'exists:venues,id'],
			'exam_date' => ['required', 'date'],
			'start_time' => ['required', 'date_format:H:i'],
			'end_time' => ['required', 'date_format:H:i'],
		]);

		try {
			Examination::create([
				'module_id' => $request->module_id,
				'venue_id' => $request->venue_id,
				'exam_date' => $request->exam_date,
				'start_time' => $request->start_time,
				'end_time' => $request->end_time,
			]);

			return back()->with('success', 'Examination created successfully');
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
			$examination = Examination::with(['module', 'venue'])->findOrFail($id);
			$modules = Module::all();
			$venues = Venue::all();
			return inertia('Admin/Examinations/EditExaminationPage', [
				'examination' => $examination,
				'modules' => $modules,
				'venues' => $venues,
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
		$examination = Examination::findOrFail($id);

		$request->validate([
			'module_id' => [
				'required', 'integer', 'exists:modules,id',
				Rule::unique('examinations')->ignore($examination->id)->where(function ($query) use ($request) {
					return $query->where('exam_date', $request->exam_date);
				}),
			],
			'venue_id' => ['required', 'integer', 'exists:venues,id'],
			'exam_date' => ['required', 'date'],
			'start_time' => ['required', 'date_format:H:i'],
			'end_time' => ['required', 'date_format:H:i'],
		]);

		try {
			$examination->module_id = $request->module_id;
			$examination->venue_id = $request->venue_id;
			$examination->exam_date = $request->exam_date;
			$examination->start_time = $request->start_time;
			$examination->end_time = $request->end_time;
			$examination->save();

			return back()->with('success', 'Examination updated successfully');
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
			$examination = Examination::findOrFail($id);
			$examination->delete();

			return back()->with('success', 'Examination deleted successfully.');
		} catch (Exception $e) {
			return back()->withErrors(['error' => 'Exception error']);
		}
	}
}


