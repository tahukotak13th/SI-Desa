<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
   public function before(RequestInterface $request, $arguments = null)
   {
      if (!session()->get('isLoggedIn') || session()->get('level') !== 'admin') {
         return redirect()->to('/login')->with('error', 'Akses ditolak');
      }
   }

   public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
   {
      // Tidak perlu action setelah request
   }
}
