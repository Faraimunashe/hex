<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Examination;
use App\Models\Invigilation;
use App\Models\Module;
use App\Models\Report;
use App\Models\Student;
use App\Models\StudentModule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TimetableController extends Controller
{
	/**
	 * Display a calendar of upcoming exams for the logged-in lecturer
	 */
	public function index(Request $request)
	{
		$search = $request->get('search');
		$userId = Auth::id();

		$examsQuery = Examination::with(['module.department', 'venue', 'invigilations.user'])
			->whereDate('exam_date', '>=', Carbon::today())
			->whereHas('invigilations', function ($q) use ($userId) {
				$q->where('user_id', $userId);
			})
			->orderBy('exam_date')
			->orderBy('start_time');

		if ($search) {
			$examsQuery->whereHas('module', function ($q) use ($search) {
				$q->where('code', 'LIKE', '%'.$search.'%')
					->orWhere('name', 'LIKE', '%'.$search.'%');
			});
		}

		$exams = $examsQuery->get();

		// Map to calendar-friendly structure
		$calendarEvents = $exams->map(function (Examination $exam) {
			return [
				'id' => $exam->id,
				'title' => ($exam->module?->code).' - '.($exam->module?->name),
				'date' => $exam->exam_date,
				'start_time' => $exam->start_time,
				'end_time' => $exam->end_time,
				'venue' => $exam->venue?->name,
			];
		});

		return inertia('Lecturer/TimetablePage', [
			'events' => $calendarEvents,
			'filters' => ['search' => $search],
		]);
	}

	/**
	 * Export upcoming exams allocated to the lecturer as PDF
	 */
	public function exportPdf(Request $request)
	{
		$userId = Auth::id();

		$exams = Examination::with(['module.department', 'venue', 'invigilations.user'])
			->whereDate('exam_date', '>=', Carbon::today())
			->whereHas('invigilations', function ($q) use ($userId) {
				$q->where('user_id', $userId);
			})
			->orderBy('exam_date')
			->orderBy('start_time')
			->get();

		$pdf = Pdf::loadView('lecturer.timetable_pdf', compact('exams'))
			->setPaper('a4', 'landscape');

		return $pdf->download('Lecturer_Timetable.pdf');
	}

	/**
	 * Show exam details, students, and invigilators
	 */
	public function show(string $examId, Request $request)
	{
		$exam = Examination::with(['module', 'venue', 'invigilations.user'])->findOrFail($examId);

		$studentsQuery = Student::with(['department', 'user'])
			->whereHas('modules', function ($q) use ($exam) {
				$q->where('modules.id', $exam->module_id);
			});

		$search = $request->get('search');
		if ($search) {
			$studentsQuery->where(function ($q) use ($search) {
				$q->where('firstnames', 'LIKE', '%'.$search.'%')
					->orWhere('surname', 'LIKE', '%'.$search.'%')
					->orWhere('regnum', 'LIKE', '%'.$search.'%');
			});
		}

		$students = $studentsQuery->paginate(10);

		$attendedStudentIds = Attendance::where('examination_id', $examId)->pluck('student_id');

		return inertia('Lecturer/ShowTimetablePage', [
			'exam' => $exam,
			'students' => $students,
			'attended' => $attendedStudentIds,
		]);
	}

	public function markAttendance(string $examId, string $studentId)
	{
		$exam = Examination::findOrFail($examId);
		Attendance::firstOrCreate([
			'examination_id' => $exam->id,
			'student_id' => $studentId,
		]);
		return back()->with('success', 'Attendance marked');
	}

	public function unmarkAttendance(string $examId, string $studentId)
	{
		$exam = Examination::findOrFail($examId);
		Attendance::where('examination_id', $exam->id)->where('student_id', $studentId)->delete();
		return back()->with('success', 'Attendance unmarked');
	}

	/**
	 * Reports index and create for a specific exam
	 */
	public function reports(string $examId, Request $request)
	{
		$exam = Examination::with(['module', 'venue'])->findOrFail($examId);
		$userId = Auth::id();

		$reports = Report::where('examination_id', $examId)
			->where('user_id', $userId)
			->latest()
			->paginate(10);

		return inertia('Lecturer/ReportsPage', [
			'exam' => $exam,
			'reports' => $reports,
		]);
	}

	public function storeReport(string $examId, Request $request)
	{
		$request->validate([
			'message' => ['required', 'string', 'max:2000'],
		]);

		$exam = Examination::findOrFail($examId);
		$userId = Auth::id();

		Report::create([
			'examination_id' => $exam->id,
			'user_id' => $userId,
			'comment' => $request->message,
		]);

		return back()->with('success', 'Report submitted');
	}
}


