<?php
namespace Album\Controller;

use Album\Model\AlbumTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Form\AlbumForm;
use Album\Model\Album;

class AlbumController extends AbstractActionController
{
    private $table;
    
    public function __construct(AlbumTable $table)
    {
        $this->table = $table;
    }
    
    public function indexAction()
    {
        return new ViewModel([
            'albums' => $this->table->fetchAll(),
        ]);
    }
    
    public function addAction()
    {
        /* We instantiate AlbumForm and set the label on the submit button to "Add". */ 
        $form = new AlbumForm();
        $form->get('submit')->setValue('Add');
        
        $request = $this->getRequest();
        
        /* If the request is not a POST request, 
         * then no form data has been submitted and we need to display the form. */
        if (! $request->isPost()) {
            return ['form' => $form];
        }
        
        $album = new Album();
        $form->setInputFilter($album->getInputFilter());
        $form->setData($request->getPost());
        
        /* If form validation fails, we want to redisplay the form. */
        if (! $form->isValid()) {
            return ['form' => $form];
        }
        
        /* If the form is valid, then we grab the data from the form and store to the model using saveAlbum(). */
        $album->exchangeArray($form->getData());
        $this->table->saveAlbum($album);
        
        /* After we have saved the new album row, we redirect back to the list of albums using the Redirect controller plugin. */
        return $this->redirect()->toRoute('album');
    }
    
    public function editAction()
    {
    }
    
    public function deleteAction()
    {
    }
}