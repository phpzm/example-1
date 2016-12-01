<?php

namespace Projeto\Model;

/**
 * Class DisciplinaModel
 * @package Projeto\Model
 */
class DisciplinaModel
{
    /**
     * @return array
     */
    public function getPost()
    {
        return [];
    }

    /**
     * @param $campos
     * @return bool
     */
    public function insert($campos)
    {
        return count($campos) > 0;
    }
}