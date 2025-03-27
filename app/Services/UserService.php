<?php
namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

   
}
