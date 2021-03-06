<?php

App::uses('AppModel', 'Model');

class User extends AppModel {

    public function index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('user', $this->User->read(null, $id));
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                __('The user could not be saved. Please, try again.')
            );
        }
    }

    public function edit($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                __('The user could not be saved. Please, try again.')
            );
        } else {
            $this->request->data = $this->User->read(null, $id);
        }
    }

    public function delete($id = null,  $cascade = true) {    
        $this->request->onlyAllow('post');

        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->User->delete()) {
            $this->Session->setFlash(__('User deleted'));
            return $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('User was not deleted'));
        return $this->redirect(array('controller'=> 'users','action' => 'index'));
    }
    

    public function uploadUsers($uploadData){

        $handle = fopen($uploadData['tmp_name'], 'r');
        $names = fgetcsv($handle);
        $numUsers = count($names);
        $data = array();
        for($i = 0; $i < $numUsers; $i++)
        {
            
                $row['firstname'] = "";
                $row['lastname'] = "";
                $row['username'] = substr($names[$i], 0, strpos($names[$i], "@"));
                $row['type'] = "student";
                $data[$i] = $row;
        }
        $this->saveMany($data);
        return true; 
    }
}