@extends('layouts.admin')

@section('title', 'Data Lokasi - SISMOKAP')
@section('page_title', 'Master Data Lokasi')

@section('content')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<div class="card p-4 bg-white">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <!-- Search -->
        <form action="{{ route('admin.lokasi.index') }}" method="GET" class="d-flex gap-2 align-items-center m-0">
            <div class="input-group" style="max-width: 250px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0 fs-7" placeholder="Cari lokasi, kota, provinsi, proyek..." value="{{ $search }}">
            </div>
            <button type="submit" class="btn btn-light border btn-sm px-3 fs-7 py-2">Cari</button>
            @if($search)
                <a href="{{ route('admin.lokasi.index') }}" class="btn btn-outline-secondary btn-sm px-3 fs-7 py-2">Reset</a>
            @endif
        </form>

        <!-- Tambah Lokasi Button -->
        <button type="button" class="btn btn-primary rounded-3 fs-7 py-2 px-3" data-bs-toggle="modal" data-bs-target="#addLokasiModal">
            <i class="bi bi-plus-lg me-1"></i> Tambah Lokasi
        </button>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
            <ul class="mb-0 fs-7">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle" style="min-width: 1200px;">
            <thead class="table-light">
                <tr class="text-muted fs-7 text-nowrap">
                    <th scope="col" style="width: 60px;">NO</th>
                    <th scope="col">NAMA LOKASI</th>
                    <th scope="col">PROYEK TERKAIT</th>
                    <th scope="col">KABUPATEN / KOTA</th>
                    <th scope="col">PROVINSI</th>
                    <th scope="col">TITIK KOORDINAT</th>
                    <th scope="col">ALAMAT LENGKAP</th>
                    <th scope="col">KETERANGAN</th>
                    <th scope="col" class="text-center" style="width: 150px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lokasis as $index => $lokasi)
                    <tr class="text-nowrap">
                        <td class="fs-7">{{ $lokasis->firstItem() + $index }}</td>
                        <td class="fs-7 fw-semibold text-dark">{{ $lokasi->nama_lokasi }}</td>
                        <td class="fs-7">
                            @if($lokasi->proyekAssociated)
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-8">{{ $lokasi->proyekAssociated->nama_proyek }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="fs-7 text-dark">{{ $lokasi->kabupaten_kota ?? '-' }}</td>
                        <td class="fs-7 text-dark">{{ $lokasi->provinsi ?? '-' }}</td>
                        <td class="fs-7 text-muted">
                            @if($lokasi->latitude && $lokasi->longitude)
                                <span class="badge bg-light text-dark border"><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $lokasi->latitude }}, {{ $lokasi->longitude }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="fs-7 text-muted text-wrap" style="min-width: 200px; max-width: 350px;">{{ $lokasi->alamat }}</td>
                        <td class="fs-7 text-muted text-wrap" style="min-width: 200px; max-width: 300px;">{{ $lokasi->keterangan_lokasi ?? '-' }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-sm btn-light border text-primary edit-button" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editLokasiModal" 
                                    data-id="{{ $lokasi->id }}" 
                                    data-nama="{{ $lokasi->nama_lokasi }}" 
                                    data-proyek="{{ $lokasi->proyek_id }}"
                                    data-kabupaten="{{ $lokasi->kabupaten_kota }}"
                                    data-provinsi="{{ $lokasi->provinsi }}"
                                    data-latitude="{{ $lokasi->latitude }}"
                                    data-longitude="{{ $lokasi->longitude }}"
                                    data-alamat="{{ $lokasi->alamat }}"
                                    data-keterangan="{{ $lokasi->keterangan_lokasi }}"
                                    title="Edit">
                                    <i class="bi bi-pencil-fill"></i> Edit
                                </button>
                                <form action="{{ route('admin.lokasi.destroy', $lokasi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lokasi ini?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus">
                                        <i class="bi bi-trash-fill"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4 fs-7">Tidak ada data lokasi ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <p class="text-muted fs-7 mb-0">Menampilkan {{ $lokasis->firstItem() ?? 0 }} sampai {{ $lokasis->lastItem() ?? 0 }} dari {{ $lokasis->total() }} lokasi</p>
        <div>
            {{ $lokasis->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Tambah Lokasi -->
<div class="modal fade" id="addLokasiModal" tabindex="-1" aria-labelledby="addLokasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="addLokasiModalLabel">Tambah Lokasi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.lokasi.store') }}" method="POST">
                @csrf
                <div class="modal-body py-4">
                    <div class="row g-3">
                        <!-- Proyek Dropdown -->
                        <div class="col-12 col-md-6">
                            <label for="proyek_id" class="form-label fs-7 fw-semibold text-dark">Proyek Terkait</label>
                            <select name="proyek_id" id="proyek_id" class="form-select fs-7">
                                <option value="">-- Pilih Proyek --</option>
                                @foreach($proyeks as $proyek)
                                    <option value="{{ $proyek->id }}">{{ $proyek->nama_proyek }} ({{ $proyek->kode_proyek }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Nama Lokasi -->
                        <div class="col-12 col-md-6">
                            <label for="nama_lokasi" class="form-label fs-7 fw-semibold text-dark">Nama Lokasi <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lokasi" id="nama_lokasi" class="form-control fs-7" placeholder="Contoh: Madiun Kota (Kartoharjo)" required>
                        </div>

                        <!-- Kabupaten / Kota -->
                        <div class="col-12 col-md-6">
                            <label for="kabupaten_kota" class="form-label fs-7 fw-semibold text-dark">Kabupaten / Kota <span class="text-danger">*</span></label>
                            <input type="text" name="kabupaten_kota" id="kabupaten_kota" class="form-control fs-7" placeholder="Contoh: Kota Madiun" required>
                        </div>

                        <!-- Provinsi -->
                        <div class="col-12 col-md-6">
                            <label for="provinsi" class="form-label fs-7 fw-semibold text-dark">Provinsi <span class="text-danger">*</span></label>
                            <input type="text" name="provinsi" id="provinsi" class="form-control fs-7" placeholder="Contoh: Jawa Timur" required>
                        </div>

                        <!-- Leaflet Maps Picker -->
                        <div class="col-12">
                            <label class="form-label fs-7 fw-semibold text-dark">Cari & Pilih Lokasi di Peta</label>
                            <div class="input-group mb-2 shadow-sm rounded-3">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" id="add_map_search" class="form-control border-start-0 fs-7" placeholder="Cari alamat atau tempat (Tekan Enter / Klik Cari)...">
                                <button type="button" id="btn_add_map_search" class="btn btn-primary fs-7 px-3">Cari</button>
                            </div>
                            <div id="add_map_container" class="rounded border shadow-sm" style="height: 280px; width: 100%; z-index: 1;"></div>
                            <small class="text-muted fs-8 d-block mt-1">Anda juga dapat menggeser pin merah atau mengklik peta untuk memperbarui koordinat.</small>
                        </div>

                        <!-- Latitude -->
                        <div class="col-12 col-md-6">
                            <label for="latitude" class="form-label fs-7 fw-semibold text-dark">Latitude</label>
                            <input type="text" name="latitude" id="latitude" class="form-control fs-7" placeholder="-7.6298">
                        </div>

                        <!-- Longitude -->
                        <div class="col-12 col-md-6">
                            <label for="longitude" class="form-label fs-7 fw-semibold text-dark">Longitude</label>
                            <input type="text" name="longitude" id="longitude" class="form-control fs-7" placeholder="111.5243">
                        </div>

                        <!-- Alamat Lengkap -->
                        <div class="col-12">
                            <label for="alamat" class="form-label fs-7 fw-semibold text-dark">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea name="alamat" id="alamat" rows="3" class="form-control fs-7" placeholder="Masukkan alamat lengkap lokasi proyek..." required></textarea>
                        </div>

                        <!-- Keterangan Lokasi -->
                        <div class="col-12">
                            <label for="keterangan_lokasi" class="form-label fs-7 fw-semibold text-dark">Keterangan Lokasi (Opsional)</label>
                            <textarea name="keterangan_lokasi" id="keterangan_lokasi" rows="2" class="form-control fs-7" placeholder="Catatan tambahan mengenai lokasi..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light border fs-7" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fs-7">Simpan Lokasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Lokasi -->
<div class="modal fade" id="editLokasiModal" tabindex="-1" aria-labelledby="editLokasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="editLokasiModalLabel">Edit Lokasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="editLokasiForm">
                @csrf
                @method('PUT')
                <div class="modal-body py-4">
                    <div class="row g-3">
                        <!-- Proyek Dropdown -->
                        <div class="col-12 col-md-6">
                            <label for="edit_proyek_id" class="form-label fs-7 fw-semibold text-dark">Proyek Terkait</label>
                            <select name="proyek_id" id="edit_proyek_id" class="form-select fs-7">
                                <option value="">-- Pilih Proyek --</option>
                                @foreach($proyeks as $proyek)
                                    <option value="{{ $proyek->id }}">{{ $proyek->nama_proyek }} ({{ $proyek->kode_proyek }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Nama Lokasi -->
                        <div class="col-12 col-md-6">
                            <label for="edit_nama_lokasi" class="form-label fs-7 fw-semibold text-dark">Nama Lokasi <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lokasi" id="edit_nama_lokasi" class="form-control fs-7" required>
                        </div>

                        <!-- Kabupaten / Kota -->
                        <div class="col-12 col-md-6">
                            <label for="edit_kabupaten_kota" class="form-label fs-7 fw-semibold text-dark">Kabupaten / Kota <span class="text-danger">*</span></label>
                            <input type="text" name="kabupaten_kota" id="edit_kabupaten_kota" class="form-control fs-7" required>
                        </div>

                        <!-- Provinsi -->
                        <div class="col-12 col-md-6">
                            <label for="edit_provinsi" class="form-label fs-7 fw-semibold text-dark">Provinsi <span class="text-danger">*</span></label>
                            <input type="text" name="provinsi" id="edit_provinsi" class="form-control fs-7" required>
                        </div>

                        <!-- Leaflet Maps Picker -->
                        <div class="col-12">
                            <label class="form-label fs-7 fw-semibold text-dark">Cari & Pilih Lokasi di Peta</label>
                            <div class="input-group mb-2 shadow-sm rounded-3">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" id="edit_map_search" class="form-control border-start-0 fs-7" placeholder="Cari alamat atau tempat (Tekan Enter / Klik Cari)...">
                                <button type="button" id="btn_edit_map_search" class="btn btn-primary fs-7 px-3">Cari</button>
                            </div>
                            <div id="edit_map_container" class="rounded border shadow-sm" style="height: 280px; width: 100%; z-index: 1;"></div>
                            <small class="text-muted fs-8 d-block mt-1">Anda juga dapat menggeser pin merah atau mengklik peta untuk memperbarui koordinat.</small>
                        </div>

                        <!-- Latitude -->
                        <div class="col-12 col-md-6">
                            <label for="edit_latitude" class="form-label fs-7 fw-semibold text-dark">Latitude</label>
                            <input type="text" name="latitude" id="edit_latitude" class="form-control fs-7">
                        </div>

                        <!-- Longitude -->
                        <div class="col-12 col-md-6">
                            <label for="edit_longitude" class="form-label fs-7 fw-semibold text-dark">Longitude</label>
                            <input type="text" name="longitude" id="edit_longitude" class="form-control fs-7">
                        </div>

                        <!-- Alamat Lengkap -->
                        <div class="col-12">
                            <label for="edit_alamat" class="form-label fs-7 fw-semibold text-dark">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea name="alamat" id="edit_alamat" rows="3" class="form-control fs-7" required></textarea>
                        </div>

                        <!-- Keterangan Lokasi -->
                        <div class="col-12">
                            <label for="edit_keterangan_lokasi" class="form-label fs-7 fw-semibold text-dark">Keterangan Lokasi (Opsional)</label>
                            <textarea name="keterangan_lokasi" id="edit_keterangan_lokasi" rows="2" class="form-control fs-7"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light border fs-7" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fs-7">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let addMap, editMap;
    let addMarker, editMarker;
    const defaultLat = -7.6298;
    const defaultLng = 111.5243;

    function initLeafletAdd() {
        const mapContainer = document.getElementById('add_map_container');
        if (!mapContainer || addMap) return;

        addMap = L.map(mapContainer).setView([defaultLat, defaultLng], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(addMap);

        addMarker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(addMap);

        addMap.on('click', function (e) {
            updateAddCoords(e.latlng.lat, e.latlng.lng);
            reverseGeocode(e.latlng.lat, e.latlng.lng, 'alamat', 'kabupaten_kota', 'provinsi');
        });

        addMarker.on('dragend', function (e) {
            const latlng = addMarker.getLatLng();
            updateAddCoords(latlng.lat, latlng.lng);
            reverseGeocode(latlng.lat, latlng.lng, 'alamat', 'kabupaten_kota', 'provinsi');
        });
    }

    function initLeafletEdit() {
        const mapContainer = document.getElementById('edit_map_container');
        if (!mapContainer || editMap) return;

        let latVal = parseFloat(document.getElementById('edit_latitude').value) || defaultLat;
        let lngVal = parseFloat(document.getElementById('edit_longitude').value) || defaultLng;

        editMap = L.map(mapContainer).setView([latVal, lngVal], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(editMap);

        editMarker = L.marker([latVal, lngVal], { draggable: true }).addTo(editMap);

        editMap.on('click', function (e) {
            updateEditCoords(e.latlng.lat, e.latlng.lng);
            reverseGeocode(e.latlng.lat, e.latlng.lng, 'edit_alamat', 'edit_kabupaten_kota', 'edit_provinsi');
        });

        editMarker.on('dragend', function (e) {
            const latlng = editMarker.getLatLng();
            updateEditCoords(latlng.lat, latlng.lng);
            reverseGeocode(latlng.lat, latlng.lng, 'edit_alamat', 'edit_kabupaten_kota', 'edit_provinsi');
        });
    }

    function updateAddCoords(lat, lng) {
        if (addMarker) {
            addMarker.setLatLng([lat, lng]);
        }
        document.getElementById('latitude').value = lat.toFixed(8);
        document.getElementById('longitude').value = lng.toFixed(8);
    }

    function updateEditCoords(lat, lng) {
        if (editMarker) {
            editMarker.setLatLng([lat, lng]);
        }
        document.getElementById('edit_latitude').value = lat.toFixed(8);
        document.getElementById('edit_longitude').value = lng.toFixed(8);
    }

    function searchLocation(query, mapObj, markerObj, latId, lngId, alamatId, kabId, provId) {
        if (!query) return;

        const isAdd = (latId === 'latitude');
        const btnSearch = isAdd ? document.getElementById('btn_add_map_search') : document.getElementById('btn_edit_map_search');
        
        if (btnSearch) {
            btnSearch.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            btnSearch.disabled = true;
        }

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`)
            .then(response => response.json())
            .then(data => {
                if (btnSearch) {
                    btnSearch.innerHTML = 'Cari';
                    btnSearch.disabled = false;
                }

                if (data && data.length > 0) {
                    const result = data[0];
                    const lat = parseFloat(result.lat);
                    const lon = parseFloat(result.lon);

                    mapObj.setView([lat, lon], 15);
                    markerObj.setLatLng([lat, lon]);

                    document.getElementById(latId).value = lat.toFixed(8);
                    document.getElementById(lngId).value = lon.toFixed(8);
                    document.getElementById(alamatId).value = result.display_name;

                    reverseGeocode(lat, lon, alamatId, kabId, provId);
                } else {
                    alert('Lokasi tidak ditemukan.');
                }
            })
            .catch(error => {
                if (btnSearch) {
                    btnSearch.innerHTML = 'Cari';
                    btnSearch.disabled = false;
                }
                console.error('Error searching location:', error);
            });
    }

    function reverseGeocode(lat, lng, alamatId, kabId, provId) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if (data) {
                    document.getElementById(alamatId).value = data.display_name || '';
                    const address = data.address;
                    if (address) {
                        let city = address.city || address.town || address.municipality || address.county || address.village || address.suburb || '';
                        let state = address.state || '';
                        
                        if (city) document.getElementById(kabId).value = city;
                        if (state) document.getElementById(provId).value = state;
                    }
                }
            })
            .catch(error => {
                console.error('Error reverse geocoding:', error);
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Event listener for Add Modal
        const addModal = document.getElementById('addLokasiModal');
        if (addModal) {
            addModal.addEventListener('shown.bs.modal', function () {
                initLeafletAdd();
                if (addMap) {
                    addMap.invalidateSize();
                }
            });
        }

        // Event listener for Edit Modal
        const editModal = document.getElementById('editLokasiModal');
        if (editModal) {
            editModal.addEventListener('shown.bs.modal', function () {
                initLeafletEdit();
                if (editMap) {
                    let latVal = parseFloat(document.getElementById('edit_latitude').value) || defaultLat;
                    let lngVal = parseFloat(document.getElementById('edit_longitude').value) || defaultLng;
                    editMap.setView([latVal, lngVal], 13);
                    editMarker.setLatLng([latVal, lngVal]);
                    editMap.invalidateSize();
                }
            });

            // Edit button data loader
            editModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const nama = button.getAttribute('data-nama');
                const proyek = button.getAttribute('data-proyek');
                const kabupaten = button.getAttribute('data-kabupaten');
                const provinsi = button.getAttribute('data-provinsi');
                const latitude = button.getAttribute('data-latitude');
                const longitude = button.getAttribute('data-longitude');
                const alamat = button.getAttribute('data-alamat');
                const keterangan = button.getAttribute('data-keterangan');

                const form = document.getElementById('editLokasiForm');
                form.action = `/admin/lokasi/${id}`;
                
                document.getElementById('edit_nama_lokasi').value = nama || '';
                document.getElementById('edit_proyek_id').value = proyek || '';
                document.getElementById('edit_kabupaten_kota').value = kabupaten || '';
                document.getElementById('edit_provinsi').value = provinsi || '';
                document.getElementById('edit_latitude').value = latitude || '';
                document.getElementById('edit_longitude').value = longitude || '';
                document.getElementById('edit_alamat').value = alamat || '';
                document.getElementById('edit_keterangan_lokasi').value = keterangan || '';
            });
        }

        // Add Search listeners
        const addSearchInput = document.getElementById('add_map_search');
        if (addSearchInput) {
            addSearchInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchLocation(addSearchInput.value, addMap, addMarker, 'latitude', 'longitude', 'alamat', 'kabupaten_kota', 'provinsi');
                }
            });
        }

        const btnAddSearch = document.getElementById('btn_add_map_search');
        if (btnAddSearch) {
            btnAddSearch.addEventListener('click', function () {
                searchLocation(addSearchInput.value, addMap, addMarker, 'latitude', 'longitude', 'alamat', 'kabupaten_kota', 'provinsi');
            });
        }

        // Edit Search listeners
        const editSearchInput = document.getElementById('edit_map_search');
        if (editSearchInput) {
            editSearchInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchLocation(editSearchInput.value, editMap, editMarker, 'edit_latitude', 'edit_longitude', 'edit_alamat', 'edit_kabupaten_kota', 'edit_provinsi');
                }
            });
        }

        const btnEditSearch = document.getElementById('btn_edit_map_search');
        if (btnEditSearch) {
            btnEditSearch.addEventListener('click', function () {
                searchLocation(editSearchInput.value, editMap, editMarker, 'edit_latitude', 'edit_longitude', 'edit_alamat', 'edit_kabupaten_kota', 'edit_provinsi');
            });
        }
    });
</script>
@endsection
