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

    protected function log(string $action, string $module, string $description): void
    {
        $this->model->insertRow('activity_logs', [
            'user_id'     => session()->get('user_id') ?? 'system',
            'user_name'   => session()->get('des')     ?? 'System',
            'role'        => session()->get('role')    ?? 'unknown',
            'action'      => $action,
            'module'      => $module,
            'description' => $description,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);
    }
}
