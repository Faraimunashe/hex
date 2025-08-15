<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Lecturer;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LecturerController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$search = $request->search;
		$lecturers = Lecturer::with(['user', 'department'])->paginate(10);
		if (isset($search)) {
			$lecturers = Lecturer::with(['user', 'department'])
				->where('firstnames', 'LIKE', '%'.$search.'%')
				->orWhere('surname', 'LIKE', '%'.$search.'%')
				->orWhere('gender', 'LIKE', '%'.$search.'%')
				->orWhereHas('user', function ($q) use ($search) {
					$q->where('email', 'LIKE', '%'.$search.'%')
						->orWhere('name', 'LIKE', '%'.$search.'%');
				})
				->orWhereHas('department', function ($q) use ($search) {
					$q->where('name', 'LIKE', '%'.$search.'%');
				})
				->paginate(10);
		}
		return inertia('Admin/LecturersPage', [
			'lecturers' => $lecturers,
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		$departments = Department::all();
		return inertia('Admin/Lecturers/CreateLecturerPage', [
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
			'firstnames' => ['required', 'string', 'max:250'],
			'surname' => ['required', 'string', 'max:250'],
			'gender' => ['required', 'in:Male,Female'],
			'email' => ['required', 'email', 'max:250', 'unique:users,email'],
			'password' => ['required', 'min:8'],
		]);

		try {
			$user = User::create([
				'name' => $request->firstnames . ' ' . $request->surname,
				'email' => $request->email,
				'password' => Hash::make($request->password),
			]);

			$user->addRole('lecturer');

			Lecturer::create([
				'user_id' => $user->id,
				'department_id' => $request->department_id,
				'firstnames' => $request->firstnames,
				'surname' => $request->surname,
				'gender' => $request->gender,
			]);

			return back()->with('success', 'Lecturer created successfully');
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
			$lecturer = Lecturer::with(['user', 'department'])->findOrFail($id);
			$departments = Department::all();
			return inertia('Admin/Lecturers/EditLecturerPage', [
				'lecturer' => $lecturer,
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
		$lecturer = Lecturer::with('user')->findOrFail($id);

		$request->validate([
			'department_id' => ['required', 'integer', 'exists:departments,id'],
			'firstnames' => ['required', 'string', 'max:250'],
			'surname' => ['required', 'string', 'max:250'],
			'gender' => ['required', 'in:Male,Female'],
			'email' => ['required', 'email', 'max:250', 'unique:users,email,' . $lecturer->user_id],
			'password' => ['nullable', 'min:8'],
		]);

		try {
			$lecturer->department_id = $request->department_id;
			$lecturer->firstnames = $request->firstnames;
			$lecturer->surname = $request->surname;
			$lecturer->gender = $request->gender;
			$lecturer->save();

			$user = $lecturer->user;
			$user->name = $request->firstnames . ' ' . $request->surname;
			$user->email = $request->email;
			if ($request->filled('password')) {
				$user->password = Hash::make($request->password);
			}
			$user->save();

			return back()->with('success', 'Lecturer updated successfully');
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
			$lecturer = Lecturer::findOrFail($id);
			$lecturer->delete();

			return back()->with('success', 'Lecturer deleted successfully.');
		} catch (Exception $e) {
			return back()->withErrors(['error' => 'Exception error']);
		}
	}
}


