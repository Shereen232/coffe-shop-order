<?php 

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;  // Mengubah referensi ke CategoryModel
use App\Models\ProductModel;

class ProductController extends BaseController
{
    public function __construct()
    {
        $this->categoryModel = new CategoryModel();  // Menggunakan CategoryModel
        $this->productModel = new ProductModel();
    }

    // Menampilkan daftar produk
    public function index()
    {
        $data['categories'] = $this->categoryModel->findAll();  // Mengambil kategori produk
        $data['products'] = $this->productModel->findAll();
        return view('admin/product/index', $data);
    }

    // Menampilkan form tambah produk
    public function create()
    {
        $data['categories'] = $this->categoryModel->findAll();  // Mengambil kategori produk
        return view('admin/product/create', $data);
    }

    // Menyimpan produk baru
    public function store()
    {
        $data = $this->request->getPost();

        // Validasi data produk
        if (!$this->validate([
            'name'        => 'required|min_length[3]|max_length[255]',
            'description' => 'required',
            'price'       => 'required|decimal',
            'stock'       => 'required|integer',
            'category_id' => 'required|integer',
        ])) {
            return redirect()->back()->withInput();
        }

        // Menangani upload gambar
        $image = $this->request->getFile('image');
        if ($image && $image->isValid()) {
            $imageName = $image->getRandomName();
            $image->move('uploads/products', $imageName);
        }

        $this->productModel->save([
            'name'        => $data['name'],
            'description' => $data['description'],
            'price'       => $data['price'],
            'stock'       => $data['stock'],
            'image'       => $imageName ?? null,
            'category_id' => $data['category_id'],
        ]);

        return redirect()->to('/admin/products')->with('success', 'Product added successfully.');
    }

    // Menampilkan form edit produk
    public function edit($id)
    {
        $data['product'] = $this->productModel->find($id);
        $data['categories'] = $this->categoryModel->findAll();  // Mengambil kategori produk
        return view('admin/product/edit', $data);
    }

    // Mengupdate produk
    public function update($id)
    {
        $data = $this->request->getPost();

        // Validasi data produk
        if (!$this->validate([
            'name'        => 'required|min_length[3]|max_length[255]',
            'description' => 'required',
            'price'       => 'required|decimal',
            'stock'       => 'required|integer',
            'category_id' => 'required|integer',
        ])) {
            return redirect()->back()->withInput();
        }

        // Menangani upload gambar
        $image = $this->request->getFile('image');
        if ($image && $image->isValid()) {
            $imageName = $image->getRandomName();
            $image->move('uploads/products', $imageName);
        }

        $this->productModel->update($id, [
            'name'        => $data['name'],
            'description' => $data['description'],
            'price'       => $data['price'],
            'stock'       => $data['stock'],
            'image'       => $imageName ?? null,
            'category_id' => $data['category_id'],
        ]);

        return redirect()->to('/admin/products')->with('success', 'Product updated successfully.');
    }

    // Menghapus produk
    public function delete($id)
    {
        $this->productModel->delete($id);
        return redirect()->to('/admin/products')->with('success', 'Product deleted successfully.');
    }
}
