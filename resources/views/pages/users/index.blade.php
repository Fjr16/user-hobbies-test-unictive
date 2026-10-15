@extends('layouts.main')

@push('styles')
<style>
    .pagination {
        margin-bottom: 0;
        gap: 4px;
    }

    .pagination .page-link {
        border-radius: 6px !important;
        border: 1px solid #dee2e6;
        color: #495057;
        min-width: 36px;
        text-align: center;
    }

    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
    }

    .pagination .page-item.disabled .page-link {
        color: #adb5bd;
        background-color: #f8f9fa;
    }
</style>
@endpush
@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">{{ $title }}</h2>
            <p class="text-muted small mb-0">Daftar user beserta hobi.</p>
        </div>
        <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" onclick="openDrawer('tambah')">Tambah</button>
    </div>  
    <div class="card-border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-7">
                        <tr>
                            <th scope="row">No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Hobby</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold text-dark">{{ $item->name ?? '-' }}</td>
                            <td class="text-muted">{{ $item->email ?? '-' }}</td>
                            <td>
                                @if($item->hobbies->isNotEmpty())
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($item->hobbies as $a)
                                            <span class="badge bg-light text-dark border px-2 py-1 fw-normal">
                                                {{ $a->hobby ?? '-' }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted small italic">Tidak ada hobi</span>
                                @endif
                                </td>
                                <td class="d-flex justify-conten-start">
                                    <button class="btn btn-sm btn-warning me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" onclick="openDrawer('edit','{{ encrypt($item->id) }}')" aria-controls="offcanvasRight">Edit</button>
                                    <form action="{{ route('users.destroy', encrypt($item->id)) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                    </form>
                                </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                Belum ada data user yang tersedia.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($data->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="text-muted small">
                        Menampilkan
                        <strong>{{ $data->firstItem() ?? 0 }}</strong>
                        sampai
                        <strong>{{ $data->lastItem() ?? 0 }}</strong>
                        dari
                        <strong>{{ $data->total() }}</strong>
                        data
                    </div>
                    <div>
                        {{ $data->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        @endif
    </div>
</div>


<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasRightLabel">Tambah User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            @method('POST')
    
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" id="name" 
                    class="form-control @error('name') is-invalid @enderror" 
                    value="{{ old('name') }}" 
                    placeholder="Masukkan nama lengkap" 
                    required
                >
    
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
    
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    placeholder="nama@email.com"
                    autocomplete="email"
                    required
                >
        
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Masukkan password"
                    autocomplete="current-password"
                    required
                >
        
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
    
            <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label mb-0">Hobi</label>
            
                        <button type="button"
                                class="btn btn-sm btn-outline-primary"
                                onclick="addHobby()">
                            <i class="bi bi-plus"></i>
                            add
                        </button>
                    </div>
        
                    <div id="hobbyContainer">
                        <div class="input-group mb-2 hobby-item">
                            <input
                                type="text"
                                name="hobbies[]"
                                class="form-control"
                                placeholder="Masukkan Hobi"
                            >
            
                            <button type="button"
                                    class="btn btn-outline-danger"
                                    onclick="removeHobby(this)"
                                    aria-label="Hapus hobi">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>
        
                    @error('hobbies')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
            
                    @error('hobbies.*')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
            </div>
    
            <div class="d-flex justify-content-end gap-2 mt-4">
                <button type="button"
                        class="btn btn-light border"
                        data-bs-dismiss="offcanvas">
                    Batal
                </button>
        
                <button type="submit" class="btn btn-primary">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        const hasValidationError = @json($errors->any());
        document.addEventListener('DOMContentLoaded',function(){
            if(hasValidationError){
                bootstrap.Offcanvas.getOrCreateInstance(
                    document.getElementById('offcanvasRight')
                ).show();
            }
        });

        const showUrl   = "{{ route('users.show', ':id') }}";
        const updateUrl = "{{ route('users.update', ':id') }}";
        const storeUrl  = "{{ route('users.store') }}";

        async function getUser(userId){
            try {
                const response = await fetch(showUrl.replace(':id', userId), {
                    headers: {
                        'Accept' : 'application/json'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                
                const res = await response.json();
                
                return res.data ?? null;
            } catch (error) {
                // Catch any network or parsing errors
                console.error('Fetch failed:', error);
                return null;
            }
        }
        async function openDrawer(aksi, userId = null){
            const canvas = document.getElementById('offcanvasRight');
            const form = canvas.querySelector('form');
            const isEdit = aksi === 'edit';

            canvas.querySelector('#offcanvasRightLabel').textContent = isEdit ? 'Edit User' : 'Tambah User';
            form.action = isEdit ? updateUrl.replace(':id', userId) : storeUrl;
            form.querySelector('input[name="_method"]').value = isEdit ? 'PUT' : 'POST';

            // isi data user
            const containerHobby = canvas.querySelector('#hobbyContainer');
            containerHobby.innerHTML = '';
            form.reset();
            if (!isEdit) return;

            const user = await getUser(userId);
            if (!user) return;

            canvas.querySelector('#name').value = user.name;
            canvas.querySelector('#email').value = user.email;
            canvas.querySelector('#password').value = '';

            (user.hobbies ?? []).forEach(function(item){
                const div = createElementHobby(item.hobby ?? '');
                containerHobby.appendChild(div);
            });

        }
        function addHobby() {
            const container = document.getElementById('hobbyContainer');

            const group = createElementHobby();
            container.appendChild(group);
        }

        function createElementHobby(value = ""){
            const group = document.createElement('div');
            group.className = 'input-group mb-2 hobby-item';

            group.innerHTML = `
                    <input type="text" name="hobbies[]" class="form-control" placeholder="Masukkan Hobi" value="${value}">
                    <button type="button" class="btn btn-outline-danger" onclick="removeHobby(this)" aria-label="Hapus hobi">
                        <i class="bi bi-x"></i>
                    </button>`
            ;
            return group;
        }

        function removeHobby(button) {
            const container = document.getElementById('hobbyContainer');
            const items = container.querySelectorAll('.hobby-item');

            if (items.length > 1) {
                button.closest('.hobby-item').remove();
            } else {
                items[0].querySelector('input').value = '';
            }
        }
</script>
@endpush