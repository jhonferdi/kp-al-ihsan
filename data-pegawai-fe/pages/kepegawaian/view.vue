<template>
  <b-container fluid>
    <page-header :title="`${$metaInfo.title}`" :items="[
      { text: 'Pegawai', to: { name: 'pegawai' } },
      { text: $metaInfo.title, active: true },
    ]" v-show="$checkPermission('edit-admin')" />
    <h4 v-show="$checkPermission('edit-admin')">Detail Pegawai</h4>
    <h4 v-show="!$checkPermission('edit-admin')">Profile Saya</h4>
    <b-card>
      <b-row class="my-1">
        <b-col sm="2">
          <div>
            <img v-if="
              dokumen_digital[''] &&
              dokumen_digital[''][''] &&
              dokumen_digital[''][''][24]
            " class="w-100" rounded="lg" :src="dokumen_digital[''][''][24].file_url" style="" />
            <img v-else class="w-100" rounded="lg" src="@/assets/images/logo-al-ihsan.png" style="" />
            <!-- <b-avatar rounded="sm" size="175px"></b-avatar> -->
          </div>
        </b-col>
        <b-col sm="10">
          <div>
            <div class="d-flex justify-content-between">
              <div>
                <h2 style="color: #616161">{{ pegawai.peg_nama_lengkap }}</h2>
              </div>
              <div>
                <!-- <nuxt-link :to="{ name: 'pegawai.edit', params: { id: pegawai.peg_id }}" class="mr-2">
                  <b-button rounded="lg" variant="darkblue">
                    <b-icon icon="pencil"></b-icon> Edit Profil
                  </b-button>
                </nuxt-link> -->
                <a href="#" @click.prevent="openModal('editUtama')">
                  <img src="@/assets/images/svg/pencil.svg" alt="" />
                </a>
                <ModalEditUtama :open="modals.editUtama.isOpen" :pegawai="pegawai" @close="closeModal('editUtama')"
                  @onSave="refreshPegawai" :title="modals.editUtama.title" />
                <!-- <b-button rounded="lg" variant="darkgreen">
                  <b-icon icon="printer"></b-icon> Cetak Profil
                </b-button> -->
              </div>
            </div>
            <div class="mt-3">
              <!--  <h5 class="text-secondary"> {{ pegawai.jabatan_nama }} </h5> -->
            </div>
            <div>
              <span class="badge badge-success" v-if="pegawai.peg_status_kerja == '0'">
                {{
                    pegawai.status_pegawai != null
                      ? pegawai.status_pegawai.status_kepegawaian
                      : "-"
                }}
                - Aktif
              </span>
              <span class="badge badge-danger" v-if="
                pegawai.peg_status_kerja != '0' ||
                pegawai.peg_status_kerja == null
              ">
                {{
                    pegawai.status_pegawai != null
                      ? pegawai.status_pegawai.status_kepegawaian
                      : "-"
                }}
                - Tidak Aktif
              </span>

              <div class="d-flex mt-3">
                <div class="w-100">
                  <b-row>
                    <b-col sm="6" class="mb-3">
                      <img src="@/assets/images/jabatan.jpg" width="15" class="img-fluid" />
                      <span class="pl-2">
                        {{
                            pegawai.jabatan != null
                              ? pegawai.jabatan.jabatan_nama
                              : "-"
                        }}
                      </span>
                    </b-col>
                    <b-col sm="6" class="mb-3">
                      <img src="@/assets/images/building.png" width="15" class="img-fluid" />
                      <span class="pl-2">
                        {{
                            pegawai.unit_kerja != null
                              ? pegawai.unit_kerja.unit_kerja_nama
                              : "-"
                        }}
                      </span>
                    </b-col>
                    <b-col sm="6" class="mb-3">
                      <img src="@/assets/images/email.png" width="15" class="img-fluid" />
                      <span class="pl-2"> {{ pegawai.peg_email || "-" }} </span>
                    </b-col>
                    <b-col sm="6" class="mb-3">
                      <img src="@/assets/images/telp.png" width="15" class="img-fluid" />
                      <span class="pl-2">
                        {{ pegawai.peg_telp_hp || "-" }}
                      </span>
                    </b-col>
                    <b-col sm="6" class="mb-3">
                      <img src="@/assets/images/ruangan.png" width="15" class="img-fluid" />
                      <span class="pl-2">
                        {{
                            pegawai.pegawai_ruangan.length > 0
                              ? pegawai.pegawai_ruangan[0].ruangan.nama_ruangan
                              : "-"
                        }}
                      </span>
                    </b-col>
                    <b-col sm="6" class="mb-3">
                      <img src="@/assets/images/location.png" width="15" class="img-fluid" />
                      <span class="pl-2">
                        {{ pegawai.peg_rumah_alamat || "-" }}
                      </span>
                    </b-col>
                  </b-row>
                </div>
                <div class="d-flex align-items-end">
                  <span class="text-secondary text-nowrap">
                    TMT Kerja : {{ pegawai.peg_tmt | formatDate }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </b-col>
      </b-row>
    </b-card>
    <template>
      <div>
        <div class="border bg-white rounded p-2" style="border-color: #34894e !important">
          <ul class="p-0 m-0 d-sm-flex flex-wrap" style="list-style: none">
            <li class="mr-2" v-for="tab in tabs">
              <b-button v-if="!tab.children" class="text-nowrap w-100 border-none font-weight-bold"
                :variant="tabIndex == i ? 'darkgreen' : 'white'" :class="
                  tabIndex == tab.id ? 'bg-success text-white' : 'text-muted'
                " @click.prevent="tabIndex = tab.id">
                {{ tab.name }}</b-button>
              <b-dropdown v-else id="dropdown-1" class="font-weight-bold d-flex" :text="tab.name" variant="success">
                <template #button-content>
                  {{ tab.name }}
                  <i style="" class="p-0 m-0 d-inline-block bx bx-caret-down mb-0"></i>
                </template>
                <b-dropdown-item-button @click.prevent="tabIndex = child.id" :active="tabIndex == child.id"
                  v-for="(child, i) in tab.children" :key="i"
                  v-if="!child.permission || $checkPermission(child.permission)">
                  {{ child.name }}
                </b-dropdown-item-button>
              </b-dropdown>
            </li>
          </ul>
        </div>
        <b-tabs v-model="tabIndex" card pills nav-class="d-none p-0 m-0">
          <b-tab title="Informasi Kepegawaian" :title-link-class="linkClass(0)">
            <b-row>
              <b-col sm="9">
                <b-card>
                  <div class="text-right">
                    <a href="#" @click.prevent="openModal('editProfile')">
                      <img src="@/assets/images/svg/pencil.svg" alt="" />
                    </a>
                    <ModalEditProfile :open="modals.editProfile.isOpen" @close="closeModal('editProfile')"
                      @onSave="refreshPegawai" :title="modals.editProfile.title" :pegawai="pegawai" />
                  </div>
                  <b-row>
                    <b-col sm="6" class="mt-3 pl-4">
                      <div style="max-width: 25rem">
                        <div class="ml-0 mb-3">
                          <img src="@/assets/images/profil.png" width="17" class="img-fluid" />
                          <span class="pl-2"><b>Profile</b></span>
                        </div>
                        <ProfileBlockItem class="ml-4" label="Nama Lengkap">
                          {{ pegawai.peg_nama_lengkap || "-" }}
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="NIP/NIPT/NIK">
                          {{ pegawai.peg_nip_nipt_nik || "-" }}
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="Tempat Tanggal Lahir">
                          {{ pegawai.peg_lahir_tempat }},
                          {{ pegawai.peg_lahir_tanggal | formatDate }}
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="Jenis Kelamin">
                          {{
                              pegawai.peg_jenis_kelamin == "L"
                                ? "Laki-laki"
                                : pegawai.peg_jenis_kelamin == "P"
                                  ? "Perempuan"
                                  : "-"
                          }}
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="Agama">
                          {{ pegawai.peg_agama || "-" }}
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="Status Pernikahan">
                          {{ pegawai.peg_status_pernikahan || "-" }}
                          <p class="pt-1 mb-0">
                            <UploadFile v-if="pegawai.peg_status_pernikahan == 'Menikah'" :pegawai="pegawai"
                              :dokumenDigital="dokumen_digital" :masterDokumenDigital="
                                getDokumenDigital('Status Pernikahan')
                              " :canEdit="canEdit('Status Pernikahan')" @onUploaded="refreshPegawai">
                            </UploadFile>
                          </p>
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="Golongan Darah">
                          {{ pegawai.peg_golongan_darah || "-" }}
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="PAS PHOTO (LATAR MERAH 4x6)">
                          <p class="mb-0">
                            <UploadFile :pegawai="pegawai" :dokumenDigital="dokumen_digital" :masterDokumenDigital="
                              getDokumenDigital('Pas Photo')
                            " :canEdit="canEdit('Pas Photo')" @onUploaded="refreshPegawai">
                            </UploadFile>
                          </p>
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="Tingkat Pendidikan">
                          {{
                              pegawai.kualifikasi_pendidikan != null
                                ? pegawai.kualifikasi_pendidikan
                                  .kualifikasi_pendidikan
                                : "-"
                          }}
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="Jurusan">
                          {{
                              pegawai.pendidikan != null
                                ? pegawai.pendidikan.pendidikan_nama
                                : "-"
                          }}
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="Almamater">
                          {{ pegawai.peg_almamater_nama || "-" }}
                        </ProfileBlockItem>
                      </div>
                      <div style="max-width: 25rem">
                        <div class="mt-4 ml-0 mb-3">
                          <img src="@/assets/images/informasi.png" width="17" class="img-fluid" />
                          <span class="pl-2"><b>Informasi Lainnya</b></span>
                        </div>

                        <ProfileBlockItem class="ml-4" label="Nomor KTP">
                          {{ pegawai.peg_ktp || "-" }}
                          <p class="pt-1 mb-0">
                            <UploadFile v-if="pegawai.peg_ktp != null" :pegawai="pegawai"
                              :dokumenDigital="dokumen_digital" :masterDokumenDigital="getDokumenDigital('KTP')"
                              :canEdit="canEdit('KTP')" @onUploaded="refreshPegawai">
                            </UploadFile>
                          </p>
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="No Kartu Keluarga">
                          {{ pegawai.peg_no_kk || "-" }}
                          <p class="pt-1 mb-0">
                            <UploadFile v-if="pegawai.peg_no_kk != null" :pegawai="pegawai"
                              :dokumenDigital="dokumen_digital" :masterDokumenDigital="
                                getDokumenDigital('Kartu Keluarga')
                              " :canEdit="canEdit('Kartu Keluarga')" @onUploaded="refreshPegawai">
                            </UploadFile>
                          </p>
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="NPWP">
                          {{ pegawai.peg_npwp || "-" }}
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="No. BPJS Kesehatan">
                          {{ pegawai.peg_bpjs || "-" }}
                          <UploadFile v-if="pegawai.peg_bpjs != null" :pegawai="pegawai"
                            :dokumenDigital="dokumen_digital" :masterDokumenDigital="
                              getDokumenDigital('BPJS Kesehatan')
                            " :canEdit="canEdit('BPJS Kesehatan')" @onUploaded="refreshPegawai">
                          </UploadFile>
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="No. BPJS Ketenagakerjaan">
                          {{ pegawai.peg_bpjs_ketenagakerjaan || "-" }}
                          <UploadFile v-if="pegawai.peg_bpjs_ketenagakerjaan != null" :pegawai="pegawai"
                            :dokumenDigital="dokumen_digital" :masterDokumenDigital="
                              getDokumenDigital('BPJS Ketenagakerjaan')
                            " :canEdit="canEdit('BPJS Ketenagakerjaan')" @onUploaded="refreshPegawai">
                          </UploadFile>
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="No Rekening BJB/Insentif">
                          {{ pegawai.peg_nomor_rekening || "-" }}
                          <UploadFile v-if="pegawai.peg_nomor_rekening != null" :pegawai="pegawai"
                            :dokumenDigital="dokumen_digital" :masterDokumenDigital="
                              getDokumenDigital('Foto Buku Rekening Depan')
                            " :canEdit="canEdit('Foto Buku Rekening Depan')" @onUploaded="refreshPegawai">
                          </UploadFile>
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="Lamaran Kerja">
                          <UploadFile :pegawai="pegawai" :dokumenDigital="dokumen_digital" :masterDokumenDigital="
                            getDokumenDigital('Lamaran Kerja')
                          " :canEdit="canEdit('Lamaran Kerja')" @onUploaded="refreshPegawai">
                          </UploadFile>
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="Masa Kerja">
                          {{ pegawai.masaKerjaTahun || 0 }} Tahun
                          {{ pegawai.masaKerjaBulan || 0 }} Bulan
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="Nomor Kontak Darurat">
                          {{
                              pegawai.kontakDarurat
                                ? pegawai.kontakDarurat.no_hp
                                : "-"
                          }}
                        </ProfileBlockItem>
                        <ProfileBlockItem v-if="pegawai.peg_status_kerja == '0'" class="ml-4" label="Status Kerja">
                          {{ pegawai.peg_status_kerja != null ? "Aktif" : "-" }}
                        </ProfileBlockItem>
                        <ProfileBlockItem v-else-if="pegawai.peg_status_kerja == '1'" class="ml-4" label="Status Kerja">
                          {{
                              pegawai.peg_status_kerja != null
                                ? "Tidak Aktif"
                                : "-"
                          }}
                        </ProfileBlockItem>
                      </div>
                    </b-col>
                    <b-col sm="6" class="mt-3 pl-3">
                      <div style="max-width: 25rem">
                        <div class="ml-0 mb-3">
                          <img src="@/assets/images/ruangan.png" width="17" class="img-fluid" />
                          <span class="pl-2"><b>Unit Kerja</b></span>
                        </div>
                        <ProfileBlockItem class="ml-4" label="Unit Kerja">
                          {{
                              pegawai.unit_kerja != null
                                ? pegawai.unit_kerja.unit_kerja_nama
                                : "-"
                          }}
                          <template v-if="
                            pegawai.unit_kerja && pegawai.unit_kerja.parent
                          ">
                            <br />
                            - {{ pegawai.unit_kerja.parent.unit_kerja_nama }}
                            <template v-if="pegawai.unit_kerja.parent.parent">
                              <br />
                              --
                              {{
                                  pegawai.unit_kerja.parent.parent.unit_kerja_nama
                              }}
                              <template v-if="pegawai.unit_kerja.parent.parent.parent">
                                <br />
                                ---
                                {{
                                    pegawai.unit_kerja.parent.parent.parent
                                      .unit_kerja_nama
                                }}
                              </template>
                            </template>
                          </template>
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="Instalasi / Ruangan / Unit / Tim">
                          <template v-if="pegawai.pegawai_ruangan.length">
                            <ul>
                              <li v-for="pegawai_ruangan in pegawai.pegawai_ruangan"
                                :key="pegawai_ruangan.peg_ruangan_id">
                                {{
                                    pegawai_ruangan.ruangan
                                      ? pegawai_ruangan.ruangan.nama_ruangan
                                      : "!! Error - Ruangan Tidak Ditemukan !!"
                                }}
                                <span v-if="pegawai_ruangan.role != 'pegawai'">
                                  | {{ pegawai_ruangan.role }}
                                </span>
                                <template v-if="
                                  pegawai_ruangan.ruangan &&
                                  pegawai_ruangan.ruangan.parent
                                ">
                                  <br />
                                  -
                                  {{
                                      pegawai_ruangan.ruangan.parent.nama_ruangan
                                  }}
                                  <template v-if="pegawai_ruangan.ruangan.parent.parent">
                                    <br />
                                    --
                                    {{
                                        pegawai_ruangan.ruangan.parent.parent
                                          .nama_ruangan
                                    }}
                                    <template v-if="
                                      pegawai_ruangan.ruangan.parent.parent
                                        .parent
                                    ">
                                      <br />
                                      ---
                                      {{
                                          pegawai_ruangan.ruangan.parent.parent
                                            .parent.nama_ruangan
                                      }}
                                    </template>
                                  </template>
                                </template>
                              </li>
                            </ul>
                          </template>
                          <template v-else> - </template>
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="Spesialis" v-if="
                          pegawai.bidang_id == 10 || pegawai.bidang_id == 11
                        ">
                          {{
                              pegawai.spesialis != null
                                ? pegawai.spesialis.spesialis_nama
                                : "-"
                          }}
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="Sub Spesialis" v-if="pegawai.bidang_id == 11">
                          {{
                              pegawai.sub_spesialis != null
                                ? pegawai.sub_spesialis.sub_spesialis_nama
                                : "-"
                          }}
                        </ProfileBlockItem>
                      </div>
                      <div style="max-width: 25rem">
                        <div class="ml-0 mb-3 mt-4 pt-3">
                          <img src="@/assets/images/jabatan.jpg" width="17" class="img-fluid" />
                          <span class="pl-2"><b>Jabatan</b></span>
                        </div>
                        <ProfileBlockItem class="ml-4" label="Jenis Jabatan">
                          {{
                              pegawai.jenis_jabatan != null
                                ? pegawai.jenis_jabatan.jenis_jabatan_nama
                                : "-"
                          }}
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="Bidang">
                          {{
                              pegawai.bidang != null
                                ? pegawai.bidang.bidang_nama
                                : "-"
                          }}
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="Nama Jabatan">
                          {{
                              pegawai.jabatan != null
                                ? pegawai.jabatan.jabatan_nama
                                : "-"
                          }}
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="Jabatan Fungsional">
                          {{
                              pegawai.jabatan != null &&
                                pegawai.jabatan.jabatan_fungsional != null
                                ? pegawai.jabatan.jabatan_fungsional
                                  .jabatan_fungsional_nama
                                : "-"
                          }}
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="Golongan">
                          {{
                              pegawai.golongan != null
                                ? pegawai.golongan.golongan_nama
                                : "0"
                          }}
                          -
                          {{
                              pegawai.golongan != null
                                ? pegawai.golongan.nama_pangkat
                                : "-"
                          }}
                        </ProfileBlockItem>
                        <ProfileBlockItem class="ml-4" label="Kualifikasi Pendidikan Tenaga Kerja">
                          {{
                              pegawai.jenis_tenaga_kerja != null
                                ? pegawai.jenis_tenaga_kerja
                                  .jenis_tenaga_kerja_nama
                                : "-"
                          }}
                        </ProfileBlockItem>
                      </div>
                    </b-col>
                  </b-row>
                </b-card>
                <!-- <b-card style="max-width: 65rem;">
                  <div class="d-flex justify-content-between">
                    <div>
                      <img src="@/assets/images/sertifikasi.png" width="15" class="img-fluid" />
                      <span class="pl-3"><b>Sertifikasi</b></span>
                    </div>
                    <div class="flex-shrink-0">
                      <a href="#" @click.prevent="openModal('editSertifikasi')">
                        <img src="@/assets/images/svg/pencil.svg" alt="">
                      </a>
                      <ModalEditSertifikasi :open="modals.editSertifikasi.isOpen" @close="closeModal('editSertifikasi')"
                        :title="modals.editSertifikasi.title" />
                    </div>
                  </div>
                  <b-row class="pt-3 pl-5">
                    <b-col sm="6" v-for="i in 2" :key="i">
                      <div>
                        <img src="@/assets/images/sertifikasi.png" width="15" class="img-fluid" />
                        <span class="pl-2"><b>Pelatihan</b></span>
                        <div class="mt-3">
                          <div class="font-weight-bold text-lightgreen mb-1">Modul Pelatihan Tekanan Darah</div>
                          <div class="text-muted mb-1">12 Agustus - 15 Agustus 2022</div>
                          <b-button block variant="lightgreen">Lihat Sertifikat</b-button>
                        </div>
                      </div>
                    </b-col>
                  </b-row>
                </b-card> -->
              </b-col>
              <b-col>
                <b-card>
                  <div class="d-flex justify-content-between">
                    <div>
                      <img src="@/assets/images/kanan.png" width="15" class="img-fluid" />
                      <span class="pl-3"><b>Tentang Saya</b></span>
                    </div>
                    <div class="flex-shrink-0">
                      <a href="#" @click.prevent="openModal('editAbout')">
                        <img src="@/assets/images/svg/pencil.svg" alt="" />
                      </a>
                      <ModalEditAbout :open="modals.editAbout.isOpen" @close="closeModal('editAbout')"
                        :pegawai="pegawai" :title="modals.editAbout.title" @onSave="refreshPegawai" />
                    </div>
                  </div>
                  <b-row>
                    <b-col sm="12">
                      <ProfileBlockItem class="ml-4 mt-3">
                        {{ pegawai.tentang_saya || "-" }}
                      </ProfileBlockItem>
                    </b-col>
                  </b-row>
                  <div class="pl-4 pt-2 mt-3">
                    <span>PORTOFOLIO
                      <p class="pt-1">
                        <UploadFile :pegawai="pegawai" :dokumenDigital="dokumen_digital" :masterDokumenDigital="
                          getDokumenDigital('Portofolio')
                        " :canEdit="canEdit('Portofolio')" @onUploaded="refreshPegawai"
                          :state="getErrorState('Portofolio')">
                        </UploadFile>
                      </p>
                    </span>
                  </div>
                </b-card>
                <b-card>
                  <img src="@/assets/images/kanan.png" width="15" class="img-fluid" />
                  <span class="pl-3"><b>SIP</b></span>
                  <div class="pl-4 pt-2 mt-3">
                    <span>
                      <p class="text-blue-500 pt-1">
                        <b>{{
                            pegawai.sipSingle != null
                              ? pegawai.sipSingle.nomor_sip
                              : "-"
                        }}</b>
                      </p>
                      <p v-if="pegawai.sipSingle != null">
                        Berlaku s.d
                        {{
                            pegawai.sipSingle != null
                              ? pegawai.sipSingle.tanggal_kadaluarsa_sip ||
                              formatDate
                              : "-"
                        }}
                      </p>
                      <div class="pr-1" v-if="pegawai.sipSingle != null">
                        <span class="" v-if="
                          pegawai.sipSingle != null
                            ? pegawai.sipSingle.tanggal_kadaluarsa_sip > today
                            : '-'
                        ">
                          <img src="@/assets/images/aktif.png" width="230" class="img-fluid" />
                        </span>
                        <span class="" v-if="
                          pegawai.sipSingle != null
                            ? pegawai.sipSingle.tanggal_kadaluarsa_sip < today
                            : '-'
                        ">
                          <img src="@/assets/images/tidakaktif.png" width="230" class="img-fluid" />
                        </span>
                      </div>
                    </span>
                  </div>
                </b-card>
                <b-card>
                  <img src="@/assets/images/kanan.png" width="15" class="img-fluid" />
                  <span class="pl-3"><b>STR</b></span>
                  <div class="pl-4 pt-2 mt-3">
                    <span>
                      <p class="text-blue-500 pt-1">
                        <b>{{
                            pegawai.strSingle != null
                              ? pegawai.strSingle.nomor_str
                              : "-"
                        }}</b>
                      </p>
                      <p v-if="pegawai.strSingle != null">
                        Berlaku s.d
                        {{
                            pegawai.strSingle != null
                              ? pegawai.strSingle.tanggal_kadaluarsa_str ||
                              formatDate
                              : "-"
                        }}
                      </p>
                      <div class="pr-1" v-if="pegawai.strSingle != null">
                        <span class="" v-if="
                          pegawai.strSingle != null
                            ? pegawai.strSingle.tanggal_kadaluarsa_str > today
                            : '-'
                        ">
                          <img src="@/assets/images/aktif.png" width="230" class="img-fluid" />
                        </span>
                        <span class="" v-if="
                          pegawai.strSingle != null
                            ? pegawai.strSingle.tanggal_kadaluarsa_str < today
                            : '-'
                        ">
                          <img src="@/assets/images/tidakaktif.png" width="230" class="img-fluid" />
                        </span>
                      </div>
                    </span>
                  </div>
                </b-card>
                <b-card>
                  <img src="@/assets/images/kanan.png" width="15" class="img-fluid" />
                  <span class="pl-3"><b>Nama Atasan</b></span>
                  <div class="pl-4 pt-2 mt-3">
                    <span>
                      <p>
                        {{
                            pegawai.pegawai_atasan != null
                              ? pegawai.pegawai_atasan.peg_nama_lengkap
                              : "-"
                        }}
                      </p>
                    </span>
                  </div>
                </b-card>
                <b-card>
                  <img src="@/assets/images/kanan.png" width="15" class="img-fluid" />
                  <span class="pl-3"><b>Riwayat Hidup</b></span>
                  <div class="pl-4 pt-2 mt-3">
                    <span>
                      Template Riwayat Hidup
                      <a :href="pegawai.pathTemplate" target="_blank">
                        <b-icon icon="cloud-download"></b-icon>
                      </a>
                    </span>
                    <div style="margin-bottom: 5%"></div>
                    <span>
                      <UploadFile :pegawai="pegawai" :dokumenDigital="dokumen_digital" :masterDokumenDigital="
                        getDokumenDigital('Daftar Riwayat Hidup')
                      " :canEdit="canEdit('Daftar Riwayat Hidup')" @onUploaded="refreshPegawai">
                      </UploadFile>
                    </span>
                  </div>
                </b-card>
              </b-col>
            </b-row>
          </b-tab>
          <b-tab title="Dokumen/Arsip" :title-link-class="linkClass(1)">
            <b-card>
              <b-row>
                <b-col>
                  <img src="@/assets/images/sertifikasi.png" width="15" class="img-fluid" />
                  <span class="pl-3"><b>Dukumen / Arsip Kepegawaian</b></span>
                </b-col>
              </b-row>
              <b-row class="pt-3">
                <b-col sm="6">
                  <b-card>
                    <img src="@/assets/images/kanan.png" width="15" class="img-fluid" />
                    <span class="pl-3"><b>SIP</b></span>
                    <b-table-simple class="mt-3" responsive striped borderless outlined>
                      <b-tbody>
                        <b-tr v-for="(sip, i) in pegawai.sip" :key="'sip_' + sip.id + i">
                          <b-td>
                            <div class="tex-green-400">
                              <table class="table table-condensed mb-2">
                                <tr>
                                  <th>No SIP:</th>
                                  <td>{{ sip.nomor_sip }}</td>
                                </tr>
                                <tr>
                                  <th>Tanggal Penerbitan:</th>
                                  <td>
                                    {{ sip.tanggal_penerbitan | formatDate }}
                                  </td>
                                </tr>
                                <tr>
                                  <th>Tanggal Kadaluarsa:</th>
                                  <td>
                                    {{
                                        sip.tanggal_kadaluarsa_sip | formatDate
                                    }}
                                  </td>
                                </tr>
                                <tr>
                                  <th>Jenis SIP:</th>
                                  <td>{{ sip.jenis_sip }}</td>
                                </tr>
                                <tr>
                                  <th>File:</th>
                                  <td>
                                    <span v-for="master_sip in master_dokumen_digital.sip"
                                      :key="'master_sip_' + master_sip.id">
                                      <UploadFile :pegawai="pegawai" :dokumenDigital="dokumen_digital" relationTo="sip"
                                        :entityId="sip.sip_id" :masterDokumenDigital="master_sip"
                                        :canEdit="canEdit('sip')" @onUploaded="refreshPegawai">
                                      </UploadFile>
                                    </span>
                                  </td>
                                </tr>
                              </table>
                              <div class="text-right">
                                <b-button size="sm" variant="lightgreen" @click="$refs['modal-sip'].openSipModal(sip)"
                                  v-if="canEdit('sip')">
                                  <b-icon icon="pencil"> </b-icon> Edit
                                </b-button>
                                <b-button size="sm" variant="danger" @click="deleteSip(sip)" v-if="canDelete('sip')">
                                  <b-icon icon="trash"></b-icon> Hapus
                                </b-button>
                              </div>
                            </div>
                          </b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                    <div class="text-right">
                      <b-button variant="outline-success" size="sm" @click="$refs['modal-sip'].openSipModal()"
                        v-if="canAdd('sip')">
                        <b-icon icon="plus"></b-icon> Tambah File
                      </b-button>
                    </div>
                  </b-card>
                </b-col>
              </b-row>
              <b-row class="pt-3">
                <b-col sm="6">
                  <b-card>
                    <img src="@/assets/images/kanan.png" width="15" class="img-fluid" />
                    <span class="pl-3"><b>STR</b></span>
                    <b-table-simple class="mt-3" responsive striped borderless outlined>
                      <b-tbody>
                        <b-tr v-for="(str, i) in pegawai.str" :key="'str_' + str.id + i">
                          <b-td>
                            <div class="tex-green-400">
                              <table class="table table-condensed mb-2">
                                <tr>
                                  <th>No STR:</th>
                                  <td>{{ str.nomor_str }}</td>
                                </tr>
                                <tr>
                                  <th>Tanggal Penerbitan:</th>
                                  <td>
                                    {{ str.tanggal_penerbitan | formatDate }}
                                  </td>
                                </tr>
                                <tr>
                                  <th>Tanggal Kadaluarsa:</th>
                                  <td>
                                    {{
                                        str.tanggal_kadaluarsa_str | formatDate
                                    }}
                                  </td>
                                </tr>
                                <tr>
                                  <th>Jenis STR:</th>
                                  <td>{{ str.jenis_str }}</td>
                                </tr>
                                <tr>
                                  <th>File:</th>
                                  <td>
                                    <span v-for="master_str in master_dokumen_digital.str"
                                      :key="'master_str_' + master_str.id">
                                      <UploadFile :pegawai="pegawai" :dokumenDigital="dokumen_digital" relationTo="str"
                                        :entityId="str.str_id" :masterDokumenDigital="master_str"
                                        :canEdit="canEdit('str')" @onUploaded="refreshPegawai">
                                      </UploadFile>
                                    </span>
                                  </td>
                                </tr>
                              </table>
                              <div class="text-right">
                                <b-button size="sm" variant="lightgreen" @click="$refs['modal-str'].openStrModal(str)"
                                  v-if="canEdit('str')">
                                  <b-icon icon="pencil"> </b-icon> Edit
                                </b-button>
                                <b-button size="sm" variant="danger" @click="deleteStr(str)" v-if="canDelete('str')">
                                  <b-icon icon="trash"></b-icon> Hapus
                                </b-button>
                              </div>
                            </div>
                          </b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                    <div class="text-right">
                      <b-button variant="outline-success" size="sm" @click="$refs['modal-str'].openStrModal()"
                        v-if="canAdd('str')">
                        <b-icon icon="plus"></b-icon> Tambah File
                      </b-button>
                    </div>
                  </b-card>
                </b-col>
              </b-row>
              <b-row class="pt-3">
                <b-col sm="6">
                  <b-card>
                    <img src="@/assets/images/kanan.png" width="15" class="img-fluid" />
                    <span class="pl-3"><b>NIRA</b></span>
                    <b-table-simple class="mt-3" responsive striped borderless outlined>
                      <b-tbody>
                        <b-tr v-for="(nira, i) in pegawai.nira" :key="'nira_' + nira.id + i">
                          <b-td>
                            <div class="tex-green-400">
                              <table class="table table-condensed mb-2">
                                <tr>
                                  <th>No NIRA:</th>
                                  <td>{{ nira.nomor_nira }}</td>
                                </tr>
                                <tr>
                                  <th>Tanggal Penerbitan:</th>
                                  <td>
                                    {{ nira.tanggal_terbit | formatDate }}
                                  </td>
                                </tr>
                                <tr>
                                  <th>Tanggal Terdaftar:</th>
                                  <td>
                                    {{ nira.tanggal_terdaftar | formatDate }}
                                  </td>
                                </tr>
                                <tr>
                                  <th>File:</th>
                                  <td>
                                    <span v-for="master_nira in master_dokumen_digital.nira"
                                      :key="'master_nira_' + master_nira.id">
                                      <UploadFile :pegawai="pegawai" :dokumenDigital="dokumen_digital" relationTo="nira"
                                        :entityId="nira.id" :masterDokumenDigital="master_nira"
                                        :canEdit="canEdit('nira')" @onUploaded="refreshPegawai">
                                      </UploadFile>
                                    </span>
                                  </td>
                                </tr>
                              </table>
                              <div class="text-right">
                                <b-button size="sm" variant="lightgreen" @click="
                                  $refs['modal-nira'].openNiraModal(nira)
                                " v-if="canEdit('nira')">
                                  <b-icon icon="pencil"> </b-icon> Edit
                                </b-button>
                                <b-button size="sm" variant="danger" @click="deleteNira(nira)" v-if="canDelete('nira')">
                                  <b-icon icon="trash"></b-icon> Hapus
                                </b-button>
                              </div>
                            </div>
                          </b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                    <div class="text-right">
                      <b-button variant="outline-success" size="sm" @click="$refs['modal-nira'].openNiraModal()"
                        v-if="canAdd('nira')">
                        <b-icon icon="plus"></b-icon> Tambah File
                      </b-button>
                    </div>
                  </b-card>
                </b-col>
              </b-row>
              <b-row class="pt-3" v-show="$checkPermission('edit-admin')">
                <b-col sm="6">
                  <b-card>
                    <img src="@/assets/images/kanan.png" width="15" class="img-fluid" />
                    <span class="pl-3"><b>HASIL TES TULIS, WAWANCARA, PSIKOTES & KESEHATAN</b></span>
                    <b-table-simple class="mt-3" responsive striped borderless outlined>
                      <b-tbody>
                        <b-tr>
                          <b-td>
                            <div class="tex-green-400">
                              <table class="table table-condensed mb-2">
                                <tr>
                                  <th>Tes Tulis:</th>
                                  <td>
                                    <span>
                                      <UploadFile :pegawai="pegawai" :dokumenDigital="dokumen_digital"
                                        :masterDokumenDigital="
                                          getDokumenDigital('Tes Tulis')
                                        " :canEdit="canEdit('Tes Tulis')" @onUploaded="refreshPegawai">
                                      </UploadFile>
                                    </span>
                                  </td>
                                </tr>
                                <tr>
                                  <th>Tes Wawancara:</th>
                                  <td>
                                    <span>
                                      <UploadFile :pegawai="pegawai" :dokumenDigital="dokumen_digital"
                                        :masterDokumenDigital="
                                          getDokumenDigital('Tes Wawancara')
                                        " :canEdit="canEdit('Tes Wawancara')" @onUploaded="refreshPegawai">
                                      </UploadFile>
                                    </span>
                                  </td>
                                </tr>
                                <tr>
                                  <th>Tes Psikotes:</th>
                                  <td>
                                    <span>
                                      <UploadFile :pegawai="pegawai" :dokumenDigital="dokumen_digital"
                                        :masterDokumenDigital="
                                          getDokumenDigital('Tes Psikotes')
                                        " :canEdit="canEdit('Tes Psikotes')" @onUploaded="refreshPegawai">
                                      </UploadFile>
                                    </span>
                                  </td>
                                </tr>
                                <tr>
                                  <th>Tes Kesehatan:</th>
                                  <td>
                                    <span>
                                      <UploadFile :pegawai="pegawai" :dokumenDigital="dokumen_digital"
                                        :masterDokumenDigital="
                                          getDokumenDigital('Tes Kesehatan')
                                        " :canEdit="canEdit('Tes Kesehatan')" @onUploaded="refreshPegawai">
                                      </UploadFile>
                                    </span>
                                  </td>
                                </tr>
                              </table>
                            </div>
                          </b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
              </b-row>
              <b-row class="pt-3" v-show="$checkPermission('edit-admin')">
                <b-col sm="6">
                  <b-card>
                    <img src="@/assets/images/kanan.png" width="15" class="img-fluid" />
                    <span class="pl-3"><b>SURAT KENAIKAN GOLONGAN</b></span>
                    <b-table-simple class="mt-3" responsive striped borderless outlined>
                      <b-tbody>
                        <b-tr>
                          <b-td>
                            <div class="tex-green-400">
                              <table class="table table-condensed mb-2">
                                <tr>
                                  <th>Surat Kenaikan Golongan:</th>
                                  <td>
                                    <span>
                                      <UploadFile :pegawai="pegawai" :dokumenDigital="dokumen_digital"
                                        :masterDokumenDigital="
                                          getDokumenDigital(
                                            'Surat Kenaikan Golongan'
                                          )
                                        " :canEdit="
  canEdit('Surat Kenaikan Golongan')
" @onUploaded="refreshPegawai">
                                      </UploadFile>
                                    </span>
                                  </td>
                                </tr>
                              </table>
                            </div>
                          </b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
              </b-row>
            </b-card>
          </b-tab>
          <b-tab title="Kinerja" :title-link-class="linkClass(2)">
            <b-card>
              <b-row>
                <b-col>
                  <b-card>
                    <div class="d-flex justify-content-between mb-3">
                      <div class="d-flex align-items-center">
                        <img src="@/assets/images/sertifikasi.png" width="15" class="img-fluid" />
                        <span class="pl-3"><b>Penilaian Kinerja</b></span>
                      </div>
                      <b-button variant="primary" size="md" @click="
                        $refs[
                          'modal-penilaian-kinerja'
                        ].openPenilaianKinerjaModal()
                      " v-if="canAdd('penilaian_kinerja')">
                        <b-icon icon="plus"></b-icon> Tambah
                      </b-button>
                    </div>
                    <b-table-simple responsive striped hover>
                      <b-thead head-variant="dark">
                        <b-tr>
                          <b-th>No</b-th>
                          <b-th>Tahun</b-th>
                          <b-th>Nilai</b-th>
                          <b-th>File</b-th>
                          <b-th>Aksi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(
                            penilaian_kinerja, i
                          ) in pegawai.penilaian_kinerja" :key="'penilaian_kinerja_' + penilaian_kinerja.id">
                          <b-td>{{ i + 1 }}</b-td>
                          <b-td>{{ penilaian_kinerja.tahun }}</b-td>
                          <b-td>{{ penilaian_kinerja.nilai }}</b-td>
                          <b-td>
                            <span v-for="master_penilaian_kinerja in master_dokumen_digital.penilaian_kinerja" :key="
                              'master_penilaian_kinerja_' +
                              master_penilaian_kinerja.id
                            ">
                              <UploadFile :pegawai="pegawai" :dokumenDigital="dokumen_digital"
                                relationTo="penilaian_kinerja" :entityId="penilaian_kinerja.id"
                                :masterDokumenDigital="master_penilaian_kinerja" :canEdit="canEdit('penilaian_kinerja')"
                                @onUploaded="refreshPegawai">
                              </UploadFile>
                            </span>
                          </b-td>
                          <b-td>
                            <b-button size="sm" variant="lightgreen" @click="
                              $refs[
                                'modal-penilaian-kinerja'
                              ].openPenilaianKinerjaModal(penilaian_kinerja)
                            " v-if="canEdit('penilaian_kinerja')">
                              <b-icon icon="pencil"></b-icon>
                            </b-button>
                            <b-button size="sm" variant="danger" @click="deletePenilaianKinerja(penilaian_kinerja)"
                              v-if="canDelete('penilaian_kinerja')">
                              <b-icon icon="trash"></b-icon>
                            </b-button>
                          </b-td>
                        </b-tr>

                        <b-tr v-if="pegawai.penilaian_kinerja.length == 0">
                          <b-td colspan="10">Tidak Ada Data</b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
              </b-row>
            </b-card>
          </b-tab>
          <b-tab title="Uraian Tugas" :title-link-class="linkClass(3)">
            <b-card>
              <b-row>
                <b-col>
                  <b-card>
                    <div class="d-flex justify-content-between mb-3">
                      <div class="d-flex align-items-center">
                        <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
                        <span class="pl-3"><b>Uraian Tugas</b></span>
                      </div>

                      <b-button variant="primary" size="md" @click="
                        $refs['modal-uraian-tugas'].openUraianTugasModal()
                      " v-if="canAdd('uraian_tugas')">
                        <b-icon icon="plus"></b-icon> Tambah
                      </b-button>
                    </div>
                    <b-table-simple responsive striped hover>
                      <b-thead head-variant="dark">
                        <b-tr>
                          <b-th>No</b-th>
                          <b-th>Tugas</b-th>
                          <b-th>File</b-th>
                          <b-th>Aksi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(uraian_tugas, i) in pegawai.uraian_tugas"
                          :key="'uraian_tugas_' + uraian_tugas.id">
                          <b-td>{{ i + 1 }}</b-td>
                          <b-td>{{ uraian_tugas.tugas }}</b-td>
                          <b-td>
                            <span v-for="master_uraian_tugas in master_dokumen_digital.uraian_tugas" :key="
                              'master_uraian_tugas_' + master_uraian_tugas.id
                            ">
                              <UploadFile :pegawai="pegawai" :dokumenDigital="dokumen_digital" relationTo="uraian_tugas"
                                :entityId="uraian_tugas.id" :masterDokumenDigital="master_uraian_tugas"
                                :canEdit="canEdit('uraian_tugas')" @onUploaded="refreshPegawai">
                              </UploadFile>
                            </span>
                          </b-td>
                          <b-td>
                            <b-button size="sm" variant="lightgreen" @click="
                              $refs[
                                'modal-uraian-tugas'
                              ].openUraianTugasModal(uraian_tugas)
                            " v-if="canEdit('uraian_tugas')">
                              <b-icon icon="pencil"></b-icon>
                            </b-button>
                            <b-button size="sm" variant="danger" @click="deleteUraianTugas(uraian_tugas)"
                              v-if="canDelete('uraian_tugas')">
                              <b-icon icon="trash"> </b-icon>
                            </b-button>
                          </b-td>
                        </b-tr>
                        <b-tr v-if="pegawai.uraian_tugas.length == 0">
                          <b-td colspan="10">Tidak Ada Data</b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
              </b-row>
            </b-card>
          </b-tab>
          <b-tab title="Manajemen Kontrak" :title-link-class="linkClass(4)">
            <b-card>
              <b-row>
                <b-col>
                  <b-card>
                    <div class="d-flex justify-content-between mb-3">
                      <div class="d-flex align-items-center">
                        <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
                        <span class="pl-3"><b>Manajemen Kontrak</b></span>
                      </div>

                      <b-button variant="primary" @click="
                        $refs[
                          'modal-manajemen-kontrak'
                        ].openManajemenKontrakModal()
                      " v-if="canAdd('manajemen_kontrak')">
                        <b-icon icon="plus"></b-icon> Tambah
                      </b-button>
                    </div>
                    <b-table-simple responsive striped hover>
                      <b-thead head-variant="dark">
                        <b-tr>
                          <b-th>No</b-th>
                          <b-th>Jenis Kontrak</b-th>
                          <b-th>Tanggal Mulai Kontrak</b-th>
                          <b-th>Tanggal Habis Kontrak</b-th>
                          <b-th>File</b-th>
                          <b-th>Aksi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(
                            manajemen_kontrak, i
                          ) in pegawai.manajemen_kontrak" :key="'manajemen_kontrak_' + manajemen_kontrak.id">
                          <b-td>{{ i + 1 }}</b-td>
                          <b-td>{{ manajemen_kontrak.jenis_kontrak }}</b-td>
                          <b-td>{{ manajemen_kontrak.tanggal_mulai | formatDate }}</b-td>
                          <b-td>{{
                              manajemen_kontrak.tanggal_habis_kontrak | formatDate}}</b-td>
                          <b-td>
                            <span v-for="master_manajemen_kontrak in master_dokumen_digital.kontrak" :key="
                              'master_manajemen_kontrak_' +
                              master_manajemen_kontrak.id
                            ">
                              <UploadFile :pegawai="pegawai" :dokumenDigital="dokumen_digital" relationTo="kontrak"
                                :entityId="manajemen_kontrak.id" :masterDokumenDigital="master_manajemen_kontrak"
                                :canEdit="canEdit('manajemen_kontrak')" @onUploaded="refreshPegawai">
                              </UploadFile>
                            </span>
                          </b-td>
                          <b-td class="text-nowrap">
                            <b-button size="sm" variant="lightgreen" @click="
                              $refs[
                                'modal-manajemen-kontrak'
                              ].openManajemenKontrakModal(manajemen_kontrak)
                            " v-if="canEdit('manajemen_kontrak')">
                              <b-icon icon="pencil"></b-icon>
                            </b-button>
                            <b-button size="sm" variant="danger" @click="deleteManajemenKontrak(manajemen_kontrak)"
                              v-if="canDelete('manajemen_kontrak')">
                              <b-icon icon="trash"> </b-icon>
                            </b-button>
                          </b-td>
                        </b-tr>
                        <b-tr v-if="pegawai.manajemen_kontrak.length == 0">
                          <b-td colspan="10">Tidak Ada Data</b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
              </b-row>
            </b-card>
          </b-tab>
          <b-tab title="Pendidikan dan Pelatihan" :title-link-class="linkClass(5)">
            <b-card>
              <b-row>
                <b-col>
                  <b-card>
                    <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
                    <span class="pl-3"><b>Pendidikan Formal</b></span>
                    <b-table-simple hover striped responsive class="mt-3">
                      <b-thead head-variant="dark">
                        <b-tr>
                          <b-th>No</b-th>
                          <b-th>Tingkat Pendidikan</b-th>
                          <b-th>File</b-th>
                          <b-th>No Ijazah</b-th>
                          <b-th>Tanggal Ijazah</b-th>
                          <b-th>Nama Sekolah</b-th>
                          <b-th>Tanggal Lulus</b-th>
                          <b-th>Pejabat Penandatangan Ijazah</b-th>
                          <b-th>Surat Izin Belajar</b-th>
                          <b-th>Pejabat Penandatangan Surat Izin Belajar</b-th>
                          <b-th>Aksi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(
                            riwayat_pendidikan_tingkat, i
                          ) in pegawai.riwayat_pendidikan_tingkat" :key="
                            'riwayat_pendidikan_tingkat_' +
                            riwayat_pendidikan_tingkat.id +
                            i
                          ">
                          <b-td>{{ i + 1 }}</b-td>
                          <b-td class="d-flex">
                            <a href="#" style="text-decoration: underline !important" @click.prevent="
                              $refs[
                                'modal-riwayat-pendidikan'
                              ].openRiwayatPendidikanModal(
                                riwayat_pendidikan_tingkat.master_pendidikan,
                                riwayat_pendidikan_tingkat
                              )
                            " v-if="
  riwayat_pendidikan_tingkat.id &&
  canEdit('riwayat_pendidikan_tingkat')
">
                              {{
                                  riwayat_pendidikan_tingkat.master_pendidikan
                                    ? riwayat_pendidikan_tingkat.master_pendidikan
                                      .tingkat_pendidikan
                                    : "-"
                              }}
                            </a>
                            <span v-else>{{
                                riwayat_pendidikan_tingkat.master_pendidikan
                                  ? riwayat_pendidikan_tingkat.master_pendidikan
                                    .tingkat_pendidikan
                                  : "-"
                            }}</span>
                            <span class="ml-2" v-if="riwayat_pendidikan_tingkat.id == null">
                              <b-button size="sm" variant="primary" @click="
                                $refs[
                                  'modal-riwayat-pendidikan'
                                ].openRiwayatPendidikanModal(
                                  riwayat_pendidikan_tingkat.master_pendidikan
                                )
                              ">
                                <b-icon icon="plus"></b-icon>
                              </b-button>
                            </span>
                          </b-td>
                          <b-td>
                            <span class="text-nowrap"
                              v-for="master_riwayat_pendidikan in master_dokumen_digital.riwayat_pendidikan" :key="
                                'master_riwayat_pendidikan_' +
                                master_riwayat_pendidikan.id
                              ">
                              <UploadFile v-if="riwayat_pendidikan_tingkat.id" :pegawai="pegawai"
                                :dokumenDigital="dokumen_digital" relationTo="riwayat_pendidikan"
                                :entityId="riwayat_pendidikan_tingkat.id" :masterDokumenDigital="
                                  master_riwayat_pendidikan
                                " :canEdit="canEdit('riwayat_pendidikan_tingkat')" @onUploaded="refreshPegawai">
                              </UploadFile>
                            </span>
                          </b-td>
                          <b-td>{{
                              riwayat_pendidikan_tingkat.no_ijazah
                          }}</b-td>
                          <b-td>{{
                              riwayat_pendidikan_tingkat.tanggal_ijazah
                              | formatDate
                          }}</b-td>
                          <b-td>{{
                              riwayat_pendidikan_tingkat.nama_sekolah
                          }}</b-td>
                          <b-td>{{
                              riwayat_pendidikan_tingkat.tanggal_lulus
                              | formatDate
                          }}</b-td>
                          <b-td>{{
                              riwayat_pendidikan_tingkat.pejabat_penandatangan_ijazah
                          }}</b-td>
                          <b-td>{{
                              riwayat_pendidikan_tingkat.surat_izin_belajar
                          }}</b-td>
                          <b-td>{{
                              riwayat_pendidikan_tingkat.pejabat_penandatangan_izin_belajar
                          }}</b-td>

                          <b-td class="text-nowrap">
                            <template v-if="riwayat_pendidikan_tingkat.id">
                              <b-button size="sm" variant="lightgreen" @click="
                                $refs[
                                  'modal-riwayat-pendidikan'
                                ].openRiwayatPendidikanModal(
                                  riwayat_pendidikan_tingkat.master_pendidikan,
                                  riwayat_pendidikan_tingkat
                                )
                              " v-if="canEdit('riwayat_pendidikan_tingkat')">
                                <b-icon icon="pencil"></b-icon>
                              </b-button>
                              <b-button size="sm" variant="danger" @click="
                                deleteRiwayatPendidikan(
                                  riwayat_pendidikan_tingkat
                                )
                              " v-if="canDelete('riwayat_pendidikan_tingkat')">
                                <b-icon icon="trash"></b-icon>
                              </b-button>
                            </template>
                          </b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
                <b-col>
                  <b-card>
                    <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
                    <span class="pl-3"><b>Pelatihan Dasar</b></span>
                    <b-table-simple hover striped responsive class="mt-3">
                      <b-thead head-variant="dark">
                        <b-tr>
                          <b-th>No</b-th>
                          <b-th>Jenis Pelatihan</b-th>
                          <b-th>File</b-th>
                          <b-th>Nama Pelatihan</b-th>
                          <b-th>Tanggal Pelaksanaan</b-th>
                          <b-th>Lama Pelatihan</b-th>
                          <b-th>Pejabat Penandatangan</b-th>
                          <b-th>Nomor Sertifikat</b-th>
                          <b-th>Aksi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(
                            riwayat_diklat_pelatihan_dasar, i
                          ) in pegawai.riwayat_diklat_pelatihan_dasar" :key="
                            'riwayat_diklat_pelatihan_dasar_' +
                            riwayat_diklat_pelatihan_dasar.id +
                            '-' +
                            i
                          ">
                          <b-td>{{ i + 1 }}</b-td>
                          <b-td class="d-flex">
                            <a href="#" style="text-decoration: underline !important" @click.prevent="
                              $refs[
                                'modal-riwayat-diklat'
                              ].openRiwayatDiklatModal(
                                riwayat_diklat_pelatihan_dasar.master_diklat,
                                riwayat_diklat_pelatihan_dasar
                              )
                            " v-if="
  canEdit('riwayat_diklat_pelatihan_dasar') &&
  riwayat_diklat_pelatihan_dasar.id
">
                              {{
                                  riwayat_diklat_pelatihan_dasar.master_diklat
                                    ? riwayat_diklat_pelatihan_dasar.master_diklat
                                      .jenis_pelatihan
                                    : "-"
                              }}
                            </a>
                            <span v-else>{{
                                riwayat_diklat_pelatihan_dasar.master_diklat
                                  ? riwayat_diklat_pelatihan_dasar.master_diklat
                                    .jenis_pelatihan
                                  : "-"
                            }}</span>
                            <span class="ml-2">
                              <b-button size="sm" variant="primary" @click="
                                $refs[
                                  'modal-riwayat-diklat'
                                ].openRiwayatDiklatModal(
                                  riwayat_diklat_pelatihan_dasar.master_diklat
                                )
                              " v-if="
  canEdit('riwayat_diklat_pelatihan_dasar') &&
  riwayat_diklat_pelatihan_dasar.id == null
">
                                <b-icon icon="plus"></b-icon>
                              </b-button>
                            </span>
                          </b-td>
                          <b-td class="text-nowrap">
                            <span v-for="master_riwayat_diklat_pelatihan_dasar in master_dokumen_digital.riwayat_diklat"
                              :key="
                                'master_riwayat_diklat_pelatihan_dasar_' +
                                master_riwayat_diklat_pelatihan_dasar.id
                              ">
                              <UploadFile v-if="riwayat_diklat_pelatihan_dasar.id" :pegawai="pegawai"
                                :dokumenDigital="dokumen_digital" relationTo="riwayat_diklat"
                                :entityId="riwayat_diklat_pelatihan_dasar.id" :masterDokumenDigital="
                                  master_riwayat_diklat_pelatihan_dasar
                                " :canEdit="
  canEdit('riwayat_diklat_pelatihan_dasar')
" @onUploaded="refreshPegawai">
                              </UploadFile>
                            </span>
                          </b-td>
                          <b-td>{{
                              riwayat_diklat_pelatihan_dasar.nama_pelatihan
                          }}</b-td>
                          <b-td>{{
                              riwayat_diklat_pelatihan_dasar.tanggal_pelaksanaan
                              | formatDate
                          }}</b-td>
                          <b-td>{{
                              riwayat_diklat_pelatihan_dasar.lama_pelatihan
                          }}</b-td>
                          <b-td>{{
                              riwayat_diklat_pelatihan_dasar.pejabat_penandatangan
                          }}</b-td>
                          <b-td>{{
                              riwayat_diklat_pelatihan_dasar.nomor_sertifikat
                          }}</b-td>

                          <b-td class="text-nowrap">
                            <template v-if="riwayat_diklat_pelatihan_dasar.id">
                              <b-button size="sm" variant="lightgreen" @click="
                                $refs[
                                  'modal-riwayat-diklat'
                                ].openRiwayatDiklatModal(
                                  riwayat_diklat_pelatihan_dasar.master_diklat,
                                  riwayat_diklat_pelatihan_dasar
                                )
                              " v-if="canEdit('riwayat_diklat_pelatihan_dasar')">
                                <b-icon icon="pencil"></b-icon>
                              </b-button>
                              <b-button size="sm" variant="danger" @click="
                                deleteRiwayatDiklat(
                                  riwayat_diklat_pelatihan_dasar
                                )
                              " v-if="
  canDelete('riwayat_diklat_pelatihan_dasar')
">
                                <b-icon icon="trash"></b-icon>
                              </b-button>
                            </template>
                          </b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
                <b-col>
                  <b-card>
                    <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
                    <span class="pl-3"><b>Pelatihan Keahlian</b></span>
                    <b-table-simple hover striped responsive class="mt-3">
                      <b-thead head-variant="dark">
                        <b-tr>
                          <b-th>No</b-th>
                          <b-th>Jenis Pelatihan</b-th>
                          <b-th>File</b-th>
                          <b-th>Nama Pelatihan</b-th>
                          <b-th>Tanggal Pelaksanaan</b-th>
                          <b-th>Lama Pelatihan</b-th>
                          <b-th>Pejabat Penandatangan</b-th>
                          <b-th>Nomor Sertifikat</b-th>
                          <b-th>Aksi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(
                            riwayat_diklat_pelatihan_keahlian, i
                          ) in pegawai.riwayat_diklat_pelatihan_keahlian" :key="
                            'riwayat_diklat_pelatihan_keahlian_' +
                            riwayat_diklat_pelatihan_keahlian.id +
                            i
                          ">
                          <b-td>{{ i + 1 }}</b-td>
                          <b-td class="d-flex">
                            <a href="#" style="text-decoration: underline !important" @click.prevent="
                              $refs[
                                'modal-riwayat-diklat'
                              ].openRiwayatDiklatModal(
                                riwayat_diklat_pelatihan_keahlian.master_diklat,
                                riwayat_diklat_pelatihan_keahlian
                              )
                            " v-if="
  canEdit('riwayat_diklat_pelatihan_keahlian') &&
  riwayat_diklat_pelatihan_keahlian.id
">
                              {{
                                  riwayat_diklat_pelatihan_keahlian.master_diklat
                                    ? riwayat_diklat_pelatihan_keahlian
                                      .master_diklat.jenis_pelatihan
                                    : "-"
                              }}
                            </a>
                            <span v-else>{{
                                riwayat_diklat_pelatihan_keahlian.master_diklat
                                  ? riwayat_diklat_pelatihan_keahlian
                                    .master_diklat.jenis_pelatihan
                                  : "-"
                            }}</span>
                            <span class="ml-2">
                              <b-button size="sm" variant="primary" @click="
                                $refs[
                                  'modal-riwayat-diklat'
                                ].openRiwayatDiklatModal(
                                  riwayat_diklat_pelatihan_keahlian.master_diklat
                                )
                              " v-if="
  canEdit(
    'riwayat_diklat_pelatihan_keahlian'
  ) &&
  riwayat_diklat_pelatihan_keahlian.id == null
">
                                <b-icon icon="plus"></b-icon>
                              </b-button>
                            </span>
                          </b-td>
                          <b-td class="text-nowrap">
                            <span
                              v-for="master_riwayat_diklat_pelatihan_keahlian in master_dokumen_digital.riwayat_diklat"
                              :key="
                                'master_riwayat_diklat_pelatihan_keahlian_' +
                                master_riwayat_diklat_pelatihan_keahlian.id
                              ">
                              <UploadFile v-if="riwayat_diklat_pelatihan_keahlian.id" :pegawai="pegawai"
                                :dokumenDigital="dokumen_digital" relationTo="riwayat_diklat"
                                :entityId="riwayat_diklat_pelatihan_keahlian.id" :masterDokumenDigital="
                                  master_riwayat_diklat_pelatihan_keahlian
                                " :canEdit="
  canEdit('riwayat_diklat_pelatihan_keahlian')
" @onUploaded="refreshPegawai">
                              </UploadFile>
                            </span>
                          </b-td>

                          <b-td>{{
                              riwayat_diklat_pelatihan_keahlian.nama_pelatihan
                          }}</b-td>
                          <b-td>{{
                              riwayat_diklat_pelatihan_keahlian.tanggal_pelaksanaan
                              | formatDate
                          }}</b-td>
                          <b-td>{{
                              riwayat_diklat_pelatihan_keahlian.lama_pelatihan
                          }}</b-td>
                          <b-td>{{
                              riwayat_diklat_pelatihan_keahlian.pejabat_penandatangan
                          }}</b-td>
                          <b-td>{{
                              riwayat_diklat_pelatihan_keahlian.nomor_sertifikat
                          }}</b-td>
                          <b-td class="text-nowrap">
                            <template v-if="riwayat_diklat_pelatihan_keahlian.id">
                              <b-button size="sm" variant="lightgreen" @click="
                                $refs[
                                  'modal-riwayat-diklat'
                                ].openRiwayatDiklatModal(
                                  riwayat_diklat_pelatihan_keahlian.master_diklat,
                                  riwayat_diklat_pelatihan_keahlian
                                )
                              " v-if="
  canEdit('riwayat_diklat_pelatihan_keahlian')
">
                                <b-icon icon="pencil"></b-icon>
                              </b-button>
                              <b-button size="sm" variant="danger" @click="
                                deleteRiwayatDiklat(
                                  riwayat_diklat_pelatihan_keahlian
                                )
                              " v-if="
  canDelete('riwayat_diklat_pelatihan_keahlian')
">
                                <b-icon icon="trash"></b-icon>
                              </b-button>
                            </template>
                          </b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
                <b-col>
                  <b-card>
                    <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
                    <span class="pl-3"><b>Pelatihan Lainnya</b></span>
                    <b-table-simple hover striped responsive class="mt-3">
                      <b-thead head-variant="dark">
                        <b-tr>
                          <b-th>No</b-th>
                          <b-th>Jenis Pelatihan</b-th>
                          <b-th>File</b-th>
                          <b-th>Nama Pelatihan</b-th>
                          <b-th>Tanggal Pelaksanaan</b-th>
                          <b-th>Lama Pelatihan</b-th>
                          <b-th>Pejabat Penandatangan</b-th>
                          <b-th>Nomor Sertifikat</b-th>
                          <b-th>Aksi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(
                            riwayat_diklat_pelatihan_lainnya, i
                          ) in pegawai.riwayat_diklat_pelatihan_lainnya" :key="
                            'riwayat_diklat_pelatihan_lainnya_' +
                            riwayat_diklat_pelatihan_lainnya.id +
                            i
                          ">
                          <b-td>{{ i + 1 }}</b-td>
                          <b-td class="d-flex">
                            <a href="#" style="text-decoration: underline !important" @click.prevent="
                              $refs[
                                'modal-riwayat-diklat'
                              ].openRiwayatDiklatModal(
                                riwayat_diklat_pelatihan_lainnya.master_diklat,
                                riwayat_diklat_pelatihan_lainnya
                              )
                            " v-if="
  canEdit('riwayat_diklat_pelatihan_lainnya') &&
  riwayat_diklat_pelatihan_lainnya.id
">
                              {{
                                  riwayat_diklat_pelatihan_lainnya.master_diklat
                                    ? riwayat_diklat_pelatihan_lainnya
                                      .master_diklat.jenis_pelatihan
                                    : "-"
                              }}
                            </a>
                            <span v-else>{{
                                riwayat_diklat_pelatihan_lainnya.master_diklat
                                  ? riwayat_diklat_pelatihan_lainnya.master_diklat
                                    .jenis_pelatihan
                                  : "-"
                            }}</span>
                            <span class="ml-2">
                              <b-button size="sm" variant="primary" @click="
                                $refs[
                                  'modal-riwayat-diklat'
                                ].openRiwayatDiklatModal(
                                  riwayat_diklat_pelatihan_lainnya.master_diklat
                                )
                              " v-if="
  canEdit('riwayat_diklat_pelatihan_lainnya')
">
                                <b-icon icon="plus"></b-icon>
                              </b-button>
                            </span>
                          </b-td>
                          <b-td class="text-nowrap">
                            <span
                              v-for="master_riwayat_diklat_pelatihan_lainnya in master_dokumen_digital.riwayat_diklat_pelatihan_lainnya"
                              :key="
                                'master_riwayat_diklat_pelatihan_lainnya_' +
                                master_riwayat_diklat_pelatihan_lainnya.id
                              ">
                              <UploadFile v-if="riwayat_diklat_pelatihan_lainnya.id" :pegawai="pegawai"
                                :dokumenDigital="dokumen_digital" relationTo="riwayat_diklat_pelatihan_lainnya"
                                :entityId="riwayat_diklat_pelatihan_lainnya.id" :masterDokumenDigital="
                                  master_riwayat_diklat_pelatihan_lainnya
                                " :canEdit="
  canEdit('riwayat_diklat_pelatihan_lainnya')
" @onUploaded="refreshPegawai">
                              </UploadFile>
                            </span>
                          </b-td>
                          <b-td>{{
                              riwayat_diklat_pelatihan_lainnya.nama_pelatihan
                          }}</b-td>
                          <b-td>{{
                              riwayat_diklat_pelatihan_lainnya.tanggal_pelaksanaan
                              | formatDate
                          }}</b-td>
                          <b-td>{{
                              riwayat_diklat_pelatihan_lainnya.lama_pelatihan
                          }}</b-td>
                          <b-td>{{
                              riwayat_diklat_pelatihan_lainnya.pejabat_penandatangan
                          }}</b-td>
                          <b-td>{{
                              riwayat_diklat_pelatihan_lainnya.nomor_sertifikat
                          }}</b-td>

                          <b-td class="text-nowrap">
                            <template v-if="riwayat_diklat_pelatihan_lainnya.id">
                              <b-button size="sm" variant="lightgreen" @click="
                                $refs[
                                  'modal-riwayat-diklat'
                                ].openRiwayatDiklatModal(
                                  riwayat_diklat_pelatihan_lainnya.master_diklat,
                                  riwayat_diklat_pelatihan_lainnya
                                )
                              " v-if="
  canEdit('riwayat_diklat_pelatihan_lainnya')
">
                                <b-icon icon="pencil"></b-icon>
                              </b-button>
                              <b-button size="sm" variant="danger" @click="
                                deleteRiwayatDiklat(
                                  riwayat_diklat_pelatihan_lainnya
                                )
                              " v-if="
  canDelete('riwayat_diklat_pelatihan_lainnya')
">
                                <b-icon icon="trash"></b-icon>
                              </b-button>
                            </template>
                          </b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
              </b-row>
            </b-card>
          </b-tab>
          <b-tab title="Pengalaman Kerja" :title-link-class="linkClass(6)">
            <b-card>
              <b-row>
                <b-col>
                  <b-card>
                    <div class="d-flex justify-content-between mb-3">
                      <div class="d-flex align-items-center">
                        <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
                        <span class="pl-3"><b>Pengalaman Kerja</b></span>
                      </div>

                      <b-button variant="primary" @click="
                        $refs[
                          'modal-pengalaman-kerja'
                        ].openPengalamanKerjaModal()
                      " v-if="canAdd('pengalaman_kerja')">
                        <b-icon icon="plus"></b-icon> Tambah
                      </b-button>
                    </div>
                    <b-table-simple hover striped responsive class="mt-3">
                      <b-thead head-variant="dark">
                        <b-tr>
                          <b-th>No</b-th>
                          <b-th>Tempat Kerja</b-th>
                          <b-th>Jabatan</b-th>
                          <b-th>Paklaring</b-th>
                          <b-th>Tanggal Mulai</b-th>
                          <b-th>Tanggal Selesai</b-th>
                          <b-th>Lama Bekerja</b-th>
                          <b-th>File</b-th>
                          <b-th>Aksi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(
                            pengalaman_kerja, i
                          ) in pegawai.pengalaman_kerja" :key="'pengalaman_kerja_' + pengalaman_kerja.id + i">
                          <b-td>{{ i + 1 }}</b-td>
                          <b-td>{{ pengalaman_kerja.tempat_kerja }}</b-td>
                          <b-td>{{ pengalaman_kerja.jabatan }}</b-td>
                          <b-td>{{ pengalaman_kerja.has_paklaring }}</b-td>
                          <b-td>{{
                              pengalaman_kerja.tanggal_mulai | formatDate
                          }}</b-td>
                          <b-td>{{
                              pengalaman_kerja.tanggal_selesai | formatDate
                          }}</b-td>
                          <b-td>{{ pengalaman_kerja.lama_kerja }}</b-td>
                          <b-td>
                            <span v-for="master_pengalaman_kerja in master_dokumen_digital.pengalaman_kerja" :key="
                              'master_pengalaman_kerja_' +
                              master_pengalaman_kerja.id
                            ">
                              <UploadFile v-if="
                                pengalaman_kerja.id &&
                                pengalaman_kerja.has_paklaring == 'Ada'
                              " :pegawai="pegawai" :dokumenDigital="dokumen_digital" relationTo="pengalaman_kerja"
                                :entityId="pengalaman_kerja.id" :masterDokumenDigital="master_pengalaman_kerja"
                                :canEdit="canEdit('pengalaman_kerja')" @onUploaded="refreshPegawai">
                              </UploadFile>
                            </span>
                          </b-td>
                          <b-td class="text-nowrap">
                            <template v-if="pengalaman_kerja.id">
                              <b-button size="sm" variant="lightgreen" @click="
                                $refs[
                                  'modal-pengalaman-kerja'
                                ].openPengalamanKerjaModal(pengalaman_kerja)
                              " v-if="canEdit('pengalaman_kerja')">
                                <b-icon icon="pencil"></b-icon>
                              </b-button>
                              <b-button size="sm" variant="danger" @click="deletePengalamanKerja(pengalaman_kerja)"
                                v-if="canDelete('pengalaman_kerja')">
                                <b-icon icon="trash"></b-icon>
                              </b-button>
                            </template>
                          </b-td>
                        </b-tr>
                        <b-tr v-if="pegawai.pengalaman_kerja.length == 0">
                          <b-td colspan="10">Tidak Ada Data</b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
              </b-row>
            </b-card>
          </b-tab>
          <b-tab title="Asesmen Kompetensi" :title-link-class="linkClass(7)">
            <b-card>
              <b-row>
                <b-col>
                  <b-card>
                    <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
                    <span class="pl-3"><b>Asesmen Kompetensi Perawat</b></span>
                    <b-table-simple hover striped responsive class="mt-3">
                      <b-thead head-variant="dark">
                        <b-tr>
                          <b-th>No</b-th>
                          <b-th>Jenis Asesmen</b-th>
                          <b-th>Bulan</b-th>
                          <b-th>Tahun</b-th>
                          <b-th>Sudah/Belum</b-th>
                          <b-th>File</b-th>
                          <b-th>Aksi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(
                            riwayat_asesmen_perawat, i
                          ) in pegawai.riwayat_asesmen_perawat" :key="
                            'riwayat_asesmen_perawat_' +
                            riwayat_asesmen_perawat.id +
                            i
                          ">
                          <b-td>{{ i + 1 }}</b-td>
                          <b-td>{{
                              riwayat_asesmen_perawat.master_asesmen_kompetensi
                                ? riwayat_asesmen_perawat
                                  .master_asesmen_kompetensi
                                  .jenis_asesmen_kompetensi
                                : "-"
                          }}
                          </b-td>
                          <b-td>{{ riwayat_asesmen_perawat.bulan }}</b-td>
                          <b-td>{{ riwayat_asesmen_perawat.tahun }}</b-td>
                          <b-td>{{
                              riwayat_asesmen_perawat.id ? "Sudah" : "Belum"
                          }}</b-td>
                          <b-td>
                            <span v-for="master_riwayat_asesmen in master_dokumen_digital.riwayat_asesmen" :key="
                              'master_riwayat_asesmen_' +
                              master_riwayat_asesmen.id
                            ">
                              <UploadFile v-if="riwayat_asesmen_perawat.id" :pegawai="pegawai"
                                :dokumenDigital="dokumen_digital" relationTo="riwayat_asesmen"
                                :entityId="riwayat_asesmen_perawat.id" :masterDokumenDigital="master_riwayat_asesmen"
                                :canEdit="canEdit('riwayat_asesmen')" @onUploaded="refreshPegawai">
                              </UploadFile>
                            </span>
                          </b-td>
                          <b-td class="text-nowrap">
                            <b-button size="sm" variant="primary" @click="
                              $refs[
                                'modal-riwayat-asesmen'
                              ].openRiwayatAsesmenModal(
                                riwayat_asesmen_perawat.master_asesmen_kompetensi
                              )
                            " v-if="
  canEdit('riwayat_asesmen') &&
  riwayat_asesmen_perawat.id == null
">
                              <b-icon icon="plus"></b-icon>
                            </b-button>
                            <template v-if="riwayat_asesmen_perawat.id">
                              <b-button size="sm" variant="lightgreen" @click="
                                $refs[
                                  'modal-riwayat-asesmen'
                                ].openRiwayatAsesmenModal(
                                  riwayat_asesmen_perawat.master_asesmen_kompetensi,
                                  riwayat_asesmen_perawat
                                )
                              " v-if="canEdit('riwayat_asesmen')">
                                <b-icon icon="pencil"></b-icon>
                              </b-button>
                              <b-button size="sm" variant="danger" @click="
                                deleteRiwayatAsesmen(riwayat_asesmen_perawat)
                              " v-if="canDelete('riwayat_asesmen')">
                                <b-icon icon="trash"></b-icon>
                              </b-button>
                            </template>
                          </b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
                <b-col>
                  <b-card>
                    <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
                    <span class="pl-3"><b>Asesmen Kompetensi Tenaga Kesehatan Lainnya</b></span>
                    <b-table-simple hover striped responsive class="mt-3">
                      <b-thead head-variant="dark">
                        <b-tr>
                          <b-th>No</b-th>
                          <b-th>Jenis Asesmen</b-th>
                          <b-th>Bulan</b-th>
                          <b-th>Tahun</b-th>
                          <b-th>Sudah/Belum</b-th>
                          <b-th>File</b-th>
                          <b-th>Aksi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(
                            riwayat_asesmen_nakes, i
                          ) in pegawai.riwayat_asesmen_nakes" :key="
                            'riwayat_asesmen_nakes_' +
                            riwayat_asesmen_nakes.id +
                            i
                          ">
                          <b-td>{{ i + 1 }}</b-td>
                          <b-td>{{
                              riwayat_asesmen_nakes.master_asesmen_kompetensi
                                ? riwayat_asesmen_nakes
                                  .master_asesmen_kompetensi
                                  .jenis_asesmen_kompetensi
                                : "-"
                          }}
                          </b-td>
                          <b-td>{{ riwayat_asesmen_nakes.bulan }}</b-td>
                          <b-td>{{ riwayat_asesmen_nakes.tahun }}</b-td>
                          <b-td>{{
                              riwayat_asesmen_nakes.id ? "Sudah" : "Belum"
                          }}</b-td>
                          <b-td>
                            <span v-for="master_riwayat_asesmen in master_dokumen_digital.riwayat_asesmen" :key="
                              'master_riwayat_asesmen_' +
                              master_riwayat_asesmen.id
                            ">
                              <UploadFile v-if="riwayat_asesmen_nakes.id" :pegawai="pegawai"
                                :dokumenDigital="dokumen_digital" relationTo="riwayat_asesmen"
                                :entityId="riwayat_asesmen_nakes.id" :masterDokumenDigital="master_riwayat_asesmen"
                                :canEdit="canEdit('riwayat_asesmen_nakes')" @onUploaded="refreshPegawai">
                              </UploadFile>
                            </span>
                          </b-td>
                          <b-td class="text-nowrap">
                            <b-button size="sm" variant="primary" @click="
                              $refs[
                                'modal-riwayat-asesmen'
                              ].openRiwayatAsesmenModal(
                                riwayat_asesmen_nakes.master_asesmen_kompetensi
                              )
                            " v-if="
  canEdit('riwayat_asesmen_nakes') &&
  riwayat_asesmen_nakes.id == null
">
                              <b-icon icon="plus"></b-icon>
                            </b-button>
                            <template v-if="riwayat_asesmen_nakes.id">
                              <b-button size="sm" variant="lightgreen" @click="
                                $refs[
                                  'modal-riwayat-asesmen'
                                ].openRiwayatAsesmenModal(
                                  riwayat_asesmen_nakes.master_asesmen_kompetensi,
                                  riwayat_asesmen_nakes
                                )
                              " v-if="canEdit('riwayat_asesmen_nakes')">
                                <b-icon icon="pencil"></b-icon>
                              </b-button>
                              <b-button size="sm" variant="danger" @click="
                                deleteRiwayatAsesmen(riwayat_asesmen_nakes)
                              " v-if="canDelete('riwayat_asesmen_nakes')">
                                <b-icon icon="trash"></b-icon>
                              </b-button>
                            </template>
                          </b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
              </b-row>
            </b-card>
          </b-tab>
          <b-tab title="Data Hubungan Kerabat" :title-link-class="linkClass(8)">
            <b-card>
              <b-row>
                <b-col>
                  <b-card>
                    <div class="d-flex justify-content-between mb-3">
                      <div class="d-flex align-items-center">
                        <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
                        <span class="pl-3"><b>Data Hubungan Kerabat</b></span>
                      </div>
                      <b-button variant="primary" size="md" @click="
                        $refs[
                          'modal-data-hubungan-kerabat'
                        ].openDataHubunganKerabatModal(data_hubungan_kerabat)
                      " v-if="canAdd('data_hubungan_kerabat')">
                        <b-icon icon="plus"></b-icon> Tambah
                      </b-button>
                    </div>
                    <b-table-simple hover striped responsive class="mt-3">
                      <b-thead head-variant="dark">
                        <b-tr>
                          <b-th>No</b-th>
                          <b-th>Nama</b-th>
                          <b-th>Hubungan Kerabat</b-th>
                          <b-th>Unit Kerja</b-th>
                          <b-th>Aksi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(
                            data_hubungan_kerabat, i
                          ) in pegawai.data_hubungan_kerabat" :key="
                            'data_hubungan_kerabat_' +
                            data_hubungan_kerabat.id +
                            i
                          ">
                          <b-td>{{ i + 1 }}</b-td>
                          <b-td>{{ data_hubungan_kerabat.nama }}</b-td>
                          <b-td>{{
                              data_hubungan_kerabat.hubungan_kerabat
                          }}</b-td>
                          <b-td>{{ data_hubungan_kerabat.unit_kerja }}</b-td>
                          <b-td>
                            <template v-if="data_hubungan_kerabat.id">
                              <b-button size="sm" variant="lightgreen" @click="
                                $refs[
                                  'modal-data-hubungan-kerabat'
                                ].openDataHubunganKerabatModal(
                                  data_hubungan_kerabat
                                )
                              " v-if="canEdit('data_hubungan_kerabat')">
                                <b-icon icon="pencil"></b-icon>
                              </b-button>
                              <b-button size="sm" variant="danger" @click="
                                deleteDataHubunganKerabat(
                                  data_hubungan_kerabat
                                )
                              " v-if="canDelete('data_hubungan_kerabat')">
                                <b-icon icon="trash"></b-icon>
                              </b-button>
                            </template>
                          </b-td>
                        </b-tr>
                        <b-tr v-if="pegawai.data_hubungan_kerabat.length == 0">
                          <b-td colspan="10">Tidak Ada Data</b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
              </b-row>
            </b-card>
          </b-tab>
          <b-tab title="Data Kontak Darurat" :title-link-class="linkClass(9)">
            <b-card>
              <b-row>
                <b-col>
                  <b-card>
                    <div class="d-flex justify-content-between mb-3">
                      <div class="d-flex align-items-center">
                        <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
                        <span class="pl-3"><b>Data Kontak Darurat</b></span>
                      </div>
                      <b-button variant="primary" size="md" @click="
                        $refs[
                          'modal-data-kontak-darurat'
                        ].openDataKontakDaruratModal(data_kontak_darurat)
                      " v-if="canAdd('data_kontak_darurat')">
                        <b-icon icon="plus"></b-icon> Tambah
                      </b-button>
                    </div>
                    <b-table-simple hover striped responsive class="mt-3">
                      <b-thead head-variant="dark">
                        <b-tr>
                          <b-th>No</b-th>
                          <b-th>Nama</b-th>
                          <b-th>Hubungan Kerabat</b-th>
                          <b-th>No Handphone</b-th>
                          <b-th>Alamat</b-th>
                          <b-th>Aksi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(
                            data_kontak_darurat, i
                          ) in pegawai.data_kontak_darurat" :key="
                            'data_kontak_darurat_' + data_kontak_darurat.id + i
                          ">
                          <b-td>{{ i + 1 }}</b-td>
                          <b-td>{{ data_kontak_darurat.nama }}</b-td>
                          <b-td>{{
                              data_kontak_darurat.hubungan_kerabat
                          }}</b-td>
                          <b-td>{{ data_kontak_darurat.no_hp }}</b-td>
                          <b-td>{{ data_kontak_darurat.alamat }}</b-td>
                          <b-td>
                            <template v-if="data_kontak_darurat.id">
                              <b-button size="sm" variant="lightgreen" @click="
                                $refs[
                                  'modal-data-kontak-darurat'
                                ].openDataKontakDaruratModal(
                                  data_kontak_darurat
                                )
                              " v-if="canEdit('data_kontak_darurat')">
                                <b-icon icon="pencil"></b-icon>
                              </b-button>
                              <b-button size="sm" variant="danger" @click="
                                deleteDataKontakDarurat(data_kontak_darurat)
                              " v-if="canDelete('data_kontak_darurat')">
                                <b-icon icon="trash"></b-icon>
                              </b-button>
                            </template>
                          </b-td>
                        </b-tr>
                        <b-tr v-if="pegawai.data_kontak_darurat.length == 0">
                          <b-td colspan="10">Tidak Ada Data</b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
              </b-row>
            </b-card>
          </b-tab>
          <b-tab title="Riwayat Kesehatan" :title-link-class="linkClass(10)">
            <b-card>
              <b-row>
                <b-col cols="6">
                  <b-card>
                    <div class="d-flex justify-content-between mb-3">
                      <div class="d-flex align-items-center">
                        <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
                        <span class="pl-3"><b>Status Vaksin</b></span>
                      </div>
                    </div>
                    <b-table-simple hover striped responsive class="mt-3">
                      <b-thead head-variant="dark">
                        <b-tr>
                          <b-th>No</b-th>
                          <b-th>Status</b-th>
                          <b-th>Tanggal</b-th>
                          <b-th>File</b-th>
                          <b-th>Aksi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(
                            riwayat_kesehatan_vaksin, i
                          ) in pegawai.riwayat_kesehatan_vaksin" :key="
                            'riwayat_kesehatan_vaksin_' +
                            riwayat_kesehatan_vaksin.id +
                            i
                          ">
                          <b-td>{{ i + 1 }}</b-td>
                          <b-td>{{
                              riwayat_kesehatan_vaksin.master_mcu
                                ? riwayat_kesehatan_vaksin.master_mcu
                                  .nama_penyakit
                                : "-"
                          }}</b-td>
                          <b-td>{{
                              riwayat_kesehatan_vaksin.tanggal_vaksin | formatDate
                          }}</b-td>
                          <b-td>
                            <span
                              v-for="master_riwayat_kesehatan_vaksin in master_dokumen_digital.riwayat_kesehatan_vaksin"
                              :key="
                                'master_riwayat_kesehatan_vaksin_' +
                                master_riwayat_kesehatan_vaksin.id
                              ">
                              <UploadFile v-if="riwayat_kesehatan_vaksin.id" :pegawai="pegawai"
                                :dokumenDigital="dokumen_digital" relationTo="riwayat_kesehatan_vaksin"
                                :entityId="riwayat_kesehatan_vaksin.id" :masterDokumenDigital="
                                  master_riwayat_kesehatan_vaksin
                                " :canEdit="canEdit('riwayat_kesehatan_vaksin')" @onUploaded="refreshPegawai">
                              </UploadFile>
                            </span>
                          </b-td>
                          <b-td class="text-nowrap">
                            <b-button size="sm" variant="primary" @click="
                              $refs[
                                'modal-riwayat-kesehatan-vaksin'
                              ].openRiwayatKesehatanVaksinModal(
                                riwayat_kesehatan_vaksin.master_mcu
                              )
                            " v-if="
  canEdit('riwayat_kesehatan_vaksin') &&
  riwayat_kesehatan_vaksin.id == null
">
                              <b-icon icon="plus"></b-icon>
                            </b-button>
                            <template v-if="riwayat_kesehatan_vaksin.id">
                              <b-button size="sm" variant="lightgreen" @click="
                                $refs[
                                  'modal-riwayat-kesehatan-vaksin'
                                ].openRiwayatKesehatanVaksinModal(
                                  riwayat_kesehatan_vaksin.master_mcu,
                                  riwayat_kesehatan_vaksin
                                )
                              " v-if="canEdit('riwayat_kesehatan_vaksin')">
                                <b-icon icon="pencil"></b-icon>
                              </b-button>
                              <b-button size="sm" variant="danger" @click="
                                deleteRiwayatKesehatanVaksin(
                                  riwayat_kesehatan_vaksin,
                                  riwayat_kesehatan_vaksin.master_mcu
                                )
                              " v-if="canDelete('riwayat_kesehatan_vaksin')">
                                <b-icon icon="trash"></b-icon>
                              </b-button>
                            </template>
                          </b-td>
                        </b-tr>
                        <b-tr v-if="pegawai.riwayat_kesehatan_vaksin.length == 0">
                          <b-td colspan="10">Tidak Ada Data</b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
                <b-col cols="6">
                  <b-card>
                    <div class="d-flex justify-content-between mb-3">
                      <div class="d-flex align-items-center">
                        <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
                        <span class="pl-3"><b>Keadaan Saat Ini</b></span>
                      </div>
                    </div>
                    <b-table-simple hover striped responsive class="mt-3">
                      <b-thead head-variant="dark">
                        <b-tr>
                          <b-th>No</b-th>
                          <b-th>Keadaan</b-th>
                          <b-th>Status</b-th>
                          <b-th>Aksi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(
                            riwayat_kesehatan_keadaan, i
                          ) in pegawai.riwayat_kesehatan_keadaan" :key="
                            'riwayat_kesehatan_keadaan_' +
                            riwayat_kesehatan_keadaan.id +
                            i
                          ">
                          <b-td>{{ i + 1 }}</b-td>
                          <b-td>{{
                              riwayat_kesehatan_keadaan.master_mcu
                                ? riwayat_kesehatan_keadaan.master_mcu
                                  .nama_penyakit
                                : "-"
                          }}</b-td>
                          <b-td v-if="riwayat_kesehatan_keadaan.status == 'Y'">
                            <b-icon icon="check-lg"></b-icon>
                          </b-td>
                          <b-td v-else-if="riwayat_kesehatan_keadaan.status == 'T'">
                            <b-icon icon="x-lg"></b-icon>
                          </b-td>
                          <b-td v-else>-</b-td>
                          <b-td class="text-nowrap">
                            <b-button size="sm" variant="primary" @click="
                              $refs[
                                'modal-riwayat-kesehatan'
                              ].openRiwayatKesehatanModal(
                                riwayat_kesehatan_keadaan.master_mcu
                              )
                            " v-if="
  canEdit('riwayat_kesehatan_keadaan') &&
  riwayat_kesehatan_keadaan.id == null
">
                              <b-icon icon="plus"></b-icon>
                            </b-button>
                            <template v-if="riwayat_kesehatan_keadaan.id">
                              <b-button size="sm" variant="lightgreen" @click="
                                $refs[
                                  'modal-riwayat-kesehatan'
                                ].openRiwayatKesehatanModal(
                                  riwayat_kesehatan_keadaan.master_mcu,
                                  riwayat_kesehatan_keadaan
                                )
                              " v-if="canEdit('riwayat_kesehatan_keadaan')">
                                <b-icon icon="pencil"></b-icon>
                              </b-button>
                              <b-button size="sm" variant="danger" @click="
                                deleteRiwayatKesehatan(
                                  riwayat_kesehatan_keadaan,
                                  riwayat_kesehatan_keadaan.master_mcu
                                )
                              " v-if="canDelete('riwayat_kesehatan_keadaan')">
                                <b-icon icon="trash"></b-icon>
                              </b-button>
                            </template>
                          </b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
              </b-row>
              <hr />
              <b-row class="mt-3">
                <b-col cols="6">
                  <b-card>
                    <div class="d-flex justify-content-between mb-3">
                      <div class="d-flex align-items-center">
                        <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
                        <span class="pl-3"><b>Riwayat Kesehatan</b></span>
                      </div>
                      <b-button size="sm" variant="primary" @click="
                        $refs['modal-master-mcu'].openMasterMcuModal(
                          master_mcu
                        )
                      " v-if="canEdit('master_mcu')">
                        <b-icon icon="plus"></b-icon>Tambah
                      </b-button>
                    </div>
                    <b-table-simple hover striped responsive class="mt-3">
                      <b-thead head-variant="dark">
                        <b-tr>
                          <b-th>No</b-th>
                          <b-th>Penyakit</b-th>
                          <b-th>Status</b-th>
                          <b-th>Keterangan</b-th>
                          <b-th>Aksi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(
                            riwayat_kesehatan_mcu, i
                          ) in pegawai.riwayat_kesehatan_mcu" :key="
                            'riwayat_kesehatan_mcu_' +
                            riwayat_kesehatan_mcu.id +
                            i
                          ">
                          <b-td>{{ i + 1 }}</b-td>
                          <b-td>{{
                              riwayat_kesehatan_mcu.master_mcu
                                ? riwayat_kesehatan_mcu.master_mcu.nama_penyakit
                                : "-"
                          }}</b-td>
                          <b-td v-if="riwayat_kesehatan_mcu.status == 'Y'">
                            <b-icon icon="check-lg"></b-icon>
                          </b-td>
                          <b-td v-else>-</b-td>
                          <b-td>{{ riwayat_kesehatan_mcu.keterangan }}</b-td>
                          <b-td class="text-nowrap">
                            <b-button size="sm" variant="primary" @click="
                              $refs[
                                'modal-riwayat-kesehatan'
                              ].openRiwayatKesehatanModal(
                                riwayat_kesehatan_mcu.master_mcu
                              )
                            " v-if="
  canEdit('riwayat_kesehatan_mcu') &&
  riwayat_kesehatan_mcu.id == null
">
                              <b-icon icon="plus"></b-icon>
                            </b-button>
                            <template v-if="riwayat_kesehatan_mcu.id">
                              <b-button size="sm" variant="lightgreen" @click="
                                $refs[
                                  'modal-riwayat-kesehatan'
                                ].openRiwayatKesehatanModal(
                                  riwayat_kesehatan_mcu.master_mcu,
                                  riwayat_kesehatan_mcu
                                )
                              " v-if="canEdit('riwayat_kesehatan_mcu')">
                                <b-icon icon="pencil"></b-icon>
                              </b-button>
                              <b-button size="sm" variant="danger" @click="
                                deleteRiwayatKesehatan(
                                  riwayat_kesehatan_mcu,
                                  riwayat_kesehatan_mcu.master_mcu
                                )
                              " v-if="canDelete('riwayat_kesehatan_mcu')">
                                <b-icon icon="trash"></b-icon>
                              </b-button>
                            </template>
                          </b-td>
                        </b-tr>
                        <b-tr v-if="pegawai.riwayat_kesehatan_mcu.length == 0">
                          <b-td colspan="10">Tidak Ada Data</b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
                <b-col cols="6">
                  <b-card>
                    <div class="d-flex justify-content-between mb-3">
                      <div class="d-flex align-items-center">
                        <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
                        <span class="pl-3"><b>Riwayat MCU</b></span>
                      </div>

                      <b-button variant="primary" size="md" @click="
                        $refs['modal-riwayat-mcu'].openRiwayatMcuModal()
                      " v-if="canAdd('uraian_tugas')">
                        <b-icon icon="plus"></b-icon> Tambah
                      </b-button>
                    </div>
                    <b-table-simple responsive striped hover>
                      <b-thead head-variant="dark">
                        <b-tr>
                          <b-th>No</b-th>
                          <b-th>Tahun</b-th>
                          <b-th>Jenis Pemerikasaan</b-th>
                          <b-th>File</b-th>
                          <b-th>Aksi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(riwayat_mcu, i) in pegawai.riwayat_mcu" :key="'riwayat_mcu_' + riwayat_mcu.id">
                          <b-td>{{ i + 1 }}</b-td>
                          <b-td>{{ riwayat_mcu.tahun_mcu }}</b-td>
                          <b-td>{{ riwayat_mcu.jenis_pemeriksaan }}</b-td>
                          <b-td>
                            <span v-for="master_riwayat_mcu in master_dokumen_digital.riwayat_mcu" :key="
                              'master_riwayat_mcu_' + master_riwayat_mcu.id
                            ">
                              <UploadFile :pegawai="pegawai" :dokumenDigital="dokumen_digital" relationTo="riwayat_mcu"
                                :entityId="riwayat_mcu.id" :masterDokumenDigital="master_riwayat_mcu"
                                :canEdit="canEdit('riwayat_mcu')" @onUploaded="refreshPegawai">
                              </UploadFile>
                            </span>
                          </b-td>
                          <b-td>
                            <b-button size="sm" variant="lightgreen" @click="
                              $refs['modal-riwayat-mcu'].openRiwayatMcuModal(
                                riwayat_mcu
                              )
                            " v-if="canEdit('riwayat_mcu')">
                              <b-icon icon="pencil"></b-icon>
                            </b-button>
                            <b-button size="sm" variant="danger" @click="deleteRiwayatMcu(riwayat_mcu)"
                              v-if="canDelete('riwayat_mcu')">
                              <b-icon icon="trash"> </b-icon>
                            </b-button>
                          </b-td>
                        </b-tr>
                        <b-tr v-if="pegawai.riwayat_mcu.length == 0">
                          <b-td colspan="5" class="text-center">Tidak Ada Data</b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
              </b-row>
            </b-card>
          </b-tab>
          <b-tab title="Riwayat Pengangkatan" :title-link-class="linkClass(11)">
            <b-card>
              <b-row>
                <b-col>
                  <b-card>
                    <div class="d-flex justify-content-between mb-3">
                      <div class="d-flex align-items-center">
                        <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
                        <span class="pl-3"><b>Riwayat Pengangkatan</b></span>
                      </div>
                      <b-button variant="primary" size="md" @click="
                        $refs[
                          'modal-riwayat-pengangkatan'
                        ].openRiwayatPengangkatanModal(riwayat_pengangkatan)
                      " v-if="canAdd('riwayat_pengangkatan')">
                        <b-icon icon="plus"></b-icon> Tambah
                      </b-button>
                    </div>
                    <b-table-simple hover striped responsive class="mt-3">
                      <b-thead head-variant="dark">
                        <b-tr>
                          <b-th>No</b-th>
                          <b-th>Nomor SK</b-th>
                          <b-th>Tanggal SK</b-th>
                          <b-th>Pendidikan</b-th>
                          <b-th>Nama Jabatan</b-th>
                          <b-th>Instalasi</b-th>
                          <b-th>Pejabat Penandatangan</b-th>
                          <b-th>File</b-th>
                          <b-th>Aksi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(
                            riwayat_pengangkatan, i
                          ) in pegawai.riwayat_pengangkatan" :key="
                            'riwayat_pengangkatan_' +
                            riwayat_pengangkatan.id +
                            i
                          ">
                          <b-td>{{ i + 1 }}</b-td>
                          <b-td>{{ riwayat_pengangkatan.nomor_sk }}</b-td>
                          <b-td>{{
                              riwayat_pengangkatan.tanggal_sk | formatDate
                          }}</b-td>
                          <b-td>{{ riwayat_pengangkatan.pendidikan }}</b-td>
                          <b-td>{{ riwayat_pengangkatan.nama_jabatan }}</b-td>
                          <b-td>{{ riwayat_pengangkatan.instansi }}</b-td>
                          <b-td>{{
                              riwayat_pengangkatan.pejabat_penandatangan
                          }}</b-td>
                          <b-td>
                            <span v-for="master_riwayat_pengangkatan in master_dokumen_digital.riwayat_pengangkatan"
                              :key="
                                'master_riwayat_pengangkatan_' +
                                master_riwayat_pengangkatan.id
                              ">
                              <UploadFile :pegawai="pegawai" :dokumenDigital="dokumen_digital"
                                relationTo="riwayat_pengangkatan" :entityId="riwayat_pengangkatan.id"
                                :masterDokumenDigital="
                                  master_riwayat_pengangkatan
                                " :canEdit="canEdit('riwayat_pengangkatan')" @onUploaded="refreshPegawai">
                              </UploadFile>
                            </span>
                          </b-td>
                          <b-td class="text-nowrap">
                            <template v-if="riwayat_pengangkatan.id">
                              <b-button size="sm" variant="lightgreen" @click="
                                $refs[
                                  'modal-riwayat-pengangkatan'
                                ].openRiwayatPengangkatanModal(
                                  riwayat_pengangkatan
                                )
                              " v-if="canEdit('riwayat_pengangkatan')">
                                <b-icon icon="pencil"></b-icon>
                              </b-button>
                              <b-button size="sm" variant="danger" @click="
                                deleteRiwayatPengangkatan(
                                  riwayat_pengangkatan
                                )
                              " v-if="canDelete('riwayat_pengangkatan')">
                                <b-icon icon="trash"></b-icon>
                              </b-button>
                            </template>
                          </b-td>
                        </b-tr>

                        <b-tr v-if="pegawai.riwayat_pengangkatan.length == 0">
                          <b-td colspan="10">Tidak Ada Data</b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
              </b-row>
            </b-card>
          </b-tab>
          <b-tab title="Riwayat Penempatan" :title-link-class="linkClass(12)">
            <b-card>
              <b-row>
                <b-col>
                  <b-card>
                    <div class="d-flex justify-content-between mb-3">
                      <div class="d-flex align-items-center">
                        <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
                        <span class="pl-3"><b>Riwayat Penempatan</b></span>
                      </div>
                      <b-button variant="primary" size="md" @click="
                        $refs[
                          'modal-riwayat-penempatan'
                        ].openRiwayatPenempatanModal(riwayat_penempatan)
                      " v-if="canAdd('riwayat_penempatan')">
                        <b-icon icon="plus"></b-icon> Tambah
                      </b-button>
                    </div>
                    <b-table-simple hover striped responsive class="mt-3">
                      <b-thead head-variant="dark">
                        <b-tr>
                          <b-th>No</b-th>
                          <b-th>Jabatan</b-th>
                          <b-th>Unit Kerja</b-th>
                          <b-th>Instalasi</b-th>
                          <b-th>Tanggal Penempatan</b-th>
                          <b-th>File</b-th>
                          <b-th>Aksi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(
                            riwayat_penempatan, i
                          ) in pegawai.riwayat_penempatan" :key="'riwayat_penempatan_' + riwayat_penempatan.id">
                          <b-td>{{ i + 1 }}</b-td>
                          <b-td>{{ riwayat_penempatan.jabatan }}</b-td>
                          <b-td>{{ riwayat_penempatan.unit_kerja }}</b-td>
                          <b-td>{{ riwayat_penempatan.instansi }}</b-td>
                          <b-td>{{
                              riwayat_penempatan.tanggal_penempatan | formatDate
                          }}</b-td>
                          <b-td>
                            <span v-for="master_riwayat_penempatan in master_dokumen_digital.riwayat_penempatan" :key="
                              'master_riwayat_penempatan_' +
                              master_riwayat_penempatan.id
                            ">
                              <UploadFile :pegawai="pegawai" :dokumenDigital="dokumen_digital"
                                relationTo="riwayat_penempatan" :entityId="riwayat_penempatan.id" :masterDokumenDigital="
                                  master_riwayat_penempatan
                                " :canEdit="canEdit('riwayat_penempatan')" @onUploaded="refreshPegawai">
                              </UploadFile>
                            </span>
                          </b-td>
                          <b-td>
                            <template v-if="riwayat_penempatan.id">
                              <b-button size="sm" variant="lightgreen" @click="
                                $refs[
                                  'modal-riwayat-penempatan'
                                ].openRiwayatPenempatanModal(
                                  riwayat_penempatan
                                )
                              " v-if="canEdit('riwayat_penempatan')">
                                <b-icon icon="pencil"></b-icon>
                              </b-button>
                              <b-button size="sm" variant="danger" @click="
                                deleteRiwayatPenempatan(riwayat_penempatan)
                              " v-if="canDelete('riwayat_penempatan')">
                                <b-icon icon="trash"></b-icon>
                              </b-button>
                            </template>
                          </b-td>
                        </b-tr>

                        <b-tr v-if="pegawai.riwayat_penempatan.length == 0">
                          <b-td colspan="10">Tidak Ada Data</b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
                <b-col>
                  <b-card>
                    <div class="d-flex justify-content-between mb-3">
                      <div class="d-flex align-items-center">
                        <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
                        <span class="pl-3"><b>Riwayat Tugas Tambahan</b></span>
                      </div>
                      <b-button variant="primary" size="md" @click="
                        $refs[
                          'modal-riwayat-tugas-tambahan'
                        ].openRiwayatTugasTambahanModal(
                          riwayat_tugas_tambahan
                        )
                      " v-if="canAdd('riwayat_tugas_tambahan')">
                        <b-icon icon="plus"></b-icon> Tambah
                      </b-button>
                    </div>
                    <b-table-simple hover striped responsive class="mt-3">
                      <b-thead head-variant="dark">
                        <b-tr>
                          <b-th>No</b-th>
                          <b-th>Tugas Tambahan</b-th>
                          <b-th>Tanggal Mulai</b-th>
                          <b-th>Tanggal Selesai</b-th>
                          <b-th>Unit Kerja</b-th>
                          <b-th>Jabatan</b-th>
                          <b-th>File</b-th>
                          <b-th>Aksi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(
                            riwayat_tugas_tambahan, i
                          ) in pegawai.riwayat_tugas_tambahan" :key="
                            'riwayat_tugas_tambahan_' +
                            riwayat_tugas_tambahan.id
                          ">
                          <b-td>{{ i + 1 }}</b-td>
                          <b-td>{{
                              riwayat_tugas_tambahan.tugas_tambahan
                          }}</b-td>
                          <b-td>{{
                              riwayat_tugas_tambahan.tanggal_mulai | formatDate
                          }}</b-td>
                          <b-td>{{
                              riwayat_tugas_tambahan.tanggal_selesai | formatDate
                          }}</b-td>
                          <b-td>{{ riwayat_tugas_tambahan.unit_kerja }}</b-td>
                          <b-td>{{ riwayat_tugas_tambahan.jabatan }}</b-td>
                          <b-td>
                            <span v-for="master_riwayat_tugas_tambahan in master_dokumen_digital.riwayat_tugas_tambahan"
                              :key="
                                'master_riwayat_tugas_tambahan_' +
                                master_riwayat_tugas_tambahan.id
                              ">
                              <UploadFile :pegawai="pegawai" :dokumenDigital="dokumen_digital"
                                relationTo="riwayat_tugas_tambahan" :entityId="riwayat_tugas_tambahan.id"
                                :masterDokumenDigital="
                                  master_riwayat_tugas_tambahan
                                " :canEdit="canEdit('riwayat_tugas_tambahan')" @onUploaded="refreshPegawai">
                              </UploadFile>
                            </span>
                          </b-td>
                          <b-td>
                            <template v-if="riwayat_tugas_tambahan.id">
                              <b-button size="sm" variant="lightgreen" @click="
                                $refs[
                                  'modal-riwayat-tugas-tambahan'
                                ].openRiwayatTugasTambahanModal(
                                  riwayat_tugas_tambahan
                                )
                              " v-if="canEdit('riwayat_tugas_tambahan')">
                                <b-icon icon="pencil"></b-icon>
                              </b-button>
                              <b-button size="sm" variant="danger" @click="
                                deleteRiwayatTugasTambahan(
                                  riwayat_tugas_tambahan
                                )
                              " v-if="canDelete('riwayat_tugas_tambahan')">
                                <b-icon icon="trash"></b-icon>
                              </b-button>
                            </template>
                          </b-td>
                        </b-tr>
                        <b-tr v-if="pegawai.riwayat_tugas_tambahan.length == 0">
                          <b-td colspan="10">Tidak Ada Data</b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
              </b-row>
            </b-card>
          </b-tab>
          <b-tab title="Riwayat Pelanggaran" :title-link-class="linkClass(13)">
            <b-card>
              <b-row>
                <b-col sm="12">
                  <b-card>
                    <div class="d-flex justify-content-between mb-3">
                      <div class="d-flex align-items-center">
                        <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
                        <span class="pl-3"><b>Riwayat Pelanggaran</b></span>
                      </div>
                      <b-button variant="primary" size="md" @click="
                        $refs['modal-pelanggaran'].openPelanggaranModal(
                          pelanggaran
                        )
                      " v-if="canAdd('pelanggaran')">
                        <b-icon icon="plus"></b-icon> Tambah
                      </b-button>
                    </div>
                    <b-table-simple hover striped responsive class="mt-3">
                      <b-thead head-variant="dark">
                        <b-tr>
                          <b-th>No</b-th>
                          <b-th>Nomor Surat</b-th>
                          <b-th>Pejabat Penandatangan</b-th>
                          <b-th>Tahun</b-th>
                          <b-th>Jenis Pelanggaran</b-th>
                          <b-th>Punishment</b-th>
                          <b-th>File</b-th>
                          <b-th>Aksi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(pelanggaran, i) in pegawai.pelanggaran" :key="'pelanggaran_' + pelanggaran.id">
                          <b-td>{{ i + 1 }}</b-td>
                          <b-td>{{ pelanggaran.nomor_surat }}</b-td>
                          <b-td>{{
                              pelanggaran.pejabat_yang_mengeluarkan_bap
                          }}</b-td>
                          <b-td>{{ pelanggaran.tahun_kejadian }}</b-td>
                          <b-td>{{ pelanggaran.jenis_pelanggaran }}</b-td>
                          <b-td>{{ pelanggaran.punishment }}</b-td>
                          <b-td>
                            <span v-for="master_pelanggaran in master_dokumen_digital.pelanggaran" :key="
                              'master_pelanggaran_' + master_pelanggaran.id
                            ">
                              <UploadFile :pegawai="pegawai" :dokumenDigital="dokumen_digital" relationTo="pelanggaran"
                                :entityId="pelanggaran.id" :masterDokumenDigital="master_pelanggaran"
                                :canEdit="canEdit('pelanggaran')" @onUploaded="refreshPegawai">
                              </UploadFile>
                            </span>
                          </b-td>
                          <b-td class="text-nowrap">
                            <template v-if="pelanggaran.id">
                              <b-button size="sm" variant="lightgreen" @click="
                                $refs[
                                  'modal-pelanggaran'
                                ].openPelanggaranModal(pelanggaran)
                              " v-if="canEdit('pelanggaran')">
                                <b-icon icon="pencil"></b-icon>
                              </b-button>
                              <b-button size="sm" variant="danger" @click="deletePelanggaran(pelanggaran)"
                                v-if="canDelete('pelanggaran-profesi')">
                                <b-icon icon="trash"></b-icon>
                              </b-button>
                            </template>
                          </b-td>
                        </b-tr>

                        <b-tr v-if="pegawai.pelanggaran.length == 0">
                          <b-td colspan="10">Tidak Ada Data</b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
              </b-row>
            </b-card>
          </b-tab>
          <b-tab title="Riwayat RKK & SPKK PK" :title-link-class="linkClass(14)">
            <b-card>
              <b-row>
                <b-col>
                  <b-card>
                    <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
                    <span class="pl-3"><b>Riwayat RKK & SPKK PK</b></span>
                    <b-table-simple hover striped responsive class="mt-3">
                      <b-thead head-variant="dark">
                        <b-tr>
                          <b-th>No</b-th>
                          <b-th>Jenis RKK & SPKK PK</b-th>
                          <b-th>Bulan</b-th>
                          <b-th>Tahun</b-th>
                          <b-th>Sudah/Belum</b-th>
                          <b-th>File</b-th>
                          <b-th>Aksi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(
                            riwayat_rkk_spkk_jk, i
                          ) in pegawai.riwayat_rkk_spkk_jk" :key="
                            'riwayat_rkk_spkk_jk_' +
                            (riwayat_rkk_spkk_jk.id || i)
                          ">
                          <b-td>{{ i + 1 }}</b-td>
                          <b-td>{{
                              riwayat_rkk_spkk_jk.master_rkk_spkk_jk
                                ? riwayat_rkk_spkk_jk.master_rkk_spkk_jk
                                  .jenis_rkk_spkk_jk
                                : "-"
                          }}</b-td>
                          <b-td>{{ riwayat_rkk_spkk_jk.bulan }}</b-td>
                          <b-td>{{ riwayat_rkk_spkk_jk.tahun }}</b-td>
                          <b-td>{{
                              riwayat_rkk_spkk_jk.id ? "Sudah" : "Belum"
                          }}</b-td>
                          <b-td>
                            <span v-for="riwayat_rkk in master_dokumen_digital.rkk_spkk_jk"
                              :key="'riwayat_rkk_' + riwayat_rkk.id">
                              <UploadFile v-if="riwayat_rkk_spkk_jk.id" :pegawai="pegawai"
                                :dokumenDigital="dokumen_digital" relationTo="rkk_spkk_jk"
                                :entityId="riwayat_rkk_spkk_jk.id" :masterDokumenDigital="riwayat_rkk"
                                :canEdit="canEdit('riwayat_rkk_spkk_jk')" @onUploaded="refreshPegawai">
                              </UploadFile>
                            </span>
                          </b-td>
                          <b-td class="text-nowrap">
                            <b-button size="sm" variant="primary" @click="
                              $refs['modal-rkk'].openRkkModal(
                                riwayat_rkk_spkk_jk.master_rkk_spkk_jk
                              )
                            " v-if="
  canEdit('riwayat_rkk_spkk_jk') &&
  riwayat_rkk_spkk_jk.id == null
">
                              <b-icon icon="plus"></b-icon>
                            </b-button>
                            <template v-if="riwayat_rkk_spkk_jk.id">
                              <b-button size="sm" variant="lightgreen" @click="
                                $refs['modal-rkk'].openRkkModal(
                                  riwayat_rkk_spkk_jk.master_rkk_spkk_jk,
                                  riwayat_rkk_spkk_jk
                                )
                              " v-if="canEdit('riwayat_rkk_spkk_jk')">
                                <b-icon icon="pencil"></b-icon>
                              </b-button>
                              <b-button size="sm" variant="danger" @click="deleteRkk(riwayat_rkk_spkk_jk)"
                                v-if="canDelete('riwayat_rkk_spkk_jk')">
                                <b-icon icon="trash"></b-icon>
                              </b-button>
                            </template>
                          </b-td>
                        </b-tr>
                        <b-tr v-if="pegawai.riwayat_rkk_spkk_jk.length == 0">
                          <b-td colspan="10">Tidak Ada Data</b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
              </b-row>
            </b-card>
          </b-tab>
          <b-tab title="Data Keluarga" :title-link-class="linkClass(15)">
            <b-card>
              <b-row>
                <b-col>
                  <b-card>
                    <div class="d-flex justify-content-between mb-3">
                      <div class="d-flex align-items-center">
                        <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
                        <span class="pl-3"><b>Data Keluarga</b></span>
                      </div>
                      <b-button variant="primary" size="md" @click="
                        $refs['modal-data-keluarga'].openDataKeluargaModal(
                          data_keluarga
                        )
                      " v-if="canAdd('data_keluarga')">
                        <b-icon icon="plus"></b-icon> Tambah
                      </b-button>
                    </div>
                    <b-table-simple hover striped responsive class="mt-3">
                      <b-thead head-variant="dark">
                        <b-tr>
                          <b-th>No</b-th>
                          <b-th>Nama</b-th>
                          <b-th>NO NIK</b-th>
                          <b-th>Jenis Hubungan</b-th>
                          <b-th>Status Pernikahan</b-th>
                          <b-th>Tempat Lahir</b-th>
                          <b-th>Tanggal Lahir</b-th>
                          <b-th>Alamat</b-th>
                          <b-th>Pendidikan</b-th>
                          <b-th>Status</b-th>
                          <b-th>Tempat Bekerja</b-th>
                          <b-th>NO BPJS</b-th>
                          <b-th>File</b-th>
                          <b-th>Aksi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(data_keluarga, i) in pegawai.data_keluarga"
                          :key="'data_keluarga_' + data_keluarga.id">
                          <b-td>{{ i + 1 }}</b-td>
                          <b-td>{{ data_keluarga.nama }}</b-td>
                          <b-td>{{ data_keluarga.no_nik }}</b-td>
                          <b-td>{{ data_keluarga.jenis_hubungan }}</b-td>
                          <b-td>{{ data_keluarga.status_pernikahan }}</b-td>
                          <b-td>{{ data_keluarga.tempat_lahir }}</b-td>
                          <b-td>{{
                              data_keluarga.tanggal_lahir | formatDate
                          }}</b-td>
                          <b-td>{{ data_keluarga.alamat }}</b-td>
                          <b-td>{{ data_keluarga.pendidikan }}</b-td>
                          <b-td>{{ data_keluarga.status }}</b-td>
                          <b-td>{{ data_keluarga.tempat_bekerja }}</b-td>
                          <b-td>{{ data_keluarga.no_bpjs }}</b-td>
                          <b-td>
                            <UploadFile 
                            v-if="data_keluarga.tempat_bekerja == null" 
                            :pegawai="pegawai" :dokumenDigital="dokumen_digital" :masterDokumenDigital="
                              getDokumenDigital('Surat Keterangan Masih Sekolah')
                            " :canEdit="canEdit('Surat Keterangan Masih Sekolah')" @onUploaded="refreshPegawai">
                            </UploadFile>
                            <span class="text-nowrap"
                              v-for="master_data_keluarga in master_dokumen_digital.data_keluarga" :key="
                                'master_data_keluarga_' +
                                master_data_keluarga.id
                              ">
                              <UploadFile v-if="data_keluarga.id" :pegawai="pegawai" :dokumenDigital="dokumen_digital"
                                relationTo="data_keluarga" :entityId="data_keluarga.id"
                                :masterDokumenDigital="master_data_keluarga" :canEdit="canEdit('data_keluarga')"
                                @onUploaded="refreshPegawai">
                              </UploadFile>
                            </span>
                          </b-td>
                          <b-td class="text-nowrap">
                            <template v-if="data_keluarga.id">
                              <b-button size="sm" variant="lightgreen" @click="
                                $refs[
                                  'modal-data-keluarga'
                                ].openDataKeluargaModal(data_keluarga)
                              " v-if="canEdit('data_keluarga')">
                                <b-icon icon="pencil"></b-icon>
                              </b-button>
                              <b-button size="sm" variant="danger" @click="deleteDataKeluarga(data_keluarga)"
                                v-if="canDelete('data_keluarga')">
                                <b-icon icon="trash"></b-icon>
                              </b-button>
                            </template>
                          </b-td>
                        </b-tr>
                        <b-tr v-if="pegawai.data_keluarga.length == 0">
                          <b-td colspan="10">Tidak Ada Data</b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
              </b-row>
            </b-card>
          </b-tab>
          <b-tab title="Riwayat Penghargaan dan Prestasi" :title-link-class="linkClass(16)">
            <b-card>
              <b-row>
                <b-col>
                  <b-card>
                    <div class="d-flex justify-content-between mb-3">
                      <div class="d-flex align-items-center">
                        <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
                        <span class="pl-3"><b>Riwayat Prestasi</b></span>
                      </div>
                      <b-button variant="primary" size="md" @click="
                        $refs[
                          'modal-riwayat-prestasi'
                        ].openRiwayatPrestasiModal(riwayat_prestasi)
                      " v-if="canAdd('riwayat_prestasi')">
                        <b-icon icon="plus"></b-icon> Tambah
                      </b-button>
                    </div>
                    <b-table-simple hover striped responsive class="mt-3">
                      <b-thead head-variant="dark">
                        <b-tr>
                          <b-th>No</b-th>
                          <b-th>Prestasi</b-th>
                          <b-th>Pejabat Penandatangan</b-th>
                          <b-th>Tanggal</b-th>
                          <b-th>File</b-th>
                          <b-th>Aksi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(
                            riwayat_prestasi, i
                          ) in pegawai.riwayat_prestasi" :key="'riwayat_prestasi_' + riwayat_prestasi.id">
                          <b-td>{{ i + 1 }}</b-td>
                          <b-td>{{ riwayat_prestasi.nama_prestasi }}</b-td>
                          <b-td>{{
                              riwayat_prestasi.pejabat_penandatangan
                          }}</b-td>
                          <b-td>{{
                              riwayat_prestasi.tanggal | formatDate
                          }}</b-td>
                          <b-td>
                            <span v-for="master_riwayat_prestasi in master_dokumen_digital.riwayat_prestasi" :key="
                              'master_riwayat_prestasi_' +
                              master_riwayat_prestasi.id
                            ">
                              <UploadFile :pegawai="pegawai" :dokumenDigital="dokumen_digital"
                                relationTo="riwayat_prestasi" :entityId="riwayat_prestasi.id"
                                :masterDokumenDigital="master_riwayat_prestasi" :canEdit="canEdit('riwayat_prestasi')"
                                @onUploaded="refreshPegawai">
                              </UploadFile>
                            </span>
                          </b-td>
                          <b-td>
                            <template v-if="riwayat_prestasi.id">
                              <b-button size="sm" variant="lightgreen" @click="
                                $refs[
                                  'modal-riwayat-prestasi'
                                ].openRiwayatPrestasiModal(riwayat_prestasi)
                              " v-if="canEdit('riwayat_prestasi')">
                                <b-icon icon="pencil"></b-icon>
                              </b-button>
                              <b-button size="sm" variant="danger" @click="deleteRiwayatPrestasi(riwayat_prestasi)"
                                v-if="canDelete('riwayat_prestasi')">
                                <b-icon icon="trash"></b-icon>
                              </b-button>
                            </template>
                          </b-td>
                        </b-tr>

                        <b-tr v-if="pegawai.riwayat_prestasi.length == 0">
                          <b-td colspan="10">Tidak Ada Data</b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
              </b-row>
            </b-card>
          </b-tab>
          <b-tab title="Riwayat Kenaikan Gaji Berkala" :title-link-class="linkClass(17)">
            <b-card>
              <b-row>
                <b-col>
                  <b-card>
                    <div class="d-flex justify-content-between mb-3">
                      <div class="d-flex align-items-center">
                        <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
                        <span class="pl-3"><b>Riwayat Kenaikan Gaji Berkala</b></span>
                      </div>
                      <b-button variant="primary" size="md" @click="
                        $refs[
                          'modal-riwayat-kenaikan-gaji-berkala'
                        ].openRiwayatKenaikanGajiBerkalaModal(
                          riwayat_kenaikan_gaji_berkala
                        )
                      " v-if="canAdd('riwayat_kenaikan_gaji_berkala')">
                        <b-icon icon="plus"></b-icon> Tambah
                      </b-button>
                    </div>
                    <b-table-simple hover striped responsive class="mt-3">
                      <b-thead head-variant="dark">
                        <b-tr>
                          <b-th>No</b-th>
                          <b-th>Tahun</b-th>
                          <b-th>Bulan</b-th>
                          <b-th>Golongan</b-th>
                          <b-th>Masa Kerja Tahun</b-th>
                          <b-th>Masa Kerja Bulan</b-th>
                          <b-th>File</b-th>
                          <b-th>Aksi</b-th>
                        </b-tr>
                      </b-thead>
                      <b-tbody>
                        <b-tr v-for="(
                            riwayat_kenaikan_gaji_berkala, i
                          ) in pegawai.riwayat_kenaikan_gaji_berkala" :key="
                            'riwayat_kenaikan_gaji_berkala_' +
                            riwayat_kenaikan_gaji_berkala.id
                          ">
                          <b-td>{{ i + 1 }}</b-td>
                          <b-td>{{ riwayat_kenaikan_gaji_berkala.tahun }}</b-td>
                          <b-td>{{ riwayat_kenaikan_gaji_berkala.bulan }}</b-td>
                          <b-td>{{
                              riwayat_kenaikan_gaji_berkala.golongan
                          }}</b-td>
                          <b-td>{{
                              riwayat_kenaikan_gaji_berkala.masa_kerja_tahun
                          }}</b-td>
                          <b-td>{{
                              riwayat_kenaikan_gaji_berkala.masa_kerja_bulan
                          }}</b-td>
                          <b-td>
                            <span
                              v-for="master_riwayat_kenaikan_gaji_berkala in master_dokumen_digital.riwayat_kenaikan_gaji_berkala"
                              :key="
                                'master_riwayat_kenaikan_gaji_berkala_' +
                                master_riwayat_kenaikan_gaji_berkala.id
                              ">
                              <UploadFile :pegawai="pegawai" :dokumenDigital="dokumen_digital"
                                relationTo="riwayat_kenaikan_gaji_berkala" :entityId="riwayat_kenaikan_gaji_berkala.id"
                                :masterDokumenDigital="
                                  master_riwayat_kenaikan_gaji_berkala
                                " :canEdit="
  canEdit('riwayat_kenaikan_gaji_berkala')
