<?php

namespace App\Http\Controllers\v1\Auth;

use App\Http\Controllers\Controller;
use App\Models\v1\People;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Get token from login
     * POST api/v1/auth/login
     * @param Request username
     * @param Request password
     * @return Response 
     **/
	public function login(Request $request)
	{
        try 
        {
            $validator = Validator::make($request->post(), [
                'username' => 'required',
                'password' => 'required',
            ]);

            if (! $validator->fails())
            {
                $credentials = $request->only(['username', 'password']);

                if (! $token = Auth::attempt($credentials)) 
                {			
                    return $this->respHandler->requestError('Unauthorized.');
                }
                
                $data = [
                    'token' => $token, 
                    'profile' => (object) [
                        'name' => People::find($this->authUser()->id_people)->name,
                        'address' => People::find($this->authUser()->id_people)->address,
                        'phone_number' => People::find($this->authUser()->id_people)->phone_number
                    ]
                ];

                return $this->respHandler->success('Success get token bearer.', $data);
            }
            else
                return $this->respHandler->requestError($validator->errors());
        } 
        catch(\Exception $e)
        {
            return $this->respHandler->requestError($e->getMessage());
        }
	}

    /**
     * Get user details. 
     * GET api/v1/auth/profile
     * @return Response
     **/	 	
    public function viewProfile()
    {
        try 
        {
            $user = $this->authUser()->toArray();
            return $this->respHandler->success('Success get user.', $user);
        } 
        catch(\Exception $e)
        {
            return $this->respHandler->requestError($e->getMessage());
        }
    }
    
    /**
     * Log the application out.
     * GET api/v1/auth/logout
     **/
    public function logout()
    {
        try 
        {
            $auth = Auth::guard('api')->logout();
            return $this->respHandler->success('Successfully logged out.');
        } 
        catch(\Exception $e)
        {
            return $this->respHandler->requestError($e->getMessage());
        }
    }

    /**
     * Refresh a token.
     * GET api/v1/auth/refresh-token
     **/
    public function refreshToken()
    {
        try
         {
            $token = Auth::guard('api')->refresh();
            return $this->respHandler->success('Succes refresh token bearer.', ['token' => $token]);
        }
         catch(\Exception $e)
        {
            return $this->respHandler->requestError($e->getMessage());
        }
    }

}
