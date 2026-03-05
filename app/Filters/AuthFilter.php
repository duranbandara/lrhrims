<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $logged_in = session()->get('logged_in');
        log_message('debug', 'AUTHFILTER: logged_in=' . var_export($logged_in, true) . ' URI=' . $request->getUri()->getPath());
        if (! $logged_in) {
            return redirect()->to(base_url('login'))->with('error', 'Please login to continue.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nothing
    }
}
