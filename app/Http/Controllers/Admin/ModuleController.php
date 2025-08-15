<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Module;
use Exception;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$search = $request->search;
		$modules = Module::with('department')->paginate(10);
		if (isset($search)) {
			$modules = Module::with('department')
				->where('name', 'LIKE', '%'.$search.'%')
				->orWhere('code', 'LIKE', '%'.$search.'%')
				->orWhereHas('department', function ($query) use ($search) {
					$query->where('name', 'LIKE', '%'.$search.'%');
				})
				->paginate(10);
		}
		return inertia('Admin/ModulesPage', [
			'modules' => $modules,
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		$departments = Department::all();
		return inertia('Admin/Modules/CreateModulePage', [
			'departments' => $departments,
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		$request->validate([
			'department_id' => ['required', 'integer', 'exists:departments,id'],
			'code' => ['required', 'string', 'max:250', 'unique:modules,code'],
			'name' => ['required', 'string', 'max:250'],
		]);

		try {
			Module::create([
				'department_id' => $request->department_id,
				'code' => $request->code,
				'name' => $request->name,
			]);

			return back()->with('success', 'Module created successfully');
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
			$module = Module::with('department')->findOrFail($id);
			$departments = Department::all();
			return inertia('Admin/Modules/EditModulePage', [
				'module' => $module,
				'departments' => $departments,
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
		$module = Module::findOrFail($id);

		$request->validate([
			'department_id' => ['required', 'integer', 'exists:departments,id'],
			'code' => ['required', 'string', 'max:250', 'unique:modules,code,' . $module->id],
			'name' => ['required', 'string', 'max:250'],
		]);

		try {
			$module->department_id = $request->department_id;
			$module->code = $request->code;
			$module->name = $request->name;
			$module->save();

			return back()->with('success', 'Module updated successfully');
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
			$module = Module::findOrFail($id);
			$module->delete();

			return back()->with('success', 'Module deleted successfully.');
		} catch (Exception $e) {
			return back()->withErrors(['error' => 'Exception error']);
		}
	}
}


