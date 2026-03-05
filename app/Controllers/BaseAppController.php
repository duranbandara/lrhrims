<?php

namespace App\Controllers;

use App\Models\MainModel;
use CodeIgniter\Controller;

class BaseAppController extends Controller
{
    protected MainModel $model;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->model = new MainModel();
        helper(['form', 'url', 'app_helper']);
    }

    protected function render(string $view, array $data = [], string $template = 'templates/dashboard'): string
    {
        $data['content'] = view($view, $data);
        return view($template, $data);
    }
}
