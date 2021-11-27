@extends('layouts.theme2')
@section('isi')
    @if ($jumlah == 0)
        <div class="alert alert-warning">
            Maaf. Sepertinya tidak ada Stake holder yang mengisi...
        </div>
    @else

        <div class="row">

            <div class="col-md-3">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <div class="box-title">Mahasiswa</div>
                    </div>
                    <div class="box-body">

                        {{ App\Prodim::where('id', $prodi)->first()->nama_prodi }}<br />
                        Jumlah Tanggapan Stakeholder : {{ $jumlah }} Orang <br />
                        <form action="{{ route('stakeholder.indexnonumum') }}" method="POST">
                            @csrf
                            <input type="hidden" name="prodi" value="{{ $prodi }}">
                            <table class="table">
                                <tr>
                                    <td>
                                        Pilih Tahun Lulus

                                    </td>
                                    <td>
                                        <select class="form-control" name="tahunlulus">
                                            <option value="0">semua</option>
                                            @if ($prodi == 1)

                                                @foreach (App\Mahasiswam::select('tahun_lulus')->distinct()->where('status', 99)->get()
        as $d)

                                                    <option value="{{ $d->tahun_lulus }}"
                                                        {{ $tahunlulus == $d->tahun_lulus ? 'selected' : '' }}>
                                                        {{ $d->tahun_lulus }}</option>
                                                @endforeach
                                            @else
                                                @foreach (App\Mahasiswam::select('tahun_lulus')->where('prodim_id', $prodi)->distinct()->where('status', 99)->get()
        as $d)
                                                    <option value="{{ $d->tahun_lulus }}"
                                                        {{ $tahunlulus == $d->tahun_lulus ? 'selected' : '' }}>
                                                        {{ $d->tahun_lulus }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Pilih Angkatan
                                    </td>
                                    <td>
                                        <select class="form-control" name="tahunangkatan">
                                            <option value="0">semua</option>
                                            @if ($prodi == 1)
                                                @foreach (App\Mahasiswam::select('angkatan')->distinct()->where('status', 99)->get()
        as $d)
                                                    <option value="{{ $d->angkatan }}"
                                                        {{ $tahunangkatan == $d->angkatan ? 'selected' : '' }}>
                                                        {{ $d->angkatan }}</option>
                                                @endforeach
                                            @else
                                                @foreach (App\Mahasiswam::select('angkatan')->where('prodim_id', $prodi)->distinct()->where('status', 99)->get()
        as $d)
                                                    <option value="{{ $d->angkatan }}"
                                                        {{ $tahunangkatan == $d->angkatan ? 'selected' : '' }}>
                                                        {{ $d->angkatan }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><button class="btn btn-primary" type="submit">Lihat Data</td>
                                </tr>

                            </table>
                        </form>

                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <div class="box-title">Etika</div>
                    </div>
                    <div class="box-body">
                        <p>Sangat Baik</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh1']['1'] / $jumlah) * 100) }}%">{{ $data['sh1'][1] }}
                                ({{ round(($data['sh1']['1'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>

                        <p>Baik</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh1']['2'] / $jumlah) * 100) }}%">{{ $data['sh1'][2] }}
                                ({{ round(($data['sh1']['2'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>

                        <p>Cukup</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh1']['3'] / $jumlah) * 100) }}%">{{ $data['sh1'][3] }}
                                ({{ round(($data['sh1']['3'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>

                        <p>Kurang</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh1']['4'] / $jumlah) * 100) }}%">{{ $data['sh1'][4] }}
                                ({{ round(($data['sh1']['4'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <div class="box-title">Keahlian Pada Bidang Ilmu (Kompetensi Utama)</div>
                    </div>
                    <div class="box-body">
                        <p>Sangat Baik</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh2']['1'] / $jumlah) * 100) }}%">{{ $data['sh2'][1] }}
                                ({{ round(($data['sh2']['1'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>

                        <p>Baik</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh2']['2'] / $jumlah) * 100) }}%">{{ $data['sh2'][2] }}
                                ({{ round(($data['sh2']['2'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>

                        <p>Cukup</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh2']['3'] / $jumlah) * 100) }}%">{{ $data['sh2'][3] }}
                                ({{ round(($data['sh2']['3'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>

                        <p>Kurang</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh2']['4'] / $jumlah) * 100) }}%">{{ $data['sh2'][4] }}
                                ({{ round(($data['sh2']['4'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <div class="box-title">Kemampuan Berbahasa Asing</div>
                    </div>
                    <div class="box-body">
                        <p>Sangat Baik</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh3']['1'] / $jumlah) * 100) }}%">{{ $data['sh3'][1] }}
                                ({{ round(($data['sh3']['1'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>

                        <p>Baik</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh3']['2'] / $jumlah) * 100) }}%">{{ $data['sh3'][2] }}
                                ({{ round(($data['sh3']['2'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>

                        <p>Cukup</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh3']['3'] / $jumlah) * 100) }}%">{{ $data['sh3'][3] }}
                                ({{ round(($data['sh3']['3'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>

                        <p>Kurang</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh3']['4'] / $jumlah) * 100) }}%">{{ $data['sh3'][4] }}
                                ({{ round(($data['sh3']['4'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <div class="box-title">Penggunaan Teknologi Informasi</div>
                    </div>
                    <div class="box-body">
                        <p>Sangat Baik</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh4']['1'] / $jumlah) * 100) }}%">{{ $data['sh4'][1] }}
                                ({{ round(($data['sh4']['1'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>

                        <p>Baik</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh4']['2'] / $jumlah) * 100) }}%">{{ $data['sh4'][2] }}
                                ({{ round(($data['sh4']['2'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>

                        <p>Cukup</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh4']['3'] / $jumlah) * 100) }}%">{{ $data['sh4'][3] }}
                                ({{ round(($data['sh4']['3'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>

                        <p>Kurang</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh4']['4'] / $jumlah) * 100) }}%">{{ $data['sh4'][4] }}
                                ({{ round(($data['sh4']['4'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <div class="box-title">Kemampuan Berkomunikasi</div>
                    </div>
                    <div class="box-body">
                        <p>Sangat Baik</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh5']['1'] / $jumlah) * 100) }}%">{{ $data['sh5'][1] }}
                                ({{ round(($data['sh5']['1'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>

                        <p>Baik</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh5']['2'] / $jumlah) * 100) }}%">{{ $data['sh5'][2] }}
                                ({{ round(($data['sh5']['2'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>

                        <p>Cukup</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh5']['3'] / $jumlah) * 100) }}%">{{ $data['sh5'][3] }}
                                ({{ round(($data['sh5']['3'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>

                        <p>Kurang</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh5']['4'] / $jumlah) * 100) }}%">{{ $data['sh5'][4] }}
                                ({{ round(($data['sh5']['4'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <div class="box-title">Kerjasama</div>
                    </div>
                    <div class="box-body">
                        <p>Sangat Baik</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh6']['1'] / $jumlah) * 100) }}%">{{ $data['sh6'][1] }}
                                ({{ round(($data['sh6']['1'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>

                        <p>Baik</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh6']['2'] / $jumlah) * 100) }}%">{{ $data['sh6'][2] }}
                                ({{ round(($data['sh6']['2'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>

                        <p>Cukup</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh6']['3'] / $jumlah) * 100) }}%">{{ $data['sh6'][3] }}
                                ({{ round(($data['sh6']['3'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>

                        <p>Kurang</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh6']['4'] / $jumlah) * 100) }}%">{{ $data['sh6'][4] }}
                                ({{ round(($data['sh6']['4'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <div class="box-title">Pengembangan diri</div>
                    </div>
                    <div class="box-body">
                        <p>Sangat Baik</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh7']['1'] / $jumlah) * 100) }}%">{{ $data['sh7'][1] }}
                                ({{ round(($data['sh7']['1'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>

                        <p>Baik</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh7']['2'] / $jumlah) * 100) }}%">{{ $data['sh7'][2] }}
                                ({{ round(($data['sh7']['2'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>

                        <p>Cukup</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh7']['3'] / $jumlah) * 100) }}%">{{ $data['sh7'][3] }}
                                ({{ round(($data['sh7']['3'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>

                        <p>Kurang</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                style="width: {{ round(($data['sh7']['4'] / $jumlah) * 100) }}%">{{ $data['sh7'][4] }}
                                ({{ round(($data['sh7']['4'] / $jumlah) * 100) }}%)
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
