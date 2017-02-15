<?php
use TYPO3\Surf\Domain\Model\Node;
use TYPO3\Surf\Domain\Model\SimpleWorkflow;

$application = new \TYPO3\Surf\Application\TYPO3\Flow();
$application->setOption('repositoryUrl', 'git@github.com:langeland/Distributions-Payout.git');
$application->setOption('localPackagePath', FLOW_PATH_DATA . 'Checkout' . DIRECTORY_SEPARATOR . $deployment->getName() . DIRECTORY_SEPARATOR . 'Live' . DIRECTORY_SEPARATOR);
$application->setOption('composerCommandPath', 'composer');
$application->setOption('transferMethod', 'rsync');
$application->setOption('packageMethod', 'git');
$application->setOption('updateMethod', NULL);
$application->setContext('Production');
$application->setDeploymentPath('/home/hostroot/sites/langeland/payout');
$application->setOption('keepReleases', 2);
$deployment->addApplication($application);


$workflow = new SimpleWorkflow();
$workflow->setEnableRollback(TRUE);


//Prevent local Settings . yaml from being transferred
//$workflow->defineTask(
//    'removeLocalConfiguration',
//    'TYPO3\\Surf\\Task\\ShellTask',
//    array(
//        'command' => 'cd "{releasePath}" && rm -f Configuration/Settings.yaml'
//    )
//);
//
//$workflow->beforeStage('migrate', array('removeLocalConfiguration'), $application);




$deployment->setWorkflow($workflow);

$deployment->onInitialize(function () use ($workflow, $application) {
    $workflow->setTaskOptions('typo3.surf:generic:createDirectories', array('directories' => array('shared/Data/Web/_Resources', 'shared/Data/Session')));
    $workflow->setTaskOptions('typo3.surf:generic:createSymlinks', array(
        'symlinks' => array(
            'Web/_Resources' => '../../../shared/Data/Web/_Resources/',
            'Data/Session' => '../../../shared/Data/Session/'
        )
    ));
});

$node = new Node('Flab');
$node->setHostname('anne.flab.dk');
$node->setOption('username', 'langeland');
$application->addNode($node);