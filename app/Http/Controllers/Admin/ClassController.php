<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Student;
use App\Models\StudentModule;
use Illuminate\Http\Request;

class ClassController extends Controller
{
	/**
	 * Display a listing of modules with department and student counts.
	 */
	public function index(Request $request)
	{
		$search = $request->get('search');

		$modulesQuery = Module::with(['department'])
			->withCount('students');

		if ($search) {
			$modulesQuery->where(function ($q) use ($search) {
				$q->where('name', 'LIKE', '%'.$search.'%')
					->orWhere('code', 'LIKE', '%'.$search.'%');
			});
		}

		$modules = $modulesQuery->paginate(10)->through(function (Module $module) {
			return [
				'id' => $module->id,
				'name' => $module->code . ' - ' . $module->name,
				'students_total' => $module->students_count,
				'department' => $module->department?->name,
			];
		});

		return inertia('Admin/ClassesPage', [
			'classes' => $modules,
			'filters' => [
				'search' => $search,
			],
		]);
	}

	/**
	 * Show students registered for a module.
	 */
	public function show(string $moduleId, Request $request)
	{
		$search = $request->get('search');

		$studentsQuery = Student::with(['department', 'user'])
			->whereHas('modules', function ($q) use ($moduleId) {
				$q->where('modules.id', $moduleId);
			});

		if ($search) {
			$studentsQuery->where(function ($q) use ($search) {
				$q->where('firstnames', 'LIKE', '%'.$search.'%')
					->orWhere('surname', 'LIKE', '%'.$search.'%')
					->orWhere('regnum', 'LIKE', '%'.$search.'%');
			});
		}

		$students = $studentsQuery->paginate(10);

		$module = Module::with('department')->findOrFail($moduleId);

		return inertia('Admin/ClassStudentsPage', [
			'module' => [
				'id' => $module->id,
				'name' => $module->code . ' - ' . $module->name,
				'department' => $module->department?->name,
			],
			'students' => $students,
			'filters' => [
				'search' => $search,
			],
		]);
	}

	/**
	 * Register all students to all modules in their department.
	 */
	public function registerAll()
	{
		Student::with('department')->chunk(200, function ($students) {
			foreach ($students as $student) {
				$modules = Module::where('department_id', $student->department_id)->pluck('id');
				foreach ($modules as $moduleId) {
					StudentModule::firstOrCreate([
						'student_id' => $student->id,
						'module_id' => $moduleId,
					]);
				}
			}
		});

		return back()->with('success', 'All students registered to their department modules.');
	}

	/**
	 * Deregister all students from all modules.
	 */
	public function deregisterAll()
	{
		StudentModule::query()->delete();

		return back()->with('success', 'All students deregistered from all modules.');
	}
}


