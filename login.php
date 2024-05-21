<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $senha = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM usuario WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        
        if (password_verify($senha, $usuario['Senha'])) {
            $_SESSION['usuario_id'] = $usuario['ID'];
            $_SESSION['usuario_email'] = $usuario['Email'];
            
            // Recupera informações adicionais do usuário, incluindo PJ_PF e Id_geral
            $stmt_funcao = $conn->prepare("SELECT Primeiro_Nome, ADM, PJ_PF, Id_geral FROM funcao_user WHERE ID = ?");
            $stmt_funcao->bind_param("i", $usuario['ID']);
            $stmt_funcao->execute();
            $resultado_funcao = $stmt_funcao->get_result();
            
            if ($resultado_funcao->num_rows > 0) {
                $funcao_usuario = $resultado_funcao->fetch_assoc();

                if (isset($funcao_usuario['Primeiro_Nome'])) {
                    $_SESSION['usuario_nome'] = $funcao_usuario['Primeiro_Nome'];
                } else {
                    $_SESSION['usuario_nome'] = 'Valor Padrão';
                }
                
                if (isset($funcao_usuario['ADM'])) {
                    $_SESSION['usuario_adm'] = $funcao_usuario['ADM'];
                }

                if (isset($funcao_usuario['PJ_PF'])) {
                    $_SESSION['usuario_pj_pf'] = $funcao_usuario['PJ_PF'];
                }

                if (isset($funcao_usuario['Id_geral'])) {
                    $_SESSION['usuario_id_geral'] = $funcao_usuario['Id_geral'];
                }
            }

            header("Location: main.php");
            exit();
        } else {
            header("Location: index.html?erro=senha_incorreta");
            exit();
        }
    } else {
        header("Location: index.html?erro=usuario_nao_encontrado");
        exit();
    }

    $stmt->close();
} else {
    header("Location: index.html?erro=dados_incompletos");
    exit();
}

$conn->close();
?>
