<?php

use PHPUnit\Framework\TestCase;

class CadastroTest extends TestCase
{
    private $url = 'http://localhost:8000/save_user.php';

    public function testCadastroComSucesso()
    {
        // Simula os dados do formulário preenchidos
        $data = [
            'bi_passaporte' => '123456789',
            'nome_completo' => 'João Silva',
            'telefone' => '912345678',
            'email' => 'joao@example.com',
            'morada' => 'Rua Principal, 123',
            'senha' => 'senhaSegura123'
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ],
        ];
        $context  = stream_context_create($options);
        $result = file_get_contents($this->url, false, $context);

        // Verifica se a resposta contém uma mensagem de sucesso
        $this->assertStringContainsString('Cadastro realizado com sucesso', $result);
    }

    public function testCadastroComCampoFaltando()
    {
        // Simula os dados do formulário com campo faltando
        $data = [
            'bi_passaporte' => '123456789',
            'nome_completo' => '',
            'telefone' => '912345678',
            'email' => 'joao@example.com',
            'morada' => 'Rua Principal, 123',
            'senha' => 'senhaSegura123'
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ],
        ];
        $context  = stream_context_create($options);
        $result = file_get_contents($this->url, false, $context);

        // Verifica se a resposta contém uma mensagem de erro
        $this->assertStringContainsString('Todos os campos são obrigatórios', $result);
    }

    public function testCadastroComEmailInvalido()
    {
        // Simula os dados do formulário com email inválido
        $data = [
            'bi_passaporte' => '123456789',
            'nome_completo' => 'João Silva',
            'telefone' => '912345678',
            'email' => 'joaoexample.com', // Email inválido
            'morada' => 'Rua Principal, 123',
            'senha' => 'senhaSegura123'
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ],
        ];
        $context  = stream_context_create($options);
        $result = file_get_contents($this->url, false, $context);

        // Verifica se a resposta contém uma mensagem de erro
        $this->assertStringContainsString('Email inválido', $result);
    }
}
