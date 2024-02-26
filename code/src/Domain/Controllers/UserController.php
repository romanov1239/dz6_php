<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Render;
use Geekbrains\Application1\Domain\Models\User;

class UserController
{
    public function actionSave ()
    {
        $render = new Render();
        if (!empty($_GET)) {
            $user = new User($_GET['name'], strtotime($_GET['birthday']));
            $message = $user->addFunction();
            return $render->renderPage('user-add.tpl', [
                'title' => 'Форма добавления пользователя',
                'message' => $message
            ]);
        }
        return $render->renderPage('user-add.tpl', ['title' => 'Форма добавления пользователя']);
    }
    public function actionUpdate(): string
    {
        $render = new Render();

        if (!empty($_GET['id']) && !empty($_GET['name'])) {
            try {
                $userId = $_GET['id'];
                $newName = $_GET['name'];

                $user = User::getUserById($userId);
                if ($user) {
                    $user->setUserName($newName);
                    $user->saveToStorage();

                    return $render->renderPage(
                        'user-updated.tpl',
                        [
                            'title' => 'Пользователь обновлен',
                            'message' => 'Имя пользователя успешно изменено на ' . $newName
                        ]
                    );
                } else {
                    return $render->renderPage(
                        'user-not-found.tpl',
                        [
                            'title' => 'Ошибка',
                            'message' => 'Пользователь с указанным ID не найден'
                        ]
                    );
                }
            } catch (Exception $e) {
                return 'Произошла ошибка: ' . $e->getMessage();
            }
        }

        return $render->renderPage('user-update.tpl', ['title' => 'Форма изменения пользователя']);
    }
    public function actionDelete(): string
    {
        $render = new Render();

        if (!empty($_GET['id'])) {
            try {
                $userId = $_GET['id'];

                $user = User::getUserById($userId);
                if ($user) {
                    $user->deleteFromStorage();

                    return $render->renderPage(
                        'user-deleted.tpl',
                        [
                            'title' => 'Пользователь удален',
                            'message' => 'Пользователь успешно удален'
                        ]
                    );
                } else {
                    return $render->renderPage(
                        'user-not-found.tpl',
                        [
                            'title' => 'Ошибка',
                            'message' => 'Пользователь с указанным ID не найден'
                        ]
                    );
                }
            } catch (Exception $e) {
                return 'Произошла ошибка: ' . $e->getMessage();
            }
        }

        return $render->renderPage('user-delete.tpl', ['title' => 'Форма удаления пользователя']);
    }



}
