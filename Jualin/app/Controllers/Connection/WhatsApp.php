<?php

namespace App\Controllers\Connection;

use App\Controllers\BaseController;
use App\Models\StoreModel;

class WhatsApp extends BaseController
{
    protected $storeModel;
    protected $waApiHost = 'http://localhost:3000/api'; // Variabel untuk host API

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->storeModel = new StoreModel();
    }

    /**
     * Display the WhatsApp connection page.
     *
     * @param string $AccountId
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function index($AccountId)
{
    if (!$this->request->isAJAX()) return redirect()->back();
    $stores = $this->storeModel->getStoreByAccountId($AccountId);
    $client = \Config\Services::curlrequest();
    $devices = [];
    $errors = [];

    foreach ($stores as $store) {
        $url = $this->waApiHost . '/devices/' . $store['StoreId'];

        log_message('info', 'Calling WhatsApp API: ' . $url);

        try {
            $response = $client->get($url, [
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody();

            log_message('info', 'WhatsApp API responded with status: ' . $statusCode);
            log_message('info', 'Response body: ' . $body);

            $data = json_decode($body, true);
            if ($statusCode == 200 && isset($data['success']) && $data['success'] == true) {
                $devices[] = [
                    'deviceId' => $data['data']['deviceId'],
                    'status' => $data['data']['status'],
                    'phoneNumber' => $data['data']['phone'],
                    'storeId' => $store['StoreId'],
                    'storeName' => $store['StoreName'] ?? 'Unknown Store'
                ];
            } else {
                $errors[] = [
                    'storeId' => $store['StoreId'],
                    'statusCode' => $statusCode,
                    'response' => $data['error'] ?? 'No error message',
                    'message' => 'API responded with non-success status code'
                ];
            }
        } catch (\Exception $e) {
            $errors[] = [
                'storeId' => $store['StoreId'],
                'error' => $e->getMessage(),
                'message' => 'Exception caught while requesting device info'
            ];
            log_message('error', 'Exception while calling WhatsApp API: ' . $e->getMessage());
        }

        // Cek QR
        try {
            $cekqr = $client->get($this->waApiHost . '/devices/' . $store['StoreId'] . '/qr', [
                'headers' => ['Content-Type' => 'application/json']
            ]);

            log_message('info', 'Checking QR code for store: ' . $store['StoreId']);

            if ($cekqr) {
                $response = json_decode($cekqr->getBody());
                if ($response->success == true) {
                    $hasil = $response->data;
                    $devices[] = [
                        'deviceId' => $hasil->deviceId,
                        'qrImageUrl' => $hasil->qrImageUrl,
                        'status' => 'qr_pending',
                    ];
                    log_message('info', 'QR code found for store : ' . $store['StoreId']);
                } else {
                    log_message('error', 'QR code response not successful for store : ' . $store['StoreId']);
                }
            }
        } catch (\Exception $e) {
            $errors[] = [
                'storeId' => $store['StoreId'],
                'error' => $e->getMessage(),
                'message' => 'Exception caught while requesting QR code'
            ];
            log_message('error', 'Exception while getting QR code: ' . $e->getMessage());
        }
    }

    return $this->response->setJSON([
        'status' => count($devices) > 0 ? 'success' : 'error',
        'count' => count($devices),
        'devices' => $devices,
        'errors' => $errors,
        'queryTime' => date('Y-m-d H:i:s')
    ]);
}


    /**
     * Create a new WhatsApp device connection
     *
     * @param string $AccountId
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function Create($AccountId)
    {
        if (!$this->request->isAJAX()) return redirect()->back();

        $client = \Config\Services::curlrequest();
        $store = $this->storeModel->getStoreByAccountId($AccountId);

        if (empty($store)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Tidak ada toko ditemukan untuk akun ini'
            ]);
        }

        try {
            $url = $this->waApiHost . '/devices/';

            // Log URL for debugging
            log_message('info', 'Creating WhatsApp device: ' . $url);
            log_message('info', 'Store data: ' . json_encode($store[0]));

            $response = $client->post($url, [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode(['deviceId' => $store[0]['StoreId']])
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody();

            // Log response for debugging
            log_message('info', 'WhatsApp API create responded with status: ' . $statusCode);
            log_message('info', 'Response body: ' . $body);

            $data = json_decode($body, true);

            if ($data['success'] == true) {
                $device = $data['data'];
                $getQR = $client->get($this->waApiHost . '/devices/' . $device['deviceId'] . '/qr', [
                    'headers' => ['Content-Type' => 'application/json']
                ]);
                $qrData = json_decode($getQR->getBody(), true);
                if ($qrData['success'] == true) {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'qrScanUrl' => $qrData['data']['qrImageUrl']

                    ]);
                } else {
                }
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal membuat device WhatsApp',
                    'apiResponse' => $data,
                    'statusCode' => $statusCode
                ]);
            }
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getCode() . ' : ' . $e->getMessage(),
                'deviceId' => $store[0]['StoreId'] ?? 'Unknown',
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => ENVIRONMENT !== 'production' ? $e->getTraceAsString() : null
            ]);
        }
    }

    /**
     * Get device status
     * 
     * @param string $deviceId
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getStatus($deviceId)
    {
        if (!$this->request->isAJAX()) return redirect()->back();

        $client = \Config\Services::curlrequest();

        try {
            $url = $this->waApiHost . '/api/devices/' . $deviceId;

            $response = $client->get($url, [
                'headers' => ['Content-Type' => 'application/json'],
                'http_errors' => false
            ]);

            $statusCode = $response->getStatusCode();
            $data = json_decode($response->getBody(), true);

            if ($statusCode === 200 && isset($data['device'])) {
                $device = $data['device'];
                return $this->response->setJSON([
                    'status' => 'success',
                    'deviceStatus' => $device['status'],
                    'phoneNumber' => $device['info']['number'] ?? 'Unknown',
                    'lastActive' => $device['lastActive'] ?? 'Never',
                    'deviceId' => $device['deviceId']
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Tidak dapat mengambil status device',
                    'apiResponse' => $data,
                    'statusCode' => $statusCode
                ]);
            }
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getCode() . ' : ' . $e->getMessage(),
                'deviceId' => $deviceId
            ]);
        }
    }

    /**
     * Logout from WhatsApp device
     * 
     * @param string $deviceId
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function logout($deviceId)
    {
        if (!$this->request->isAJAX()) return redirect()->back();

        $client = \Config\Services::curlrequest();

        try {
            $url = $this->waApiHost . '/api/device/' . $deviceId . '/logout';

            $response = $client->post($url, [
                'headers' => ['Content-Type' => 'application/json'],
                'http_errors' => false
            ]);

            $statusCode = $response->getStatusCode();
            $data = json_decode($response->getBody(), true);

            if ($statusCode === 200) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Berhasil logout dari WhatsApp',
                    'deviceId' => $deviceId
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal logout dari WhatsApp',
                    'apiResponse' => $data,
                    'statusCode' => $statusCode
                ]);
            }
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getCode() . ' : ' . $e->getMessage(),
                'deviceId' => $deviceId
            ]);
        }
    }

    /**
     * Delete WhatsApp device
     * 
     * @param string $deviceId
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function Delete($deviceId)
    {
        if (!$this->request->isAJAX()) return redirect()->back();

        $client = \Config\Services::curlrequest();

        try {
            $url = $this->waApiHost . '/devices/' . $deviceId;

            $response = $client->delete($url, [
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $statusCode = $response->getStatusCode();
            $data = json_decode($response->getBody(), true);

            if ($data['success'] == true) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Berhasil menghapus device WhatsApp',
                    'deviceId' => $deviceId
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal menghapus device WhatsApp',
                    'apiResponse' => $data,
                    'statusCode' => $statusCode
                ]);
            }
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getCode() . ' : ' . $e->getMessage(),
                'deviceId' => $deviceId
            ]);
        }
    }
}
