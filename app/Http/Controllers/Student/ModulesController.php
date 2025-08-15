<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Module;
use App\Models\Student;
use App\Models\StudentModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModulesController extends Controller
{
	/**
	 * Display a listing of the student's modules.
	 */
	public function index(Request $request)
	{
		$search = $request->get('search');
		$userId = Auth::id();

		$student = Student::where('user_id', $userId)->firstOrFail();
		$moduleIds = StudentModule::where('student_id', $student->id)->pluck('module_id');

		$query = Module::with('department')
			->whereIn('id', $moduleIds)
			->orderBy('code');

		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->where('code', 'LIKE', '%'.$search.'%')
					->orWhere('name', 'LIKE', '%'.$search.'%');
			});
		}

		$modules = $query->paginate(10);

		return inertia('Student/ModulesPage', [
			'modules' => $modules,
			'filters' => ['search' => $search],
		]);
	}

	/**
	 * Display the specified module details and fellow students.
	 */
	public function show(string $moduleId)
	{
		$userId = Auth::id();
		$student = Student::where('user_id', $userId)->firstOrFail();
		$allowedModuleIds = StudentModule::where('student_id', $student->id)->pluck('module_id');

		$module = Module::with('department')->findOrFail($moduleId);
		abort_unless($allowedModuleIds->contains($module->id), 403);

		$fellowStudents = StudentModule::with(['student.user', 'student.department'])
			->where('module_id', $module->id)
			->paginate(10);

		return inertia('Student/ShowModulePage', [
			'module' => $module,
			'students' => $fellowStudents,
		]);
	}

	// Resource placeholders (not used for create/store/edit/update/destroy in this context)
	public function create() {}
	public function store(Request $request) {}
	public function edit(string $id) {}
	public function update(Request $request, string $id) {}
	public function destroy(string $id) {}
}


