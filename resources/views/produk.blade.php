@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center">

    <div>
        <h1>Produk</h1>
        <p>Kelola produk anda</p>
    </div>

    <button type="button" class="btn" style="background-color: #1B3C53; color: white" data-bs-toggle="modal" data-bs-target="#inputModal"> 
        <i class="bi bi-plus-lg"></i>
        Tambah Produk
   </button>

</div>

<div class="input-group mt-3">
  <input type="text" class="form-control" placeholder="Search" aria-label="Username" aria-describedby="basic-addon1">
</div>

<div class="container">

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Supplier</th>
                <th>Harga Jual</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($product as $p)
            <tr>
                <td>{{ $p->product_name }}</td>    
                <td>{{ $p->category->category_name }}</td>
                <td>{{ $p->supplier_id }}</td>
                <td>Rp {{ number_format($p->purchase_price, 0, ',', '.') }}</td>
                <td>{{ $p->minimum_stock }}</td> 
                <td>{{ $p->unit }}</td>
                <td>
                    <form action="{{ route('produk.destroy', $p->product_id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $p->product_id }}">
                            <i class="bi bi-trash3"></i>
                        </button>
                        
                        <div class="modal fade" id="deleteModal{{ $p->product_id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Hapus</h5>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Anda yakin ingin menghapus data ini?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Ya</button>
                                </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <form action="{{ route('produk.update', $p->product_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $p->product_id }}">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        
                        <div class="modal fade" id="editModal{{ $p->product_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="editModalLabel">Tambahkan Produk</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">           
                                                
                                            <input type="text" value="{{ $p->product_name }}" name="nama_produk" placeholder="Nama Produk" class="form-control mb-3" required>
                                                
                                            <select value="{{ $p->category_id }}" name="kategori" id="kategori" class="form-select mb-3" required>
                                                <option value="" selected disabled>Pilih Kategori</option>
                                                @foreach ($category as $c)
                                                    <option value="{{ $c->category_id }}">
                                                        {{ $c->category_name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <select value="{{ $p->supplier_id }}" name="supplier" id="supplier" class="form-select mb-3" required>
                                                <option value="" selected disabled>Pilih Supplier</option>
                                                <option value="1">PT.XYZ</option>
                                            </select>

                                            <input type="number" min="0" step="1" value="{{ $p->purchase_price }}" name="harga_beli" placeholder="Harga Beli" class="form-control mb-3" required>
                                                
                                            <input type="number" min="0" step="1" value="{{ $p->selling_price }}" name="harga_jual" placeholder="Harga Jual" class="form-control mb-3" required>
                                            
                                            <input type="text" value="{{ $p->initial_stock }}" name="stok_awal" placeholder="Stok Awal" class="form-control mb-3" required>

                                            <input type="text" value="{{ $p->minimum_stock }}" name="stok_minimum" placeholder="Stok Minimum" class="form-control mb-3" required>
                                                
                                            <input type="text" value="{{ $p->unit }}" name="satuan" placeholder="Satuan" class="form-control mb-3" required>   
                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn" style="background-color: #1B3C53; color: white">Simpan Perubahan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </td>    
            </tr>                
            @endforeach
        </tbody>
    </table>
</div>

<form action="{{ route('produk.store') }}" method="POST" id="formTambahProduk">
    @csrf 
    <div class="modal fade" id="inputModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="inputModalLabel">Tambahkan Produk</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">           
                            
                        <input type="text" name="product_name" placeholder="Nama Produk" class="form-control mb-3" required>

                        <select name="category_id" id="kategori" class="form-select mb-3" required>
                            <option value="" selected disabled>Pilih Kategori</option>
                            @foreach ($category as $c)
                                <option value="{{ $c->category_id }}">
                                     {{ $c->category_name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="supplier_id" id="supplier" class="form-select mb-3" required>
                            <option value="" selected disabled>Pilih Supplier</option>
                            <option value="1">PT.XYZ</option>
                        </select>

                        <input type="number" min="0" step="1" name="purchase_price" placeholder="Harga Beli" class="form-control mb-3" required>
                            
                        <input type="number" min="0" step="1" name="selling_price" placeholder="Harga Jual" class="form-control mb-3" required>
                        
                        <input type="text" name="initial_stock" placeholder="Stok Awal" class="form-control mb-3" required>

                        <input type="text" name="minimum_stock" placeholder="Stok Minimum" class="form-control mb-3" required>
                            
                        <input type="text" name="unit" placeholder="Satuan" class="form-control mb-3" required>   
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn" style="background-color: #1B3C53; color: white">Tambahkan</button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection