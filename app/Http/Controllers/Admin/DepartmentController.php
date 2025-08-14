<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Exception;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $departments = Department::with(['modules', 'lecturers'])->paginate(10);
        if(isset($search))
        {
            $departments = Department::with(['modules', 'lecturers'])->where('name', 'LIKE', '%'.$search.'%')
            ->paginate(10);
        }
        return inertia('Admin/DepartmentsPage',[
            'departments' => $departments
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Admin/Departments/CreateDepartmentPage');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:250', 'unique:departments'],
        ]);

        try {
            Department::create([
                'name' => $request->name,
            ]);

            return back()->with('success', 'Department created successfully');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $department = Department::with(['modules', 'lecturers'])->findOrFail($id);
            return inertia('Admin/Departments/ShowDepartmentPage', [
                'department' => $department
            ]);
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try{
            $department = Department::findOrFail($id);
            return inertia('Admin/Departments/EditDepartmentPage', [
                'department' => $department
            ]);
        }catch(Exception $e)
        {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $department = Department::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:250', 'unique:departments,name,' . $department->id],
        ]);

        try {
            $department->name = $request->name;
            $department->save();

            return back()->with('success', 'Department updated successfully');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $department = Department::findOrFail($id);
            $department->delete();

            return back()->with('success', 'Department deleted successfully.');
        }catch(Exception $e)
        {
            return back()->withErrors(['error' => 'Exception error']);
        }
    }
}
