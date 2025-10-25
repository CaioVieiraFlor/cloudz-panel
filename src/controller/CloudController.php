<?php

use CloudZ\CloudService;
use CloudZ\CloudServiceFile;
use CloudZ\DeleteCloudServiceFile;
use CloudZ\GoogleDrive\GoogleDriveHelper;

require 'Controller.php';

class CloudController extends Controller
{
    public static function index()
    {
        if (!empty($_POST)) {
            if ($_POST['service'] == 'AWS-S3') {
                $account = [
                    'serviceType' => $_POST['service'],
                    'key' => $_POST['aws-s3-key'],
                    'secretKey' => $_POST['aws-s3-secret-key'],
                    'region' => $_POST['aws-s3-region'],
                    'bucketName' => $_POST['aws-s3-bucket-name'],
                    'type' => 's3'
                ];
            } elseif ($_POST['service'] == 'FTP') {
                $account = [
                    'serviceType' => $_POST['service'],
                    'host' => $_POST['host'],
                    'user' => $_POST['user'],
                    'password' => $_POST['password'],
                    'port' => $_POST['port'],
                    'isPassive' => ($_POST['passive']),
                    'useSSH' => $_POST['use-ssh']
                ];
            } elseif ($_POST['service'] == 'GOOGLE-DRIVE') {
                $account = [
                    'serviceType' => $_POST['service'],
                    'clientId' => $_POST['client-id'],
                    'clientSecret' => $_POST['client-secret'],
                    'refreshToken' => $_POST['refresh-token'],
                    'folderName' => $_POST['folder-name'],
                    'type' => 'GOOGLE-DRIVE'
                ];
            }

            $cloudService = new CloudService($account['serviceType'], $account);
            $cloudServiceAccount = $cloudService->account;

            if ($account['serviceType'] == 'GOOGLE-DRIVE') {
                $googleDriveHelper = new GoogleDriveHelper($cloudServiceAccount);
                if (!$googleDriveHelper->testConnection()) {
                    echo "❌ Erro na conexão com Google Drive!\n";
                    exit;
                }

                $pastaId = $googleDriveHelper->findFolderByName($account['folderName']);
                if (!$pastaId) {
                    $pastaId = $googleDriveHelper->createFolder($account['folderName']);
                }

                $cloudServiceAccount->folderId = $pastaId;
                $cloudService->settings->add('makePublic', true);
            }

            $cloudService->settings->add('canEncryptName', false);
            $cloudService->settings->add('canDeleteAfterUpload', false);

            if (!empty($_FILES['attachments'])) {
                self::upload($cloudService);
            } else {
                self::delete($cloudService);
            }
        } else {
            require PATH_VIEW . 'cloud/index.php';
        }
    }

    public static function upload(CloudService $cloudService)
    {
        try {
            $results = [];
            foreach ($_FILES['attachments']['tmp_name'] as $index => $tmpName) {
                $originalName = $_FILES['attachments']['name'][$index];
                $file = new CloudServiceFile($tmpName, $originalName);
                $uploadResult = $cloudService->upload($file);

                $results[] = [
                    'url' => $uploadResult->getUrl(),
                    'name' => $originalName
                ];
            }

            http_response_code(200);
            echo json_encode(['files' => $results]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public static function delete(CloudService $cloudService)
    {
        try {
            $url = $_GET['url'];
            $deleteFile = new DeleteCloudServiceFile($url);
            $deleteResult = $cloudService->delete($deleteFile);

            http_response_code(200);
        } catch (Exception $e) {
            http_response_code(500);    
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

CloudController::index();
