<?php namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table      = 'category_product';  // Nama tabel tetap 'category_product'
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nama_category'];

    // Menambahkan validation rules jika diperlukan
    protected $validationRules = [
        'nama_category' => 'required|min_length[3]|max_length[255]',
    ];

    protected $validationMessages = [];
}
