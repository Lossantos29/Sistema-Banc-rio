<?php

use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
    private $url = 'http://localhost/verify.php';

    public function testFormSubmission()
    {
        // Simula a submissão do formulário
        $data = ['document_number' => '123456789'];
        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ],
        ];
        $context  = stream_context_create($options);
        $result = file_get_contents($this->url, false, $context);

        // Verifica se a resposta contém o texto esperado
        $this->assertStringContainsString('Verificação realizada com sucesso', $result);
    }

    public function testEmptyDocumentNumber()
    {
        // Simula a submissão com campo vazio
        $data = ['document_number' => ''];
        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ],
        ];
        $context  = stream_context_create($options);
        $result = file_get_contents($this->url, false, $context);

        // Verifica se a resposta contém o erro esperado
        $this->assertStringContainsString('Número de documento inválido', $result);
    }
}
