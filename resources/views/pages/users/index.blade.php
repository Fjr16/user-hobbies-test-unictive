@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-header">
        <h6>Data User</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" data-bs-theme="dark">
                <thead>
                    <tr>
                        <th scope="row">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Hobby</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $data =['1'];
                    @endphp
                    @forelse ($data as $item)
                    <tr>
                        <td>1</td>
                        <td>Andi</td>
                        <td>andi@gmail.com</td>
                        <td>
                            <table>
                                <tr>
                                    <td>Bermain bola</td>
                                </tr>
                                <tr>
                                    <td>Bermain bola2</td>
                                </tr>
                                <tr>
                                    <td>Bermain bola4</td>
                                </tr>
                                <tr>
                                    <td>Bermain bola4</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Andi</td>
                        <td>andi@gmail.com</td>
                        <td>
                            <table>
                                <tr>
                                    <td>Bermain bola</td>
                                </tr>
                                <tr>
                                    <td>Bermain bola2</td>
                                </tr>
                                <tr>
                                    <td>Bermain bola4</td>
                                </tr>
                                <tr>
                                    <td>Bermain bola4</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">Belum ada data user</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection