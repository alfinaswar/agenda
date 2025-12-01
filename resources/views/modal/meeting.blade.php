 <!-- Modal Lihat Detail Booking Meeting -->
 <div class="modal fade" id="modalMeetingDetail" tabindex="-1" aria-labelledby="modalMeetingDetailLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title mb-0" id="modalMeetingDetailLabel">Detail Booking Ruangan</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
             </div>
             <div class="modal-body py-4">
                 <dl class="row g-2 mb-0">
                     <dt class="col-sm-4 fw-semibold text-muted">Ruangan</dt>
                     <dd class="col-sm-8" id="detailRuangan"></dd>

                     <dt class="col-sm-4 fw-semibold text-muted">Judul Meeting</dt>
                     <dd class="col-sm-8" id="detailJudulMeeting"></dd>

                     <dt class="col-sm-4 fw-semibold text-muted">Deskripsi</dt>
                     <dd class="col-sm-8" id="detailDeskripsiMeeting"></dd>

                     <dt class="col-sm-4 fw-semibold text-muted">Tanggal</dt>
                     <dd class="col-sm-8" id="detailTanggal"></dd>

                     <dt class="col-sm-4 fw-semibold text-muted">Jam Mulai</dt>
                     <dd class="col-sm-8" id="detailJamMulai"></dd>

                     <dt class="col-sm-4 fw-semibold text-muted">Jam Selesai</dt>
                     <dd class="col-sm-8" id="detailJamSelesai"></dd>

                     <dt class="col-sm-4 fw-semibold text-muted">Durasi (Menit)</dt>
                     <dd class="col-sm-8" id="detailDurasiMenit"></dd>

                     <dt class="col-sm-4 fw-semibold text-muted">Tautan Meeting</dt>
                     <dd class="col-sm-8" id="detailTautanMeeting"></dd>

                     <dt class="col-sm-4 fw-semibold text-muted">Lampiran</dt>
                     <dd class="col-sm-8" id="detailLampiranMeeting"></dd>

                     <dt class="col-sm-4 fw-semibold text-muted">User Pemesan</dt>
                     <dd class="col-sm-8" id="detailUserCreate"></dd>
                 </dl>
             </div>
             <div class="modal-footer d-flex justify-content-end">
                 @can('meeting-edit')
                     <button type="button" class="btn btn-warning btn-sm me-2" id="btnEditMeeting">
                         <i class="bi bi-pencil-square me-1"></i> Edit
                     </button>
                 @endcan
                 @can('meeting-delete')
                     <button type="button" class="btn btn-danger btn-sm me-2" id="btnDeleteMeeting">
                         <i class="bi bi-trash me-1"></i> Hapus
                     </button>
                 @endcan
             </div>
         </div>
     </div>
 </div>
 <!-- End Modal Lihat Detail Booking Meeting -->
