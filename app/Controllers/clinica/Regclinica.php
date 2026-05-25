<?php

namespace App\Controllers\Clinica;

use App\Controllers\BaseController;
use App\Models\Modelgeneral;
use App\Models\ClinicaModel;

class Regclinica extends BaseController
{
    private const LOGO_PATH = 'vendor/uploads/logo/';

    protected Modelgeneral $modelGeneral;
    protected ClinicaModel $clinicaModel;

    public function __construct()
    {
        $this->modelGeneral = new Modelgeneral();
        $this->clinicaModel = new ClinicaModel();

        helper('url');
    }

    public function index()
    {
        if (!session()->get('login')) {
            return redirect()->to(base_url());
        }

        return
            view('layouts/header') .
            view('layouts/aside') .
            view('clinica/viewclinica') .
            view('layouts/footer');
    }

    public function jsonClinica()
    {
        if (!session()->get('login')) {
            return $this->response->setJSON([
                'error' => 'No autorizado',
            ]);
        }

        $list = $this->clinicaModel->getDatatables();

        $data = [];

        foreach ($list as $clinica) {
            $row = [];

            $row[] = $clinica->nomb_clin;
            $row[] = $clinica->direc_clin;
            $row[] = $clinica->telf_clin;
            $row[] = $clinica->email_clin;
            $row[] = $clinica->ruc_clin;

            if (!empty($clinica->photo)) {
                $row[] = '
                    <a href="' . base_url(self::LOGO_PATH . $clinica->photo) . '" target="_blank">
                        <img src="' . base_url(self::LOGO_PATH . $clinica->photo) . '"
                             class="img-responsive" />
                    </a>
                ';
            } else {
                $row[] = '(No photo)';
            }

            $row[] = '
                <div class="btn-footer text-center">
                    <a class="btn btn-sm btn-success"
                       style="padding:2px 5px;margin:0px 2px"
                       href="javascript:void(0)"
                       title="Edit"
                       onclick="edit_clinica(\'' . $clinica->id_clin . '\')">

                        <i class="fa fa-edit"></i>

                    </a>
                </div>
            ';

            $data[] = $row;
        }

        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => $this->clinicaModel->countAllClinicas(),
            'recordsFiltered' => $this->clinicaModel->countFiltered(),
            'data'            => $data,
        ]);
    }

    public function ajaxEdit(int $id)
    {
        if (!session()->get('login')) {
            return $this->response->setJSON([
                'error' => 'No autorizado',
            ]);
        }

        $data = $this->clinicaModel->getById($id);

        if ($data && $data->fecha_clin === '0000-00-00') {
            $data->fecha_clin = '';
        }

        return $this->response->setJSON($data);
    }

    public function ajaxUpdate()
    {
        if (!session()->get('login')) {
            return $this->response->setJSON([
                'status' => false,
                'error'  => 'No autorizado',
            ]);
        }

        $idClin = $this->request->getPost('id_clin');

        $data = [
            'nomb_clin'  => $this->request->getPost('nomb_clin'),
            'direc_clin' => $this->request->getPost('direc_clin'),
            'telf_clin'  => $this->request->getPost('telf_clin'),
            'email_clin' => $this->request->getPost('email_clin'),
            'ruc_clin'   => $this->request->getPost('ruc_clin'),
            'fecha_clin' => $this->request->getPost('fecha_clin'),
        ];

        $removePhoto = $this->request->getPost('remove_photo');

        if (!empty($removePhoto)) {
            $path = ROOTPATH . self::LOGO_PATH . $removePhoto;

            if (is_file($path)) {
                unlink($path);
            }

            $data['photo'] = '';
        }

        $photo = $this->request->getFile('photo');

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $upload = $this->doUpload($photo);

            if (!$upload['status']) {
                return $this->response->setJSON($upload);
            }

            $clinica = $this->clinicaModel->getById((int) $idClin);

            if ($clinica && !empty($clinica->photo)) {
                $oldPath = ROOTPATH . self::LOGO_PATH . $clinica->photo;

                if (is_file($oldPath)) {
                    unlink($oldPath);
                }
            }

            $data['photo'] = $upload['file_name'];
        }

        $this->clinicaModel->updateData(
            ['id_clin' => $idClin],
            $data
        );

        return $this->response->setJSON([
            'status' => true,
        ]);
    }

    private function doUpload($photo): array
    {
        $uploadPath = ROOTPATH . self::LOGO_PATH;

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $validTypes = [
            'image/png',
            'image/jpg',
            'image/jpeg',
        ];

        if (!in_array($photo->getMimeType(), $validTypes, true)) {
            return [
                'inputerror'   => ['photo'],
                'error_string' => ['Solo se permiten imágenes PNG, JPG o JPEG'],
                'status'       => false,
            ];
        }

        if ($photo->getSizeByUnit('kb') > 100) {
            return [
                'inputerror'   => ['photo'],
                'error_string' => ['La imagen no debe superar los 100 KB'],
                'status'       => false,
            ];
        }

        $imageInfo = getimagesize($photo->getTempName());

        if ($imageInfo !== false) {
            [$width, $height] = $imageInfo;

            if ($width > 1000 || $height > 1000) {
                return [
                    'inputerror'   => ['photo'],
                    'error_string' => ['La imagen no debe superar 1000x1000 px'],
                    'status'       => false,
                ];
            }
        }

        $fileName = 'logogeneral-' . time() . '.' . $photo->getExtension();

        $photo->move($uploadPath, $fileName);

        return [
            'status'    => true,
            'file_name' => $fileName,
        ];
    }
}
