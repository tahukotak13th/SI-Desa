<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class SekretarisFilter implements FilterInterface
{
   public function before(RequestInterface $request, $arguments = null)
   {
      // Check if user is logged in and has sekretaris role
      if (!session()->get('is_logged_in')) {
         return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
      }

      if (session()->get('level') != 'sekretaris') {
         return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini');
      }
   }

   public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
   {
      // Do something here if needed
   }
}
