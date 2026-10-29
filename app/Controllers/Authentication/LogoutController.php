<?php
declare(strict_types=1);

namespace App\Controllers\Authentication;

use App\Core\Controller;
use App\Security\Session;

class LogoutController extends Controller
{
    public function logout(): void
    {
        Session::destroy();
        $this->redirect('/');
    }
}
