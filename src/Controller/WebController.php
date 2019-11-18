<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use App\Model\Table\News1Table;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Spatie\Crawler\Crawler;
use App\getTitle;
use Cake\Http\Session;
use App\Model\Table\NewsTable;
use Cake\Datasource\ConnectionManager;

include dirname(__DIR__) . '/simple_html_dom.php';

/**
 * WebController
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 * @property NewsTable $News
 * @property News1Table $News1
 * @property Session Session
 */
class WebController extends Controller
{
    protected $connection;
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('News');
        $this->loadModel('News1');
        $this->connection = ConnectionManager::get('default');

        $this->viewBuilder()->setLayout('Front/index');
    }

    public function index()
    {
    }

    public function runScript()
    {
        try {
            $this->autoRender = false;
            $this->News->deleteAll('1 = 1');
            $this->connection->execute('ALTER TABLE news AUTO_INCREMENT = 1');
            $status = shell_exec("cd " . PATH_FILE_CSV . " && bin/cake LoadDataINet 2>&1");
            $data = $this->News->getAllNews()->toList();
            if (isset($status)) {
                echo json_encode(['error' => 0, 'data' => $data]);
            } else {
                echo json_encode(['error' => 1, 'status' => $status]);
            }
        } catch (\Exception $e) {
            $this->log($e);
        }
        die;
    }

    public function runScript1()
    {
        try {
            $this->autoRender = false;
            $this->News1->deleteAll('1 = 1');
            $this->connection->execute('ALTER TABLE news1 AUTO_INCREMENT = 1');
            $exe = "cd " . PATH_FILE_CSV . " && bin/cake LoadDataINetSwoole";
            $status = shell_exec($exe);
            $data = $this->News1->getAllNews()->toList();
            if (isset($status)) {
                echo json_encode(['error' => 0, 'data' => $data, 'status' => $status]);
            } else {
                echo json_encode(['error' => 1, 'status' => $status]);
            }
        } catch (\Exception $e) {
            $this->log($e);
        }
        die;
    }

    public function run()
    {
        try {
            $this->autoRender = false;
            $exe = "cd " . PATH_FILE_CSV . " && bin/cake LoadDataINetSwoole";
            $status = shell_exec($exe);
            dd($status);
        } catch (\Exception $e) {
            $this->log($e);
        }
        die;
    }
}
