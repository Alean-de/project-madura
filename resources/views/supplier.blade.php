@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center">

    <div>
        <h1>Supplier</h1>
        <p>lorem ipsum dolor sit amet.</p>
    </div>

    <button type="button" class="btn" style="background-color: #1B3C53; color: white" data-bs-toggle="modal" data-bs-target="#supplierModal"> 
        <i class="bi bi-plus-lg"></i>
        Tambah Supplier
   </button>

</div>

<form action="{{ route('supplier.create') }}" method="POST" id="formBuatSupplier">
    @csrf 
    <div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="kategoriModalLabel">Tambahkan Supplier</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">           
                    <input type="text" placeholder="Nama Supplier" name="supplier_name" class="form-control mb-3" required>

                    <input type="number" placeholder="Kontak" name="contacts" class="form-control mb-3" required>

                    <input type="text" placeholder="Kota" name="city" class="form-control mb-3" required>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn" style="background-color: #1B3C53; color: white">Tambahkan</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="container">
    <table class="table">
        <thead>
            <tr>
                <th>Nama Supplier</th>
                <th>Contacts</th>
                <th>City</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($supplier as $s)
            <tr>
                <td>{{ $s->supplier_name }}</td>
                <td>{{ preg_replace('/(\d{4})(\d{4})(\d{4})/', '$1-$2-$3', $s->contacts) }}</td>
                <td>{{ $s->city }}</td>
                <td>
                    <form action="{{ route('supplier.status', $s->supplier_id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="status" onchange="this.form.submit()" class="form-control">
                            <option value="1" {{ $s->status ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !$s->status ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </form>
                </td>
                <td>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $s->supplier_id }}">
                        <i class="bi bi-trash3"></i>
                    </button>

                    <!-- DELETE -->
                    <form action="{{ route('supplier.delete', $s->supplier_id) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <div class="modal fade" id="deleteModal{{ $s->supplier_id }}" tabindex="-1">
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
                                        Anda yakin ingin menghapus {{ $s->supplier_name }} ?
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button"
                                            class="btn btn-secondary"
                                            data-bs-dismiss="modal">

                                            Batal
                                        </button>

                                        <button type="submit" class="btn btn-danger">
                                              Ya
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <form action="{{ route('supplier.update', $s->supplier_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editSupplierModal{{ $s->supplier_id }}">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <div class="modal fade" id="editSupplierModal{{ $s->supplier_id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit</h5>

                                        <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal">
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <input type="text" value="{{ $s->supplier_name }}" name="supplier_name" placeholder="Supplier Name" class="form-control mb-3" required>

                                        <input type="text" value="{{ $s->contacts }}" name="contacts" placeholder="contacts" class="form-control mb-3" required>
                                        
                                        <input type="text" value="{{ $s->city }}" name="city" placeholder="City" class="form-control mb-3" required>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button"
                                            class="btn btn-secondary"
                                            data-bs-dismiss="modal">

                                            Batal
                                        </button>

                                        <button type="submit" class="btn btn-primary">
                                              Ya
                                        </button>
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
@endsection