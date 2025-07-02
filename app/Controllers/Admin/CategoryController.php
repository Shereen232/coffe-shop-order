<?php 

namespace App\Controllers\Admin;

use App\Models\CategoryModel;

class CategoryController extends BaseController
{
    public function index()
    {
        $categoryModel = new CategoryModel();
        $data['categories'] = $categoryModel->findAll();  // Mengambil semua data kategori produk
        return view('admin/category/index', $data);  // Menampilkan kategori produk di view
    }

    public function create()
    {
        return view('admin/category/create');
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'nama_category' => 'required|min_length[3]|max_length[255]',
            'image' => 'permit_empty|is_image[image]|max_size[image,2048]|mime_in[image,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('admin/category-product/create')
                            ->withInput()
                            ->with('validation', $validation->getErrors());
        }

        $imageFile = $this->request->getFile('image');
        $imageName = null;

        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            // Pastikan folder ada
            if (!is_dir('uploads/category')) {
                mkdir('uploads/category', 0755, true);
            }
            
            $imageName = $imageFile->getRandomName();
            $imageFile->move('uploads/category', $imageName);
        }

        $categoryModel = new CategoryModel();
        $categoryModel->save([
            'nama_category' => $this->request->getPost('nama_category'),
            'image' => $imageName,
        ]);

        session()->setFlashdata('success_message', 'Kategori berhasil ditambahkan.');
        return redirect()->to('admin/category-product');
    }

    public function edit($id)
    {
        $categoryModel = new CategoryModel();
        $category = $categoryModel->find($id);

        if (!$category) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kategori tidak ditemukan');
        }

        // ✅ Tampilkan form edit
        return view('admin/category/edit', ['category' => $category]);
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();

        $rules = [
            'nama_category' => 'required|min_length[3]|max_length[255]',
            'image' => 'permit_empty|is_image[image]|max_size[image,2048]|mime_in[image,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('admin/category-product/edit/' . $id)
                            ->withInput()
                            ->with('validation', $validation->getErrors());
        }

        $categoryModel = new CategoryModel();
        $category = $categoryModel->find($id);

        if (!$category) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kategori tidak ditemukan');
        }

        $imageFile = $this->request->getFile('image');
        $imageName = $category['image'];

        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            // Hapus gambar lama jika ada
            if (!empty($imageName) && file_exists('uploads/category/' . $imageName)) {
                unlink('uploads/category/' . $imageName);
            }

            // Pastikan folder ada
            if (!is_dir('uploads/category')) {
                mkdir('uploads/category', 0755, true);
            }

            $imageName = $imageFile->getRandomName();
            $imageFile->move('uploads/category', $imageName);
        }

        $categoryModel->update($id, [
            'nama_category' => $this->request->getPost('nama_category'),
            'image' => $imageName,
        ]);

        session()->setFlashdata('success_message', 'Kategori berhasil diperbarui.');
        return redirect()->to('admin/category-product');
    }

    public function delete($id)
    {
        $categoryModel = new CategoryModel();
        $category = $categoryModel->find($id);

        if ($category && !empty($category['image']) && file_exists('uploads/category/' . $category['image'])) {
            // ✅ Hapus file gambar jika ada
            unlink('uploads/category/' . $category['image']);
        }

        if ($categoryModel->delete($id)) {
            session()->setFlashdata('success_message', 'Kategori berhasil dihapus.');
        } else {
            session()->setFlashdata('error_message', 'Terjadi kesalahan saat menghapus kategori.');
        }

        return redirect()->to('admin/category-product');
    }
}
