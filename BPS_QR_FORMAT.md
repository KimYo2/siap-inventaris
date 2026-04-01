# Format QR Code BPS

## 📋 Format QR Code Asli BPS

### Isi QR Code

QR Code yang tertempel pada barang inventaris BPS berisi teks berikut:

```
INV-20210420145333129398000*054010300C*190000000KD*3100102001*37
```

Contoh representasi visual isi QR (teks):

```
+----------------------------------------------------------+
|  INV-20210420145333129398000*054010300C*190000000KD*     |
|  3100102001*37                                           |
+----------------------------------------------------------+
  ↑ Teks di atas adalah isi decoded QR Code dari stiker BPS
```

## 🔍 Struktur Data

QR Code BPS menggunakan **delimiter asterisk (`*`)** untuk memisahkan data:

```
INV-[timestamp]*[kode_unit]*[kode_kategori]*[NOMOR_BMN]*[NUP]
```

### Breakdown (Index 0-based setelah split `*`):

| Index | Contoh | Keterangan |
|-------|--------|------------|
| `[0]` | `INV-20210420145333129398000` | Prefix + Timestamp |
| `[1]` | `054010300C` | Kode lokasi/unit |
| `[2]` | `190000000KD` | Kode kategori |
| **`[3]`** | **`3100102001`** | **Kode Barang (Nomor BMN)** ⭐ |
| **`[4]`** | **`37`** | **NUP (Nomor Urut Pendaftaran)** ⭐ |

> **Catatan penting**: Setelah `split('*')`, indeks dimulai dari `0`.
> Kode Barang ada di `parts[3]`, bukan `parts[2]`.
> NUP ada di `parts[4]`, bukan `parts[3]`.

## ✅ Parser Implementation

### JavaScript Parser

```javascript
// Parse BPS QR Code if detected (Format: INV-...*UNIT*KATEGORI*KODE*NUP)
if (decodedText.includes('*')) {
    const parts = decodedText.split('*');
    if (parts.length >= 5) {
        // Full BPS format: INV*UNIT*KATEGORI*KODE*NUP
        this.scannedCode = parts[3].trim() + '-' + parts[4].trim();
        this.isRawBPS = true;
    } else if (parts.length >= 3) {
        // Shorter fallback (e.g. BPS*KATEGORI*KODE*NUP)
        this.scannedCode = parts[2].trim();
        this.isRawBPS = true;
    } else {
        this.scannedCode = decodedText; // Fallback langsung
    }
}
```

### Contoh Parsing

**Input (format lengkap BPS):**
```
INV-20210420145333129398000*054010300C*190000000KD*3100102001*37
```

**Proses:**
```
parts[0] = "INV-20210420145333129398000"  → timestamp, diabaikan
parts[1] = "054010300C"                   → kode unit, diabaikan
parts[2] = "190000000KD"                  → kode kategori, diabaikan
parts[3] = "3100102001"                   → ✅ Kode Barang
parts[4] = "37"                           → ✅ NUP
```

**Output:**
```
3100102001-37
```

## 🎯 Cara Kerja Sistem

### Flow Lengkap

```
1. Scan QR Code BPS (stiker fisik pada barang)
   ↓
2. Decode QR → "INV...*054010300C*190000000KD*3100102001*37"
   ↓
3. Cek apakah string mengandung delimiter '*'
   ↓
4. Split string dengan '*' → array 5 elemen
   ↓
5. Ambil parts[3] (Kode Barang) dan parts[4] (NUP)
   ↓
6. Gabungkan dengan hyphen: "3100102001-37"
   ↓
7. Query database: WHERE kode_barang = '3100102001' AND nup = '37'
```

### Logika Parsing

- **Format Target**: `[kode_barang]-[NUP]`
- **Sumber Data**:
    - `parts[3]` → Kode Barang (e.g. `3100102001`)
    - `parts[4]` → NUP (Nomor Urut Pendaftaran, e.g. `37`)
- **Tujuan**: Kombinasi `kode_barang + NUP` adalah *composite key* unik yang mengidentifikasi satu unit barang secara spesifik.

## 🏷️ Format QR Code Sistem Ini (Generated)