" @onUploaded="refreshPegawai">
                              </UploadFile>
                            </span>
                          </b-td>
                          <b-td>
                            <template v-if="riwayat_kenaikan_gaji_berkala.id">
                              <b-button size="sm" variant="lightgreen" @click="
                                $refs[
                                  'modal-riwayat-kenaikan-gaji-berkala'
                                ].openRiwayatKenaikanGajiBerkalaModal(
                                  riwayat_kenaikan_gaji_berkala
                                )
                              " v-if="canEdit('riwayat_kenaikan_gaji_berkala')">
                                <b-icon icon="pencil"></b-icon>
                              </b-button>
                              <b-button size="sm" variant="danger" @click="
                                deleteRiwayatKenaikanGajiBerkala(
                                  riwayat_kenaikan_gaji_berkala
                                )
                              " v-if="
  canDelete('riwayat_kenaikan_gaji_berkala')
">
                                <b-icon icon="trash"></b-icon>
                              </b-button>
                            </template>
                          </b-td>
                        </b-tr>

                        <b-tr v-if="
                          pegawai.riwayat_kenaikan_gaji_berkala.length == 0
                        ">
                          <b-td colspan="10">Tidak Ada Data</b-td>
                        </b-tr>
                      </b-tbody>
                    </b-table-simple>
                  </b-card>
                </b-col>
              </b-row>
            </b-card>
          </b-tab>
        </b-tabs>
      </div>
    </template>

    <UraianTugasModal ref="modal-uraian-tugas" :pegawai="pegawai" :dokumenDigital="dokumen_digital"
      :masterDokumenDigitals="master_dokumen_digital.uraian_tugas" @onSave="refreshPegawai">
    </UraianTugasModal>
    <ManajemenKontrakModal ref="modal-manajemen-kontrak" :pegawai="pegawai" :dokumenDigital="dokumen_digital"
      :masterDokumenDigitals="master_dokumen_digital.kontrak" @onSave="refreshPegawai">
    </ManajemenKontrakModal>
    <RiwayatDiklatModal ref="modal-riwayat-diklat" :pegawai="pegawai" :dokumenDigital="dokumen_digital"
      :masterDokumenDigitals="master_dokumen_digital.riwayat_diklat" @onSave="refreshPegawai"></RiwayatDiklatModal>
    <DataHubunganKerabatModal ref="modal-data-hubungan-kerabat" :pegawai="pegawai" @onSave="refreshPegawai">
    </DataHubunganKerabatModal>
    <PengalamanKerjaModal ref="modal-pengalaman-kerja" :pegawai="pegawai" :dokumenDigital="dokumen_digital"
      :masterDokumenDigitals="master_dokumen_digital.pengalaman_kerja" @onSave="refreshPegawai">
    </PengalamanKerjaModal>
    <NiraModal ref="modal-nira" :pegawai="pegawai" :dokumenDigital="dokumen_digital"
      :masterDokumenDigitals="master_dokumen_digital.nira" @onSave="refreshPegawai"></NiraModal>
    <RiwayatPendidikanModal ref="modal-riwayat-pendidikan" :pegawai="pegawai" :dokumenDigital="dokumen_digital"
      :masterDokumenDigitals="master_dokumen_digital.riwayat_pendidikan" @onSave="refreshPegawai">
    </RiwayatPendidikanModal>
    <PenilaianKinerjaModal ref="modal-penilaian-kinerja" :pegawai="pegawai" :dokumenDigital="dokumen_digital"
      :masterDokumenDigitals="master_dokumen_digital.penilaian_kinerja" @onSave="refreshPegawai">
    </PenilaianKinerjaModal>
    <RiwayatAsesmenModal ref="modal-riwayat-asesmen" :pegawai="pegawai" :dokumenDigital="dokumen_digital"
      :masterDokumenDigitals="master_dokumen_digital.riwayat_asesmen" @onSave="refreshPegawai"></RiwayatAsesmenModal>
    <DataKontakDaruratModal ref="modal-data-kontak-darurat" :pegawai="pegawai" @onSave="refreshPegawai">
    </DataKontakDaruratModal>
    <RiwayatKesehatanModal ref="modal-riwayat-kesehatan" :pegawai="pegawai" :dokumenDigital="dokumen_digital"
      :masterDokumenDigitals="master_dokumen_digital.riwayat_kesehatan_mcu" @onSave="refreshPegawai">
    </RiwayatKesehatanModal>
    <MasterMcuModal ref="modal-master-mcu" :pegawai="pegawai" @onSave="refreshPegawai">
    </MasterMcuModal>
    <RiwayatPengangkatanModal ref="modal-riwayat-pengangkatan" :pegawai="pegawai" :dokumenDigital="dokumen_digital"
      :masterDokumenDigitals="master_dokumen_digital.riwayat_pengangkatan" @onSave="refreshPegawai">
    </RiwayatPengangkatanModal>
    <RiwayatPenempatanModal ref="modal-riwayat-penempatan" :pegawai="pegawai" :dokumenDigital="dokumen_digital"
      :masterDokumenDigitals="master_dokumen_digital.riwayat_penempatan" @onSave="refreshPegawai">
    </RiwayatPenempatanModal>
    <RiwayatTugasTambahanModal ref="modal-riwayat-tugas-tambahan" :pegawai="pegawai" :dokumenDigital="dokumen_digital"
      :masterDokumenDigitals="master_dokumen_digital.riwayat_tugas_tambahan" @onSave="refreshPegawai">
    </RiwayatTugasTambahanModal>
    <RiwayatPrestasiModal ref="modal-riwayat-prestasi" :pegawai="pegawai" :dokumenDigital="dokumen_digital"
      :masterDokumenDigitals="master_dokumen_digital.riwayat_prestasi" @onSave="refreshPegawai">
    </RiwayatPrestasiModal>
    <RiwayatKesehatanVaksinModal ref="modal-riwayat-kesehatan-vaksin" :pegawai="pegawai"
      :dokumenDigital="dokumen_digital" :masterDokumenDigitals="master_dokumen_digital.riwayat_kesehatan_vaksin"
      @onSave="refreshPegawai">
    </RiwayatKesehatanVaksinModal>
    <DataKeluargaModal ref="modal-data-keluarga" :pegawai="pegawai" :dokumenDigital="dokumen_digital"
      :masterDokumenDigitals="master_dokumen_digital.data_keluarga" @onSave="refreshPegawai"></DataKeluargaModal>
    <RkkModal ref="modal-rkk" :pegawai="pegawai" :dokumenDigital="dokumen_digital"
      :masterDokumenDigitals="master_dokumen_digital.rkk_spkk_jk" @onSave="refreshPegawai"></RkkModal>
    <PelanggaranModal ref="modal-pelanggaran" :pegawai="pegawai" :dokumenDigital="dokumen_digital"
      :masterDokumenDigitals="master_dokumen_digital.pelanggaran" @onSave="refreshPegawai"></PelanggaranModal>
    <StrModal ref="modal-str" :pegawai="pegawai" :dokumenDigital="dokumen_digital"
      :masterDokumenDigitals="master_dokumen_digital.str" @onSave="refreshPegawai"></StrModal>
    <SipModal ref="modal-sip" :pegawai="pegawai" :dokumenDigital="dokumen_digital"
      :masterDokumenDigitals="master_dokumen_digital.sip" @onSave="refreshPegawai"></SipModal>
    <RiwayatKenaikanGajiBerkalaModal ref="modal-riwayat-kenaikan-gaji-berkala" :pegawai="pegawai"
      :dokumenDigital="dokumen_digital" :masterDokumenDigitals="
        master_dokumen_digital.riwayat_kenaikan_gaji_berkala
      " @onSave="refreshPegawai"></RiwayatKenaikanGajiBerkalaModal>
    <RiwayatMcuModal ref="modal-riwayat-mcu" :pegawai="pegawai" :dokumenDigital="dokumen_digital"
      :masterDokumenDigitals="master_dokumen_digital.riwayat_mcu" @onSave="refreshPegawai"></RiwayatMcuModal>
  </b-container>
