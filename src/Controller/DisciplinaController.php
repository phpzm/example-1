<?php

namespace Projeto\Controller;

use Projeto\Model\DisciplinaModel;


/**
 * Class DisciplinaController
 * @package Projeto\Controller
 */
class DisciplinaController
{
    /**
     * @return string
     */
    public function cadastrar()
    {
        try {
            $disciplina = new DisciplinaModel();
            $campos = $disciplina->getPost();
            if ($disciplina->insert($campos)) {
                return 'Cadastro salvo com sucesso!';
            } else {
                return 'Falha no cadastro!';
            }

        } catch (\Throwable $e) {
            return 'Erro: ' . $e->getMessage() . PHP_EOL . 'Linha: ' . $e->getLine() . PHP_EOL;
        }
    }
}