<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mahasiswam;
use PDF;
use Illuminate\Support\Facades\Auth;
use App\Stakeholderm;

class CetakController extends Controller
{
    //
    public function pdfbukti()
    {
        $mahasiswa = Mahasiswam::where('user_id', Auth::user()->id)->first();
        $data = [
            'text' => 'hallo',
            'nama' => $mahasiswa->user->name,
            'nim' => $mahasiswa->user->nim,
            'mulai' => $mahasiswa->created_at,
            'selesai' => $mahasiswa->updated_at,
            'foto' => $mahasiswa->user->foto,
            'ipk' => $mahasiswa->ipk,
            'fakultas' => $mahasiswa->prodi->fakultas->nama_fakultas,
            'prodi' => $mahasiswa->prodi->nama_prodi,
            'telpon' => $mahasiswa->user->tlp,
            'email' => $mahasiswa->user->email,
            'facebook' => 'FACEBOOK',
            'tahunlulus' => $mahasiswa->tahun_lulus,
            'semesterlulus' => $mahasiswa->semester_lulus,
        ];
        $pdf = PDF::loadView('cetak.cetaktracer', $data);
        $pdf->getDomPDF()->setHttpContext(
            stream_context_create([
                'ssl' => [
                    'allow_self_signed' => TRUE,
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                ]
            ])
        );
        return $pdf->stream('selesai.pdf');
        //return view('depan.cetakpdf');
    }
    public function pdfbuktistakeholder()
    {
        $stakeholder = Stakeholderm::where('user_id', Auth::user()->id)->first();
        $data = [
            'namastakeholder' => $stakeholder->user->name,
            'nama' => $stakeholder->user->name,
            'jabatan' => $stakeholder->jabatan,
            'hp' => $stakeholder->user->tlp,
            'pekerjaan' => $stakeholder->pekerjaan,
            'instansi' => $stakeholder->instansi,
            'alamat' => $stakeholder->alamat,
            'fax' => $stakeholder->fax,
            'email' => $stakeholder->user->email,
            'namamahasiswa' => $stakeholder->mahasiswa->user->name,
            'jabatanalumni' => $stakeholder->jabatanalumni,
            'selesai' => $stakeholder->updated_at,
        ];
        $pdf = PDF::loadView('cetak.stakeholder', $data);
        $pdf->getDomPDF()->setHttpContext(
            stream_context_create([
                'ssl' => [
                    'allow_self_signed' => TRUE,
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                ]
            ])
        );
        return $pdf->stream('selesai_stakeholder.pdf');
        //return view('depan.cetakpdf');
    }
    public function pdfbukti2($id)
    {
        $mahasiswa = Mahasiswam::find($id);
        $data = [
            'text' => 'hallo',
            'nama' => $mahasiswa->user->name,
            'nim' => $mahasiswa->user->nim,
            'mulai' => $mahasiswa->created_at,
            'selesai' => $mahasiswa->updated_at,
            'foto' => $mahasiswa->user->foto,
            'ipk' => $mahasiswa->ipk,
            'fakultas' => $mahasiswa->prodi->fakultas->nama_fakultas,
            'prodi' => $mahasiswa->prodi->nama_prodi,
            'telpon' => $mahasiswa->user->tlp,
            'email' => $mahasiswa->user->email,
            'facebook' => 'FACEBOOK',
            'tahunlulus' => $mahasiswa->tahun_lulus,
            'semesterlulus' => $mahasiswa->semester_lulus,
        ];
        $pdf = PDF::loadView('cetak.cetaktracer', $data);
        $pdf->getDomPDF()->setHttpContext(
            stream_context_create([
                'ssl' => [
                    'allow_self_signed' => TRUE,
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                ]
            ])
        );
        return $pdf->stream('selesai.pdf');
        //return view('depan.cetakpdf');
    }
    public function pdfbuktistakeholder2($id)
    {
        $stakeholder = Stakeholderm::where('mahasiswam_id', $id)->first();
        $data = [
            'namastakeholder' => $stakeholder->user->name,
            'nama' => $stakeholder->user->name,
            'jabatan' => $stakeholder->jabatan,
            'hp' => $stakeholder->user->tlp,
            'pekerjaan' => $stakeholder->pekerjaan,
            'instansi' => $stakeholder->instansi,
            'alamat' => $stakeholder->alamat,
            'fax' => $stakeholder->fax,
            'email' => $stakeholder->user->email,
            'namamahasiswa' => $stakeholder->mahasiswa->user->name,
            'jabatanalumni' => $stakeholder->jabatanalumni,
            'selesai' => $stakeholder->updated_at,
        ];
        $pdf = PDF::loadView('cetak.stakeholder', $data);
        $pdf->getDomPDF()->setHttpContext(
            stream_context_create([
                'ssl' => [
                    'allow_self_signed' => TRUE,
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                ]
            ])
        );
        return $pdf->stream('selesai_stakeholder.pdf');
        //return view('depan.cetakpdf');
    }
}
