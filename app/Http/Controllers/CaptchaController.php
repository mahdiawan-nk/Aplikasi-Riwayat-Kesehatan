<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;

class CaptchaController extends Controller
{
    // Menampilkan CAPTCHA dalam bentuk matematika
    public function showMathCaptcha()
    {
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        $answer = $num1 + $num2;

        // Simpan jawaban ke dalam session
        Session::put('math_captcha', $answer);

        return response()->json([
            'question' => "What is $num1 + $num2?",
        ]);
    }

    // Menampilkan CAPTCHA dalam bentuk teks acak
    public function showTextCaptcha()
    {
        $captchaText = $this->generateRandomString(6);
        Session::put('text_captcha', $captchaText);

        return response()->json([
            'captcha' => $captchaText,
        ]);
    }

    public function showTextCaptchaImage()
    {
        $captchaText = $this->generateRandomString(6);
        Session::put('text_captcha', $captchaText);

        $image = imagecreatetruecolor(120, 40);
        $background = imagecolorallocate($image, 255, 255, 255); // Putih
        $textColor = imagecolorallocate($image, 56, 97, 57); // Hitam
        $lineColor = imagecolorallocate($image, 64, 64, 64); // Abu-abu

        imagefilledrectangle($image, 0, 0, 125, 40, $background);

        // Menambahkan beberapa garis untuk keamanan
        // for ($i = 0; $i < 5; $i++) {
        //     imageline($image, rand(0, 120), rand(0, 70), rand(0, 120), rand(0, 40), $lineColor);
        // }

        // Menambahkan teks CAPTCHA
        imagettftext($image, 20, 0, 10, 30, $textColor, public_path('assets/fonts/arial.ttf'), $captchaText);

        // Output gambar ke browser
        ob_start();
        imagepng($image);
        $imageData = ob_get_contents();
        ob_end_clean();

        imagedestroy($image);

        return Response::make($imageData, 200, ['Content-Type' => 'image/png']);
    }


    // Menampilkan CAPTCHA teks sebagai gambar
    // public function showTextCaptchaImage()
    // {
    //     $captchaText = $this->generateRandomString(6);
    //     Session::put('text_captcha', $captchaText);

    //     $image = imagecreatetruecolor(160, 50);
    //     $background = imagecolorallocate($image, 255, 255, 255); // Putih
    //     $lineColor = imagecolorallocate($image, 64, 64, 64); // Abu-abu

    //     imagefilledrectangle($image, 0, 0, 160, 50, $background);

    //     // Menambahkan beberapa garis untuk keamanan
    //     for ($i = 0; $i < 5; $i++) {
    //         imageline($image, rand(0, 160), rand(0, 50), rand(0, 160), rand(0, 50), $lineColor);
    //     }

    //     // Menambahkan teks CAPTCHA dengan warna acak untuk setiap karakter
    //     $fontPath = public_path('assets/fonts/arial.ttf');
    //     $fontSize = 20;
    //     $x = 10; // Koordinat awal x
    //     $y = 35; // Koordinat y

    //     for ($i = 0; $i < strlen($captchaText); $i++) {
    //         $text = $captchaText[$i];
    //         $textColor = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255)); // Warna acak

    //         imagettftext($image, $fontSize, rand(-10, 10), $x, $y, $textColor, $fontPath, $text);

    //         $x += $fontSize * 0.8; // Jarak antara karakter
    //     }

    //     // Output gambar ke browser
    //     ob_start();
    //     imagepng($image);
    //     $imageData = ob_get_contents();
    //     ob_end_clean();

    //     imagedestroy($image);

    //     return Response::make($imageData, 200, ['Content-Type' => 'image/png']);
    // }


    // Validasi CAPTCHA matematika
    public function validateMathCaptcha(Request $request)
    {
        $request->validate([
            'answer' => 'required|integer',
        ]);

        $answer = $request->input('answer');
        $correctAnswer = Session::get('math_captcha');

        if ($answer == $correctAnswer) {
            return response()->json(['message' => 'CAPTCHA valid'], 200);
        } else {
            return response()->json(['message' => 'CAPTCHA invalid'], 200);
        }
    }

    // Validasi CAPTCHA teks
    public function validateTextCaptcha(Request $request)
    {
        $request->validate([
            'captcha' => 'required|string',
        ]);

        $captcha = $request->input('captcha');
        $correctCaptcha = Session::get('text_captcha');

        if ($captcha === $correctCaptcha) {
            return response()->json(['success'=> true,'message' => 'CAPTCHA valid'], 200);
        } else {
            return response()->json(['success'=> true,'message' => 'CAPTCHA invalid','captcha' => $captcha,'correctCaptcha' => $correctCaptcha], 400);
        }
    }

    // Generate random string for text CAPTCHA
    private function generateRandomString($length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
