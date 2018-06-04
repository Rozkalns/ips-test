<?php

namespace App\Http\Controllers;

use App\Http\Helpers\InfusionsoftHelper;
use App\Http\Responses\ApiResponse;
use App\Module;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Response;

class ApiController extends Controller
{
    public function moduleReminders($email)
    {
        $user = User::where(compact('email'))->with('completedModules')->first();

        $infusionsoft = new InfusionsoftHelper();

        if ($user && $api_user = $infusionsoft->getContact($email)) {
            $user->tags = $api_user['Groups'] ?? null; // Added tags
            $user->products = $api_user['_Products'] ?? null; // Purchased courses
        } else {
            return ApiResponse::create(null, ApiResponse::STATUS_NOT_FOUND)
                ->setError('User not found');
        }

        if (!$user->products) {
            return ApiResponse::create(null, ApiResponse::STATUS_BAD_REQUEST)
                ->setError('User does not have any purchased courses');
        }

        $user->products = collect(explode(',', $user->products));

        // If there are no completed modules, add first

        // If any started, add next

        // If all competed, add "Module reminders completed"

        return ApiResponse::create('success', ApiResponse::STATUS_CREATED);
    }

    public function exampleCustomer()
    {
        $infusionsoft = new InfusionsoftHelper();

        $uniqid = uniqid();

        $infusionsoft->createContact([
            'Email' => $uniqid.'@test.com',
            "_Products" => 'ipa,iea'
        ]);

        $user = User::create([
            'name' => 'Test ' . $uniqid,
            'email' => $uniqid.'@test.com',
            'password' => bcrypt($uniqid)
        ]);

        // attach IPA M1-3 & M5
        $user->completedModules()->attach(Module::where('course_key', 'ipa')->limit(3)->get());
        $user->completedModules()->attach(Module::where('name', 'IPA Module 5')->first());

        return $user;
    }
}
