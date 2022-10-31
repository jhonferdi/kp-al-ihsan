<template>
  <b-container fluid>
    <h4>Halaman Profile</h4>
    <b-card>

      <b-row class="my-1">
        <b-col sm="2">
          <div>
            <b-img class="w-100 mb-3 mb-sm-0" rounded="lg"
              src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8cHJvZmlsZXxlbnwwfHwwfHw%3D&w=1000&q=80"
              style=""></b-img>
            <!-- <b-avatar rounded="sm" size="175px"></b-avatar> -->
          </div>
        </b-col>
        <b-col sm="10">
          <div>
            <div class="d-sm-flex justify-content-between">
              <div>
                <h2 style="color:#616161"> {{ form.peg_nama_lengkap }} </h2>
              </div>
              <div>
                <nuxt-link :to="{ name: 'settings.edit', params: { id: form.peg_id }}" class="mr-2">
                  <b-button rounded="lg" variant="darkblue">
                    <b-icon icon="pencil"></b-icon> Edit Profil
                  </b-button>
                </nuxt-link>
                <b-button rounded="lg" variant="darkgreen">
                  <b-icon icon="printer"></b-icon> Cetak Profil
                </b-button>
              </div>
            </div>
            <div class="mt-3">
              <h5 class="text-secondary"> {{ form.jabatan_nama }} </h5>
            </div>
            <div>
              <span class="badge badge-softgreen text-darkgreen" v-if="form.status_kerja == 'Aktif'"> {{ form.status_kepegawaian }} - {{
              form.status_kerja }} </span>
              <span class="badge badge-softdanger text-danger" v-if="form.status_kerja != 'Aktif'"> {{ form.status_kepegawaian }} - {{
              form.status_kerja }} </span>

              <div class="d-sm-flex mt-3">
                <div class="w-100">
                  <b-row>
                    <b-col sm="6" class="mb-3" v-for="(item,i ) in listDataPribadi" :key="i">
                      <b-img :src="require(`@/assets/images/${item.imgUrl}`)" width="15" class="img-fluid" />
                      <span class="pl-2"> {{ form[item.key] || '-' }} </span>
                    </b-col>
                  </b-row>
                </div>
                <div class="d-flex align-items-end ">
                  <span class="text-secondary text-nowrap"> TMT Masuk : {{ form.peg_tmt | formatDate }} </span>
                </div>
              </div>
            </div>
          </div>
        </b-col>
      </b-row>
    </b-card>
    <template>
      <div>
        <div class="border bg-white rounded p-2 mb-3" style="border-color:#34894E !important">
          <ul class="p-0 m-0 d-sm-flex" style="list-style:none">
            <li class="mr-2" v-for="(item,i) in tabsList">
              <b-button class="d-block w-100 border-none font-weight-bold" :variant="tabIndex== i ? 'darkgreen' : 'white'"
              :class="tabIndex== i ? '' : 'text-muted'"
                @click.prevent="tabIndex = i">{{item.name}}</b-button>
            </li>
          </ul>
        </div>
        <Transition name="fade">
          <div v-if="tabIndex===0">
            <b-row>
              <b-col sm="9">
                <b-card class="shadow-none">
                  <b-row>
                    <b-col sm="6" class="mt-sm-4 ml-sm-4">
                      <b-card style="max-width: 25rem;" border-variant="dark">
                        <div class="ml-4 mb-3">
                          <img src="@/assets/images/profil.png" width="17" class="img-fluid" />
                          <span class="pl-2"><b>Profile</b></span>
                        </div>
                        <div class="pl-sm-4 pl-2 pt-1 ">
                          <label class="text-muted mb-0 font-weight-bold">Nama Lengkap</label>
                          <p><b>{{ form.peg_nama_lengkap || '-'}}</b></p>
                        </div>
                        <div class="pl-sm-4 pl-2 pt-1 ">
                          <label class="text-muted mb-0 font-weight-bold">NIP/NIPT/NIK</label>
                          <p><b>{{ form.peg_nip_nipt_nik || '-'}}</b></p>
                        </div>
                        <div class="pl-sm-4 pl-2 pt-1 ">
                          <label class="text-muted mb-0 font-weight-bold">Tempat Tanggal Lahir</label>
                          <p><b>{{ form.peg_lahir_tempat }}, {{ form.peg_lahir_tanggal | formatDate}}</b></p>
                        </div>
                        <div class="pl-sm-4 pl-2 pt-1 ">
                          <label class="text-muted mb-0 font-weight-bold">Jenis Kelamin</label>
                          <p v-if="form.peg_jenis_kelamin == 'L'"><b>Laki-Laki</b></p>
                          <p v-else-if="form.peg_jenis_kelamin == 'P'"><b>Perempuan</b></p>
                          <p v-else><b>-</b></p>
                        </div>
                      </b-card>
                      <b-card border-variant="dark" style="max-width: 25rem;">
                        <div class="mt-sm-4 ml-sm-4 mb-3">
                          <img src="@/assets/images/informasi.png" width="17" class="img-fluid" />
                          <span class="pl-2"><b>Informasi Lainnya</b></span>
                        </div>
                        <div class="pl-sm-4 pl-2 pt-1 ">
                          <label class="text-muted mb-0 font-weight-bold">Nomor KTP</label>
                          <p><b>{{ form.peg_ktp || '-'}}</b></p>
                        </div>
                        <div class="pl-sm-4 pl-2 pt-1 ">
                          <label class="text-muted mb-0 font-weight-bold">NPWP</label>
                          <p><b>{{ form.peg_npwp || '-'}}</b></p>
                        </div>
                        <div class="pl-sm-4 pl-2 pt-1 ">
                          <label class="text-muted mb-0 font-weight-bold">No. BPJS</label>
                          <p><b>{{ form.peg_bpjs || '-'}}</b></p>
                        </div>
                        <div class="pl-sm-4 pl-2 pt-1 ">
                          <label class="text-muted mb-0 font-weight-bold">Tahun</label>
                          <p><b>{{ form.peg_masa_kerja || 0 }} Tahun</b></p>
                        </div>
                      </b-card>
                    </b-col>
                    <b-col sm="5" class="mt-sm-4 ml-sm-4 mb-3">
                      <b-card border-variant="dark" style="max-width: 25rem;">
                        <div class="ml-sm-4 mb-3">
                          <img src="@/assets/images/ruangan.png" width="17" class="img-fluid" />
                          <span class="pl-2"><b>Ruangan</b></span>
                        </div>
                        <div class="pl-sm-4 pl-2 pt-1 ">
                          <label class="text-muted mb-0 font-weight-bold">Ruangan</label>
                          <p><b>{{ form.nama_ruangan || '-' }} </b></p>
                        </div>
                        <div class="pl-sm-4 pl-2 pt-1 ">
                          <label class="text-muted mb-0 font-weight-bold">Unit Kerja</label>
                          <p><b>{{ form.unit_kerja_nama || '-' }} </b></p>
                        </div>
                        <div class="pl-sm-4 pl-2 pt-1 ">
                          <label class="text-muted mb-0 font-weight-bold">Spesialis</label>
                          <p><b>{{ form.spesialis_nama || '-' }} </b></p>
                        </div>
                      </b-card>
                      <b-card border-variant="dark" style="max-width: 25rem;">
                        <div class="ml-sm-4 mt-sm-4">
                          <img src="@/assets/images/jabatan.jpg" width="17" class="img-fluid" />
                          <span class="pl-2"><b>Jabatan</b></span>
                        </div>
                        <div class="pl-sm-4 pl-2 pt-1 ">
                          <label class="text-muted mb-0 font-weight-bold">Nama Jabatan</label>
                          <p><b>{{ form.jabatan_nama || '-' }} </b></p>
                        </div>
                        <div class="pl-sm-4 pl-2 pt-1 ">
                          <label class="text-muted mb-0 font-weight-bold">Jenis Jabatan</label>
                          <p><b>{{ form.jenis_jabatan_nama || '-' }} </b></p>
                        </div>
                        <div class="pl-sm-4 pl-2 pt-1 ">
                          <label class="text-muted mb-0 font-weight-bold">Jabatan Fungsional</label>
                          <p><b>{{ form.jabatan_fungsional_nama || '-' }} </b></p>
                        </div>
                        <div class="pl-sm-4 pl-2 pt-1 ">
                          <label class="text-muted mb-0 font-weight-bold">Jenjang Jabatan</label>
                          <p><b>{{ form.jenjang_jabatan_nama || '-' }} </b></p>
                        </div>
                        <div class="pl-sm-4 pl-2 pt-1 ">
                          <label class="text-muted mb-0 font-weight-bold">Golongan</label>
                          <p><b>{{ form.golongan_nama || '-' }} </b></p>
                        </div>
                      </b-card>
                    </b-col>
                  </b-row>
                </b-card>
                <b-card style="max-width: 65rem;">
                  <div class="ml-4 mt-2">
                    <img src="@/assets/images/sertifikasi.png" width="15" class="img-fluid" />
                    <span class="pl-3"><b>Sertifikasi</b></span>
                  </div>
                  <b-row class="pt-3 pl-sm-4">
                    <b-col sm="6" v-for="i in 2" :key="i">
                      <b-card>
                        <img src="@/assets/images/sertifikasi.png" width="15" class="img-fluid" />
                        <span class="pl-2"><b>Pelatihan</b></span>
                        <div class="mt-3">
                          <div class="font-weight-bold text-lightgreen mb-1">Modul Pelatihan Tekanan Darah</div>
                          <div class="text-muted mb-1">12 Agustus - 15 Agustus 2022</div>
                          <b-button block variant="lightgreen">Lihat Sertifikat</b-button>
                        </div>
                      </b-card>
                    </b-col>
                  </b-row>
                </b-card>

              </b-col>
              <b-col>
                <b-card>
                  <img src="@/assets/images/kanan.png" width="15" class="img-fluid" />
                  <span class="pl-3"><b>Tentang Saya</b></span>
                  <div class="mt-3 text-small">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ac molestie tellus. Sed dictum laoreet purus in iaculis. Nunc vehicula, lacus sit amet laoreet interdum, diam tortor euismod sapien, viverra porta nunc justo id est. lacus sit amet laoreet interdum, diam tortor euismod sapien, viverra porta nunc justo id est, lacus sit amet laoreet inen, viverra porta nunc justo id est.est, lacus sit amet laoreet inen, viverra porta nunc justo id est.</div>
                </b-card>
                <b-card>
                  <img src="@/assets/images/kanan.png" width="15" class="img-fluid" />
                  <span class="pl-3"><b>SIP</b></span>
                  <div class="pl-sm-4 pl-2 pt-2 ">
                    <span>
                      <p class="text-primary pt-1"><b>{{ form.nomor_sip || '-' }}</b></p>
                      <p>Berlaku s.d {{ (form.tanggal_kadaluarsa_sip | formatDate)  || '-' }}</p>
                      <div class="pr-1">
                        <!-- <img src="@/assets/images/aktif.png" class="w-100 img-fluid" /> -->
                        <h5 v-if="form.status_sip == 1">
                          <b-badge class="w-100 py-1 text-darkgreen bg-softgreen border">Aktif</b-badge>
                        </h5>
                        <h5 v-if="form.status_sip == 0">
                          <b-badge class="w-100 py-1 text-danger bg-softdanger border">Tidak Aktif</b-badge>
                        </h5>
                      </div>
                    </span>
                  </div>
                </b-card>
                <b-card>
                  <img src="@/assets/images/kanan.png" width="15" class="img-fluid" />
                  <span class="pl-3"><b>STR</b></span>
                  <div class="pl-sm-4 pl-2 pt-1 ">
                    <span>
                      <p class="text-primary pt-1"><b>{{ form.nomor_str || '-' }}</b></p>
                      <p>Berlaku s.d {{ (form.tanggal_kadaluarsa_str | formatDate || '-') }}</p>
                      <div class="pr-1">
                        <h5 v-if="form.status_str == 1">
                          <b-badge class="w-100 py-1 text-darkgreen bg-softgreen border">Aktif</b-badge>
                        </h5>
                        <h5 v-if="form.status_str == 0">
                          <b-badge class="w-100 py-1 text-danger bg-softdanger border">Tidak Aktif</b-badge>
                        </h5>
                      </div>
                    </span>
                  </div>
                </b-card>
                <b-card>
                  <img src="@/assets/images/kanan.png" width="15" class="img-fluid" />
                  <span class="pl-3"><b>Nama Atasan</b></span>
                  <div class="pl-sm-4 pl-2 pt-3 ">
                    <div v-for="i in 3" class="mb-2">
                      <p class="text-muted mb-2">Direktur</p>
                      <div class="d-flex align-items-center">
                        <img class="flex-shrink-0" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8cHJvZmlsZXxlbnwwfHwwfHw%3D&w=1000&q=80" style="width:30px; height:30px; object-fit: cover; border-radius:50%" alt="">
                        <div class="ml-2 font-weight-bold">Dewi Basmala </div>
                      </div>
                    </div>
                  </div>
                </b-card>
              </b-col>
            </b-row>
          </div>
          <div v-if="tabIndex===1">
            <b-card>
              <b-row>
                <b-col>
                  <img src="@/assets/images/sertifikasi.png" width="15" class="img-fluid" />
                  <span class="pl-3"><b>Dukumen / Arsip Kepegawaian</b></span>
                </b-col>
              </b-row>
              <b-row class="pt-3">
                <b-col sm="4">
                  <b-card class="border">
                    <img src="@/assets/images/kanan.png" width="15" class="img-fluid" />
                    <span class="pl-3"><b>SIP</b></span>
                    <div class="pt-3">
                      <p class="text-muted mb-1"> {{ form.nomor_sip || '-' }} </p>
                      <div class="mb-2">
                        <b-icon class="text-danger" style="font-size: 20px;" icon="file-pdf-fill"></b-icon> <span class="text-primary">SIP_AnneRosliana.pdf</span>
                      </div>
                      <b-button block variant="outline-darkgreen" size="md" class="font-weight-bold" :to="{ name: 'sip.create' }">
                        <b-icon icon="plus"></b-icon> Tambah File
                      </b-button>
                    </div>
                  </b-card>
                </b-col>
                <b-col sm="4">
                  <b-card class="border">
                    <img src="@/assets/images/kanan.png" width="15" class="img-fluid" />
                    <span class="pl-3"><b>STR</b></span>
                    <div class="pt-3">
                      <p class="text-muted mb-1"> {{ form.nomor_str || '-' }} </p>
                      <div class="mb-2">
                        <b-icon class="text-danger" style="font-size: 20px;" icon="file-pdf-fill"></b-icon> <span class="text-primary">STR_AnneRosliana.pdf</span>
                      </div>
                      <b-button block variant="outline-darkgreen" size="md" class="font-weight-bold" :to="{ name: 'str.create' }">
                        <b-icon icon="plus"></b-icon> Tambah File
                      </b-button>
                    </div>
                  </b-card>
                </b-col>
                <b-col sm="4">
                  <b-card class="border">
                    <img src="@/assets/images/kanan.png" width="15" class="img-fluid" />
                    <span class="pl-3"><b>SK Pengangkatan</b></span>
                    <div class="pt-3">
                      <p class="text-muted mb-1"> {{ form.nomor_str || '-' }} </p>
                      <div class="mb-2">
                        <b-icon class="text-danger" style="font-size: 20px;" icon="file-pdf-fill"></b-icon> <span class="text-primary">KTP_AnneRosliana.pdf</span>
                      </div>
                      <b-button block variant="outline-darkgreen" size="md" class="font-weight-bold" :to="{ name: 'str.create' }">
                        <b-icon icon="plus"></b-icon> Tambah File
                      </b-button>
                    </div>
                  </b-card>
                </b-col>
              </b-row>
            </b-card>
            <b-card>
              <b-row>
                <b-col>
                  <img src="@/assets/images/sertifikasi.png" width="15" class="img-fluid" />
                  <span class="pl-3"><b>Dukumen / Arsip Pribadi</b></span>
                </b-col>
              </b-row>
              <b-row class="pt-3">
                <b-col sm="4">
                  <b-card class="border">
                    <img src="@/assets/images/kanan.png" width="15" class="img-fluid" />
                    <span class="pl-3"><b>KTP</b></span>
                    <div class="pt-3">
                      <p class="text-muted mb-1"> {{ form.nomor_ijazah || '-' }} </p>
                      <div class="mb-2">
                        <b-icon class="text-danger" style="font-size: 20px;" icon="file-pdf-fill"></b-icon> <span class="text-primary">STR_AnneRosliana.pdf</span>
                      </div>
                      <b-button block variant="outline-darkgreen" size="md" class="font-weight-bold" :to="{ name: 'ijazah.create' }">
                        <b-icon icon="plus"></b-icon> Tambah File
                      </b-button>
                    </div>
                  </b-card>
                </b-col>
                <b-col sm="4">
                  <b-card class="border">
                    <img src="@/assets/images/kanan.png" width="15" class="img-fluid" />
                    <span class="pl-3"><b>Ijazah</b></span>
                    <div class="pt-3">
                      <p class="text-muted mb-1"> {{ form.nomor_ijazah || '-' }} </p>
                      <div class="mb-2">
                        <b-icon class="text-danger" style="font-size: 20px;" icon="file-pdf-fill"></b-icon> <span class="text-primary">STR_AnneRosliana.pdf</span>
                      </div>
                      <b-button block variant="outline-darkgreen" size="md" class="font-weight-bold" :to="{ name: 'ijazah.create' }">
                        <b-icon icon="plus"></b-icon> Tambah File
                      </b-button>
                    </div>
                  </b-card>
                </b-col>
                <b-col sm="4">
                  <b-card class="border">
                    <img src="@/assets/images/kanan.png" width="15" class="img-fluid" />
                    <span class="pl-3"><b>NPWP</b></span>
                    <div class="pt-3">
                      <p class="text-muted mb-1"> {{ form.nomor_ijazah || '-' }} </p>
                      <div class="mb-2">
                        <b-icon class="text-danger" style="font-size: 20px;" icon="file-pdf-fill"></b-icon> <span class="text-primary">STR_AnneRosliana.pdf</span>
                      </div>
                      <b-button block variant="outline-darkgreen" size="md" class="font-weight-bold" :to="{ name: 'ijazah.create' }">
                        <b-icon icon="plus"></b-icon> Tambah File
                      </b-button>
                    </div>
                  </b-card>
                </b-col>
                <b-col sm="4">
                  <b-card class="border">
                    <img src="@/assets/images/kanan.png" width="15" class="img-fluid" />
                    <span class="pl-3"><b>BPJS</b></span>
                    <div class="pt-3">
                      <p class="text-muted mb-1"> {{ form.nomor_ijazah || '-' }} </p>
                      <div class="mb-2">
                        <b-icon class="text-danger" style="font-size: 20px;" icon="file-pdf-fill"></b-icon> <span class="text-primary">STR_AnneRosliana.pdf</span>
                      </div>
                      <b-button block variant="outline-darkgreen" size="md" class="font-weight-bold" :to="{ name: 'ijazah.create' }">
                        <b-icon icon="plus"></b-icon> Tambah File
                      </b-button>
                    </div>
                  </b-card>
                </b-col>
                
              </b-row>
            </b-card>
          </div>
          <div v-if="tabIndex===2">
            <b-card>
              <b-row>
                <b-col>
                  <b-card>
                    <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
                    <span class="pl-3"><b>Riwayat Pendidikan</b></span>
                    <div class="mt-3 text-muted pl-4">
                      <div v-for="i in 3" class="mb-3">
                        <p class="text-darkgreen mb-1 font-weight-bold"> Institut Teknologi Bandung </p>
                        <p class="mb-1"> Doktor, Informatika </p>
                        <p class="mb-0"> 2022 </p>
                      </div>
                      <!-- <p class="text-darkgreen mb-1 font-weight-bold"> {{ form.peg_almamater_nama }} </p>
                      <p class="mb-1"> {{ form.kualifikasi_pendidikan }}, {{ form.pendidikan_nama }} </p>
                      <p class="mb-0"> 2022 </p> -->
                    </div>
                  </b-card>
                </b-col>
                <b-col>
                  <b-card>
                    <img src="@/assets/images/sertifikasi.png" width="15" class="img-fluid" />
                    <span class="pl-3"><b>Riwayat Golongan</b></span>
                    <div class="mt-3 pl-4">
                      <div v-for="i in 4" class="mb-3">
                        <p class="text-darkgreen mb-1 font-weight-bold"> III/a Penata Muda </p>
                        <p class="mb-1"> 01 April 2019 </p>
                        <p class="mb-0"> Kepala Bagian Mutasi dan Penilaian Kinerja Pegawai </p>
                      </div>
                      <!-- <p class="tex-green-400"> {{ form.golongan_nama }} {{ form.jabatan_fungsional_nama }} </p>
                      <p> {{ form.jabatan_nama }} </p> -->
                    </div>
                  </b-card>
                </b-col>
              </b-row>
            </b-card>
            <b-card>
              <img src="@/assets/images/pendidikan.png" width="15" class="img-fluid" />
              <span class="pl-3"><b>Riwayat Pendidikan</b></span>
              <div class="mt-3 text-muted pl-4">
                <div v-for="i in 3" class="mb-3">
                  <p class="text-darkgreen mb-1 font-weight-bold"> Manggala Informatika Ahli Utama pada Pusat Data dan Informasi Sekretariat Jenderal Kementerian Kesehatan  </p>
                  <p class="mb-1"> Februari 2022 </p>
                  <p class="mb-0"> Pusat Data dan Teknologi Informasi </p>
                </div>
              </div>
            </b-card>
          </div>
          <div v-if="tabIndex===3">
            Tab Kinerja
          </div>
        </Transition>
        
      </div>
    </template>




  </b-container>

