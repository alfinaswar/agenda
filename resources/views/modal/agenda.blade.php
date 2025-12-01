 <div class="modal fade" id="modalAgendaDetail" tabindex="-1" aria-labelledby="modalAgendaDetailLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title mb-0" id="modalAgendaDetailLabel">Detail Agenda</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
             </div>
             <div class="modal-body py-4">
                 <dl class="row g-2 mb-0">
                     <dt class="col-sm-4 fw-semibold text-muted">Judul Agenda</dt>
                     <dd class="col-sm-8" id="detailJudul"></dd>

                     <dt class="col-sm-4 fw-semibold text-muted">Kategori</dt>
                     <dd class="col-sm-8" id="detailKategori"></dd>

                     <dt class="col-sm-4 fw-semibold text-muted">Deskripsi</dt>
                     <dd class="col-sm-8" id="detailDeskripsi"></dd>

                     <dt class="col-sm-4 fw-semibold text-muted">Tanggal Mulai</dt>
                     <dd class="col-sm-8" id="detailTanggalMulai"></dd>

                     <dt class="col-sm-4 fw-semibold text-muted">Tanggal Selesai</dt>
                     <dd class="col-sm-8" id="detailTanggalSelesai"></dd>

                     <dt class="col-sm-4 fw-semibold text-muted">Jam Mulai</dt>
                     <dd class="col-sm-8" id="detailJamMulai"></dd>

                     <dt class="col-sm-4 fw-semibold text-muted">Jam Selesai</dt>
                     <dd class="col-sm-8" id="detailJamSelesai"></dd>

                     <dt class="col-sm-4 fw-semibold text-muted">Lokasi</dt>
                     <dd class="col-sm-8" id="detailLokasi"></dd>

                     <dt class="col-sm-4 fw-semibold text-muted">Tautan Rapat</dt>
                     <dd class="col-sm-8" id="detailTautan"></dd>

                     <dt class="col-sm-4 fw-semibold text-muted">Status</dt>
                     <dd class="col-sm-8" id="detailStatus"></dd>

                     <dt class="col-sm-4 fw-semibold text-muted">Lampiran</dt>
                     <dd class="col-sm-8" id="detailLampiran"></dd>
                 </dl>
             </div>
             <div class="modal-footer d-flex justify-content-end">
                 @can('agenda-edit')
                     <button type="button" class="btn btn-warning btn-sm me-2" id="btnEditAgenda">
                         <i class="bi bi-pencil-square me-1"></i> Edit
                     </button>
                 @endcan
                 @can('agenda-delete')
                     <button type="button" class="btn btn-danger btn-sm me-2" id="btnDeleteAgenda">
                         <i class="bi bi-trash me-1"></i> Hapus
                     </button>
                 @endcan


             </div>
         </div>
     </div>
 </div>
 <!-- End Modal Lihat Detail Agenda -->
