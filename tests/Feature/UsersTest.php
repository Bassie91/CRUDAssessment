<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * Class UsersTest
 *
 * @package Tests\Feature
 */
class UsersTest extends TestCase
{
    /**
     * @return mixed
     */
    public function generateToken()
    {
        $permissions = array();
        $permissions['email'] = "api@app.com";
        $permissions['password'] = "test1234";
        $response = $this->postJson('/api/login', $permissions);
        return $response->json('token');
    }

    /**
     * Check if request to /api/users returns good status and proper json structure. Shouldn't throw any errors
     */
    public function testGetUsersWithAuthentication()
    {
        $token = $this->generateToken();
        $response = $this->withToken($token)->get('api/users/');

        $response->assertStatus(200);
        $response->assertJsonStructure(array(
            'message',
            'data' => [
                '*' => [
                    'id',
                    'firstname',
                    'surname',
                    'dob',
                    'phone',
                    'email',
                    'created_at',
                    'updated_at'
                ]
            ]
        ));
    }

    /**
     * Check if request to /api/users returns the right status. Shouldn't throw any errors
     */
    public function testGetUsersWithoutAuthentication()
    {
        $response = $this->withHeaders(['Accept' => 'application/json',])->get('api/users');
        $response->assertStatus(401);
    }

    /**
     * Check if POST request to /api/users returns good status and proper json structure. Shouldn't throw any errors
     */
    public function testStoreUserWithAuthentication()
    {
        $testData = array(
            "firstname" => "test",
            "surname" => "test",
            "dob" => Carbon::today()->subDays(rand(0, 365)),
            "phone" => "07123345",
            "email" => Str::random(10) . '@gmail.com'
        );
        $token = $this->generateToken();
        $response = $this->withToken($token)->post('api/users', $testData);

        $response->assertStatus(201);
        $response->assertJsonStructure(array(
            'message',
            'data' => [
                    'firstname',
                    'surname',
                    'dob',
                    'phone',
                    'email',
                    'created_at',
                    'updated_at',
                    'id',
                ]
            
        ));
    }

    /**
     * Check if request to /api/users returns the right status. Shouldn't throw any errors
     */
    public function testStoreUserWithoutAuthentication()
    {
        $response = $this->withHeaders(['Accept' => 'application/json',])->post('api/users');
        $response->assertStatus(401);
    }

    /**
     * Check if PUT request to /api/users/$id returns good status and proper json structure. Shouldn't throw any errors
     */
    public function testUpdateUserWithAuthentication()
    {
        $token = $this->generateToken();
        $response = $this->withToken($token)->get('api/users/');
        $response->assertStatus(200);
        $user = $response->json()['data'];
        $user = $user[0];


        $update = $this->withToken($token)->put( 'api/users/'.$user['id'],[
            "firstname" => "Changed for test",
            "surname" => $user["surname"],
            "dob" => $user["dob"],
            "phone" => $user["phone"],
            "email" => $user["email"],
        ]);
        $update->assertStatus(202);
        $update->assertJsonStructure(array(
            'message',
            'data' => [
                    'firstname',
                    'surname',
                    'dob',
                    'phone',
                    'email',
                    'created_at',
                    'updated_at',
                    'id',
                ]
            
        ));
    }
}