</template>

<script>
import Form from 'vform'
import { mapGetters } from 'vuex'
import axios from 'axios'
import Swal from 'sweetalert2'
import moment from 'moment'
import { buildQueryParams } from '~/plugins/utils'


export default {
  scrollToTop: false,
  middleware: ['auth'],

  async asyncData({ params }) {
    let f2 = axios.get('settings')
    let f2resp = (await f2).data
    let form = f2resp.models

    // let f3 = axios.get('/jenis-jabatan', {
    //   params: {
    //     perpage: 'all',
    //     sortby: 'jenis_jabatan_nama'
    //   }
    // })
    // let f4 = axios.get('/jabatan', {
    //   params: {
    //     perpage: 'all',
    //     sortby: 'jabatan_nama'
    //   }
    // })
    // let f5 = axios.get('/jenjang-jabatan', {
    //   params: {
    //     perpage: 'all',
    //     sortby: 'jenjang_jabatan_nama'
    //   }
    // })
    // let f6 = axios.get('/unit-kerja', {
    //   params: {
    //     perpage: 'all',
    //     sortby: 'unit_kerja_nama'
    //   }
    // })
    // let f7 = axios.get('/spesialis', {
    //   params: {
    //     perpage: 'all',
    //     sortby: 'spesialis_nama'
    //   }
    // })
    // let f8 = axios.get('/golongan', {
    //   params: {
    //     perpage: 'all',
    //     sortby: 'golongan_nama'
    //   }
    // })
    // let f9 = axios.get('/kualifikasi-pendidikan', {
    //   params: {
    //     perpage: 'all',
    //     sortby: 'kualifikasi_pendidikan'
    //   }
    // })
    // let f10 = axios.get('/bidang', {
    //   params: {
    //     perpage: 'all',
    //     sortby: 'bidang_nama'
    //   }
    // })
    // let f11 = axios.get('/jenis-tenaga-kerja', {
    //   params: {
    //     perpage: 'all',
    //     sortby: 'jenis_tenaga_kerja_nama'
    //   }
    // })
    // let f12 = axios.get('/status-pegawai', {
    //   params: {
    //     perpage: 'all',
    //     sortby: 'status_kepegawaian'
    //   }
    // })
    // let f13 = axios.get('/pendidikan', {
    //   params: {
    //     perpage: 'all',
    //     sortby: 'pendidikan_nama'
    //   }
    // })
    // let f14 = axios.get('/str', {
    //   params: {
    //     perpage: 'all',
    //     sortby: 'nomor_str'
    //   }
    // })
    // let f15 = axios.get('/sip', {
    //   params: {
    //     perpage: 'all',
    //     sortby: 'nomor_sip'
    //   }
    // })
    // let f16 = axios.get('/ijazah', {
    //   params: {
    //     perpage: 'all',
    //     sortby: 'nomor_ijazah'
    //   }
    // })

    // let f3resp = (await f3).data
    // let f4resp = (await f4).data
    // let f5resp = (await f5).data
    // let f6resp = (await f6).data
    // let f7resp = (await f7).data
    // let f8resp = (await f8).data
    // let f9resp = (await f9).data
    // let f10resp = (await f10).data
    // let f11resp = (await f11).data
    // let f12resp = (await f12).data
    // let f13resp = (await f13).data
    // let f14resp = (await f14).data
    // let f15resp = (await f15).data
    // let f16resp = (await f16).data


    // let JJOptions = f3resp.data
    // let JBOptions = f4resp.data
    // let JENJOptions = f5resp.data
    // let UKEROptions = f6resp.data
    // let SPOptions = f7resp.data
    // let GOLOptions = f8resp.data
    // let KPOptions = f9resp.data
    // let BIDOptions = f10resp.data
    // let JTKOptions = f11resp.data
    // let SKOptions = f12resp.data
    // let PDOptions = f13resp.data
    // let STOptions = f14resp.data
    // let SIOptions = f15resp.data
    // let IJOptions = f16resp.data

    return {
      form,
      //   JJOptions,
      //   JBOptions,
      //   JENJOptions,
      //   UKEROptions,
      //   SPOptions,
      //   GOLOptions,
      //   KPOptions,
      //   BIDOptions,
      //   JTKOptions,
      //   SKOptions,
      //   PDOptions,
      //   STOptions,
      //   SIOptions,
      //   IJOptions
      // }
    }
  },

  data: () => ({
    loading: false,
    errors: {},
    tabIndex: 0,
    listDataPribadi: [
      { key: 'peg_telp_hp', imgUrl: 'telp.png' },
      { key: 'peg_email', imgUrl: 'email.png' },
      { key: 'nama_ruangan', imgUrl: 'building.png' },
      { key: 'peg_rumah_alamat', imgUrl: 'location.png' },
    ],
    tabsList: [
      { id: 0, name: 'Informasi Kepegawaian' },
      { id: 1, name: 'Dokumen / Arsip' },
      { id: 2, name: 'Riwayat' },
      { id: 3, name: 'Kinerja' },
    ]
  }),

  head() {
    return { title: this.$t('user') }
  },

  computed: mapGetters({
    user: 'auth/user'
  }),

  // created () {
  //   // Fill the form with user data.
  //   this.form.keys().forEach((key) => {
  //     this.form[key] = this.user[key]
  //   })
  // },

  methods: {
    linkClass(idx) {
      if (this.tabIndex === idx) {
        return ['bg-darkgreen', 'text-white']
      } else {
        return ['bg-white', 'text-dark']
      }
    },
    formatDate(value) {
      if (value) {
        return moment(String(value)).format('DDMMYYYY')
      }
    },
    async update() {
      this.loading = true
      try {
        let resp = (await axios.patch('pegawai/' + this.$route.params.id, this.form)).data
        this.form.patch('/setting/profile').then(({ data: user }) => {
          this.$store.dispatch('auth/updateUser', { user })
        })
        if (resp.success) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data berhasil disimpan',
            confirmButtonText: 'ok',
          })
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Gagal menyimpan data',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        }
        this.errors = {}
      } catch (err) {
        if (err.response && err.response.status == 422) {
          this.errors = err.response.data.errors
        }
      }
      this.loading = false
    },
    getErrorState(key) {
      if (this.errors[key]) {
        return false
      }
      return null
    },
    getErrorMessage(key) {
      if (this.errors[key]) {
        return this.errors[key].join(', ')
      }
      return null
    },
  }
}
</script>

<style scoped>
.box {
  width: 300px;
  height: 60px;
  border: 1px solid grey;
  border-radius: 0.25rem;
}
</style>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
