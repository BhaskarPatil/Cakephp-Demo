<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Utility\Security;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ForbiddenException;

class AppController extends Controller
{

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('DisplayErrorMessage');
        // $this->loadComponent('Auth', [
        //     'authError'    => 'Your session has expired. Please log in again',
        //     'loginRedirect' => [
        //     'controller' => 'users',
        //     'action' => 'index'
        //     ],
        //     'logoutRedirect' => [
        //     'controller' => 'users',
        //     'action' => 'login'
        //      ],
        //     'authenticate' => [
        //     'Form' => [
        //     'userModel' => 'users',
        //     'fields' => [
        //     'username' => 'username',
        //     'password' => 'password' ]]],
        //     'loginAction' => [
        //     'controller' => 'users',
        //     'action' => 'login'
        //     ],
        //     'unauthorizedRedirect' => $this->referer()
    //]);
    $this->Auth->allow(['login']);
       // $this->loadComponent('Security');
        //$this->loadComponent('Csrf');
    }
    
    public function beforeFilter(Event $event)
    {
        
        //$requestURL = $this->request->here();
       
        // if (strpos($requestURL, '/api/') === false) 
        // {
        //   //return $this->redirect('/welcome.php');
        // }
        // else
        // {
        //     $apiKey=$this->request->header('ApiKey');
        //     if(!$apiKey)
        //     {
        //         throw new UnauthorizedException("Please Provide API Key.");
        //     }
        //     else 
        //     {
        //         $apiKeys = TableRegistry::get('api_keys');
        //         $dbApikey =  $apiKeys
        //                         ->find()
        //                         ->where(['api_key'=>$apiKey,'is_active' => true])
        //                         ->first();

        //         if($dbApikey)
        //         {
        //             $blowfished = Security::hash($dbApikey->application_secret.'-'.$dbApikey->client_secret, 'sha1', true);
        //             if ($blowfished === $dbApikey->api_key) {
                        
        //                 //return true; // Api key is correct.
        //             }
        //             else {
        //                 throw new UnauthorizedException("2 Invalid Apikey! "); 
        //             }
        //         }
        //         else {                   
        //             throw new UnauthorizedException("3 Invalid Apikey!"); 
        //         }
        //     }
        // }        
    }
    public function requestLogData(){
       $requestlogs = TableRegistry::get('RequestLogs');
       $requests=$requestlogs->newEntity();
       $requests->data=serialize($this->request->getData());
       $requests->header=serialize($this->request->getServerParams());
       $requests->cookies=serialize($this->request->cookies);
       $requests->uri=$this->request->here;
       $requests->hostname=$this->request->header('host');
       $requests->user_agent=$this->request->header('user_agent');
       $requests->ip=$this->request->clientIp();
       $requests->apikey=$this->request->header('apikey');
      if($requestlogs->save($requests)){}

    }
    public function beforeRender(Event $event)
    {
        $this->requestLogData();
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }

}