</template>

<script>
import Form from "vform";
import { mapGetters } from "vuex";
import axios from "axios";
import Swal from "sweetalert2";
import moment from "moment";
import { buildQueryParams } from "~/plugins/utils";
import Button from "~/components/global/Button.vue";
import UraianTugasModal from "~/components/pegawai/UraianTugasModal.vue";
import RiwayatDiklatModal from "~/components/pegawai/RiwayatDiklatModal.vue";
import RiwayatPendidikanModal from "~/components/pegawai/RiwayatPendidikanModal.vue";
import UploadFile from "~/components/pegawai/UploadFile.vue";
import ManajemenKontrakModal from "../../components/pegawai/ManajemenKontrakModal.vue";
import PenilaianKinerjaModal from "../../components/pegawai/PenilaianKinerjaModal.vue";
import PengalamanKerjaModal from "../../components/pegawai/PengalamanKerjaModal.vue";
import NiraModal from "../../components/pegawai/NiraModal.vue";
import DataHubunganKerabatModal from "../../components/pegawai/DataHubunganKerabatModal.vue";
import RiwayatAsesmenModal from "~/components/pegawai/RiwayatAsesmenModal.vue";
import DataKontakDaruratModal from "~/components/pegawai/DataKontakDaruratModal.vue";
import RiwayatKesehatanModal from "~/components/pegawai/RiwayatKesehatanModal.vue";
import MasterMcuModal from "~/components/pegawai/MasterMcuModal.vue";
import RiwayatKesehatanVaksinModal from "~/components/pegawai/RiwayatKesehatanVaksinModal.vue";
import RiwayatPengangkatanModal from "~/components/pegawai/RiwayatPengangkatanModal.vue";
import RiwayatPenempatanModal from "~/components/pegawai/RiwayatPenempatanModal.vue";
import RiwayatPrestasiModal from "~/components/pegawai/RiwayatPrestasiModal.vue";
import RiwayatTugasTambahanModal from "~/components/pegawai/RiwayatTugasTambahanModal.vue";
import RiwayatKenaikanGajiBerkalaModal from "~/components/pegawai/RiwayatKenaikanGajiBerkalaModal.vue";
import DataKeluargaModal from "../../components/pegawai/DataKeluargaModal.vue";
import RiwayatMcuModal from "../../components/pegawai/RiwayatMcuModal.vue";
import RkkModal from "~/components/pegawai/RkkModal.vue";
import StrModal from "~/components/pegawai/StrModal.vue";
import SipModal from "~/components/pegawai/SipModal.vue";
import ProfileBlockItem from "~/components/profile/ProfileBlockItem.vue";
import PelanggaranModal from "~/components/pegawai/PelanggaranModal.vue";
import ModalEditUtama from "~/components/profile/Modals/ModalEditUtama.vue";
import ModalEditProfile from "~/components/profile/Modals/ModalEditProfile.vue";
import ModalEditSertifikasi from "~/components/profile/Modals/ModalEditSertifikasi.vue";
import ModalEditAbout from "~/components/profile/Modals/ModalEditAbout.vue";

