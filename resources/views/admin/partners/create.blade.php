@extends('layouts.admin')

@section('title', 'Tambah Partner')

@section('content')
<div style="max-width:640px;">
    <!-- Back link -->
    <a href="{{ route('admin.partners.index') }}"
       style="display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:#94a3b8;text-decoration:none;margin-bottom:20px;transition:color 150ms;"
       onmouseover="this.style.color='#1e293b'" onmouseout="this.style.color='#94a3b8'">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Daftar Partner
    </a>

    <h1 style="font-size:24px;font-weight:700;color:#0f172a;letter-spacing:-0.02em;margin-bottom:6px;">Tambah Partner Baru</h1>
    <p style="font-size:14px;color:#475569;margin-bottom:24px;">Isi formulir di bawah untuk menambahkan mitra baru.</p>

    <div style="background:#fff;border:1px solid #f1f5f9;border-radius:16px;padding:24px;
                box-shadow:0 1px 3px 0 rgba(15,23,42,.03);">
        <form action="{{ route('admin.partners.store') }}" method="POST">
            @csrf

            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:13px;font-weight:600;color:#1e293b;margin-bottom:6px;">Nama Partner</label>
                <input type="text" name="name" placeholder="Masukkan nama mitra" required
                       style="width:100%;height:44px;padding:0 16px;border:1px solid #e2e8f0;border-radius:12px;
                              font-size:14px;color:#1e293b;background:#fff;outline:none;font-family:'Manrope',sans-serif;box-sizing:border-box;"
                       onfocus="this.style.borderColor='#9d5ef5';this.style.boxShadow='0 0 0 4px #f3ebfe';"
                       onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none';">
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:13px;font-weight:600;color:#1e293b;margin-bottom:6px;">Logo URL</label>
                <input type="url" name="logo_url" placeholder="https://example.com/logo.png" required
                       style="width:100%;height:44px;padding:0 16px;border:1px solid #e2e8f0;border-radius:12px;
                              font-size:14px;color:#1e293b;background:#fff;outline:none;font-family:'Manrope',sans-serif;box-sizing:border-box;"
                       onfocus="this.style.borderColor='#9d5ef5';this.style.boxShadow='0 0 0 4px #f3ebfe';"
                       onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none';">
                <p style="font-size:12px;color:#94a3b8;margin-top:6px;">Masukkan URL langsung menuju file gambar logo.</p>
            </div>

            <div style="border-top:1px solid #f1f5f9;padding-top:20px;display:flex;justify-content:flex-end;gap:10px;">
                <a href="{{ route('admin.partners.index') }}"
                   style="height:40px;padding:0 20px;display:inline-flex;align-items:center;background:#fff;color:#475569;
                          border:1px solid #e2e8f0;border-radius:8px;font-size:14px;font-weight:600;text-decoration:none;transition:background 150ms;"
                   onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">Batal</a>
                <button type="submit"
                        style="height:40px;padding:0 24px;background:#8436f2;color:#fff;border:none;border-radius:8px;
                               font-size:14px;font-weight:600;cursor:pointer;font-family:'Manrope',sans-serif;transition:background 150ms;"
                        onmouseover="this.style.background='#7831dc'" onmouseout="this.style.background='#8436f2'">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
