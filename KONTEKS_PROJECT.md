# KONTEKS PROJECT: Sistem Inventaris Sparepart Truk
## Untuk Pembuatan Flowchart, Use Case Diagram, Sequence Diagram, dll.

---

## 1. RINGKASAN SISTEM

**Nama:** Sistem Inventaris Sparepart Truk  
**Teknologi:** Laravel (PHP), MySQL, Blade Templates, Bootstrap 5  
**Tujuan:** Mengelola inventaris sparepart kendaraan truk di sebuah perusahaan transportasi/logistik. Mencakup manajemen data sparepart, transaksi masuk/keluar barang, pengajuan pembelian, stock opname, dan pelaporan.

---

## 2. AKTOR / ROLE SISTEM

Sistem memiliki **3 role utama**:

| Role | Label | Akses Utama |
|------|-------|-------------|
| **Admin** | Admin Gudang | Full CRUD semua master data, transaksi, laporan, pengajuan pembelian, stock opname |
| **Pimpinan** | Pimpinan | Dashboard monitoring, approval pengajuan pembelian, approval stock opname, laporan |
| **Karyawan** | Karyawan/Mekanik | Buat permintaan sparepart, lihat katalog sparepart, lihat status permintaan |

---

## 3. DATA ENTITAS (DATABASE)

### 3.1 Tabel Master

**users** — Data pengguna sistem
- id, name, email, password, role (admin/pimpinan/karyawan), timestamps

**categories** — Kategori sparepart (misal: Oli, Filter, Ban, dll)
- id, name, description, timestamps

**suppliers** — Pemasok sparepart
- id, name, address, phone, email, timestamps

**spareparts** — Data sparepart
- id, code (unik, misal SP-001), name, category_id (FK→categories), supplier_id (FK→suppliers), stock (stok saat ini), min_stock (batas minimum), unit (pcs/liter/set), timestamps
- **PENTING:** stock dihitung otomatis — bertambah saat barang masuk, berkurang saat barang keluar diproses

### 3.2 Tabel Transaksi

**barang_masuk** — Pencatatan barang masuk dari supplier
- id, invoice_no (unik), date, time, supplier_id (FK), user_id (FK→admin yang mencatat), status (pending/approved/rejected), approved_by (FK→pimpinan), notes, timestamps

**detail_barang_masuk** — Detail item per transaksi masuk
- id, barang_masuk_id (FK), sparepart_id (FK), quantity, price (harga satuan)

**barang_keluar** — Pencatatan barang keluar / permintaan sparepart
- id, reference_no (unik), date, time, purpose (tujuan, misal "Perawatan Truk DT-014"), user_id (FK→user pembuat), requested_by (FK→karyawan peminta), truck_name (nama truk), notes, status (pending/processed), timestamps

**detail_barang_keluar** — Detail item per transaksi keluar
- id, barang_keluar_id (FK), sparepart_id (FK), quantity
- before_photo (path foto bukti kondisi sebelum), after_photo (path foto bukti setelah pasang), item_status (pending/processed/completed)

### 3.3 Tabel Pengajuan & Approval

**pengajuan_pembelian** — Pengajuan pembelian sparepart baru oleh Admin
- id, ajuan_no (unik), date, user_id (FK→admin pembuat), approved_by (FK→pimpinan), status (pending/approved/rejected), notes, timestamps

**detail_pengajuan_pembelian** — Detail item pengajuan
- id, pengajuan_pembelian_id (FK), sparepart_id (FK), quantity, notes
- price (decimal, harga satuan estimasi)

**stock_opnames** — Pencatatan stock opname (audit fisik)
- id, opname_no (unik), date, cycle_month (YYYY-MM), cycle_group (A/B/C/D), user_id (FK→admin), approved_by (FK→pimpinan), status (draft/submitted/approved/rejected), notes, timestamps

**stock_opname_details** — Detail hasil opname per sparepart
- id, stock_opname_id (FK), sparepart_id (FK), system_stock (stok di sistem), physical_stock (stok fisik hasil hitung), discrepancy (selisih = physical - system), notes

