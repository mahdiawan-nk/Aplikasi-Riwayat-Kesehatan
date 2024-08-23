<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Adldap\Laravel\Facades\Adldap;
use PhpParser\Node\Stmt\TryCatch;

class LDAPController extends Controller
{

    public function checkLdapConnection(Request $request)
    {
        try {
            // Coba koneksi ke server LDAP
            $connected = Adldap::getConnection()->isBound();

            if ($connected) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Koneksi ke server LDAP berhasil.'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal terhubung ke server LDAP.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }
    public function getEmployees()
    {
        // Ambil semua data karyawan
        try {
            // Ambil semua pengguna dari server LDAP
            $users = Adldap::search()->findBaseDn();


            // Jika tidak ada pengguna yang ditemukan
            // if ($users->isEmpty()) {
                
            //     return response()->json([
            //         'status' => 'success',
            //         'message' => 'Tidak ada pengguna yang ditemukan.'
            //     ]);
            // }

            // Array untuk menyimpan data pengguna
            // $userData = [];

            // foreach ($users as $user) {
            //     $userData[] = [
            //         'name'  => $user->getCommonName(),
            //         'email' => $user->getEmail(),
            //         // Tambahkan atribut lain sesuai kebutuhan Anda
            //         'username' => $user->getAccountName(),
            //         'department' => $user->getDepartment(),
            //         'title' => $user->getTitle(),
            //     ];
            // }

            return response()->json([
                'status' => 'success',
                'data' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }

    public function addUsers() {
        
        try {
            $user = Adldap::make()->user([
                'cn' => 'John Doe',
            ]);
            return response()->json([
                'status' => 'success',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }
}
