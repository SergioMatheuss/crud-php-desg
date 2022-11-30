<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ProfessorRepository;
use Dompdf\Dompdf;
use App\Model\Professor;
use Exception;

class ProfessorController extends AbstractController
{
    private ProfessorRepository $repository;

    public function __construct()
    {
        $this->repository = new ProfessorRepository();
    }

    public function listar(): void
    {
        $this->checkLogin();
        
        $rep = new ProfessorRepository();
        $professores = $rep->buscarTodos();

        $this->render("professor/listar", [
            'professores' => $professores,
        ]);
    }

    public function cadastrar(): void
    {
        $this->checkLogin();
        $professores = new Professor();
        if (true === empty($_POST)) {
            $this->render("professor/cadastrar", ['professores' => $professores, 
        
        ]);
        }

        $professor = new Professor();
        $professor->nome = $_POST['nome'];
        $professor->cpf = $_POST['cpf'];

        try {
            $this->repository->inserir($professor);
        } catch (Exception $exception) {
            if (true === str_contains($exception->getMessage(), 'nome')) {
                die('nome ja existe');
            }

            if (true === str_contains($exception->getMessage(), 'cpf')) {
                die('cpf ja existe');
            }
        }

        $this->redirect('/professores/listar');
    }

    public function excluir(): void
    {
        $id = $_GET['id'];
        $rep = new ProfessorRepository();
        $rep->excluir($id);
        $this->redirect("/professores/listar");
    }

    public function editar(): void
    {
        $this->checkLogin();

        $id = $_GET['id'];

        $rep = new ProfessorRepository();
        $professor = $rep->buscarUm($id);
        $this->render('professor/editar', [$professor]);
        if (false === empty($_POST)) {
            $professor->nome = $_POST['nome'];
            $professor->cpf = $_POST['cpf'];
            
    
            try {
                $rep->atualizar($professor, $id);
            } catch (Exception $exception) {
                if (true === str_contains($exception->getMessage(), 'nome')) {
                    die('nome ja existe');
                }
    
                if (true === str_contains($exception->getMessage(), 'cpf')) {
                    die('CPF ja existe');
                }
    
                die('Vish, aconteceu um erro');
            }
            $this->redirect('/professores/listar');
        }
    }

    private function renderizar(iterable $professores)
    {
        $resultado = '';
        foreach ($professores as $professor){

            $resultado .= "
            <tr>
            <td>{$professor['professor_id']}</td>
            <td>{$professor['professor_nome']}</td>
            </tr>
            ";
        }
        return $resultado;
    }
    public function relatorio(): void
    {
        $hoje = date('d/m/Y');

        $professores = $this->repository->buscarTodos();
        
        $design =  "
            <h1>Relatorio de Alunos</h1>
            <hr>
            <em>Gerado em {$hoje}</em>
            <br>
            <table border='1' width='100%' style='margin-top: 30px;'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Status</th>
                        <th>Descrição</th>
                        <th>Carga Horaria</th>
                        <th>Categoria</th>
                    </tr>
                </thead>
                <tbody>
                ".$this->renderizar($professores)."
                </tbody>
            </table>
        ";
        $dompdf = new Dompdf();
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->loadHtml($design);
        $dompdf->render();
        $dompdf->stream('relatorio-professores.pdf', ['Attachment' => 0,]);
    }
}