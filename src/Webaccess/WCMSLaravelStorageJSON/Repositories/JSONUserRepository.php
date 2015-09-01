<?php

namespace Webaccess\WCMSLaravelStorageJSON\Repositories;

use CMS\Entities\User;
use CMS\Repositories\UserRepositoryInterface;

class JSONUserRepository implements UserRepositoryInterface
{
    public function __construct()
    {
        $this->json = storage_path() . '/w-cms/users.json';
        $this->counter = 1;
        $this->users = [];

        $this->loadFromJSON();
    }

    public function findByID($userID)
    {
        foreach ($this->users as $user) {
            if ($userID == $user->getID()) {
                return $user;
            }
        }

        return false;
    }

    public function findByLogin($userLogin)
    {
        foreach ($this->users as $user) {
            if ($userLogin == $user->getLogin()) {
                return $user;
            }
        }

        return false;
    }

    public function findAll()
    {
        return $this->users;
    }

    public function createUser(User $user)
    {
        $this->counter++;
        $user->setID($this->counter);
        $this->users[]= $user;
        $this->writeToJSON();

        return $this->counter;
    }

    public function updateUser(User $user)
    {
        foreach ($this->users as $i => $userJSON) {
            if ($user->getID() == $userJSON->getID()) {
                $this->users[$i] = $user;
            }
        }

        $this->writeToJSON();
    }

    public function deleteUser($userID)
    {
        foreach ($this->users as $i => $userJSON) {
            if ($userJSON->getID() == $userID) {
                unset($this->users[$i]);
            }
        }

        $this->writeToJSON();
    }

    private function writeToJSON()
    {
        $users = [];
        foreach ($this->users as $user) {
            $users[]= [
                'id' => $user->getID(),
                'login' => $user->getLogin(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'last_name' => $user->getLastName(),
                'first_name' => $user->getFirstName(),
            ];
        }

        file_put_contents($this->json, json_encode([$this->counter, $users]));
    }

    private function loadFromJSON()
    {
        $string = file_get_contents($this->json);
        $data = json_decode($string, true);

        if ($data) {
            $this->counter = $data[0];
            $users = $data[1];

            if (is_array($users) && sizeof($users) > 0) {
                foreach ($users as $userData) {
                    $user = new User();
                    foreach ($userData as $property => $value) {
                        $method = 'set' . ucfirst(str_replace('_', '', $property));
                        $user->$method($value);
                    }

                    $this->users[] = $user;
                }
            }
        }
    }
}