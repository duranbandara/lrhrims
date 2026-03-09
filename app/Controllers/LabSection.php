<?php

namespace App\Controllers;

class LabSection extends BaseAppController
{
    private array $rules = [
        'section_name' => 'required|trim|max_length[100]',
    ];

    public function index(): string
    {
        return $this->render('labsection/index', [
            'title'    => 'Lab Sections',
            'sections' => $this->model->getLabSections(),
        ]);
    }

    public function add()
    {
        if ($thisstrtolower(->request->getMethod()) === 'post') {
            if (! $this->validate($this->rules)) {
                return $this->render('labsection/add', [
                    'title'      => 'Add Lab Section',
                    'validation' => $this->validator,
                ]);
            }
            $data = ['section_name' => $this->request->getPost('section_name')];
            if ($this->model->insertRow('lab_sections', $data)) {
                return redirect()->to(base_url('labsection'))->with('message', 'Lab section added successfully!');
            }
            return redirect()->to(base_url('labsection/add'))->with('error', 'Something went wrong.');
        }
        return $this->render('labsection/add', ['title' => 'Add Lab Section']);
    }

    public function edit(int $id)
    {
        if ($thisstrtolower(->request->getMethod()) === 'post') {
            if (! $this->validate($this->rules)) {
                return $this->render('labsection/edit', [
                    'title'      => 'Edit Lab Section',
                    'section'    => $this->model->getRow('lab_sections', ['id_section' => $id]),
                    'validation' => $this->validator,
                ]);
            }
            $data = ['section_name' => $this->request->getPost('section_name')];
            if ($this->model->updateRow('lab_sections', 'id_section', $id, $data)) {
                return redirect()->to(base_url('labsection'))->with('message', 'Lab section updated!');
            }
            return redirect()->to(base_url("labsection/edit/{$id}"))->with('error', 'Something went wrong.');
        }
        return $this->render('labsection/edit', [
            'title'   => 'Edit Lab Section',
            'section' => $this->model->getRow('lab_sections', ['id_section' => $id]),
        ]);
    }

    public function delete(int $id)
    {
        if ($this->model->deleteRow('lab_sections', 'id_section', $id)) {
            return redirect()->to(base_url('labsection'))->with('message', 'Lab section deleted!');
        }
        return redirect()->to(base_url('labsection'))->with('error', 'Something went wrong.');
    }
}
