# TODO - Data Scope & Role Access

## Context
- Saat ini akses data dokumen masih cenderung flat untuk user yang punya izin `ViewAny:Document` / `View:Document`.
- Capability (`upload`, `view`, `approve`) sudah ada, tapi data scope (dokumen mana yang boleh terlihat) belum dipisahkan secara tegas.

## Target Behavior (Draft)
- `super_admin`: akses semua data + semua action.
- `uploader_*`: upload + lihat dokumen miliknya sendiri (opsional: +scope departemen/kategori).
- `viewer_*`: view-only, idealnya hanya dokumen yang sudah `approved` dalam scope.
- `approver_*`: lihat + approve dokumen `submitted` dalam scope.
- `approver_all_documents`: approve semua dokumen `submitted`.

## Technical Plan
- Tambah konsep data scope (disarankan via tabel relasi scope user, misalnya berdasarkan kategori/departemen).
- Buat service pusat visibility query, contoh: `DocumentVisibility::queryFor(User $user)`.
- Terapkan service visibility di:
  - `DocumentResource::getEloquentQuery()`
  - seluruh widget dashboard (agar metrik mengikuti scope user)
  - policy per-record (`view`, `approve`, `download`)
- Tambah bypass policy untuk `super_admin` via `before()`.

## Pending Business Decisions (Client)
- Uploader hanya lihat dokumen sendiri, atau semua dokumen dalam scope unit?
- Viewer boleh lihat semua status, atau hanya `approved`?
- Scope utama pakai departemen atau kategori?

## Notes
- Jangan implement sebelum keputusan bisnis final dari client untuk 3 poin di atas.
