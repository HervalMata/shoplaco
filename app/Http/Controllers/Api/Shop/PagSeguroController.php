<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use App\Models\PagSeguro;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PagSeguroController extends Controller
{
    /**
     * @param PagSeguro $pagseguro
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function pagseguro(PagSeguro $pagseguro)
    {
        /** @var PagSeguro $pagseguro */
        $code = $pagseguro->generate();
        $urlRedirect = config('pagseguro.url_redirect_after_request') . $code;
        dd($urlRedirect);
        return $urlRedirect;
    }

    /**
     * @return Factory|View
     */
    public function transparente()
    {
        return view('pagseguro-transparente');
    }

    public function getCode(PagSeguro $pagSeguro)
    {
        return $pagSeguro->getSessionId();
    }

    public function billet(Request $request, PagSeguro $pagSeguro)
    {
        $sendHash = $request->get('sendHash');
        return $pagSeguro->paymentBillet($sendHash);
    }
}
