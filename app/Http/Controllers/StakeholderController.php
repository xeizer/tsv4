<?php

namespace App\Http\Controllers;

use App\Prodim;
use App\Stakeholderm;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StakeholderController extends Controller
{
    public function index($prodi, $tahunangkatan, $tahunlulus)
    {
        for ($i = 1; $i <= 7; $i++) {
            for ($r = 1; $r <= 4; $r++) {
                $data['sh' . $i][$r] = Stakeholderm::where('sh' . $i, $r)->whereHas('mahasiswa', function ($q) use ($prodi) {
                    if ($prodi > 1) {
                        $q->where('prodim_id', $prodi);
                    }
                })->count();
            }
        }
        if ($prodi > 1) {
            $jumlah = Stakeholderm::whereHas('mahasiswa', function ($q) use ($prodi) {
                $q->where('prodim_id', $prodi);
            })->where('status', 1)->count();
        } else {
            $jumlah = Stakeholderm::where('status', 1)->count();
        };

        return view('administrasi.stakeholder.index', [
            'data' => $data,
            'jumlah' => $jumlah,
            'prodi' => $prodi,
            'title' => Prodim::find($prodi)->nama_prodi,
            'tahunlulus' => $tahunlulus,
            'tahunangkatan' => $tahunangkatan
        ]);
    }
    public function indexpublik($prodi, $tahunangkatan, $tahunlulus)
    {
        for ($i = 1; $i <= 7; $i++) {
            for ($r = 1; $r <= 4; $r++) {
                $data['sh' . $i][$r] = Stakeholderm::where('sh' . $i, $r)->whereHas('mahasiswa', function ($q) use ($prodi) {
                    if ($prodi > 1) {
                        $q->where('prodim_id', $prodi);
                    }
                })->count();
            }
        }
        if ($prodi > 1) {
            $jumlah = Stakeholderm::whereHas('mahasiswa', function ($q) use ($prodi) {
                $q->where('prodim_id', $prodi);
            })->where('status', 1)->count();
        } else {
            $jumlah = Stakeholderm::where('status', 1)->count();
        };


        return view('umum.stakeholder.index', [
            'data' => $data,
            'jumlah' => $jumlah,
            'prodi' => $prodi,
            'title' => Prodim::find($prodi)->nama_prodi,
            'tahunangkatan' => $tahunangkatan,
            'tahunlulus' => $tahunlulus,
        ]);
    }
    public function indexpublik2(Request $req)
    {
        $prodi = $req->prodi;
        $tahunangkatan = $req->tahunangkatan;
        $tahunlulus = $req->tahunlulus;
        ///
        if ($prodi == 1) {
            if ($tahunangkatan == 0) {
                if ($tahunlulus == 0) {
                    //odin, prodi all,  tahun angkatan all, tahun lulus all OK
                    for ($i = 1; $i <= 7; $i++) {
                        for ($r = 1; $r <= 4; $r++) {
                            $data['sh' . $i][$r] = Stakeholderm::where('sh' . $i, $r)->whereHas('mahasiswa', function ($q) use ($prodi) {
                            })->count();
                        }
                    }

                    $jumlah = Stakeholderm::whereHas('mahasiswa', function ($q) use ($prodi) {
                    })->where('status', 1)->count();
                } else {
                    //odin, prodi all,  tahun angkatan all, tahun lulus pilih OK
                    for ($i = 1; $i <= 7; $i++) {
                        for ($r = 1; $r <= 4; $r++) {
                            $data['sh' . $i][$r] = Stakeholderm::where('sh' . $i, $r)->whereHas('mahasiswa', function ($q) use ($tahunlulus) {
                                $q->where('tahun_lulus', $tahunlulus);
                            })->count();
                        }
                    }
                    $jumlah = Stakeholderm::whereHas('mahasiswa', function ($q) use ($tahunlulus) {
                        $q->where('tahun_lulus', $tahunlulus);
                    })->where('status', 1)->count();
                }
            } else {
                if ($tahunlulus == 0) {
                    //odin, prodi all,  tahun angkatan pilih, tahun lulus all
                    for ($i = 1; $i <= 7; $i++) {
                        for ($r = 1; $r <= 4; $r++) {
                            $data['sh' . $i][$r] = Stakeholderm::where('sh' . $i, $r)->whereHas('mahasiswa', function ($q) use ($tahunangkatan) {
                                $q->where('angkatan', $tahunangkatan);
                            })->count();
                        }
                    }
                    $jumlah = Stakeholderm::whereHas('mahasiswa', function ($q) use ($tahunangkatan) {
                        $q->where('angkatan', $tahunangkatan);
                    })->where('status', 1)->count();
                } else {
                    //odin, prodi all,  tahun angkatan pilih, tahun lulus pilih
                    for ($i = 1; $i <= 7; $i++) {
                        for ($r = 1; $r <= 4; $r++) {
                            $data['sh' . $i][$r] = Stakeholderm::where('sh' . $i, $r)->whereHas('mahasiswa', function ($q) use ($tahunangkatan, $tahunlulus) {
                                $q->where('angkatan', $tahunangkatan)->where('tahun_lulus', $tahunlulus);
                            })->count();
                        }
                    }
                    $jumlah = Stakeholderm::whereHas('mahasiswa', function ($q) use ($tahunangkatan, $tahunlulus) {
                        $q->where('angkatan', $tahunangkatan)->where('tahun_lulus', $tahunlulus);
                    })->where('status', 1)->count();
                }
            }
        } else {
            //odin, prodi pilih,  tahun angkatan all, tahun lulus all
            if ($tahunangkatan == 0) {
                if ($tahunlulus == 0) {
                    //odin, prodi pilih,  tahun angkatan all, tahun lulus all
                    for ($i = 1; $i <= 7; $i++) {
                        for ($r = 1; $r <= 4; $r++) {
                            $data['sh' . $i][$r] = Stakeholderm::where('sh' . $i, $r)->whereHas('mahasiswa', function ($q) use ($tahunangkatan, $tahunlulus, $prodi) {
                                $q->where('prodim_id', $prodi);
                            })->count();
                        }
                    }
                    $jumlah = Stakeholderm::whereHas('mahasiswa', function ($q) use ($tahunangkatan, $tahunlulus, $prodi) {
                        $q->where('prodim_id', $prodi);
                    })->where('status', 1)->count();
                } else {
                    //odin, prodi pilih,  tahun angkatan all, tahun lulus pilih
                    for ($i = 1; $i <= 7; $i++) {
                        for ($r = 1; $r <= 4; $r++) {
                            $data['sh' . $i][$r] = Stakeholderm::where('sh' . $i, $r)->whereHas('mahasiswa', function ($q) use ($tahunangkatan, $tahunlulus, $prodi) {
                                $q->where('tahun_lulus', $tahunlulus)->where('prodim_id', $prodi);
                            })->count();
                        }
                    }
                    $jumlah = Stakeholderm::whereHas('mahasiswa', function ($q) use ($tahunangkatan, $tahunlulus, $prodi) {
                        $q->where('tahun_lulus', $tahunlulus)->where('prodim_id', $prodi);
                    })->where('status', 1)->count();
                }
            } else {
                //odin, prodi pilih,  tahun angkatan pilih, tahun lulus all
                if ($tahunlulus == 0) {
                    for ($i = 1; $i <= 7; $i++) {
                        for ($r = 1; $r <= 4; $r++) {
                            $data['sh' . $i][$r] = Stakeholderm::where('sh' . $i, $r)->whereHas('mahasiswa', function ($q) use ($tahunangkatan, $tahunlulus, $prodi) {
                                $q->where('angkatan', $tahunangkatan)->where('prodim_id', $prodi);
                            })->count();
                        }
                    }
                    $jumlah = Stakeholderm::whereHas('mahasiswa', function ($q) use ($tahunangkatan, $tahunlulus, $prodi) {
                        $q->where('angkatan', $tahunangkatan)->where('prodim_id', $prodi);
                    })->where('status', 1)->count();
                } else {
                    //odin, prodi pilih,  tahun angkatan pilih, tahun lulus pilih
                    for ($i = 1; $i <= 7; $i++) {
                        for ($r = 1; $r <= 4; $r++) {
                            $data['sh' . $i][$r] = Stakeholderm::where('sh' . $i, $r)->whereHas('mahasiswa', function ($q) use ($tahunangkatan, $tahunlulus, $prodi) {
                                $q->where('angkatan', $tahunangkatan)->where('tahun_lulus', $tahunlulus)->where('prodim_id', $prodi);
                            })->count();
                        }
                    }
                    $jumlah = Stakeholderm::whereHas('mahasiswa', function ($q) use ($tahunangkatan, $tahunlulus, $prodi) {
                        $q->where('angkatan', $tahunangkatan)->where('tahun_lulus', $tahunlulus)->where('prodim_id', $prodi);
                    })->where('status', 1)->count();
                }
            }
        }
        ///
        // for ($i = 1; $i <= 7; $i++) {
        //     for ($r = 1; $r <= 4; $r++) {
        //         $data['sh' . $i][$r] = Stakeholderm::where('sh' . $i, $r)->whereHas('mahasiswa', function ($q) use ($prodi) {
        //             if ($prodi > 1) {
        //                 $q->where('prodim_id', $prodi);
        //             }
        //         })->count();
        //     }
        // }
        // if ($prodi > 1) {
        //     $jumlah = Stakeholderm::whereHas('mahasiswa', function ($q) use ($prodi) {
        //         $q->where('prodim_id', $prodi);
        //     })->where('status', 1)->count();
        // } else {
        //     $jumlah = Stakeholderm::where('status', 1)->count();
        // };


        return view('umum.stakeholder.index', [
            'data' => $data,
            'jumlah' => $jumlah,
            'prodi' => $prodi,
            'title' => Prodim::find($prodi)->nama_prodi,
            'tahunangkatan' => $tahunangkatan,
            'tahunlulus' => $tahunlulus,
        ]);
    }
    public function indexpublikninumum(Request $req)
    {
        $prodi = $req->prodi;
        $tahunangkatan = $req->tahunangkatan;
        $tahunlulus = $req->tahunlulus;
        ///
        if ($prodi == 1) {
            if ($tahunangkatan == 0) {
                if ($tahunlulus == 0) {
                    //odin, prodi all,  tahun angkatan all, tahun lulus all OK
                    for ($i = 1; $i <= 7; $i++) {
                        for ($r = 1; $r <= 4; $r++) {
                            $data['sh' . $i][$r] = Stakeholderm::where('sh' . $i, $r)->whereHas('mahasiswa', function ($q) use ($prodi) {
                            })->count();
                        }
                    }

                    $jumlah = Stakeholderm::whereHas('mahasiswa', function ($q) use ($prodi) {
                    })->where('status', 1)->count();
                } else {
                    //odin, prodi all,  tahun angkatan all, tahun lulus pilih OK
                    for ($i = 1; $i <= 7; $i++) {
                        for ($r = 1; $r <= 4; $r++) {
                            $data['sh' . $i][$r] = Stakeholderm::where('sh' . $i, $r)->whereHas('mahasiswa', function ($q) use ($tahunlulus) {
                                $q->where('tahun_lulus', $tahunlulus);
                            })->count();
                        }
                    }
                    $jumlah = Stakeholderm::whereHas('mahasiswa', function ($q) use ($tahunlulus) {
                        $q->where('tahun_lulus', $tahunlulus);
                    })->where('status', 1)->count();
                }
            } else {
                if ($tahunlulus == 0) {
                    //odin, prodi all,  tahun angkatan pilih, tahun lulus all
                    for ($i = 1; $i <= 7; $i++) {
                        for ($r = 1; $r <= 4; $r++) {
                            $data['sh' . $i][$r] = Stakeholderm::where('sh' . $i, $r)->whereHas('mahasiswa', function ($q) use ($tahunangkatan) {
                                $q->where('angkatan', $tahunangkatan);
                            })->count();
                        }
                    }
                    $jumlah = Stakeholderm::whereHas('mahasiswa', function ($q) use ($tahunangkatan) {
                        $q->where('angkatan', $tahunangkatan);
                    })->where('status', 1)->count();
                } else {
                    //odin, prodi all,  tahun angkatan pilih, tahun lulus pilih
                    for ($i = 1; $i <= 7; $i++) {
                        for ($r = 1; $r <= 4; $r++) {
                            $data['sh' . $i][$r] = Stakeholderm::where('sh' . $i, $r)->whereHas('mahasiswa', function ($q) use ($tahunangkatan, $tahunlulus) {
                                $q->where('angkatan', $tahunangkatan)->where('tahun_lulus', $tahunlulus);
                            })->count();
                        }
                    }
                    $jumlah = Stakeholderm::whereHas('mahasiswa', function ($q) use ($tahunangkatan, $tahunlulus) {
                        $q->where('angkatan', $tahunangkatan)->where('tahun_lulus', $tahunlulus);
                    })->where('status', 1)->count();
                }
            }
        } else {
            //odin, prodi pilih,  tahun angkatan all, tahun lulus all
            if ($tahunangkatan == 0) {
                if ($tahunlulus == 0) {
                    //odin, prodi pilih,  tahun angkatan all, tahun lulus all
                    for ($i = 1; $i <= 7; $i++) {
                        for ($r = 1; $r <= 4; $r++) {
                            $data['sh' . $i][$r] = Stakeholderm::where('sh' . $i, $r)->whereHas('mahasiswa', function ($q) use ($tahunangkatan, $tahunlulus, $prodi) {
                                $q->where('prodim_id', $prodi);
                            })->count();
                        }
                    }
                    $jumlah = Stakeholderm::whereHas('mahasiswa', function ($q) use ($tahunangkatan, $tahunlulus, $prodi) {
                        $q->where('prodim_id', $prodi);
                    })->where('status', 1)->count();
                } else {
                    //odin, prodi pilih,  tahun angkatan all, tahun lulus pilih
                    for ($i = 1; $i <= 7; $i++) {
                        for ($r = 1; $r <= 4; $r++) {
                            $data['sh' . $i][$r] = Stakeholderm::where('sh' . $i, $r)->whereHas('mahasiswa', function ($q) use ($tahunangkatan, $tahunlulus, $prodi) {
                                $q->where('tahun_lulus', $tahunlulus)->where('prodim_id', $prodi);
                            })->count();
                        }
                    }
                    $jumlah = Stakeholderm::whereHas('mahasiswa', function ($q) use ($tahunangkatan, $tahunlulus, $prodi) {
                        $q->where('tahun_lulus', $tahunlulus)->where('prodim_id', $prodi);
                    })->where('status', 1)->count();
                }
            } else {
                //odin, prodi pilih,  tahun angkatan pilih, tahun lulus all
                if ($tahunlulus == 0) {
                    for ($i = 1; $i <= 7; $i++) {
                        for ($r = 1; $r <= 4; $r++) {
                            $data['sh' . $i][$r] = Stakeholderm::where('sh' . $i, $r)->whereHas('mahasiswa', function ($q) use ($tahunangkatan, $tahunlulus, $prodi) {
                                $q->where('angkatan', $tahunangkatan)->where('prodim_id', $prodi);
                            })->count();
                        }
                    }
                    $jumlah = Stakeholderm::whereHas('mahasiswa', function ($q) use ($tahunangkatan, $tahunlulus, $prodi) {
                        $q->where('angkatan', $tahunangkatan)->where('prodim_id', $prodi);
                    })->where('status', 1)->count();
                } else {
                    //odin, prodi pilih,  tahun angkatan pilih, tahun lulus pilih
                    for ($i = 1; $i <= 7; $i++) {
                        for ($r = 1; $r <= 4; $r++) {
                            $data['sh' . $i][$r] = Stakeholderm::where('sh' . $i, $r)->whereHas('mahasiswa', function ($q) use ($tahunangkatan, $tahunlulus, $prodi) {
                                $q->where('angkatan', $tahunangkatan)->where('tahun_lulus', $tahunlulus)->where('prodim_id', $prodi);
                            })->count();
                        }
                    }
                    $jumlah = Stakeholderm::whereHas('mahasiswa', function ($q) use ($tahunangkatan, $tahunlulus, $prodi) {
                        $q->where('angkatan', $tahunangkatan)->where('tahun_lulus', $tahunlulus)->where('prodim_id', $prodi);
                    })->where('status', 1)->count();
                }
            }
        }
        ///
        // for ($i = 1; $i <= 7; $i++) {
        //     for ($r = 1; $r <= 4; $r++) {
        //         $data['sh' . $i][$r] = Stakeholderm::where('sh' . $i, $r)->whereHas('mahasiswa', function ($q) use ($prodi) {
        //             if ($prodi > 1) {
        //                 $q->where('prodim_id', $prodi);
        //             }
        //         })->count();
        //     }
        // }
        // if ($prodi > 1) {
        //     $jumlah = Stakeholderm::whereHas('mahasiswa', function ($q) use ($prodi) {
        //         $q->where('prodim_id', $prodi);
        //     })->where('status', 1)->count();
        // } else {
        //     $jumlah = Stakeholderm::where('status', 1)->count();
        // };


        return view('administrasi.stakeholder.index', [
            'data' => $data,
            'jumlah' => $jumlah,
            'prodi' => $prodi,
            'title' => Prodim::find($prodi)->nama_prodi,
            'tahunangkatan' => $tahunangkatan,
            'tahunlulus' => $tahunlulus,
        ]);
    }

    public function bekerja($prodi, $tahunangkatan, $tahunlulus)
    {
        if ($prodi > 1) {
            $data = Stakeholderm::where('status', 1)->whereHas('mahasiswa', function ($q) use ($prodi) {

                $q->where('prodim_id', $prodi);
            })->get();
        } else {
            $data = Stakeholderm::where('status', 1)->get();
        }


        return view('administrasi.bekerja.index', [
            'data' => $data,
            'prodi' => $prodi,
            'title' => Prodim::find($prodi)->nama_prodi,
            'tahunangkatan' => $tahunangkatan,
            'tahunlulus' => $tahunlulus,
        ]);
    }
    public function apibekerja($prodi)
    {
        if ($prodi > 1) {
            $data = Stakeholderm::where('status', 1)->whereHas('mahasiswa', function ($q) use ($prodi) {
                $q->where('prodim_id', $prodi);
            })->get();
        } else {
            $data = Stakeholderm::where('status', 1)->get();
        }
        return DataTables::of($data)
            ->addColumn('nim', function ($data) {
                return $data->user->nim;
            })
            ->addColumn('nama', function ($data) {
                return $data->user->name;
            })
            ->make(true);

        ///


        return view('umum.stakeholder.index', [

            'prodi' => $prodi,
            'title' => Prodim::find($prodi)->nama_prodi,

        ]);
    }
}
