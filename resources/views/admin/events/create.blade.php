@extends('layouts.admin')

@section('title', 'Tambah Event')

@section('content')
<div style="max-width:720px;">
    <a href="{{ route('admin.events.index') }}"
       style="display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:#94a3b8;text-decoration:none;margin-bottom:20px;transition:color 150ms;"
       onmouseover="this.style.color='#1e293b'" onmouseout="this.style.color='#94a3b8'">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Daftar Event
    </a>

    <h1 style="font-size:24px;font-weight:700;color:#0f172a;letter-spacing:-0.02em;margin-bottom:6px;">Tambah Event Baru</h1>
    <p style="font-size:14px;color:#475569;margin-bottom:24px;">Isi seluruh informasi yang diperlukan untuk acara baru.</p>

    <div style="background:#fff;border:1px solid #f1f5f9;border-radius:16px;padding:24px;box-shadow:0 1px 3px 0 rgba(15,23,42,.03);">
        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Input style helper (inline via JS is repetitive, define once) --}}
            <style>
                .form-input {
                    width: 100%; height: 44px; padding: 0 16px;
                    border: 1px solid #e2e8f0; border-radius: 12px;
                    font-size: 14px; color: #1e293b; background: #fff;
                    outline: none; font-family: 'Manrope', sans-serif;
                    box-sizing: border-box; transition: border-color 150ms, box-shadow 150ms;
                }
                .form-input:focus { border-color: #9d5ef5; box-shadow: 0 0 0 4px #f3ebfe; }
                .form-input::placeholder { color: #94a3b8; }
                .form-textarea {
                    width: 100%; min-height: 100px; padding: 12px 16px;
                    border: 1px solid #e2e8f0; border-radius: 12px;
                    font-size: 14px; color: #1e293b; background: #fff;
                    outline: none; font-family: 'Manrope', sans-serif;
                    box-sizing: border-box; resize: vertical;
                    transition: border-color 150ms, box-shadow 150ms;
                }
                .form-textarea:focus { border-color: #9d5ef5; box-shadow: 0 0 0 4px #f3ebfe; }
                .form-label { display: block; font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 6px; }
                .form-group { margin-bottom: 20px; }
            </style>

            <div class="form-group">
                <label class="form-label">Judul Event</label>
                <input type="text" name="title" class="form-input" placeholder="Masukkan judul acara" required>
            </div>

            <div class="form-group">
                <label class="form-label">Kategori Event</label>
                <select name="category_id" class="form-input" style="height:44px;" required>
                    <option value="" disabled selected>— Pilih Kategori —</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi Singkat</label>
                <textarea name="description" class="form-textarea" rows="4" required placeholder="Deskripsikan acara secara singkat..."></textarea>
            </div>

            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px;">
                <div>
                    <label class="form-label">Tanggal &amp; Waktu</label>
                    <input type="datetime-local" name="date" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Harga Tiket (Rp)</label>
                    <input type="number" name="price" class="form-input" placeholder="0" min="0" required>
                </div>
                <div>
                    <label class="form-label">Kapasitas / Stok</label>
                    <input type="number" name="stock" class="form-input" placeholder="0" min="1" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Lokasi / Gedung</label>
                <input type="text" name="location" class="form-input" placeholder="Nama gedung atau lokasi" required>
            </div>

            <div class="form-group">
                <label class="form-label">Poster Event <span style="color:#94a3b8;font-weight:400;">(opsional)</span></label>
                <input type="file" name="poster" accept="image/*"
                       style="width:100%;padding:10px 16px;border:1px solid #e2e8f0;border-radius:12px;
                              font-size:13px;color:#475569;background:#fff;font-family:'Manrope',sans-serif;box-sizing:border-box;">
                <p style="font-size:12px;color:#94a3b8;margin-top:6px;">Format: JPG, PNG, WEBP. Maks 2MB.</p>
            </div>

            <div style="border-top:1px solid #f1f5f9;padding-top:20px;display:flex;justify-content:flex-end;gap:10px;">
                <a href="{{ route('admin.events.index') }}"
                   style="height:40px;padding:0 20px;display:inline-flex;align-items:center;background:#fff;color:#475569;
                          border:1px solid #e2e8f0;border-radius:8px;font-size:14px;font-weight:600;text-decoration:none;transition:background 150ms;"
                   onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">Batal</a>
                <button type="submit"
                        style="height:40px;padding:0 24px;background:#8436f2;color:#fff;border:none;border-radius:8px;
                               font-size:14px;font-weight:600;cursor:pointer;font-family:'Manrope',sans-serif;transition:background 150ms;"
                        onmouseover="this.style.background='#7831dc'" onmouseout="this.style.background='#8436f2'">
                    Simpan Event
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
