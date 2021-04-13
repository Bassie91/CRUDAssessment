<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

/**
 * Controller for handling CRUD requests related to users .
 *
 */
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $users = User::all();
            $response = [
                "message" => "OK",
                "data" => $users
            ];
            return response()->json($response, 200);
        } catch (Exception $exception) {
            $error = [
                "message" => $exception->getMessage()
            ];
            return response()->json($error, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            /**
             * todo change dob to human friendly name
             */
            $rules = array(
                'firstname' => 'required|max:255',
                'surname' => 'required|max:255',
                'dob' => 'required|date',
                'phone' => 'required|numeric',
                'email' => 'required|email:rfc,dns|unique:users,email',
            );
            $messages = array(
                'firstname.required' => 'First name is required.',
                'surname.required' => 'Last name is required.',
                'dob.required' => 'Date of birth is required.',
                'dob.date' => 'Wrong date format.',
                'phone.required' => 'Phone number is required.',
                'phone.numeric' => 'Phone number must be digits only.',
                'email.required' => 'Email address is required.',
                'email.email' => 'Wrong email format.',
                'email.unique' => 'This email exists in database.',
            );
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $response = [
                    "message" => "Validation Failed",
                    "data" => $validator->errors()
                ];
                return response()->json($response, 406);
            } else {
                $user = User::create($request->all());
                $response = [
                    "message" => "User created successfully!",
                    "data" => $user
                ];
                return response()->json($response, 201);
            }
        } catch (Exception $exception) {

            $error = [
                'message' => $exception->getMessage()
            ];

            return response()->json($error, 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            if (!is_numeric($id)) {
                $response = [
                    "message" => "User ID must be a number!"
                ];
                return response()->json($response, 406);
            } else {
                $user = User::find($id);
                if (!$user) {
                    $response = [
                        "message" => "User not found"
                    ];
                    return response()->json($response, 404);
                } else {
                    $response = [
                        "message" => "User found",
                        "data" => $user
                    ];
                    return response()->json($response, 200);
                }
            }
        } catch (Exception $exception) {
            $error = [
                'message' => $exception->getMessage()
            ];
            return response()->json($error, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Illuminate\Http\Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);
            $rules = array(
                'firstname' => 'required|max:255',
                'surname' => 'required|max:255',
                'dob' => 'required|date',
                'phone' => 'required|numeric',
                'email' => 'required|email:rfc,dns',
            );
            $messages = array(
                'firstname.required' => 'First name is required.',
                'surname.required' => 'Last name is required.',
                'dob.required' => 'Date of birth is required.',
                'dob.date' => 'Wrong date format.',
                'phone.required' => 'Phone number is required.',
                'phone.numeric' => 'Phone number must be digits only.',
                'email.email' => 'Wrong email format.',
                'email.unique' => 'This email exists in database.',
            );
            $validator = Validator::make($request->all(), $rules, $messages);

            if (!is_numeric($id)) {
                $response = [
                    "message" => "User ID must be a number!"
                ];
                return response()->json($response, 406);
            } else if (!$user) {
                $response = [
                    "message" => "User not found"
                ];
                return response()->json($response, 404);
            } else if ($validator->fails()) {
                $response = [
                    "message" => "Validation Failed",
                    "data" => $validator->errors()
                ];
                return response()->json($response, 406);
            } else {
                $user->update($request->all());
                $response = [
                    "message" => "User updated successfully!",
                    "data" => $user
                ];
                return response()->json($response, 202);
            }
        } catch (Exception $exception) {
            $errorCode = $exception->errorInfo[1];
            if ($errorCode == 1062) {
                $emailError = [
                    "email" => ["This email is used by other user."]
                ];
                $response = [
                    "message" => "Failed validation",
                    "data" => $emailError
                ];
                return response()->json($response, 406);
            } else {
                $error = [
                    'error' => [
                        'message' => $exception->getMessage()
                    ]
                ];
            }
            return response()->json($error, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            if(!is_numeric($id)){
                $response = ["message" => "User ID must be numeric!"];
                    return response()->json($response, 406);
            } else {
                $user = User::find($id);

                if (!$user) {
                    $response = ["message" => "User not found"];
                    return response()->json($response, 404);
                } else {
                    User::destroy($id);
                    $response = [
                        "message" => "User deleted successfully!",
                        "data" => $user
                    ];
                    return response()->json($response, 200);
                }
            }
        } catch (Exception $exception) {
            $error = [
                'message' => $exception->getMessage()
            ];
            return response()->json($error, 500);
        }
    }
}
