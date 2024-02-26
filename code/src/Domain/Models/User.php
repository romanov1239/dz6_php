<?php

namespace Geekbrains\Application1\Domain\Models;

use Geekbrains\Application1\Application\Application;
use Geekbrains\Application1\Infrastructure\Storage;

class User
{
    private ?string $userName;
    private ?string $userLastName;
    private ?int $userBirthday;
    private ?int $userId;
    private static string $storageAddress = '/storage/birthdays.txt';

    public function __construct (string $name = null, string $lastName = null, int $birthday = null)
    {
        $this -> userName = $name;
        $this -> userBirthday = $birthday;
        $this -> userLastName = $lastName;
        $this -> userId = null;
    }

    public function getId (): ?int
    {
        return $this -> userId;
    }

    public function setUserId (int $userId): void
    {
        $this -> userId = $userId;
    }


    public function getUserLastName (): ?string
    {
        return $this -> userLastName;
    }

    public function setUserLastName (?string $userLastName): void
    {
        $this -> userLastName = $userLastName;
    }

    public function getUserName (): string
    {
        return $this -> userName;
    }

    public function setUserName (string $userName): void
    {
        $this -> userName = $userName;
    }

    public function getUserBirthday (): ?int
    {
        return $this -> userBirthday;
    }

    public function setUserBirthday (?int $userBirthday): void
    {
        $this -> userBirthday = $userBirthday;
    }

    public function setBirthDayFromString (?string $birthdayString): void
    {
        if ($birthdayString !== null && $birthdayString !== '') {
            $this -> userBirthday = strtotime ($birthdayString);
        }
    }

    public static function getAllUsersFromStorageFILE (): array|false
    {
        $address = $_SERVER['DOCUMENT_ROOT'] . User ::$storageAddress;
        if (file_exists ($address) && is_readable ($address)) {
            $file = fopen ($address, "r");
            $users = [];
            while (!feof ($file)) {
                $userString = fgets ($file);
                $userString = trim ($userString);

                if ($userString === '') {
                    continue;
                }

                $userArray = explode (",", $userString);

                $user = new User($userArray[0]);
                $user -> setBirthDayFromString ($userArray[1]);
                $users[] = $user;
            }
            fclose ($file);
            return $users;
        } else {
            return false;
        }
    }

    public static function getAllUsersFromStorage (): array|false
    {
        $sql = "SELECT * FROM users";
        $handler = Application ::$storage -> get () -> prepare ($sql);
        $handler -> execute ();

        $result = $handler -> fetchAll ();
        $users = [];
        foreach ($result as $item) {
            $user = new User($item['user_name'], $item['user_lastname'], $item['user_birthday_timestamp']);
            $users[] = $user;
        }
        return $users;
    }

    public static function getUserById ($userId): ?User
    {
        $user = null;

        $sql = "SELECT * FROM users WHERE id_user = :id_user";
        $handler = Application ::$storage -> get () -> prepare ($sql);
        $handler -> execute (['id_user' => $userId]);

        $result = $handler -> fetch ();

        if ($result) {
            $user = new User($result['user_name'], $result['user_lastname'], $result['user_birthday_timestamp']);
            $user -> userId = $userId;
        }

        return $user;
    }
    public function addFunction (): string
    {
        if (!isset($_GET['name']) || !isset($_GET['birthday'])) {
            return 'Не указаны параметры name и/или birthday';
        }

        $name = $_GET['name'];
        $birthday = $_GET['birthday'];

        if ($birthday === '') {
            return 'Не указана дата рождения';
        }

        $birthday = date ('d-m-Y', strtotime ($birthday));
        $address = $_SERVER['DOCUMENT_ROOT'] . User ::$storageAddress;
        $file = fopen ($address, "a");
        fwrite ($file, "$name,$birthday\n");
        fclose ($file);

        return 'Пользователь успешно добавлен';
    }

    public static function validateRequestData (): bool
    {
        if (
            isset($_GET['name']) && !empty($_GET['name']) &&
            isset($_GET['lastname']) && !empty($_GET['lastname']) &&
            isset($_GET['birthday']) && !empty($_GET['birthday'])
        ) {
            return true;
        } else {
            return false;
        }
    }

    public function setParamsFromRequestData (): void
    {
        $this -> userName = $_GET['name'];
        $this -> userLastName = $_GET['lastname'];
        $this -> setBirthDayFromString ($_GET['birthday']);
    }

    public function saveToStorage ()
    {
        $storage = new Storage();
        if ($this -> userId) {
            $sql = "UPDATE users SET user_name = :user_name, user_lastname = :user_lastname, user_birthday_timestamp = :user_birthday WHERE id_user = :id";
        } else {
            $sql = "INSERT INTO users(user_name, user_lastname, user_birthday_timestamp) VALUES (:user_name, :user_lastname, :user_birthday)";
        }

        $handler = $storage -> get () -> prepare ($sql);
        $params = [
            'user_name' => $this -> userName,
            'user_lastname' => $this -> userLastName,
            'user_birthday' => $this -> userBirthday
        ];

        if ($this -> userId) {
            $params['id'] = $this -> userId;
        }

        $handler -> execute ($params);
    }

    public function deleteFromStorage ()
    {
        $storage = new Storage();
        $sql = "DELETE FROM users WHERE id_user = :id_user";
        $handler = $storage -> get () -> prepare ($sql);

        $handler -> execute ([
            'id_user' => $this -> userId
        ]);
    }

}
