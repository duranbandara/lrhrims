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
        if (strtolower($this->request->getMethod()) === 'post') {
            if (! $this->validate($this->rules)) {
                return $this->render('labsection/add', [
                    'title'      => 'Add Lab Section',
                    'validation' => $this->validator,
                ]);
            }
            $data = ['section_name' => $this->request->getPost('section_name')];
            if ($this->model->insertRow('lab_sections', $data)) {
                $this->log('create', 'Lab Section', 'Added lab section: ' . $data['section_name']);
                return redirect()->to(base_url('labsection'))->with('message', 'Lab section added successfully!');
            }
            return redirect()->to(base_url('labsection/add'))->with('error', 'Something went wrong.');
        }
        return $this->render('labsection/add', ['title' => 'Add Lab Section']);
    }

    public function edit(int $id)
    {
        if (strtolower($this->request->getMethod()) === 'post') {
            if (! $this->validate($this->rules)) {
                return $this->render('labsection/edit', [
                    'title'      => 'Edit Lab Section',
                    'section'    => $this->model->getRow('lab_sections', ['id_section' => $id]),
                    'validation' => $this->validator,
                ]);
            }
            $data = ['section_name' => $this->request->getPost('section_name')];
            if ($this->model->updateRow('lab_sections', 'id_section', $id, $data)) {
                $this->log('update', 'Lab Section', 'Updated lab section ID: ' . $id . ' — ' . $data['section_name']);
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
        $target = $this->model->getRow('lab_sections', ['id_section' => $id]);
        if ($this->model->deleteRow('lab_sections', 'id_section', $id)) {
            $this->log('delete', 'Lab Section', 'Deleted lab section: ' . ($target['section_name'] ?? $id));
            return redirect()->to(base_url('labsection'))->with('message', 'Lab section deleted!');
        }
        return redirect()->to(base_url('labsection'))->with('error', 'Something went wrong.');
    }
}
