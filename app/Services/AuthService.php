<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AuthService{
public function login(array $credentials){
   $user = User::where('email',$credentials['email'])->first();

   //pang check kung nag eexist yung user sa database at kung tama ba yung password
   if(!$user || !Hash::check($credentials['password'],$user->password_hash)){
     return null;
   }
   //access token
   $accessToken = $user->createToken('access')->plainTextToken;

  //refresh token
   $refreshToken = $user->createToken('refresh')->plainTextToken;
  
   return [
    'user' => $user,
    'access_token'=> $accessToken,
    'refresh_token'=> $refreshToken
   ];
}



public function register(array $data){
    $user = User::create([
    'name' => $data['name'],
    'email' => $data['email'],
    'password_hash' => Hash::make($data['password']),
    'first_name' =>data['first_name'],
    'last_name'=>data['last_name'],
    ]);
  


    return $user;
}

}