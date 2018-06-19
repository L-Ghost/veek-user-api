<?php

namespace Test\Unit;

use Laravel\Lumen\Testing\DatabaseMigrations;

class UsersTest extends \TestCase
{
    use DatabaseMigrations;

    public function testREST()
    {
        // inserting user
        $this->post('/users', [
            'name' => 'Luke Skywalker', 'email' => 'luke.skywalker@tatooine.com'
        ])->seeJson([
            'data' => [
                'message' => 'The user has been created',
                'id' => 1
            ]
        ])->assertResponseStatus(201);

        // inserting another user
        $this->post('/users', [
            'name' => 'Han Solo', 'email' => 'han@solo.com'
        ])->seeJson([
            'data' => [
                'message' => 'The user has been created',
                'id' => 2
            ]
        ])->assertResponseStatus(201);

        // inserting one more user
        $this->post('/users', [
            'name' => 'Leia Skywalker', 'email' => 'leia.skywalkern@alderaan.com'
        ])->seeJson([
            'data' => [
                'message' => 'The user has been created',
                'id' => 3
            ]
        ])->assertResponseStatus(201);

        // inserting user with repeated email
        $this->post('/users', [
            'name' => 'Ben Solo', 'email' => 'luke.skywalker@tatooine.com'
        ])->seeJson([
            'data' => [
                'message' => 'You cannot insert another user with the same email'
            ]
        ])->assertResponseStatus(422);

        // trying to insert user with invalid data
        $this->post('/users', [
            'name' => 'C3PO', 'email' => 'I have a bad feeling about this'
        ])->seeJsonEquals([
            'email' => ['The email must be a valid email address.']
        ])->assertResponseStatus(422);

        // getting user information
        $this->get('/users/1'
        )->seeJson([
            'name' => 'Luke Skywalker', 'email' => 'luke.skywalker@tatooine.com'
        ])->assertResponseStatus(200);

        // trying to get non existent user
        $this->get('/users/5'
        )->seeJsonEquals([
            'data' => [
                'message' => 'The user with id 5 does not exist'
            ]
        ])->assertResponseStatus(404);

        // getting all users
        $json = json_decode($this->get('/users')->response->getContent());
        $this->assertCount(3, $json->data);
        $this->assertEquals('Luke Skywalker', $json->data[0]->name);
        $this->assertEquals('Han Solo', $json->data[1]->name);
        $this->assertEquals('Leia Skywalker', $json->data[2]->name);

        // updating users
        $this->put('/users/2', [
            'name' => 'Han Solo', 'email' => 'han.solo@milleniumfalcon.com'
        ])->seeJsonEquals([
            'data' => [
                'id' => '2',
                'message' => 'The user has been updated'
            ]
        ])->assertResponseStatus(200);

        $this->patch('/users/3', [
            'name' => 'Leia Organa', 'email' => 'leia.organa@alderaan.com'
        ])->seeJsonEquals([
            'data' => [
                'id' => '3',
                'message' => 'The user has been updated'
            ]
        ])->assertResponseStatus(200);

        // getting users again to see updates
        $this->get('/users/2'
        )->seeJson([
            'name' => 'Han Solo', 'email' => 'han.solo@milleniumfalcon.com'
        ])->assertResponseStatus(200);

        $this->get('/users/3'
        )->seeJson([
            'name' => 'Leia Organa', 'email' => 'leia.organa@alderaan.com'
        ])->assertResponseStatus(200);

        // trying to update user email to email of previous user
        $this->put('/users/3', [
            'name' => 'Leia Organa', 'email' => 'luke.skywalker@tatooine.com'
        ])->seeJsonEquals([
            'data' => [
                'message' => 'There is another user already using this email'
            ]
        ])->assertResponseStatus(422);

        // updating non existing user
        $this->put('/users/4', [
            'name' => 'Yoda', 'email' => 'yoda@dagoba.com'
        ])->seeJsonEquals([
            'data' => [
                'message' => 'The user with id 4 does not exist'
            ]
        ])->assertResponseStatus(404);

        // deleting user
        $this->delete('/users/1'
        )->seeJson([
            'data' => [
                'id' => '1',
                'message' => 'The user has been deleted'
            ]
        ])->assertResponseStatus(200);

        // trying to get user after deletion
        $this->get('/users/1'
        )->seeJsonEquals([
            'data' => [
                'message' => 'The user with id 1 does not exist'
            ]
        ])->assertResponseStatus(404);

        // trying to delete non existent user
        $this->delete('/users/4'
        )->seeJson([
            'data' => [
                'message' => 'The user with id 4 does not exist'
            ]
        ])->assertResponseStatus(404);
    }

}