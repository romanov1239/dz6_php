<?php

namespace Geekbrains\Application1\Application;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Render
{
    private string $viewFolder = '/src/Domain/Views/';
    private FilesystemLoader $loader;
    private Environment $environment;

    public function __construct()
    {
        $this->loader = new FilesystemLoader($_SERVER['DOCUMENT_ROOT'] . $this->viewFolder);
        $this->environment = new Environment($this->loader, [
            //'cache'=>$_SERVER['DOCUMENT_ROOT'].'/cache/',
        ]);
    }

    public function renderPage(string $contentTemplateName = 'page-index.tpl', array $templateVariables = [])
    {
        $template = $this->environment->load('main.tpl');
        $templateVariables['content_template_name'] = $contentTemplateName;
        $templateVariables['title'] = 'Наше первое приложение';

        return $template->render($templateVariables);
    }

    public static function renderExceptionPage(\Throwable $e): string
    {
        $render = new Render();
        $mainTemplate = $render->environment->load('main.tpl');
        $template = $render->environment->load('error.tpl');

        $templateVariables = [
            'title' => 'Ошибка',
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ];

        $templateVariables['content_template_name'] = 'error.tpl';

        return $mainTemplate->render($templateVariables);
    }

}
