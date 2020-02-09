<?php

namespace App\Controllers;

use Core\Utilities;
use \Core\View;
use \App\Auth;
use \App\Flash;
use Exception;

/**
 * Profile controller
 *
 * PHP version 7.2
 */
class Profile extends Authenticated {
    private $user;

    /**
     * Before filter - called before each action method
     *
     * @return void
     */
    protected function before(): void {
        parent::before();

        $this->user = Auth::getUser();
    }

    /**
     * Show the profile
     *
     * @throws Exception
     *
     * @return void
     */
    public function showAction(): void {
        View::renderTemplate('Profile/show.twig', [
            'user' => $this->user
        ]);
    }

    /**
     * Show the form for editing the profile
     *
     * @throws Exception
     *
     * @return void
     */
    public function editAction(): void {
        View::renderTemplate('Profile/edit.twig', [
            'user' => $this->user
        ]);
    }

    /**
     * Update the profile
     *
     * @throws Exception
     *
     * @return void
     */
    public function updateAction(): void {
        if ($this->user->updateProfile($_POST)) {
            Flash::addMessage('Changes saved');
            Utilities::redirect('/profile/show');
        } else {
            View::renderTemplate('Profile/edit.twig', [
                'user' => $this->user
            ]);
        }
    }
}
