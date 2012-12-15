<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of components
 *
 * @author dioner911
 */
class homeComponents extends sfComponents {
    public function executeCategorias(sfWebRequest $request)
    {
        $this->categorias = Doctrine_Core::getTable('CategoriasPublicaciones')->getAllActives();
    }
    


}
?>