---

## 4. ALUR KERJA PER MODUL

### 4.1 AUTENTIKASI (Login/Logout)

**Alur Login:**
1. User buka halaman login
2. User input email + password
3. Sistem validasi: cari user by email, cek password (Hash::check)
4. Jika gagal → tampilkan error "Email atau password tidak sesuai"
5. Jika berhasil → regenerate session, simpan data user ke session `auth_user`
6. Redirect ke dashboard sesuai role:
   - admin → `/admin/dashboard`
   - pimpinan → `/pimpinan/dashboard`
   - karyawan → `/karyawan/dashboard`

**Alur Logout:**
1. User klik logout (POST)
2. Hapus session, invalidate, regenerate CSRF token
3. Redirect ke halaman login

**Middleware:**
- `simple.auth` — cek session `auth_user` ada
- `role:admin` / `role:pimpinan` / `role:karyawan` — cek role sesuai, jika tidak → redirect ke dashboard masing-masing

---

### 4.2 MANAJEMEN MASTER DATA (Admin Only)

#### 4.2.1 Sparepart (CRUD)
1. **Lihat Daftar** — Tabel sparepart dengan relasi category & supplier, paginate 10
2. **Tambah:**
   - Isi: code, name, category_id, supplier_id, min_stock, unit
   - Validasi: code unik, wajib semua kecuali unit
   - **Stok awal selalu 0** — stok berubah hanya via transaksi masuk/keluar
3. **Edit:** Ubah data master, **tidak bisa ubah stok** dari form ini
4. **Hapus:** Hapus sparepart dari database

#### 4.2.2 Kategori (CRUD)
- Standar CRUD: name (unik), description

#### 4.2.3 Supplier (CRUD)
- Standar CRUD: name, address, phone, email

---

### 4.3 TRANSAKSI BARANG MASUK (Admin Only)

**Alur Pencatatan Barang Masuk:**
1. Admin klik "Tambah Barang Masuk"
2. Isi form:
   - Tanggal & waktu transaksi
   - No Invoice/Faktur (unik)
   - Pilih Supplier
   - Tambah item: sparepart + quantity + harga satuan (bisa multi-item)
   - Catatan (opsional)
3. Klik simpan
4. Sistem validasi data (termasuk no invoice unik)
5. Sistem simpan transaksi + detail
6. **Sistem otomatis increment stok** sparepart sesuai quantity
7. Redirect ke daftar barang masuk

**Edit Barang Masuk:**
1. Admin buka form edit
2. Ubah data transaksi
3. **Stok dikoreksi otomatis:** rollback stok lama → apply stok baru (system adjusts stock by reversing old quantities and applying new ones)

**Hapus Barang Masuk:**
1. Admin hapus transaksi
2. **Sistem otomatis decrement stok** (mengembalikan stok)
3. Detail ikut terhapus (cascade)

---

### 4.4 TRANSAKSI BARANG KELUAR (Admin Only untuk proses)

**Alur Barang Keluar ada 2 jalur:**

**Jalur A — Admin langsung input (tanpa permintaan karyawan):**
1. Admin buat barang keluar langsung
2. Isi: reference_no, date, time, purpose, truck_name, items (sparepart + qty)
3. Status langsung "processed"
4. Stok otomatis berkurang

**Jalur B — Dari permintaan karyawan (flow lebih panjang):**
1. Karyawan buat permintaan (lihat modul 4.7)
2. Karyawan mengisi: reference_no, purpose, truck_name, items + **foto before per item** (wajib)
3. Status awal "pending", stok belum berkurang
4. Admin review permintaan
5. Admin klik "Proses" → status berubah "processed", **stok baru dikurangi**
6. Karyawan memasang sparepart → upload **foto after per item** (wajib)
7. Semua foto after terisi → status otomatis "completed"
8. Jika stok tidak cukup saat proses → error

**Edit Barang Keluar:**
- Mirip barang masuk — stok dikoreksi otomatis (rollback lama, apply baru)

**Hapus Barang Keluar:**
- Stok dikembalikan (increment)