export default {
  components: {
    Button,
    UraianTugasModal,
    RiwayatDiklatModal,
    UploadFile,
    PenilaianKinerjaModal,
    PenilaianKinerjaModal,
    ManajemenKontrakModal,
    RiwayatPendidikanModal,
    PengalamanKerjaModal,
    NiraModal,
    DataHubunganKerabatModal,
    RiwayatAsesmenModal,
    DataKontakDaruratModal,
    DataKeluargaModal,
    RiwayatKesehatanModal,
    MasterMcuModal,
    RiwayatKesehatanVaksinModal,
    RiwayatPengangkatanModal,
    RiwayatPenempatanModal,
    RiwayatTugasTambahanModal,
    RiwayatPrestasiModal,
    RiwayatKenaikanGajiBerkalaModal,
    RiwayatMcuModal,
    RkkModal,
    StrModal,
    SipModal,
    ProfileBlockItem,
    PelanggaranModal,
    ModalEditUtama,
    ModalEditProfile,
    ModalEditSertifikasi,
    ModalEditAbout,
  },
  scrollToTop: false,
  middleware: ["auth"],

  async asyncData({ params, error }) {
    try {
      let pegawaiResp = (
        await axios.get(params.id ? "pegawai/" + params.id : "settings")
      ).data;
      let pegawai = pegawaiResp.models;
      let dokumen_digital = pegawaiResp.dokumen_digital;
      let master_dokumen_digital = pegawaiResp.master_dokumen_digital;
      return {
        pegawai,
        dokumen_digital,
        master_dokumen_digital,
      };
    } catch (e) {
      error({
        statusCode: 403,
        message: "Akses Terbatas",
      });
    }
  },
  data: () => ({
    loading: false,
    errors: {},
    tabIndex: 0,
    url: process.env.apiUrl.replace("/api", ""),
    today: new Date().toISOString().slice(0, 10),
    tabs: [
      {
        name: "Kepegawaian",
        children: [
          { id: 0, name: "Informasi Kepegawaian " },
          { id: 1, name: "Dokumen/Arsip" },
          { id: 2, name: "Kinerja" },
          { id: 3, name: "Uraian Tugas" },
          { id: 4, name: "Manajemen Kontrak" },
          { id: 7, name: "Asesmen Kompetensi" },
        ],
      },
      {
        name: "Data Pribadi",
        children: [
          { id: 5, name: "Pendidikan dan Pelatihan" },
          { id: 6, name: "Pengalaman Kerja" },
        ],
      },
      {
        name: "Riwayat",
        children: [
          { id: 10, name: "Riwayat Kesehatan" },
          { id: 11, name: "Riwayat Pengangkatan" },
          { id: 12, name: "Riwayat Penempatan" },
          {
            id: 13,
            name: "Riwayat Pelanggaran",
            permission: ["riwayat-pelanggaran"],
          },
          { id: 14, name: "Riwayat RKK & SPKK PK" },
          { id: 16, name: "Riwayat Penghargaan dan Prestasi" },
          { id: 17, name: "Riwayat Kenaikan Gaji Berkala" },
        ],
      },
      {
        name: "Hubungan",
        children: [
          { id: 15, name: "Data Keluarga" },
          { id: 8, name: "Data Hubungan Kerabat" },
          { id: 9, name: "Data Kontak Darurat" },
        ],
      },
    ],
    modals: {
      editUtama: { title: "Edit", isOpen: false },
      editProfile: { title: "Edit Profile", isOpen: false },
      editAbout: { title: "Edit Tentang Saya", isOpen: false },
      editSertifikasi: { title: "Edit Sertifikasi", isOpen: false },
    },
  }),

  head() {
    return { title: "Detail - " + this.pegawai.peg_nama_lengkap };
  },

  computed: mapGetters({
    user: "auth/user",
  }),

  methods: {
    getDokumenDigital(file_nama) {
      const masters = this.master_dokumen_digital[""];
      for (let i = 0; i < masters.length; i++) {
        if (masters[i].file_nama == file_nama) {
          return masters[i];
        }
      }
    },
    linkClass(idx) {
      if (this.tabIndex === idx) {
        return ["bg-success", "text-light"];
      } else {
        return ["bg-light", "text-dark"];
      }
    },
    formatDate(value) {
      if (value) {
        return moment(String(value)).format("DDMMYYYY");
      }
    },
    async update() {
      this.loading = true;
      try {
        let resp = (
          await axios.patch("pegawai/" + this.$route.params.id, this.form)
        ).data;
        this.pegawai.patch("/setting/profile").then(({ data: user }) => {
          this.$store.dispatch("auth/updateUser", { user });
        });
        if (resp.success) {
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil disimpan",
            confirmButtonText: "ok",
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Gagal menyimpan data",
            text: resp.message,
            confirmButtonText: "ok",
          });
        }
        this.errors = {};
      } catch (err) {
        if (err.response && err.response.status == 422) {
          this.errors = err.response.data.errors;
        }
      }
      this.loading = false;
    },
    getErrorState(key) {
      if (this.errors[key]) {
        return false;
      }
      return null;
    },
    getErrorMessage(key) {
      if (this.errors[key]) {
        return this.errors[key].join(", ");
      }
      return null;
    },
    canAdd(jenis_data) {
      return true;
    },
    canEdit(jenis_data) {
      return true;
    },
    canDelete(jenis_data) {
      return true;
    },
    deleteNira(data) {
      Swal.fire({
        title:
          "Apakah Anda yakin hendak menghapus data nomor nira " +
          data.nomor_nira +
          "?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(`/pegawai/nira/${data.id}`);
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.refreshPegawai();
        }
      });
    },
    deleteRiwayatTugasTambahan(data) {
      Swal.fire({
        title:
          "Apakah Anda yakin hendak menghapus " + data.tugas_tambahan + "?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(
            `/pegawai/riwayat-tugas-tambahan/${data.id}`
          );
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.refreshPegawai();
        }
      });
    },
    deleteRiwayatKenaikanGajiBerkala(data) {
      Swal.fire({
        title:
          "Apakah Anda yakin hendak menghapus data kenaikan gaji pada tahun " +
          data.tahun +
          " dan pada bulan " +
          data.bulan +
          " ?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(
            `/pegawai/riwayat-kenaikan-gaji-berkala/${data.id}`
          );
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.refreshPegawai();
        }
      });
    },
    deleteRiwayatPrestasi(data) {
      Swal.fire({
        title: "Apakah Anda yakin hendak menghapus " + data.nama_prestasi + "?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(
            `/pegawai/riwayat-prestasi/${data.id}`
          );
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.refreshPegawai();
        }
      });
    },
    deleteStr(data) {
      Swal.fire({
        title:
          "Apakah Anda yakin hendak menghapus nomor str ini " +
          data.nomor_str +
          "?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(`/str/${data.str_id}`);
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.refreshPegawai();
        }
      });
    },
    deleteSip(data) {
      Swal.fire({
        title:
          "Apakah Anda yakin hendak menghapus data nomor sip ini " +
          data.nomor_sip +
          "?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(`/sip/${data.sip_id}`);
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.refreshPegawai();
        }
      });
    },
    deleteDataKeluarga(data) {
      Swal.fire({
        title: "Apakah Anda yakin hendak menghapus " + data.nama + "?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(
            `/pegawai/data-keluarga/${data.id}`
          );
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.refreshPegawai();
        }
      });
    },
    deleteRiwayatPengangkatan(data) {
      Swal.fire({
        title: "Apakah Anda yakin hendak menghapus " + data.nomor_sk + "?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(
            `/pegawai/riwayat-pengangkatan/${data.id}`
          );
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.refreshPegawai();
        }
      });
    },
    deleteRiwayatPendidikan(data) {
      Swal.fire({
        title: "Apakah Anda yakin hendak menghapus " + data.nama_sekolah + "?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(
            `/pegawai/riwayat-pendidikan/${data.id}`
          );
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.refreshPegawai();
        }
      });
    },
    deleteRiwayatPenempatan(data) {
      Swal.fire({
        title: "Apakah Anda yakin hendak menghapus " + data.jabatan + "?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(
            `/pegawai/riwayat-penempatan/${data.id}`
          );
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.refreshPegawai();
        }
      });
    },
    deleteDataKontakDarurat(data) {
      Swal.fire({
        title: "Apakah Anda yakin hendak menghapus " + data.nama + "?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(
            `/pegawai/data-kontak-darurat/${data.id}`
          );
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.refreshPegawai();
        }
      });
    },
    deleteDataHubunganKerabat(data) {
      Swal.fire({
        title: "Apakah Anda yakin hendak menghapus " + data.nama + "?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(
            `/pegawai/data-hubungan-kerabat/${data.id}`
          );
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.refreshPegawai();
        }
      });
    },
    deleteUraianTugas(data) {
      Swal.fire({
        title: "Apakah Anda yakin hendak menghapus " + data.tugas + "?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(
            `/pegawai/uraian-tugas/${data.id}`
          );
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.refreshPegawai();
        }
      });
    },
    deletePengalamanKerja(data) {
      Swal.fire({
        title: "Apakah Anda yakin hendak menghapus " + data.tempat_kerja + "?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(
            `/pegawai/pengalaman-kerja/${data.id}`
          );
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.refreshPegawai();
        }
      });
    },
    deleteManajemenKontrak(data) {
      Swal.fire({
        title:
          "Apakah Anda yakin hendak menghapus " +
          data.jenis_kontrak +
          "( " +
          data.tanggal_mulai +
          " - " +
          data.tanggal_habis_kontrak +
          " ) ?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(
            `/pegawai/manajemen-kontrak/${data.id}`
          );
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.refreshPegawai();
        }
      });
    },
    deletePenilaianKinerja(data) {
      Swal.fire({
        title:
          "Apakah Anda yakin hendak menghapus penilaian kinerja tahun " +
          data.tahun +
          "?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(
            `/pegawai/penilaian-kinerja/${data.id}`
          );
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.refreshPegawai();
        }
      });
    },
    deleteRiwayatDiklat(data) {
      Swal.fire({
        title:
          "Apakah Anda yakin hendak menghapus " + data.nama_pelatihan + "?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(
            `/pegawai/riwayat-diklat/${data.id}`
          );
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.refreshPegawai();
        }
      });
    },
    deleteRiwayatAsesmen(data) {
      Swal.fire({
        title:
          "Apakah Anda yakin hendak menghapus " +
          data.master_asesmen_kompetensi.jenis_asesmen_kompetensi +
          "?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(
            `/pegawai/riwayat-asesmen/${data.id}`
          );
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.refreshPegawai();
        }
      });
    },
    deleteRiwayatKesehatan(data) {
      Swal.fire({
        title: "Apakah Anda yakin hendak menghapus " + data.nama_penyakit + "?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(
            `/pegawai/riwayat-kesehatan/${data.id}`
          );
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.refreshPegawai();
        }
      });
    },
    deleteRiwayatKesehatanVaksin(data) {
      Swal.fire({
        title: "Apakah Anda yakin hendak menghapus " + data.nama_penyakit + "?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(
            `/pegawai/riwayat-kesehatan-vaksin/${data.id}`
          );
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.refreshPegawai();
        }
      });
    },
    deletePelanggaran(data) {
      Swal.fire({
        title: "Apakah Anda yakin hendak menghapus " + data.nomor_surat + "?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(
            `/pegawai/pelanggaran/${data.id}`
          );
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.refreshPegawai();
        }
      });
    },
    deleteRkk(data) {
      Swal.fire({
        title:
          "Apakah Anda yakin hendak menghapus " +
          data.master_rkk_spkk_jk.jenis_rkk_spkk_jk +
          "?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(`/pegawai/rkk/${data.id}`);
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.refreshPegawai();
        }
      });
    },
    deleteRiwayatMcu(data) {
      Swal.fire({
        title:
          "Apakah Anda yakin hendak menghapus " + data.jenis_pemeriksaan + "?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(
            `/pegawai/riwayat-mcu/${data.id}`
          );
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.refreshPegawai();
        }
      });
    },
    async refreshPegawai() {
      let dataPegawai = axios.get(
        this.$route.params.id ? "pegawai/" + this.$route.params.id : "settings"
      );
      let pegawaiResp = (await dataPegawai).data;
      this.pegawai = pegawaiResp.models;
      this.dokumen_digital = pegawaiResp.dokumen_digital;
      this.master_dokumen_digital = pegawaiResp.master_dokumen_digital;
    },
    openModal(key) {
      this.modals[key].isOpen = true;
    },
    closeModal(key) {
      this.modals[key].isOpen = false;
    },
  },
  mounted() {
    this.$store.dispatch("useroptions/fetchOptions");
  },
};
</script>

<style scoped>
.box {
  width: 300px;
  height: 60px;
  border: 1px solid grey;
  border-radius: 0.25rem;
}

:deep(.tabs) .card-header {
  padding: 0;
}

:deep(.dropdown-item.active) {
  background-color: #34c38f;
  color: white;
}
</style>
