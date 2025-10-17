<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use App\Models\Carrinho;
use App\Models\Pagamento;
use App\Models\Pedido;

class PagamentoController extends Controller
{
    public function checkout(Request $request)
    {
        $user = Auth::user();

        try {
            $carrinho = Carrinho::where('id_usuario', $user->id)
                ->with('itens.produto')
                ->first();

            if (!$carrinho || $carrinho->itens->isEmpty()) {
                return redirect()->route('carrinho.index')->with('error', 'O carrinho está vazio.');
            }

            // Cria pedido se não existir
            $pedido = Pedido::create([
                'id_usuario' => $user->id,
                'status' => 'Pendente',
                'total' => $carrinho->itens->sum(fn($i) => $i->produto->preco * $i->quantidade)
            ]);

            // Monta itens para PagSeguro
            $itens = [];
            foreach ($carrinho->itens as $item) {
                $itens[] = [
                    'name' => $item->produto->nome,
                    'quantity' => (int) $item->quantidade,
                    'unit_amount' => (int) round($item->produto->preco * 100),
                ];
            }

            $appUrl = env('APP_URL', 'http://localhost:8000');

            $payload = [
                'reference_id' => 'PEDIDO-' . uniqid(),
                'customer' => [
                    'name' => $user->nome ?? 'Cliente Teste',
                    'email' => $user->email ?? env('PAGSEGURO_EMAIL'),
                    'tax_id' => $user->cpf ?? '52998224725',
                    'phones' => [[
                        'country' => '55',
                        'area' => '11',
                        'number' => '999999999',
                        'type' => 'MOBILE'
                    ]]
                ],
                'items' => $itens,
                'shipping' => [
                    'address' => [
                        'street' => $request->rua ?? 'Rua Teste',
                        'number' => $request->numero ?? '123',
                        'complement' => $request->complemento ?: 'S/N',
                        'locality' => $request->bairro ?? 'Centro',
                        'city' => $request->cidade ?? 'São Paulo',
                        'region_code' => $request->estado ?? 'SP',
                        'country' => 'BRA',
                        'postal_code' => preg_replace('/\D/', '', $request->cep ?? '01452002')
                    ],
                    'type' => 'FREE'
                ],
                'redirect_url' => $appUrl . '/pagamentos/sucesso',
                //'notification_urls' => [$appUrl . '/pagamentos/notificacao'], // Comente se não usar ngrok
                'payment_methods' => [
                    ['type' => 'CREDIT_CARD', 'brands' => ['visa','mastercard']],
                    ['type' => 'PIX'],
                    ['type' => 'BOLETO']
                ]
            ];

            \Log::info('Payload PagSeguro: ' . json_encode($payload, JSON_PRETTY_PRINT));

            $client = new Client();
            $response = $client->post(rtrim(env('PAGSEGURO_API_URL'), '/') . '/orders', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('PAGSEGURO_TOKEN'),
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ],
                'json' => $payload
            ]);

            $data = json_decode($response->getBody(), true);
            \Log::info('Resposta PagSeguro: ', $data);

            if (!empty($data['id'])) {
                Pagamento::create([
                    'id_pedido' => $pedido->id,
                    'forma_pagamento' => 'PagSeguro Sandbox',
                    'status' => 'Pendente',
                    'checkout_id' => $data['id'],
                ]);

                // Redireciona para link de pagamento
                if (!empty($data['links'][1]['href'])) {
                    return redirect()->away($data['links'][1]['href']);
                }
            }

            return redirect()->route('pagamentos.erro')
                ->with('error', 'Não foi possível gerar o link de pagamento.');

        } catch (\Exception $e) {
            \Log::error('Erro ao criar checkout PagSeguro: ' . $e->getMessage());
            return redirect()->route('pagamentos.erro')
                ->with('error', 'Erro ao criar checkout: ' . $e->getMessage());
        }
    }

    public function sucesso()
    {
        return view('pagamentos.sucesso');
    }

    public function erro()
    {
        return view('pagamentos.erro');
    }

    public function notificacao(Request $request)
    {
        \Log::info('Notificação PagSeguro:', $request->all());
        return response()->json(['success' => true]);
    }
}
