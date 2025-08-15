<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\VenueController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\ExaminationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LecturerController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\InvigilationController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthenticationController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::resource('profile', ProfileController::class);
});


Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboards', [DashboardController::class, 'index'])->name('dashboards');

    //CRUD users
    Route::resource('users', UserController::class);

    //CRUD departments
    Route::resource('departments', DepartmentController::class);

    //CRUD venues
    Route::resource('venues', VenueController::class);

    //CRUD modules
    Route::resource('modules', ModuleController::class);

    //CRUD examinations
    Route::resource('examinations', ExaminationController::class);

    //CRUD lecturers
    Route::resource('lecturers', LecturerController::class);

    //CRUD students
    Route::resource('students', StudentController::class);

    // Classes management
    Route::get('classes', [ClassController::class, 'index'])->name('classes.index');
    Route::get('classes/{module}', [ClassController::class, 'show'])->name('classes.show');
    Route::post('classes/register-all', [ClassController::class, 'registerAll'])->name('classes.registerAll');
    Route::delete('classes/deregister-all', [ClassController::class, 'deregisterAll'])->name('classes.deregisterAll');

    // Invigilation management
    Route::get('invigilations', [InvigilationController::class, 'index'])->name('invigilations.index');
    Route::get('invigilations/{exam}', [InvigilationController::class, 'show'])->name('invigilations.show');
    Route::post('invigilations/allocate', [InvigilationController::class, 'allocateInvigilators'])->name('invigilations.allocate');
    Route::post('invigilations/{exam}/notify', [InvigilationController::class, 'notify'])->name('invigilations.notify');
    Route::post('invigilations/notify-all', [InvigilationController::class, 'notifyAll'])->name('invigilations.notifyAll');
});


Route::prefix('lecturer')->middleware(['auth', 'role:lecturer'])->group(function () {
    Route::resource('timetables', App\Http\Controllers\Lecturer\TimetableController::class);
    // Reports sub-routes
    Route::get('timetable/{exam}/reports', [App\Http\Controllers\Lecturer\TimetableController::class, 'reports']);
    Route::post('timetable/{exam}/reports', [App\Http\Controllers\Lecturer\TimetableController::class, 'storeReport']);
    // Attendance
    Route::post('timetable/{exam}/attendance/{student}', [App\Http\Controllers\Lecturer\TimetableController::class, 'markAttendance']);
    Route::delete('timetable/{exam}/attendance/{student}', [App\Http\Controllers\Lecturer\TimetableController::class, 'unmarkAttendance']);
    // PDF export
    Route::get('timetable/pdf', [App\Http\Controllers\Lecturer\TimetableController::class, 'exportPdf'])->name('lecturer.timetable.pdf');
});


Route::prefix('student')->middleware(['auth', 'role:student'])->group(function () {
    Route::resource('timetable', App\Http\Controllers\Student\TimetableController::class);
    // PDF export
    Route::get('timetables/pdf', [App\Http\Controllers\Student\TimetableController::class, 'exportPdf'])->name('student.timetable.pdf');
    // Modules
    Route::resource('modules', App\Http\Controllers\Student\ModulesController::class);
});
