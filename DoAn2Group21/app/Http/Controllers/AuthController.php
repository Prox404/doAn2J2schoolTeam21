<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Classes;
use App\Models\ClassStudent;
use App\Models\Schedules;
use App\Models\Subjects;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }  
      
    public function customLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = User::query()
                ->where('email', $request->get('email'))
                ->firstOrFail();
            return redirect()->intended('dashboard')
                        ->withSuccess('Signed in');
        }
  
        return redirect("login")->withSuccess('Login details are not valid');
    }

    public function findValueById($object, $id, $id_field, $field)
    {
        if($object[$id_field] == $id){
            return $object[$field];
        }
    }

    public function findElementById($object, $id, $id_field, $field)
    {
        foreach ($object as $key => $value) {
            if ($value->$id_field == $id) {
                return $value->$field;
            }
        }
    }
    
    public function dashboard()
    {
        if(Auth::check()){
            if(auth()->user()->level == 1){
                $id = auth()->user()->id;
                //get number of class
                $classes_id = ClassStudent::where('user_id', $id)->get('class_id');
                $classes = [];
                $teachers = [];
                $sessions = [];
                $scheduleOfWeek = [];
                $now = Carbon::now();
                $weekStartDate = $now->startOfWeek()->format('Y-m-d');
                $weekEndDate = $now->endOfWeek()->format('Y-m-d');
                foreach($classes_id as $class_id){
                    // get class
                    $class = Classes::query()
                        ->addSelect('classes.*')
                        ->addSelect('subjects.name as subject_name')
                        ->leftJoin('subjects', 'classes.subject_id', 'subjects.id')
                        ->where('classes.id', $class_id['class_id'])
                        ->first();
                    $classes[] = $class;

                    // get teacher
                    $user_id = ClassStudent::where('class_id', $class_id['class_id'])->first()->get('user_id');
                    $teacher = User::query()
                        ->addSelect('users.name', 'users.email')
                        ->whereIn('id', $user_id)
                        ->where('level', 2)
                        ->first();
                    $teacher['subject'] = $this->findValueById($class, $class_id['class_id'], 'id', 'subject_name');
                    $teachers[] = $teacher;

                    $schedule = Schedules::query()->where('class_id', $class_id['class_id']);
                    $number_sessions = $schedule->get('id'); // get all id of sessions in schedule
                    $date = $schedule->get(['date','id']); // get all date and id of sessions in schedule to search
                    
                    //get all lessons learned and add subject name, date
                    if(!empty($number_sessions)){
                        $attendances = Attendance::whereIn('schedule_id', $number_sessions)->where('user_id', $id)->get();
                        foreach ($attendances as $attendance){
                            $attendance['subject'] = $this->findValueById($class, $class_id['class_id'], 'id', 'subject_name'); // search subject name
                            $attendance['date'] = $this->findElementById($date, $attendance['schedule_id'], 'id', 'date'); // search date
                            $attendance['class'] = $this->findValueById($class, $class_id['class_id'], 'id', 'name');
                            $sessions[] = $attendance;
                        }
                    }

                    $weekSchedule = $schedule->whereBetween('date', [$weekStartDate, $weekEndDate])->get();
                    foreach ($weekSchedule as $schedule){
                        $schedule['class'] = $this->findValueById($class, $class_id['class_id'], 'id', 'name');
                        $schedule['shift'] = $this->findValueById($class, $class_id['class_id'], 'id', 'shift');
                        $scheduleOfWeek[] = $schedule;
                    }
                }

                

                return view('home')->with([
                    'classes' => $classes,
                    'sessions' => $sessions,
                    'teachers' => $teachers,
                    'scheduleOfWeek' => $scheduleOfWeek,
                ]);

            }else if (auth()->user()->level == 2){
                $id = auth()->user()->id;
                //get number of class
                $classes_id = ClassStudent::where('user_id', $id)->get('class_id');
                $classes = [];
                $scheduleOfWeek = [];
                $now = Carbon::now();
                $weekStartDate = $now->startOfWeek()->format('Y-m-d');
                $weekEndDate = $now->endOfWeek()->format('Y-m-d');

                foreach($classes_id as $class_id){
                    // get class
                    $class = Classes::query()
                        ->addSelect('classes.*')
                        ->addSelect('subjects.name as subject_name')
                        ->leftJoin('subjects', 'classes.subject_id', 'subjects.id')
                        ->where('classes.id', $class_id['class_id'])
                        ->first();
                    $classes[] = $class;

                    $schedule = Schedules::query()->where('class_id', $class_id['class_id']);
                    $number_sessions = $schedule->get('id'); // get all id of sessions in schedule

                    $weekSchedule = $schedule->whereBetween('date', [$weekStartDate, $weekEndDate])->get();
                    foreach ($weekSchedule as $schedule){
                        $schedule['class'] = $this->findValueById($class, $class_id['class_id'], 'id', 'name');
                        $schedule['shift'] = $this->findValueById($class, $class_id['class_id'], 'id', 'shift');
                        $scheduleOfWeek[] = $schedule;
                    }
                }

                $admins = User::where('level', 3)->inRandomOrder()->limit(3)->get(['name', 'email']);

                return view('home')->with([
                    'classes' => $classes,
                    'admins' => $admins,
                    'scheduleOfWeek' => $scheduleOfWeek,
                ]);

            }
            if(auth()->user()->level == 3 || auth()->user()->level == 4){
                $number_student = User::where('level', 1)->count();
                $number_teacher = User::where('level', 2)->count();
                $number_class = Classes::count();
                $number_subject = Subjects::count();

                return view('home')->with([
                    'number_student' => $number_student,
                    'number_teacher' => $number_teacher,
                    'number_class' => $number_class,
                    'number_subject' => $number_subject,
                ]);

                // return $number_student;
            }
            
        }
  
        return redirect("login")->withSuccess('You are not allowed to access');
    }
    
    public function signOut() {
        Session::flush();
        Auth::logout();
  
        return Redirect('login');
    }
}
