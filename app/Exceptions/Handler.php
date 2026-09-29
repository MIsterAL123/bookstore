<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // 419 = token CSRF tidak valid / sesi sudah berakhir. Ini kondisi normal
        // (mis. tombol logout ditekan dua kali, atau halaman di-refresh setelah
        // keluar), bukan bug aplikasi, jadi tidak perlu masuk log.
        //
        // Catatan: TokenMismatchException sudah dipetakan menjadi HttpException(419)
        // oleh Handler::mapException() SEBELUM callback renderable dipanggil,
        // jadi di sini harus menangkap HttpException.
        $this->renderable(function (HttpException $e, $request) {
            if ($e->getStatusCode() !== 419) {
                return null;
            }

            return $this->renderSessionExpired($request);
        });
    }

    /**
     * Halaman "sesi berakhir": gunakan tema BookStore, dan arahkan ke beranda
     * untuk permintaan non-GET supaya pengguna tidak melihat URL /logout lagi.
     */
    protected function renderSessionExpired(Request $request): Response
    {
        if ($request->isMethod('GET')) {
            return response()->view('errors.419', ['exception' => new HttpException(419)], 419);
        }

        // Permintaan non-GET (mis. POST /logout diulang setelah keluar):
        // kembalikan ke beranda supaya URL /logout tidak pernah terlihat lagi.
        return redirect()->route('home');
    }
}
