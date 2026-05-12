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
            @foreach ($produk as $p)
            <tr>
                <td>{{ $p->nama_produk }}</td>    
                <td>{{ $p->kategori }}</td>
                <td>{{ $p->supplier }}</td>
                <td>Rp {{ number_format($p->harga_jual, 0, ',', '.') }}</td>
                <td>{{ $p->stok_minimum }}</td> 
                <td>{{ $p->satuan }}</td>
                <td>
                    <form action="{{ route('produk.destroy', $p->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash3"></i>
                            Hapus
                        </button>
                        
                        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

                    <form action="{{ route('produk.update', $p->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $p->id }}">
                            <i class="bi bi-pencil-square"></i>
                            Edit
                        </button>
                        
                        <div class="modal fade" id="editModal{{ $p->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="editModalLabel">Tambahkan Produk</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">           
                                                
                                            <input type="text" value="{{ $p->nama_produk }}" name="nama_produk" placeholder="Nama Produk" class="form-control mb-3" required>

                                            <select value="{{ $p->kategori_id }}" name="kategori" id="kategori" class="form-select mb-3" required>
                                                <option value="" selected disabled>Pilih Kategori</option>
                                                <option value="1">Makanan</option>
                                                <option value="2">Minuman</option>
                                                <option value="3">Sembako</option>
                                            </select>

                                            <select value="{{ $p->supplier_id }}" name="supplier" id="supplier" class="form-select mb-3" required>
                                                <option value="" selected disabled>Pilih Supplier</option>
                                                <option value="1">PT.XYZ</option>
                                            </select>

                                            <input type="number" min="0" step="1" value="{{ $p->harga_beli }}" name="harga_beli" placeholder="Harga Beli" class="form-control mb-3" required>
                                                
                                            <input type="number" min="0" step="1" value="{{ $p->harga_jual }}" name="harga_jual" placeholder="Harga Jual" class="form-control mb-3" required>
                                            
                                            <input type="text" value="{{ $p->stok_awal }}" name="stok_awal" placeholder="Stok Awal" class="form-control mb-3" required>

                                            <input type="text" value="{{ $p->stok_minimum }}" name="stok_minimum" placeholder="Stok Minimum" class="form-control mb-3" required>
                                                
                                            <input type="text" value="{{ $p->satuan }}" name="satuan" placeholder="Satuan" class="form-control mb-3" required>   
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
                            
                        <input type="text" name="nama_produk" placeholder="Nama Produk" class="form-control mb-3" required>

                        <select name="kategori" id="kategori" class="form-select mb-3" required>
                            <option value="" selected disabled>Pilih Kategori</option>
                            <option value="1">Makanan</option>
                            <option value="2">Minuman</option>
                            <option value="3">Sembako</option>
                        </select>

                        <select name="supplier" id="supplier" class="form-select mb-3" required>
                            <option value="" selected disabled>Pilih Supplier</option>
                            <option value="1">PT.XYZ</option>
                        </select>

                        <input type="number" min="0" step="1" name="harga_beli" placeholder="Harga Beli" class="form-control mb-3" required>
                            
                        <input type="number" min="0" step="1" name="harga_jual" placeholder="Harga Jual" class="form-control mb-3" required>
                        
                        <input type="text" name="stok_awal" placeholder="Stok Awal" class="form-control mb-3" required>

                        <input type="text" name="stok_minimum" placeholder="Stok Minimum" class="form-control mb-3" required>
                            
                        <input type="text" name="satuan" placeholder="Satuan" class="form-control mb-3" required>   
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn" style="background-color: #1B3C53; color: white">Tambahkan</button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection