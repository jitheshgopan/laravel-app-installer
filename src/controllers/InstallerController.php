<?php
namespace Jitheshgopan\AppInstaller;
use Illuminate\Support\Facades\Config;


class InstallerController extends \BaseController {
    public function index(){
        //$stages = Config::get('app-installer::stages');
        $installer = new Installer();

        //Requirements check stage
        $this->_setupRequirementsStage($installer);

        //Directory permissions stage
        $this->_directoryPermissionsStage($installer);

        //Database config stage
        $this->_setupDbConnectionStage($installer);

        $this->_setupFinishStage($installer);

        $dbConfig = require(app_path('config/database.php'));

        try {
            return $installer->run();
        } catch (InstallerException $e){
            return Response::make($e->getMessage(), 400);
        }

    }

    public function _setupRequirementsStage($installer){
        $requirementsStage = $installer->addStage("System requirements", [

        ]);

        //Php version
        $requirementsStage->addPhpVersionCheckStep('5.5', '>=');

        //GD extension check
        $gdExtensionCheck = $requirementsStage->addStep("Checking GD Extension", [
            'type' => 'ExtensionCheck'
        ]);
        $gdExtensionCheck->check('gd');

        //PDO extension
        $pdoCheck = $requirementsStage->addStep("Checking PDO Extension", [
            'type' => 'ExtensionCheck'
        ]);
        $pdoCheck->check('pdo');
    }

    public function _setupDbConnectionStage($installer){
        $dbConfigStage = $installer->addStage("Database connection", [

        ]);
        $dbConfigStep = $dbConfigStage->addDbConfigStep('mysql', [
            'configFilePath' => app_path('config/database.php')
        ]);
    }

    public function _directoryPermissionsStage($installer) {
        $directoryWritableStage = $installer->addStage("Directory permissions", [

        ]);
        //Is directory writable check
        $writableCheck = $directoryWritableStage->addStep("Checking if the 'config' directory is writable", [
            'type' => 'WritableCheck'
        ]);
        $writableCheck->checkWritable(app_path('config'));
    }

    public function _setupFinishStage($installer){
        $finishStage = $installer->addFinishStage("Installation Complete", [
            'proceedUrl' => '/admin/login',
            'proceedUrlText' => 'Go to admin panel'
        ]);
    }
}