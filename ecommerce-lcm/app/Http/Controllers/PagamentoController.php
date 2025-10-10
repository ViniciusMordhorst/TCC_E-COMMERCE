<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use Illuminate\Support\Facades\Http; // importante para usar requisições HTTP

class PagamentoController extends Controller
{
    public function pagar($pedidoId)
    {
        $pedido = Pedido::findOrFail($pedidoId);
        $urlRetorno = route('pagamento.retorno', ['pedidoId' => $pedido->id]);

        $data = [
            'email' => 'EMAIL_DO_PAGSEGURO',
            'token' => 'TOKEN_DO_PAGSEGURO',
            'currency' => 'BRL',
            'itemId1' => $pedido->id,
            'itemDescription1' => 'Compra no Meu Site',
            'itemAmount1' => number_format($pedido->total, 2, '.', ''),
            'itemQuantity1' => 1,
            'reference' => $pedido->id,
            'redirectURL' => $urlRetorno,
        ];

        $query = http_build_query($data);
        return redirect()->away("https://pagseguro.uol.com.br/v2/checkout/payment.html?$query");
    }

    public function retorno(Request $request, $pedidoId)
    {
        $pedido = Pedido::findOrFail($pedidoId);

        // ID da transação retornada pelo PagSeguro
        $transactionId = $request->get('transaction_id');

        if (!$transactionId) {
            return redirect()->route('carrinho.sucesso', ['pedidoId' => $pedido->id])
                             ->with('warning', 'Não foi possível confirmar o pagamento.');
        }

        // Chama a API do PagSeguro
        $email = 'EMAIL_DO_PAGSEGURO';
        $token = 'TOKEN_DO_PAGSEGURO';
        $url = "https://api.pagseguro.uol.com.br/v3/transactions/{$transactionId}?email={$email}&token={$token}";

        try {
            $response = Http::get($url);

            if ($response->failed()) {
                return redirect()->route('carrinho.sucesso', ['pedidoId' => $pedido->id])
                                 ->with('error', 'Falha ao consultar o pagamento.');
            }

            $xml = simplexml_load_string($response->body());
            $status = (string) $xml->status;

            // Status possíveis:
            // 1 = Aguardando pagamento
            // 2 = Em análise
            // 3 = Paga
            // 4 = Disponível
            // 5 = Em disputa
            // 6 = Devolvida
            // 7 = Cancelada

            if ($status == '3' || $status == '4') {
                $pedido->status = 'Pago';
                $pedido->save();

                return redirect()->route('carrinho.sucesso', ['pedidoId' => $pedido->id])
                                 ->with('success', 'Pagamento confirmado com sucesso!');
            } else {
                $pedido->status = 'Pendente';
                $pedido->save();

                return redirect()->route('carrinho.sucesso', ['pedidoId' => $pedido->id])
                                 ->with('warning', 'Pagamento ainda não foi aprovado.');
            }

        } catch (\Exception $e) {
            return redirect()->route('carrinho.sucesso', ['pedidoId' => $pedido->id])
                             ->with('error', 'Erro ao verificar pagamento: ' . $e->getMessage());
        }
    }
}
