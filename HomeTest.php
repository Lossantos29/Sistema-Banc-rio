<?php
use PHPUnit\Framework\TestCase;

class HomeTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        // Mock da conexão com o banco de dados
        $this->conn = $this->createMock(mysqli::class);

        // Mock da preparação de declarações
        $this->stmt = $this->createMock(mysqli_stmt::class);

        // Configuração do comportamento do banco
        $this->conn->method('prepare')
            ->willReturn($this->stmt);
    }

    public function testRedirectIfNotLoggedIn()
    {
        // Simulação de sessão
        $_SESSION = [];

        // Captura saída do header()
        $this->expectOutputRegex('/Location: login\.php/');

        if (!isset($_SESSION['user_name'])) {
            header("Location: login.php");
            exit();
        }

        $this->fail("O usuário não foi redirecionado adequadamente.");
    }

    public function testGenerateUniqueCode()
    {
        // Simulação de dados da sessão
        $_SESSION['user_email'] = 'teste@exemplo.com';

        // Simulação do retorno da consulta SQL
        $this->stmt->method('get_result')
            ->willReturn($this->createMock(mysqli_result::class));

        // Simulação do fetch_assoc()
        $this->stmt->method('fetch_assoc')
            ->willReturn([
                'codigo_unico' => null
            ]);

        $email = $_SESSION['user_email'];

        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (empty($user['codigo_unico'])) {
            $codigoUnico = uniqid("USER");

            $sqlUpdate = "UPDATE usuarios SET codigo_unico = ? WHERE email = ?";
            $stmtUpdate = $this->conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("ss", $codigoUnico, $email);
            $stmtUpdate->execute();

            $this->assertNotEmpty($codigoUnico);
        }
    }

    public function testFetchUserDetails()
    {
        $_SESSION['user_email'] = 'teste@exemplo.com';
        $email = $_SESSION['user_email'];

        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $this->assertTrue($stmt->execute());
    }
}