Selain membaca QR Code dari stiker BPS lama, sistem ini juga **meng-generate QR Code sendiri** yang dapat dicetak sebagai label barang baru melalui panel admin.

### Format QR yang Di-generate Sistem

```
[kode_barang]-[NUP]
```

**Contoh:**
```
3100102001-37
```

QR Code ini berisi **plain text** dalam format `kode_barang-NUP`, tanpa segmen tambahan.

### Cara Generate

1. Masuk ke panel admin → halaman **Daftar Barang**
2. Klik ikon QR pada baris barang yang diinginkan
3. Sistem menampilkan halaman cetak label QR
4. Gunakan fungsi print browser untuk mencetak

### Perbedaan dengan QR BPS Lama

| Aspek | QR BPS Lama (Stiker) | QR Sistem Ini |
|-------|----------------------|---------------|
| Format | `INV-...*...*...*KODE*NUP` | `KODE-NUP` |
| Segmen | 5 bagian dipisah `*` | 1 string langsung |
| Parsing | `parts[3]` + `parts[4]` | Langsung digunakan |
| Asal | Dicetak BPS pusat | Di-generate dari admin |

## 🧪 Testing

### Test Case 1: Format Lengkap BPS

**Input:**
```
INV-20210420145333129398000*054010300C*190000000KD*3100102001*37
```

**Proses:** `parts.length = 5` → ambil `parts[3]` dan `parts[4]`

**Expected output:**
```
3100102001-37 ✅
```

### Test Case 2: Plain BMN (QR sistem ini atau manual)

**Input:**
```
3100102001-37
```

**Proses:** Tidak ada `*` → digunakan langsung

**Expected output:**
```
3100102001-37 ✅
```

### Test Case 3: Format Fallback (4 segmen)

**Input:**
```
BPS*LAPTOP*3100102002*2021
```

**Proses:** `parts.length = 4` → masuk blok `>= 3`, ambil `parts[2]`

**Expected output:**
```
3100102002 ✅ (fallback — NUP tidak diambil karena format tidak standar)
```

> **Catatan**: Jika `parts[3]` berisi angka (NUP), sistem pencarian akan mencoba mencocokkan dengan kode saja. Untuk hasil terbaik, gunakan QR format lengkap.

## 🔧 Troubleshooting

### QR Tidak Terbaca

**Penyebab:**
- QR Code rusak/blur atau terlipat
- Pencahayaan kurang memadai
- Jarak kamera terlalu dekat/jauh

**Solusi:**
- Pastikan QR Code dalam kondisi bersih dan jelas
- Tambah pencahayaan dari arah samping
- Jaga jarak kamera sekitar 10–30 cm

### Barang Tidak Ditemukan Setelah Scan

**Penyebab:**
- Nomor BMN atau NUP tidak sesuai dengan database
- Format parsing menghasilkan nilai yang salah

**Langkah Debug:**
1. Buka console browser (F12 → Console)
2. Cari log: `"Extracted BMN: xxx"`
3. Pastikan nilai yang ter-extract sesuai dengan data di tabel `barang`
4. Cek kombinasi `kode_barang` + `nup` di MySQL

## 📊 Database Matching

### Query yang Dijalankan

```sql
-- Pencarian berdasarkan kode_barang dan nup (composite key)
SELECT * FROM barang 
WHERE kode_barang = '3100102001' 
  AND nup = 37
```

### Jika Tidak Ditemukan

Sistem akan mengembalikan respons:
```json
{
  "success": false,
  "message": "Barang dengan nomor BMN tersebut tidak ditemukan"
}
```

### Query Debugging

```sql
-- Cek apakah kode_barang ada
SELECT kode_barang, nup, brand, tipe 
FROM barang 
WHERE kode_barang LIKE '31001020%';

-- Cek kombinasi spesifik
SELECT * FROM barang 
WHERE kode_barang = '3100102001' AND nup = 37;
```

## Changelog

- **v1.0** — Implementasi awal parser QR Code BPS dengan delimiter `*`
- **v1.1** — Perbaikan indeks segmen: `parts[3]` = kode_barang, `parts[4]` = NUP
- **v1.2** — Tambah fallback untuk format QR non-standar (< 5 segmen)
- **v1.3** — Tambah dokumentasi format QR yang di-generate sistem