---

### 4.5 PENGAJUAN PEMBELIAN (Admin buat → Pimpinan approve)

**Alur:**
1. **Admin** buat pengajuan pembelian:
   - Isi: ajuan_no, tanggal, catatan
   - Tambah item: sparepart + quantity + **estimasi harga satuan** + catatan item
   - Harga terakhir barang masuk ditampilkan sebagai referensi (auto-suggest)
   - Total estimasi (qty × harga) otomatis dihitung dan ditampilkan
   - Status awal: "pending"
2. **Pimpinan** melihat daftar pengajuan masuk
3. **Pimpinan** review detail pengajuan
   - Melihat estimasi harga per item dan total keseluruhan
   - Informasi budget sebagai pertimbangan approve/reject
4. Pimpinan pilih:
   - **Approve** → status "approved", catat siapa yang approve
   - **Reject** → status "rejected", wajib isi alasan penolakan
5. Admin bisa lihat status pengajuan yang sudah diproses

---

### 4.6 STOCK OPNAME (Admin buat → Pimpinan approve)

**Alur:**
1. **Admin** buat stock opname:
   - Isi: opname_no, tanggal, cycle_month (bulan periode), cycle_group (A/B/C/D)
   - Tambah item: pilih sparepart → input stok fisik hasil hitungan manual
   - Sistem otomatis hitung: system_stock (dari DB) vs physical_stock → discrepancy (selisih)
   - Status awal: "submitted"
2. **Pimpinan** melihat daftar stock opname
3. **Pimpinan** review detail (termasuk selisih stok)
4. Pimpinan pilih:
   - **Approve** → status "approved"
   - **Reject** → status "rejected", wajib isi alasan

---

### 4.7 PERMINTAAN SPAREPART (Karyawan → Admin proses)

**Alur:**
1. **Karyawan** login, masuk menu "Permintaan Sparepart"
2. Karyawan bisa lihat **Katalog Sparepart** (read-only, termasuk info stok tersedia)
3. Karyawan buat permintaan:
   - Isi: reference_no, purpose (tujuan penggunaan), truck_name (nama truk), catatan
   - Tambah item: sparepart + quantity yang dibutuhkan + **foto before** (wajib, bukti kondisi truk sebelum perbaikan)
   - Foto bisa diambil dari kamera langsung atau pilih dari galeri (mobile-friendly)
   - Foto otomatis dikompres (max 1920px, JPEG 80%) sebelum disimpan
4. **Sistem validasi:** stok sparepart harus cukup (qty <= stock saat ini)
5. Jika stok tidak cukup → error "Stok [nama] tidak cukup! Tersedia: [qty]"
6. Jika OK → simpan sebagai barang_keluar dengan status **"pending"**
7. Karyawan bisa lihat status permintaan (pending/processed/completed)
8. Setelah Admin proses (status "processed"), karyawan upload **foto after per item** (bukti pemasangan)
9. Semua foto after terisi → status otomatis **"completed"**
8. **Admin** melihat permintaan di daftar barang keluar (filter status pending)
9. Admin review dan klik **"Proses"** → status berubah "processed", stok berkurang

---

### 4.8 MONITORING STOK (Semua Role)

- Menampilkan semua sparepart dengan status visual:
  - 🔴 **Habis** — stok <= 0
  - 🟡 **Hampir Habis** — stok <= min_stock (tapi > 0)
  - 🟢 **Aman** — stok > min_stock
- Diurutkan: habis dulu, lalu hampir habis, lalu aman
- Filter berdasarkan kategori

---

### 4.9 LAPORAN / REPORTS (Admin & Pimpinan)

Sistem menyediakan **10 jenis laporan** dengan filter tanggal/periode:

| No | Nama Laporan | Filter | Keterangan |
|----|-------------|--------|------------|
| 1 | Data Inventaris | - | Seluruh data sparepart dengan kategori, supplier, stok, status |
| 2 | Barang Masuk | Tanggal/Periode/Supplier | Detail transaksi masuk |
| 3 | Barang Keluar | Tanggal/Periode | Detail transaksi keluar |
| 4 | Stok Minimum | - | Sparepart yang stoknya <= batas minimum |
| 5 | Riwayat Transaksi | Tanggal/Periode | Gabungan masuk + keluar |
| 6 | Transaksi per Supplier | Tanggal/Periode | Rekap per supplier: frekuensi, total barang, nilai |
| 7 | Sparepart Paling Sering Keluar | Tanggal/Periode | Ranking berdasarkan frekuensi keluar |
| 8 | Rekap Bulanan | Tanggal/Periode | Perbandingan masuk vs keluar per bulan |
| 9 | Pengajuan Pembelian | Tanggal/Periode | Riwayat pengajuan + status approval |
| 10 | Inventaris Bulanan | Periode | Gabungan stok, transaksi, dan hasil opname |

**Semua laporan bisa di-export ke PDF.**

---

### 4.10 DASHBOARD

**Dashboard Admin:**
- Statistik: Total sparepart, stok hampir habis, barang masuk hari ini, barang keluar hari ini
- Donut chart status stok (aman / hampir habis / habis)
- Aktivitas terakhir (3 transaksi terbaru masuk+keluar)

**Dashboard Pimpinan:**
- Statistik: Total sparepart, total stok gudang, masuk bulan ini, keluar bulan ini
- Bar chart transaksi 6 bulan terakhir (masuk vs keluar)

**Dashboard Karyawan:**
- Statistik: Total permintaan, pending, processed
- 5 permintaan terakhir

---

## 5. RELASI ANTAR ENTITAS (untuk ERD)

```
users ──1:N──> barang_masuk (user_id: admin pencatat)
users ──1:N──> barang_keluar (user_id: pembuat)
users ──1:N──> barang_keluar (requested_by: karyawan peminta)
users ──1:N──> pengajuan_pembelian (user_id: admin pembuat)
users ──1:N──> pengajuan_pembelian (approved_by: pimpinan)
users ──1:N──> stock_opnames (user_id: admin)
users ──1:N──> stock_opnames (approved_by: pimpinan)

categories ──1:N──> spareparts
suppliers ──1:N──> spareparts
suppliers ──1:N──> barang_masuk

spareparts ──1:N──> detail_barang_masuk
spareparts ──1:N──> detail_barang_keluar
spareparts ──1:N──> detail_pengajuan_pembelian
spareparts ──1:N──> stock_opname_details

barang_masuk ──1:N──> detail_barang_masuk
barang_keluar ──1:N──> detail_barang_keluar
pengajuan_pembelian ──1:N──> detail_pengajuan_pembelian
stock_opnames ──1:N──> stock_opname_details
```

---

## 6. ATURAN BISNIS PENTING

1. **Stok sparepart** tidak bisa diubah manual — hanya berubah otomatis via:
   - Barang Masuk → stok bertambah
   - Barang Keluar (status processed) → stok berkurang
   - Hapus/Edit transaksi → stok dikoreksi

2. **Karyawan** tidak bisa langsung mengambil barang — harus buat permintaan dulu, Admin yang memproses

3. **Stok awal sparepart** selalu 0 saat pertama ditambahkan

4. **Pengajuan Pembelian** dan **Stock Opname** butuh approval Pimpinan

5. **Barang Keluar dari permintaan karyawan** dimulai status "pending" — stok baru berkurang saat Admin "memproses"

6. **Validasi stok cukup** dilakukan saat karyawan membuat permintaan (qty <= stock)
9. **Foto before/after** wajib untuk setiap item permintaan karyawan — foto diupload via HP (accept="image/*" capture="environment"), dikompres otomatis
10. **Estimasi harga pengajuan pembelian** wajib diisi admin — harga terakhir barang masuk ditampilkan sebagai referensi

7. **Semua transaksi** mencatat user_id (siapa yang input) dan timestamp

8. **Laporan** memerlukan filter tanggal/periode — tanpa filter menampilkan data kosong

---

## 7. DAFTAR HALAMAN / MENU

