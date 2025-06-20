<?php

namespace App\Http\Controllers;

use App\Models\PengajuanModel;
use App\Models\SubKategoriModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class ApproveController extends Controller
{

    public function index()
    {   

        $pengajuan = DB::table('pengajuan')
        ->leftJoin('users', 'users.id', '=', 'pengajuan.user_id') 
        ->leftJoin('arsip', 'arsip.nama', '=', 'pengajuan.nama') // Hubungkan pengajuan ke arsip berdasarkan nama arsip
        ->leftJoin('subkategori', 'pengajuan.subkategori_id', '=', 'subkategori.id') // Menggunakan subkategori_id
        ->leftJoin('kategori', 'subkategori.kategori', '=', 'kategori.id') // Hubungkan subkategori ke kategori
 
        ->select(
                'pengajuan.*',
                'users.name as user_name',
                'pengajuan.nama as nama',
                'pengajuan.jenis_arsip',
                'pengajuan.type',
                'kategori.nama as kategori_nama', 
                'subkategori.nama as sub_nama',
                'pengajuan.tujuan',
                'pengajuan.status',   
                'pengajuan.approved_at', // Waktu persetujuan
                // 'arsip.nama as nama_arsip',
           
        )
        ->orderBy('pengajuan.created_at', 'desc')
        ->get();

        // return $pengajuan;

        return view('pages.approval.index', compact('pengajuan'));

    }

        public function index2()
    {   

        $pengajuan = DB::table('pengajuan')
        ->leftJoin('users', 'users.id', '=', 'pengajuan.user_id') 
        ->leftJoin('arsip', 'arsip.nama', '=', 'pengajuan.nama') // Hubungkan pengajuan ke arsip berdasarkan nama arsip
        ->leftJoin('subkategori', 'pengajuan.subkategori_id', '=', 'subkategori.id') // Menggunakan subkategori_id
        ->leftJoin('kategori', 'subkategori.kategori', '=', 'kategori.id') // Hubungkan subkategori ke kategori
 
        ->select(
                'pengajuan.*',
                'users.name as user_name',
                'pengajuan.nama as nama',
                'pengajuan.jenis_arsip',
                'pengajuan.type',
                'kategori.nama as kategori_nama', 
                'subkategori.nama as sub_nama',
                'pengajuan.tujuan',
                'pengajuan.status',   
                'pengajuan.approved_at', // Waktu persetujuan
                // 'arsip.nama as nama_arsip',
           
        )
        ->orderBy('pengajuan.created_at', 'desc')
        ->get();

        // return $pengajuan;

        return view('pages.approval.index2', compact('pengajuan'));

    }
    
    
    public function approve($id)
    {
        $pengajuan = PengajuanModel::findOrFail($id);
        $pengajuan->status = 'approved'; // Ubah status menjadi "Disetujui"
        $pengajuan->approved_at = Carbon::now();
        $pengajuan->approved_by = auth()->user()->id; // Simpan ID petugas yang approve
        $pengajuan->rejected_by = null; // Kosongkan jika sebelumnya ditolak
        // Tetapkan batas pengembalian selalu pukul 15:00 hari ini
        // $pengajuan->due_date = Carbon::now()->setTime(15, 0, 0);

        $pengajuan->save();
    
        return redirect()->route('approvals.index')->with('success', 'Peminjaman telah disetujui.');
    }

    public function reject($id)
    {
        $pengajuan = PengajuanModel::findOrFail($id);
        $pengajuan->status = 'rejected'; // Ubah status menjadi "Ditolak"
        $pengajuan->rejected_at = Carbon::now();
        $pengajuan->approved_by = null; // Kosongkan jika sebelumnya disetujui
        $pengajuan->rejected_by = auth()->user()->id; // Simpan ID user yang menolak
        $pengajuan->save();

        return redirect()->route('approvals.index')->with('success', 'Peminjaman telah ditolak.');
    }
    public function return($id)
    {
        // return $id;
        $pengajuan = PengajuanModel::findOrFail($id);
        $pengajuan->jenis_arsip == 'fisik'; // Ubah status menjadi "Ditolak"
        $pengajuan->status = 'returned';
        $pengajuan->returned_at = Carbon::now();$pengajuan->returned_at = Carbon::now(); // Simpan waktu pengembalian
      
        $pengajuan->save();

        return redirect()->route('approvals.index')->with('success', 'Peminjaman Sudah Dikembalikan.');
    }

public function approve_2($id)
{
    $pengajuan = PengajuanModel::findOrFail($id);

    if ($pengajuan->status != null && $pengajuan->status_approval_2 == 'pending') {
        $pengajuan->status_approval_2 = 'approved';
        $pengajuan->approved_2_at = Carbon::now();
        $pengajuan->approved_2_by = auth()->user()->id;

       $pengajuan->due_date = Carbon::today()->setHour(15)->setMinute(0)->setSecond(0);

        // Set status final berdasarkan keputusan petugas 2
  
        $pengajuan->save();

        return redirect()->route('approvals.index2')->with('success', 'Peminjaman telah disetujui oleh Petugas 2.');
    }

    return redirect()->route('approvals.index2')->with('error', 'Tidak dapat menyetujui data ini.');
}

public function reject_2($id)
{
    $pengajuan = PengajuanModel::findOrFail($id);

    if ($pengajuan->status != null && $pengajuan->status_approval_2 == 'pending') {
        $pengajuan->status_approval_2 = 'rejected';
        $pengajuan->rejected_2_at = Carbon::now();
        $pengajuan->rejected_2_by = auth()->user()->id;

        // Set status final berdasarkan keputusan petugas 2
     
        $pengajuan->save();

        return redirect()->route('approvals.index2')->with('success', 'Peminjaman telah ditolak oleh Petugas 2.');
    }

    return redirect()->route('approvals.index2')->with('error', 'Tidak dapat menolak data ini.');
}





    

}
