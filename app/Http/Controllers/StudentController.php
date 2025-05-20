<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StudentFormRequest;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class StudentController extends \Illuminate\Routing\Controller
{
    use \App\Traits\UserPhotoFileStorage;
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Student::class);
    }

    public function index(Request $request): View
    {
        $courseOptions = Course::orderBy('type')->orderBy('name')->get()->pluck('fullName', 'abbreviation')->toArray();
        $courseOptions = array_merge([null => 'Any course'], $courseOptions);

        $filterByCourse = $request->query('course');
        $filterByName = $request->query('name');
        $studentsQuery = Student::query();
        if ($filterByCourse !== null) {
            $studentsQuery->where('course', $filterByCourse);
        }
        // Next 3 lines are required when sorting by name:
        // ->join is necessary so that we have access to the users.name - to be able to order by "users.name"
        // ->select avoids bringing to many fields (that may conflict with each other)
        $studentsQuery
            ->join('users', 'users.id', '=', 'students.user_id')
            ->select( 'students.*')
            ->orderBy('users.name');

        // Since we are joining students and users, we can simplify the code to search by name
        if ($filterByName !== null) {
            $studentsQuery
                ->where('users.type', 'S')
                ->where('users.name', 'like', "%$filterByName%");
        }
        // Next line were used to filter by name, when there were no join clauses
        // if ($filterByName !== null) {
        //     $usersIds = User::where('type', 'S')
        //         ->where('name', 'like', "%$filterByName%")
        //         ->pluck('id')
        //         ->toArray();
        //     $studentsIds = Student::whereIntegerInRaw('user_id', $usersIds)
        //         ->pluck('id')
        //         ->toArray();
        //     $studentsQuery->whereIntegerInRaw('students.id', $studentsIds);
        // }

        $students = $studentsQuery
            ->with('user', 'courseRef', 'disciplines')
            ->paginate(20)
            ->withQueryString();

        return view(
            'students.index',
            compact('students', 'courseOptions', 'filterByCourse', 'filterByName')
        );
    }

    public function create(): View
    {
        $newStudent = new Student();
        // Next 2 lines ensure that the expression $newStudent->user->name is valid
        $newUser = new User();
        $newUser->type= 'S';
        $newStudent->user = $newUser;
        $newStudent->course = 'EI';
        return view('students.create')
            ->with('student', $newStudent);
    }

    public function store(StudentFormRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        $newStudent = DB::transaction(function () use ($validatedData, $request) {
            $newUser = new User();
            $newUser->type = 'S';
            $newUser->name = $validatedData['name'];
            $newUser->email = $validatedData['email'];
            // Student is never an administrator
            $newUser->admin = 0;
            $newUser->gender = $validatedData['gender'];
            // Initial password is always 123
            $newUser->password = bcrypt('123');
            $newUser->save();
            $newStudent = new Student();
            $newStudent->user_id = $newUser->id;
            $newStudent->course = $validatedData['course'];
            $newStudent->number = $validatedData['number'];
            $newStudent->save();
            $newStudent->user->sendEmailVerificationNotification();
            // File store is the last thing to execute!
            // Files do not rollback, so the probability of having a pending file 
            // (not referenced by any user) is reduced by being the last operation
            $this->storeUserPhoto($request->photo_file, $newUser);
            return $newStudent;
        });
        $url = route('students.show', ['student' => $newStudent]);
        $htmlMessage = "Student <a href='$url'><u>{$newStudent->user->name}</u></a> has been created successfully!";
        return redirect()->route('students.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function edit(Student $student): View
    {
        return view('students.edit')
            ->with('student', $student);
    }

    public function update(StudentFormRequest $request, Student $student): RedirectResponse
    {
        $validatedData = $request->validated();
        $student = DB::transaction(function () use ($validatedData, $student, $request) {
            $student->course = $validatedData['course'];
            $student->number = $validatedData['number'];
            $student->save();
            $student->user->type = 'S';
            $student->user->name = $validatedData['name'];
            $student->user->email = $validatedData['email'];
            // Student is never an administrator
            $student->user->admin = 0;
            $student->user->gender = $validatedData['gender'];
            $student->user->save();
            if ($request->photo_file) {
                $this->deleteUserPhoto($student->user);
                $this->storeUserPhoto($request->photo_file, $student->user);
            }
            return $student;
        });
        $url = route('students.show', ['student' => $student]);
        $htmlMessage = "Student <a href='$url'><u>{$student->user->name}</u></a> has been updated successfully!";
        return redirect()->route('students.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function destroy(Student $student): RedirectResponse
    {
        try {
            $url = route('students.show', ['student' => $student]);
            $totalStudentsDisciplines = DB::scalar(
                'select count(*) from students_disciplines where students_id = ?',
                [$student->id]
            );
            if ($totalStudentsDisciplines == 0) {
                DB::transaction(function () use ($student) {
                    $fileName = $student->user->photo_url;
                    $student->delete();
                    $student->user->delete();
                    $this->deletePhotoFile($fileName);
                });
                $alertType = 'success';
                $alertMsg = "Student {$student->user->name} has been deleted successfully!";
            } else {
                $alertType = 'warning';
                $gender = $student->user->gender == 'M' ? 'he' : 'she';
                $justification = match (true) {
                    $totalStudentsDisciplines <= 0 => "",
                    $totalStudentsDisciplines == 1 => "$gender is enrolled in 1 discipline",
                    $totalStudentsDisciplines > 1 => "$gender is enrolled in $totalStudentsDisciplines disciplines",
                };
                $alertMsg = "Student <a href='$url'><u>{$student->user->name}</u></a> cannot be deleted because $justification.";
            }
        } catch (\Exception $error) {
            $alertType = 'danger';
            $alertMsg = "It was not possible to delete the student
                            <a href='$url'><u>{$student->user->name}</u></a>
                            because there was an error with the operation!";
        }
        return redirect()->route('students.index')
            ->with('alert-type', $alertType)
            ->with('alert-msg', $alertMsg);
    }

    public function destroyPhoto(Student $student): RedirectResponse
    {
        if ($this->deleteUserPhoto($student->user)) {
            return redirect()->back()
                ->with('alert-type', 'success')
                ->with('alert-msg', "Photo of student {$student->user->name} has been deleted.");
        } else {
            return redirect()->back()
                ->with('alert-type', 'warning')
                ->with('alert-msg', "Photo of student {$student->user->name} does not exist.");
        }
    }
    
    public function show(Student $student): View
    {
        return view('students.show')->with('student', $student);
    }

    public function myStudents(Request $request): View
    {
        if ($request->user()?->type == 'T') {
            $disciplinesIds = $request->user()?->teacher?->disciplines->pluck('id')?->toArray();
            if (empty($disciplinesIds)) {
                return view('students.my')->with('students', new Collection);
            }
        } else {
            return view('students.my')->with('students', new Collection);
        }
        $studentsID = DB::table('students_disciplines')
            ->whereIntegerInRaw('discipline_id', $disciplinesIds)
            ->pluck('students_id')
            ->toArray();
        $students =  Student::whereIntegerInRaw('id', $studentsID)
            ->with('user', 'courseRef', 'disciplines')
            ->paginate(20)
            ->withQueryString();
        return view('students.my', compact('students'));
    }    
}