### Menu Admin (/admin/*)
- Dashboard
- Data Sparepart (CRUD)
- Data Kategori (CRUD)
- Data Supplier (CRUD)
- Data User (read-only)
- Barang Masuk (CRUD)
- Barang Keluar (CRUD + Proses permintaan)
- Monitoring Stok
- Pengajuan Pembelian (Create, View, Delete)
- Stock Opname (Create, View, Delete)
- Laporan (10 jenis + PDF export)

### Menu Pimpinan (/pimpinan/*)
- Dashboard
- Pengajuan Pembelian (View, Approve, Reject)
- Stock Opname (View, Approve, Reject)
- Laporan (View + PDF export)

### Menu Karyawan (/karyawan/*)
- Dashboard
- Permintaan Sparepart (Create, View status)
- Katalog Sparepart (read-only)

### Menu Umum (semua role login)
- Monitoring Stok
- Laporan Detail + PDF

---

## 8. LIST USE CASE (untuk Use Case Diagram)

| UC-01 | Login | Semua user |
|-------|-------|------------|
| UC-02 | Logout | Semua user |
| UC-03 | Lihat Dashboard | Semua user (berbeda per role) |
| UC-04 | Kelola Sparepart | Admin |
| UC-05 | Kelola Kategori | Admin |
| UC-06 | Kelola Supplier | Admin |
| UC-07 | Lihat Data User | Admin |
| UC-08 | Catat Barang Masuk | Admin |
| UC-09 | Edit Barang Masuk | Admin |
| UC-10 | Hapus Barang Masuk | Admin |
| UC-11 | Catat Barang Keluar | Admin |
| UC-12 | Edit Barang Keluar | Admin |
| UC-13 | Proses Permintaan Karyawan | Admin |
| UC-14 | Hapus Barang Keluar | Admin |
| UC-15 | Buat Pengajuan Pembelian | Admin |
| UC-16 | Lihat Pengajuan Pembelian | Admin, Pimpinan |
| UC-17 | Approve/Reject Pengajuan | Pimpinan |
| UC-18 | Buat Stock Opname | Admin |
| UC-19 | Lihat Stock Opname | Admin, Pimpinan |
| UC-20 | Approve/Reject Stock Opname | Pimpinan |
| UC-21 | Monitoring Stok | Semua user |
| UC-22 | Lihat Laporan | Admin, Pimpinan |
| UC-23 | Export Laporan PDF | Admin, Pimpinan |
| UC-24 | Buat Permintaan Sparepart | Karyawan |
| UC-25 | Lihat Status Permintaan | Karyawan |
| UC-26 | Lihat Katalog Sparepart | Karyawan |

---

## 9. CONTOH SEQUENCE DIAGRAM YANG RELEVAN

Berikut alur-alur kunci yang cocok dijadikan Sequence Diagram:

**SD-01: Login**
Actor → Login Page → AuthController → DB (users) → Session → Dashboard

**SD-02: Catat Barang Masuk**
Admin → Form → InventoryController → Validasi → DB (barang_masuk + detail) → Update Stok Sparepart → Redirect

**SD-03: Karyawan Buat Permintaan**
Karyawan → Form → KaryawanController → Validasi Stok → DB (barang_keluar, status=pending) → Notifikasi

**SD-04: Admin Proses Permintaan Karyawan**
Admin → Daftar Barang Keluar (pending) → Klik Proses → InventoryController → Update status=processed → Decrement Stok → Redirect

**SD-05: Pengajuan Pembelian (end-to-end)**
Admin → Buat Pengajuan → DB (pending) → Pimpinan Review → Approve/Reject → Update Status

**SD-06: Stock Opname (end-to-end)**
Admin → Buat Opname → Input Fisik vs Sistem → DB (submitted) → Pimpinan Review → Approve/Reject

**SD-07: Export Laporan PDF**
User → Pilih Laporan → Set Filter → ReportController → Query DB → Generate PDF → Download

---

*Dokumen ini dibuat berdasarkan analisis source code project Laravel di C:\laragon\www\sistem-inventaris-truk per 8 Juni 2026.*
