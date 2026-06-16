<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\CustomerModel;

class AuthController extends BaseController
{
    public function login()
    {
        if (session()->get('customer_logged_in')) {
            return redirect()->to(base_url('shop'));
        }
        return view('customer/auth/login', ['title' => 'Masuk ke Akun Anda']);
    }

    public function redirectToGoogle()
    {
        $state = bin2hex(random_bytes(16));
        session()->set('oauth_state', $state);

        $params = http_build_query([
            'client_id'     => env('GOOGLE_CLIENT_ID'),
            'redirect_uri'  => base_url('shop/auth/callback'),
            'response_type' => 'code',
            'scope'         => 'openid email profile',
            'state'         => $state,
            'access_type'   => 'online',
            'prompt'        => 'select_account',
        ]);

        return redirect()->to('https://accounts.google.com/o/oauth2/v2/auth?' . $params);
    }

    public function callback()
    {
        $code  = $this->request->getGet('code');
        $state = $this->request->getGet('state');

        if (!$code || $state !== session()->get('oauth_state')) {
            return redirect()->to(base_url('shop/login'))
                ->with('error', 'Proses login gagal. Silakan coba lagi.');
        }

        session()->remove('oauth_state');

        $tokenData = $this->postToGoogle('https://oauth2.googleapis.com/token', [
            'code'          => $code,
            'client_id'     => env('GOOGLE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_CLIENT_SECRET'),
            'redirect_uri'  => base_url('shop/auth/callback'),
            'grant_type'    => 'authorization_code',
        ]);

        if (empty($tokenData['access_token'])) {
            return redirect()->to(base_url('shop/login'))
                ->with('error', 'Gagal mendapatkan token dari Google.');
        }

        $userInfo = $this->getFromGoogle(
            'https://www.googleapis.com/oauth2/v3/userinfo',
            $tokenData['access_token']
        );

        if (empty($userInfo['sub'])) {
            return redirect()->to(base_url('shop/login'))
                ->with('error', 'Gagal mengambil data pengguna dari Google.');
        }

        $customer = (new CustomerModel())->findOrCreateFromGoogle($userInfo);

        session()->set([
            'customer_logged_in' => true,
            'customer_id'        => $customer['id'],
            'customer_nama'      => $customer['nama'],
            'customer_email'     => $customer['email'],
            'customer_foto'      => $customer['foto'],
        ]);

        return redirect()->to(base_url('shop'))
            ->with('success', 'Selamat datang, ' . $customer['nama'] . '!');
    }

    public function logout()
    {
        session()->remove('customer_logged_in');
        session()->remove('customer_id');
        session()->remove('customer_nama');
        session()->remove('customer_email');
        session()->remove('customer_foto');
        session()->remove('cart');

        return redirect()->to(base_url('shop/login'))
            ->with('success', 'Anda telah berhasil logout.');
    }

    private function postToGoogle(string $url, array $data): array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_TIMEOUT        => 10,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response ?: '[]', true) ?? [];
    }

    private function getFromGoogle(string $url, string $accessToken): array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $accessToken],
            CURLOPT_TIMEOUT        => 10,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response ?: '[]', true) ?? [];
    }
}
