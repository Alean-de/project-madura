@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center">

    <div>
        <h1>Kategori</h1>
        <p>lorem ipsum dolor sit amet.</p>
    </div>

    <button type="button" class="btn" style="background-color: #1B3C53; color: white" data-bs-toggle="modal" data-bs-target="#kategoriModal"> 
        <i class="bi bi-plus-lg"></i>
        Buat Kategori
   </button>

</div>

<div class="container">
    <form action="{{ route('produk.store') }}" method="POST" id="formBuatKategori">
        @csrf 
        <div class="modal fade" id="kategoriModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="kategoriModalLabel">Buat Kategori</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">           
                        <input type="text" placeholder="Nama Kategori" class="form-control" required>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn" style="background-color: #1B3C53; color: white">Tambahkan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Jumlah Produk</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>Makanan</td>
                <td>12</td>
                <td>Aktif</td>

                <td class="d-flex gap-2">

                    <!-- DELETE -->
                    <form action="" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="button"
                            class="btn btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteModal">

                            <i class="bi bi-trash3"></i>
                            Hapus
                        </button>

                        <div class="modal fade" id="deleteModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Hapus</h5>

                                        <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal">
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        Anda yakin ingin menghapus data ini?
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button"
                                            class="btn btn-secondary"
                                            data-bs-dismiss="modal">

                                            Batal
                                        </button>

                                        <button type="submit"
                                            class="btn btn-danger">

                                            Ya
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>


                    <!-- EDIT -->
                    <form action="" method="POST">
                        @csrf
                        @method('PUT')

                        <button type="button"
                            class="btn btn-warning"
                            data-bs-toggle="modal"
                            data-bs-target="#editModal">

                            <i class="bi bi-pencil-square"></i>
                            Edit
                        </button>

                        <div class="modal fade" id="editModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            Edit Produk
                                        </h5>

                                        <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal">
                                        </button>
                                    </div>

                                    <div class="modal-body">

                                        <input type="text"
                                            name="nama_produk"
                                            class="form-control"
                                            placeholder="Nama Produk">

                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit"
                                            class="btn btn-primary">

                                            Simpan
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </form>

                </td>
            </tr>
        </tbody>
    </table>
</div>

@endsection