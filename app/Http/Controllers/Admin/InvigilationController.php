<?php

namespace App\Http\Controllers\Admin;

use App\Events\ExamNotification;
use App\Events\InvigilatorsAllocated;
use App\Http\Controllers\Controller;
use App\Models\Examination;
use App\Models\Invigilation;
use App\Models\Lecturer;
use App\Models\Module;
use App\Models\Student;
use App\Models\StudentModule;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class InvigilationController extends Controller
{
	/**
	 * List upcoming examinations with details and actions
	 */
	public function index(Request $request)
	{
		$search = $request->get('search');

		$examsQuery = Examination::with([
			'module.department',
			'venue',
			'invigilations.user',
			'module.students',
		])
			->whereDate('exam_date', '>=', Carbon::today())
			->orderBy('exam_date', 'asc')
			->orderBy('start_time', 'asc');

		if ($search) {
			$examsQuery->whereHas('module', function ($q) use ($search) {
				$q->where('code', 'LIKE', '%'.$search.'%')
					->orWhere('name', 'LIKE', '%'.$search.'%');
			});
		}

		$exams = $examsQuery->paginate(10)->through(function (Examination $exam) {
			return [
				'id' => $exam->id,
				'module_code' => $exam->module?->code,
				'module_name' => $exam->module?->name,
				'exam_date' => $exam->exam_date,
				'start_time' => $exam->start_time,
				'end_time' => $exam->end_time,
				'student_count' => $exam->module?->students?->count() ?? 0,
				'invigilators' => $exam->invigilations->map(fn($i) => $i->user?->name)->filter()->values(),
			];
		});

		return inertia('Admin/InvigilationPage', [
			'examinations' => $exams,
			'filters' => ['search' => $search],
		]);
	}

	/**
	 * Show students for a specific examination (by module)
	 */
	public function show(string $examId, Request $request)
	{
		$exam = Examination::with('module')->findOrFail($examId);
		$moduleId = $exam->module_id;
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

		return inertia('Admin/InvigilationStudentsPage', [
			'exam' => [
				'id' => $exam->id,
				'module_code' => $exam->module?->code,
				'module_name' => $exam->module?->name,
				'exam_date' => $exam->exam_date,
			],
			'students' => $students,
			'filters' => ['search' => $search],
		]);
	}

	/**
	 * Allocate invigilators to upcoming exams with constraints
	 */
	public function allocateInvigilators()
	{
		$upcomingExams = Examination::with(['module.department', 'invigilations'])
			->whereDate('exam_date', '>=', Carbon::today())
			->orderBy('exam_date')
			->get();

		foreach ($upcomingExams as $exam) {
			$module = $exam->module;
			if (!$module) {
				continue;
			}

			// Eligible lecturers: not from the module's department
			$candidateLecturers = Lecturer::with('user')
				->where('department_id', '!=', $module->department_id)
				->get();

			// Exclude lecturers already allocated at the same date and time
			$busyUserIds = Invigilation::whereHas('examination', function ($q) use ($exam) {
				$q->whereDate('exam_date', $exam->exam_date)
					->where('start_time', $exam->start_time)
					->where('end_time', $exam->end_time);
			})->pluck('user_id')->toArray();

			$available = $candidateLecturers->filter(function (Lecturer $lecturer) use ($busyUserIds) {
				return $lecturer->user && !in_array($lecturer->user->id, $busyUserIds, true);
			})->values();

			if ($available->isEmpty()) {
				continue; // Skip if none available
			}

			$selected = $available->shuffle()->take(3); // up to 3
			if ($selected->isEmpty()) {
				continue;
			}

			$assignedUsers = [];
			foreach ($selected as $lecturer) {
				$inv = Invigilation::firstOrCreate([
					'examination_id' => $exam->id,
					'user_id' => $lecturer->user->id,
				]);
				$assignedUsers[] = $lecturer->user;
			}

			// Dispatch event
			event(new InvigilatorsAllocated($exam, collect($assignedUsers)));
		}

		return back()->with('success', 'Invigilators allocation attempted for all upcoming exams.');
	}

	/**
	 * Notify a specific exam's invigilators and students
	 */
	public function notify(string $examId)
	{
		$exam = Examination::with(['module.students.user', 'invigilations.user', 'venue'])
			->findOrFail($examId);

		event(new ExamNotification($exam));

		return back()->with('success', 'Notifications sent for the selected exam.');
	}

	/**
	 * Notify all upcoming exams
	 */
	public function notifyAll()
	{
		$exams = Examination::with(['module.students.user', 'invigilations.user', 'venue'])
			->whereDate('exam_date', '>=', Carbon::today())
			->get();

		foreach ($exams as $exam) {
			event(new ExamNotification($exam));
		}

		return back()->with('success', 'Notifications sent for all upcoming exams.');
	}
}


