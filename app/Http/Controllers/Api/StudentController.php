<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{

    public function index()
    {

        $students = DB::table('students')
            ->select('id', 'firstname', 'surname', 'lastname', 'birthdate', 'group')
            ->where('user_id', auth()->user()->id)
            ->get();
        return response()->json(['students' => $students]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "firstname" => "required|string|min:2|max:100",
            "surname" => "required|string|min:2|max:100",
            "lastname" => "required|string|min:2|max:100",
            'birthdate' => 'required|date',
            "group" => "required|string|min:3|max:20"
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $student = Student::create([
            "firstname" => $request->firstname,
            "surname" => $request->surname,
            "lastname" => $request->lastname,
            "birthdate" => $request->birthdate,
            "group" => $request->group,
            "user_id" => auth()->user()->id
        ]);
        return response()->json([
            "msg" => "Student Created Successfully",
            "student" => $student
        ]);
    }

    public function show($id)
    {
        $student = DB::table('students')->select('id', 'firstname', 'surname', 'lastname', 'birthdate', 'group')
            ->where('id', $id)
            ->get();
        return view('students.edit', ['student' => $student]);
    }

    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        $validator = Validator::make($request->all(), [
            "firstname" => "required|string|min:2|max:100",
            "surname" => "required|string|min:2|max:100",
            "lastname" => "required|string|min:2|max:100",
            'birthdate' => 'required|date',
            "group" => "required|string|min:3|max:20"
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $student->update([
            "firstname" => $request->firstname,
            "surname" => $request->surname,
            "lastname" => $request->lastname,
            "birthdate" => $request->birthdate,
            "group" => $request->group,
        ]);

        return response()->json(["result" => "Student Updated"]);
    }

    public function destroy($id)
    {
        Student::where('id', $id)->delete();

        return response()->json(["result" => "Student Deleted"]);
    }

}
