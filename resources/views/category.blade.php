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
    <form action="{{ route('category.create') }}" method="POST" id="formBuatKategori">
        @csrf 
        <div class="modal fade" id="kategoriModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="kategoriModalLabel">Buat Kategori</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">           
                        <input type="text" placeholder="Nama Kategori" name="category_name" class="form-control" required>
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
            </tr>
        </thead>

        <tbody>
            @foreach ($category as $c)
            <tr>
                <td>{{ $c->category_name }}</td>
                <td>{{ $c->products_count }}</td>
                <td>
                    <form action="{{ route('category.status', $c->category_id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="status" onchange="this.form.submit()" class="form-control">
                            <option value="1" {{ $c->status ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !$c->status ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection