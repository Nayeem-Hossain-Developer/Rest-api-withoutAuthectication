<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserApiController extends Controller
{
    private $user;
    private $data;
    private $rules;
    private $customMessage;
    private $validation;
    private $message;
    private $ids;
    //--------for show single user or all user information-----
    public function showUser($id=null)
    {
       if($id==''){
           $this->users = User::get();
           return response()->json(['users'=>$this->users],200);
       }else{
           $this->users = User::find($id);
           return response()->json(['users'=>$this->users],200);
       }
    }
    //-----for add single user---------
    public function storeUser(Request $request){
        if($request->isMethod('post')){
            $this->data=$request->all();
            $this->rules =[
                'name'     => 'required|max:50',
                'email'    => 'required|email|unique:users',
                'password' => 'required'
            ];
            $this->customMessage = [
              'name.required'     => 'Name is required',
              'email.required'    => 'Email is required',
              'email.email'       => 'Email must be a valid email',
              'password.required' => 'Password is required'

            ];
            $this->validation = Validator::make($this->data,$this->rules,$this->customMessage);
            if($this->validation->fails()) {
                return response()->json($this->validation->errors(), 422);
            }
                User::insert($this->data);
                return response()->json(['message'=>'User Added Successfully!'],201);
        }
    }
    //-----------for update single user-------
    public function updateUser(Request $request,$id){
        if($request->isMethod('put')){
            $this->data = $request->all();
            $this->rules=[
                'name'     => 'required',
                'password' => 'required'
            ];
            $this->customMessage = [
                'name.required' => 'Name is required',
                'password.required' => 'Password is required'
            ];
            $this->validation = Validator::make($this->data,$this->rules,$this->customMessage);
            if($this->validation->fails()){
                return response()->json($this->validation->errors(),422);
            }
            $this->user = User::findOrFail($id);
            $this->user->name = $this->data['name'];
            $this->user->password = Hash::make($this->data['password']);
            $this->user->save();
            return response()->json(['message'=> 'user update successfully!'],202);

        }
    }

    //-------------for update single field using patch method-----
    public function updateSingleFieldUser(Request $request,$id){
        if($request->isMethod('patch')){
            $this->data = $request->all();
            $this->rules=[
                'password' => 'required'
            ];
            $this->customMessage = [
                'password.required' => 'Password is required'
            ];
            $this->validation = Validator::make($this->data,$this->rules,$this->customMessage);
            if($this->validation->fails()){
                return response()->json($this->validation->errors(),422);
            }
            $this->user = User::findOrFail($id);
            $this->user->password = Hash::make($this->data['password']);
            $this->user->save();
            return response()->json(['message'=> 'user update successfully!'],202);

        }
    }

    //--------for delete single user-----
    public function deleteUser($id=null)
    {
            $this->users = User::findOrFail($id)->delete();
            return response()->json(['message'=>'User Delete Successfully!'],200);
    }

    //--------for delete single user using json-----
    public function deleteUserWithJson(Request $request)
    {
        if($request->isMethod('delete')){
            $this->users = User::findOrFail($request->id)->delete();
            return response()->json(['message'=>'User Delete Successfully using json data!'],200);
        }
    }

    //---------for add multiple user--------
    public function storeMultiple(Request $request){
        if($request->isMethod('post')){
            $this->data = $request->all();
            $this->rules = [
                'users.*.name' => 'required',
                'users.*.email' => 'required|email|unique:users',
                'users.*.password' => 'required'
            ];

            $this->customMessage = [
                'users.*.name.required' => 'Name is required',
                'users.*.email.required' => 'Email is required',
                'users.*.email.email' => 'Email must be a valid email',
                'users.*.password.required' => 'Password is required'
            ];
            $this->validation = Validator::make($this->data,$this->rules,$this->customMessage);
            if($this->validation->fails()){
                return response()->json($this->validation->errors(),422);
            }
            foreach($this->data['users'] as $addUser){
                $this->user = new User();
                $this->user->name = $addUser['name'];
                $this->user->email = $addUser['email'];
                $this->user->password = Hash::make($addUser['password']);
                $this->user->save();
                $this->message = 'User added successfully!';
            }
            return response()->json(['message' =>$this->message],201);
        }
    }

    //--------for delete Multiple user-----
    public function deleteMultipleUser($ids)
    {   $this->ids = explode(',',$ids);
        $this->users = User::WhereIn('id',$this->ids)->delete();
        return response()->json(['message'=>'Multiple User Delete Successfully!'],200);
    }
    //--------for delete multiple user using json-----
    public function deleteMultipleWithJson(Request $request)
    {
        if($request->isMethod('delete')){
            $this->data = $request->all();
            $this->users = User::WhereIn('id',$this->data['ids'])->delete();
            return response()->json(['message'=>'User Delete Successfully using json data!'],200);
        }
    }
}
