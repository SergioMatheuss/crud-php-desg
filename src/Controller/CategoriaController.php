<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Categoria;
use App\Repository\CategoriaRepository;
use Dompdf\Dompdf;


class CategoriaController extends AbstractController
{

    private CategoriaRepository $repository;

    public function __construct()
    {
        $this->repository = new CategoriaRepository();
    }

    public function listar(): void
    {
        $this->checkLogin();
        $rep = new CategoriaRepository();
        $categorias = $rep->buscarTodos();

        $this->render("categoria/listar", [
            'categorias' => $categorias,
        ]);
    }

    public function cadastrar(): void
    {
        
        $rep = new CategoriaRepository();
        if (true === empty($_POST)) {
            $categorias = $rep->buscarTodos();
            $this->render("/categoria/cadastrar", ['categorias' => $categorias]);
            return;
        }

        $categoria = new Categoria();
        $categoria->nome = $_POST['nome'];
        $this->repository->inserir($categoria);
       $this->redirect('/categorias/listar');
    }

    public function excluir(): void
    {
        $id = $_GET['id'];

        $this->repository->excluir($id);
        
        $this->redirect('/categorias/listar');
    }

    public function editar(): void
    {
        $id = $_GET['id'];
        $rep = new CategoriaRepository();
        $categorias = $rep->buscarTodos();
        $categoria = $this->repository->buscarUm($id);
        $this->render("/categoria/editar", [
            'categorias' => $categorias,
            'categoria' => $categoria
        ]);
        if (false === empty($_POST)) {
            $categoria = new Categoria();
            $categoria->nome = $_POST['nome'];
            $this->repository->atualizar($categoria, $id);
            $this->redirect('/categorias/listar');
        }
    }
    private function renderizar(iterable $categorias)
    {
        $resultado = '';
        foreach ($categorias as $categoria){
            $resultado .= "
            <tr>
            <td>{$categoria->id}</td>
            <td>{$categoria->nome}</td>
            </tr>
            ";
        }
        return $resultado;
    }
    public function relatorio(): void
    {
        $hoje = date('d/m/Y');

        $categorias = $this->repository->buscarTodos();
        
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
                    </tr>
                </thead>
                <tbody>
                ".$this->renderizar($categorias)."
                </tbody>
            </table>
        ";
        $dompdf = new Dompdf();
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->loadHtml($design);
        $dompdf->render();
        $dompdf->stream('relatorio-categorias.pdf', ['Attachment' => 0,]);
    }
}