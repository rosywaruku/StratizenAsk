<?php

namespace App\Http\Controllers;

use App\User;
use App\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUserRequest;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(20);
        return view('users.index', compact('users'))->with('i', (request()->input('page', 1) - 1) * 20);
    }

    public function edit(User $user)
    {   
        $courses =  Course::get();
        foreach ($courses as $course) {
            $course->description = $course->name;
        }
        $user = Auth::user();
        return view('users.edit', compact('courses', 'user'));
    }

    public function update(StoreUserRequest $request, User $user)
    { 
        $data = $request->validated();
        $user->name = $data["name"];
        $user->email = $data["email"];
        $user->phone = $data["phone"];
        $user->course = $data["course"];
        $user->about = $data["about"];
        $user->website = $data["website"];
        $user->password = bcrypt($data["password"]);
        $user->update();
        return back();
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}