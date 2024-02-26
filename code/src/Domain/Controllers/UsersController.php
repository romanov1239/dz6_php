<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Exception;
use Geekbrains\Application1\Application\Render;
use Geekbrains\Application1\Domain\Models\User;

class UsersController
{
    public function actionIndex ()
    {
        $users = User ::getAllUsersFromStorage ();
        $render = new Render();
        if (!$users) {
            return $render -> renderPage (
                'user-empty.tpl',
                [
                    'title' => 'Список пользователей в хранилище',
                    'message' => 'Список пуст или не найден'
                ]
            );
        } else {
            return $render -> renderPage (
                'user-index.tpl',
                [
                    'title' => 'Список пользователей в хранилище',
                    'users' => $users
                ]
            );
        }
    }


    public function actionSave (): string
    {
        $render = new Render();

        if (!empty($_GET)) {
            try {
                if (User ::validateRequestData ()) {
                    $user = new User();
                    $user -> setParamsFromRequestData ();
                    $user -> saveToStorage ();

                    return $render -> renderPage (
                        'user-created.tpl',
                        [
                            'title' => 'Пользователь создан',
                            'message' => "Создан пользователь " . $user -> getUserName () . " " . $user -> getUserLastName ()
                        ]
                    );
                } else {
                    throw new \Exception("Переданные данные некорректны");
                }
            } catch (Exception $e) {
                return 'Произошла ошибка: ' . $e -> getMessage ();
            }
        }

        return $render -> renderPage ('user-addBD.tpl', ['title' => 'Форма добавления пользователя']);
    }
}
