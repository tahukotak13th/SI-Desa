<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class GuestFilter implements FilterInterface
{
   public function before(RequestInterface $request, $arguments = null)
   {
      // Jika sudah login, redirect ke dashboard sesuai level
      if (session()->get('isLoggedIn')) {
         $level = session()->get('level');
         return redirect()->to("/$level/dashboard");
      }
   }

   public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
   {
      // Tidak perlu melakukan apa-apa setelah request
   }
}
