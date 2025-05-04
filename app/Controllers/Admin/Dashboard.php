<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PejabatModel;

class Dashboard extends BaseController
{
   protected $userModel;
   protected $pejabatModel;

   public function __construct()
   {
      $this->userModel = new UserModel();
      $this->pejabatModel = new PejabatModel();
   }

   public function index()
   {
      // Cek session
      if (!session()->get('isLoggedIn') || session()->get('level') !== 'admin') {
         return redirect()->to('/login');
      }

      $data = [
         'title' => 'Dashboard Admin',
         'total_users' => $this->userModel->countAll(),
         'total_pejabat' => $this->pejabatModel->countAll(),
         'users' => $this->userModel->findAll(),
         'pejabat' => $this->pejabatModel->findAll()
      ];

      return view('admin/dashboard', $data);
   }
}
