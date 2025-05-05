<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
   public function before(RequestInterface $request, $arguments = null)
   {
      // Jika belum login, redirect ke halaman login
      if (!session()->get('isLoggedIn')) {
         return redirect()->to('/login');
      }

      // Cek level akses jika diperlukan
      $level = session()->get('level');
      $uri = service('uri');
      $segment = $uri->getSegment(1);

      if ($segment !== str_replace('_', '-', $level)) {
         return redirect()->to("/$level/dashboard");
      }
   }

   public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
   {
      // Tidak perlu melakukan apa-apa setelah request
   }
}
