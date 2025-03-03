<?php namespace App\Controllers\Admin;

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
        // Jika ada request method POST, maka proses tambah kategori
        if ($this->request->getMethod() == 'post') {
            // Validasi input
            $validation =  \Config\Services::validation();
            if (!$this->validate([
                'nama_category' => 'required|min_length[3]|max_length[255]',
            ])) {
                // Menampilkan pesan error jika validasi gagal
                return redirect()->to('admin/category-product/create')->withInput()->with('validation', $validation->getErrors());
            }

            // Menyimpan data kategori ke database
            $categoryModel = new CategoryModel();
            $categoryModel->save([
                'nama_category' => $this->request->getPost('nama_category'),
            ]);

            session()->setFlashdata('success_message', 'Kategori berhasil ditambahkan.');
            return redirect()->to('admin/category-product');
        }

        return view('admin/category/create');
    }

    public function store()
    {
        // Validasi input
        if (!$this->validate([
            'nama_category' => 'required|min_length[3]|max_length[255]',
        ])) {
            // Jika validasi gagal, kirimkan kembali ke form dengan pesan error
            return redirect()->to('admin/category-product/create')->withInput()->with('validation', \Config\Services::validation()->getErrors());
        }

        // Menyimpan data kategori ke database
        $categoryModel = new CategoryModel();
        $categoryModel->save([
            'nama_category' => $this->request->getPost('nama_category'),
        ]);

        // Menampilkan pesan sukses setelah berhasil menyimpan
        session()->setFlashdata('success_message', 'Kategori berhasil ditambahkan.');
        return redirect()->to('admin/category-product');
    }

    public function edit($id)
    {
        // Mengambil data kategori berdasarkan ID
        $categoryModel = new CategoryModel();
        $category = $categoryModel->find($id);

        // Jika kategori tidak ditemukan, redirect ke halaman kategori
        if (!$category) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kategori tidak ditemukan');
        }

        // Jika ada request method POST, maka proses update kategori
        if ($this->request->getMethod() == 'POST') {
            // Validasi input
            $validation =  \Config\Services::validation();
            if (!$this->validate([
                'nama_category' => 'required|min_length[3]|max_length[255]',
            ])) {
                // Menampilkan pesan error jika validasi gagal
                session()->setFlashdata('success_message', 'Kategori berhasil diubah.');
                return redirect()->to('admin/category-product/edit/' . $id)->withInput()->with('validation', $validation->getErrors());
            }

            // Mengupdate data kategori
            $categoryModel->update($id, [
                'nama_category' => $this->request->getPost('nama_category'),
            ]);

            session()->setFlashdata('success_message', 'Kategori berhasil diperbarui.');
            return redirect()->to('admin/category-product');
        }

        return view('admin/category/edit', ['category' => $category]);
    }

    public function delete($id)
    {
        $categoryModel = new CategoryModel();

        // Menghapus kategori berdasarkan ID
        if ($categoryModel->delete($id)) {
            session()->setFlashdata('success_message', 'Kategori berhasil dihapus.');
        } else {
            session()->setFlashdata('error_message', 'Terjadi kesalahan saat menghapus kategori.');
        }

        return redirect()->to('admin/category-product');
    }

}
