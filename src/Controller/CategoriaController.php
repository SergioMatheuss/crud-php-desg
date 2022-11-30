<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Categoria;
use App\Repository\CategoriaRepository;


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
        $categoria->id = $_POST['id'];
        $categoria->nome = $_POST['nome'];
        // $categoria->descricao = $_POST['descricao'];
        // $categoria->cargaHoraria = intval($_POST['cargaHoraria']);
        // $categoria->categoria_id = intval($_POST['categoria']);

        $this->repository->inserir($categoria);
       $this->redirect('/categorias/listar');
    }

    public function excluir(): void
    {
        echo "Pagina de excluir";
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
            // $categoria->descricao = $_POST['descricao'];
            // $categoria->cargaHoraria = intval($_POST['cargaHoraria']);
            // $categoria->categoria_id = intval($_POST['categoria']);
            $this->repository->atualizar($categoria, $id);
            $this->redirect('/categorias/listar');
        }
    }
}