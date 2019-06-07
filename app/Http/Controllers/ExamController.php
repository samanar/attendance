<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exam;
use App\Course;
use App\Professor;
use App\Student;
use GuzzleHttp\Client;
use Log;
use DB;
use Session;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Support\Arr;

class ExamController extends Controller
{

    private function dropAll()
    {
        DB::statement("SET foreign_key_checks=0");
        $databaseName = DB::getDatabaseName();
        $tables = DB::select("SELECT * FROM information_schema.tables WHERE table_schema = '$databaseName'");
        foreach ($tables as $table) {
            $name = $table->TABLE_NAME;
            if ($name == 'migrations') {
                continue;
            }
            DB::table($name)->truncate();
        }
        DB::statement("SET foreign_key_checks=1");
    }

    public function getData()
    {
        $client = new Client([
            'base_uri' => 'http://142.93.134.194:8088/api/',
            'timeout'         => 5.0,
            'connect_timeout' => 5.0
        ]);
        try {
            $response = $client->request('GET', 'attendance');
            $data = json_decode($response->getBody(), true);
            if ($data['status'] != 200) {
                Session::flash('Error', 'Error getting data from api');
                return redirect()->route('home');
            }
            $this->dropAll();
            DB::table('req')->insert(['date' => $data['date']]);
            foreach ($data['classes'] as $class) {
                $course = new Course;
                $professor = new Professor;
                $exam = new Exam;
                $professor->first_name = $class['professor']['first_name'];
                $professor->last_name = $class['professor']['last_name'];
                $professor->id = $class['professor']['id'];
                $professor->save();
                $course->name = $class['course_name'];
                $course->professor_id = $class['professor']['id'];
                $course->save();
                $exam->start_at = $class['start_at'];
                $exam->end_at = $class['end_at'];
                $exam->room_number = $class['room_number'];
                $exam->id = $class['exam_id'];
                $exam->course_id = $course->id;
                $exam->save();
                foreach ($class['students'] as $student) {
                    $std = new Student;
                    $std['first_name'] = $student['first_name'];
                    $std['last_name'] = $student['last_name'];
                    // $std['chair_number'] = $student['chair_number'];
                    $std['id'] = $student['id'];
                    $std->save();
                    $course->students()->attach($student['id'], ['chair_number' => $student['chair_number']]);
                }
            }
            return redirect()->route('home');
        } catch (TransferException $e) {
            Session::flash('Error', 'Error getting data from api');
            return redirect()->route('home');
        }
    }


    public function index()
    {
        $req = DB::table('req')->first();
        return view('home')->with('req', $req);
    }

    public function exams()
    {
        return view('exams')->with('exams', Exam::with('course')->get());
    }

    public function attendance_check(Request $request, $exam_id)
    {
        $next_student = null;
        $finished_students = null;
        $exam = null;

        $exam = Exam::where('id', $exam_id)->first();
        $course = $exam->course;
        $finished_students = $exam->students;
        $all_students = $course->students;
        foreach ($all_students as $all_student) {
            $found = false;
            foreach ($finished_students as $finished_student) {
                if ($all_student->id == $finished_student->id) {
                    error_log($all_student->id);
                    $found = true;
                }
            }
            if (!$found) {
                $next_student = $all_student;
                break;
            }
        }
        if ($next_student) {
            return view('attendance_check')
                ->with('next_student', $next_student)
                ->with('finished_students', $finished_students)
                ->with('exam', $exam);
        } else {
            return view('attendance_end')
                ->with('exam', $exam)
                ->with('finished_students', $finished_students);
        }
    }

    public function attendance_submit(Request $request)
    {
        $exam_id = $request->input('exam_id');
        $student_id = $request->input('student_id');
        $status = $request->input('status');
        $chair_number = 0;
        $exam = Exam::find($exam_id);
        $students = $exam->course->students;
        foreach ($students as $student) {
            if ($student->id == $student_id) {
                $chair_number = $student->pivot->chair_number;
            }
        }
        $exam->students()->attach($student_id, ['status' => $status, 'chair_number' => $chair_number]);

        return redirect()->route('attendance_check', ['exam_id' => $exam_id]);
    }

    private function check_student_exists($student_id, $course_id)
    {
        $course = Course::find($course_id);
        $course_students = $course->students;
        foreach ($course_students as $course_student) {
            if ($course_student->id == $student_id) {
                return true;
            }
        }
        return false;
    }

    public function add_exam_student(Request $request)
    {
        $exam_id = $request->exam_id;
        $exam = Exam::find($exam_id);
        if ($this->check_student_exists($request->student_id, $exam->course->id)) {
            $request->session()->flash('Error', 'دانشجو با شماره دانشجویی داده شده وجود دارد');
            return redirect()->route('attendance_check', ['exam_id' => $exam_id]);
        }

        if (
            !$request->has('first_name') || $request->first_name == "" ||
            !$request->has('last_name') || $request->last_name == "" ||
            !$request->has('student_id') || $request->student_id == "" ||
            !$request->has('chair_number') || $request->chair_number == ""
        ) {
            $request->session()->flash('Error', 'لطفا همه ی فیلد ها را پر کنید');
            return redirect()->route('attendance_check', ['exam_id' => $exam_id]);
        }





        $student = new Student;
        $student->first_name = $request->first_name;
        $student->last_name = $request->last_name;
        $student->id = $request->student_id;
        $chair_number = $request->chair_number;
        $student->save();
        $exam->students()->attach($student->id, ['status' => 1, 'check' => 1, 'chair_number' => $chair_number]);
        return redirect()->route('attendance_check', ['exam_id' => $exam_id]);
    }

    public function get_signature_from_teacher(Request $request)
    {
        $exam_id = $request->exam_id;
        $exam = Exam::find($exam_id);
        $exam->teacher_signed = true;
        $exam->status = true;
        $exam->save();
        $this->send_data($exam);
        return redirect()->route('exams');
    }

    public function get_signature_from_other(Request $request)
    {
        $exam_id = $request->exam_id;
        $exam = Exam::find($exam_id);
        $exam->teacher_signed = false;
        $exam->signer_id = $request->signer_id;
        $exam->status = true;
        $exam->save();
        $this->send_data($exam);
        return redirect()->route('exams');
    }
    public function report(Request $request, $exam_id)
    {
        $exam = Exam::find($exam_id);
        $course = $exam->course;
        $professor = Professor::find($course->professor_id);
        $students = $exam->students;
        return view('report')
            ->with('exam', $exam)
            ->with('professor', $professor)
            ->with('students', $students);
    }

    private function send_data($exam)
    {
        $students = $exam->students()->where('status', 1)->get();
        $student_ids = Arr::pluck($students, 'id');
        $student_ids = (array_values($student_ids));
        $is_teacher_signed = json_encode($exam->teacher_signed ? true : false);
        // dd($student_ids);
        $client = new Client();
        $response = $client->post('http://142.93.134.194:8088/api/attendance', [
            \GuzzleHttp\RequestOptions::JSON => [
                'exam_id' => $exam->id,
                'is_teacher_signed' => $is_teacher_signed,
                'present_students_list' => $student_ids
            ]
        ]);
        // dd($response->getBody());
    }
}
