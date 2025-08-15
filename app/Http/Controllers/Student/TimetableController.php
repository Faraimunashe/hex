<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use App\Models\Invigilation;
use App\Models\Module;
use App\Models\Student;
use App\Models\StudentModule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TimetableController extends Controller
{
    /**
     * Display a calendar of upcoming exams for the logged-in student
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $userId = Auth::id();

        $student = Student::where('user_id', $userId)->firstOrFail();
        $moduleIds = StudentModule::where('student_id', $student->id)->pluck('module_id');

        $examsQuery = Examination::with(['module.department', 'venue', 'invigilations.user'])
            ->whereIn('module_id', $moduleIds)
            ->whereDate('exam_date', '>=', Carbon::today())
            ->orderBy('exam_date')
            ->orderBy('start_time');

        if ($search) {
            $examsQuery->whereHas('module', function ($q) use ($search) {
                $q->where('code', 'LIKE', '%'.$search.'%')
                    ->orWhere('name', 'LIKE', '%'.$search.'%');
            });
        }

        $exams = $examsQuery->get();

        $events = $exams->map(function (Examination $exam) {
            return [
                'id' => $exam->id,
                'title' => ($exam->module?->code).' - '.($exam->module?->name),
                'date' => $exam->exam_date,
                'start_time' => $exam->start_time,
                'end_time' => $exam->end_time,
                'venue' => $exam->venue?->name,
            ];
        });

        return inertia('Student/TimetablePage', [
            'events' => $events,
            'filters' => ['search' => $search],
        ]);
    }

    /**
     * Show exam details and invigilators for a specific exam
     */
    public function show(string $examId)
    {
        $userId = Auth::id();
        $student = Student::where('user_id', $userId)->firstOrFail();
        $moduleIds = StudentModule::where('student_id', $student->id)->pluck('module_id');

        $exam = Examination::with(['module', 'venue', 'invigilations.user'])->findOrFail($examId);
        abort_unless($moduleIds->contains($exam->module_id), 403);

        // Dummy exam rules/guidelines
        $rules = [
            'Arrive at least 30 minutes before the exam start time.',
            'Bring your student ID and required materials.',
            'No mobile phones or smart devices are allowed in the exam room.',
            'Follow invigilator instructions at all times.',
        ];

        return inertia('Student/ShowTimetablePage', [
            'exam' => $exam,
            'rules' => $rules,
        ]);
    }

    /**
     * Export upcoming exams for the student as PDF
     */
    public function exportPdf(Request $request)
    {
        $userId = Auth::id();
        $student = Student::where('user_id', $userId)->firstOrFail();
        $moduleIds = StudentModule::where('student_id', $student->id)->pluck('module_id');

        $exams = Examination::with(['module.department', 'venue', 'invigilations.user'])
            ->whereIn('module_id', $moduleIds)
            ->whereDate('exam_date', '>=', Carbon::today())
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get();

        $pdf = Pdf::loadView('student.timetable_pdf', compact('exams'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('Student_Timetable.pdf');
    }
}


