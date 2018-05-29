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
        /* params is a controller plugin that provides a convenient way to retrieve parameters from the matched route. */ 
        $id = (int) $this->params()->fromRoute('id', 0);
        
        if(0 === $id) {
            return $this->redilect()->toRoute('album', ['action' => 'add']);
        }
        
        /* Retrieve(ŒŸõ‚·‚é) the album with the specified id, Doing so raises
         * an exception if the album is not found, which should result
         * in redirecting to the landing page. */         
        try {
            $album = $this->table->getAlbum($id);
        } catch (\Exception $e) {
            return $this->redidect()->toRoute('album', ['action' => 'index']);
        }
 
        /* The form's bind() method attaches the model to the form. This is used in two ways:
         * When displaying the form, the initial values for each element are extracted from the model.
         * After successful validation in isValid(), the data from the form is put back into the model. */
        $form = new AlbumForm();
        $form->bind($album);
        $form->get('submit')->setAttribute('value', 'Edit');
        
        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];
        
        if (! $request->isPost()) {
            return $viewData;
        }
        
        $form->setInputFilter($album->getInputFilter());
        $form->setData($request->getPost());
        
        if (! $form->isValid()) {
            return $viewData;
        }
        
        $this->table->saveAlbum($album);
        
        //Redirect to album list
        return $this->redirect()->toRoute('album', ['action' => 'index']);
    }
    
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('album');
        }
            
        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');
            
            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deletealbum($id);
            }
            
            // Redirect to list of albums
            return $this->redirect()->toRoute('album');
        }
        
        return [
            'id' => $id,
            'album' => $this->table->getAlbum($id),
        ];
    }
}