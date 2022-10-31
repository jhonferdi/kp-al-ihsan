<template>
  <b-container fluid>
    <b-card>

    
    <b-row class="my-1">
      <b-col sm="2"> 
        <div>
          <b-avatar rounded="sm" size="175px"></b-avatar>
        </div>
      </b-col>
      <b-col sm="6" class="mr-5">
        <div>
          <div>
            <h2> {{ form.peg_nama_lengkap }} </h2>
          </div>
          <div class="mt-3">
            <h5 class="text-gray-800"> {{ form.jabatan_nama }} </h5>
          </div>
          <div>
            <span class="badge badge-success" v-if="form.status_kerja == 'Aktif'">  {{ form.status_kepegawaian }} - {{ form.status_kerja }} </span>
            <span class="badge badge-danger" v-if="form.status_kerja != 'Aktif'">  {{ form.status_kepegawaian }} - {{ form.status_kerja }} </span>
            <b-row class="mt-4">
              <b-col>
                <div class="">
                  <b-input-group>
                    <b-input-group-prepend is-text>
                      <img
                    src="@/assets/images/telp.png"
                    width="15"
                    class="img-fluid"
                  />
                    </b-input-group-prepend>
                    <b-form-input type="text" v-model="form.peg_telp_hp" :state="getErrorState('peg_telp_hp')" placeholder="Masukan nomor telepon"></b-form-input>
                  </b-input-group>
                  <b-form-invalid-feedback v-if="getErrorState('peg_telp_hp') === false">
                    {{ getErrorMessage('peg_telp_hp') }}
                  </b-form-invalid-feedback>
                </div>
              </b-col>
              <b-col>
                <div>        
                    <b-input-group>
                      <b-input-group-prepend is-text>
                        <img
                      src="@/assets/images/email.png"
                      width="15"
                      class="img-fluid"
                    />
                      </b-input-group-prepend>
                      <b-form-input type="text" v-model="form.peg_email" :state="getErrorState('peg_email')" placeholder="Masukan alamat email"></b-form-input>
                    </b-input-group>
                    <b-form-invalid-feedback v-if="getErrorState('peg_email') === false">
                      {{ getErrorMessage('peg_email') }}
                    </b-form-invalid-feedback>
                </div>
              </b-col>
            </b-row>
            <b-row class="mt-3">
              <b-col>
                <div class="">
                  <img
                      src="@/assets/images/building.png"
                      width="15"
                      class="img-fluid"
                    />
                    <span class="pl-2"> {{ form.nama_ruangan }} </span>
                </div>
              </b-col>
              <b-col>
                <div class="">
                  <b-input-group>
                    <b-input-group-prepend is-text>
                      <img
                    src="@/assets/images/location.png"
                    width="15"
                    class="img-fluid"
                  />
                    </b-input-group-prepend>
                    <b-form-input type="text" v-model="form.peg_rumah_alamat" :state="getErrorState('peg_rumah_alamat')" placeholder="Masukan alamat email"></b-form-input>
                  </b-input-group>
                  <b-form-invalid-feedback v-if="getErrorState('peg_rumah_alamat') === false">
                    {{ getErrorMessage('peg_rumah_alamat') }}
                  </b-form-invalid-feedback>
                </div>
              </b-col>
            </b-row>
          </div>
        </div>
      </b-col>
      <b-col sm="3" class="">
        <div class="mb-5">
            <nuxt-link :to="{ name: 'settings.profile', params: { id: form.peg_id }}" class="mr-2">
              <b-button variant="primary" @click="save" :disabled="loading"><b-icon icon="pencil"></b-icon> Save Profil</b-button>
            </nuxt-link>
            <b-button variant="success"><b-icon icon="printer"></b-icon>  Cetak Profil</b-button>
        </div>
        <div class="pt-5 ml-5 pl-4">
          <span> TMT Masuk : {{ form.peg_tmt | formatDate }} </span>
        </div>
      </b-col>
      </b-row>
    </b-card>
      <template>
        <div>
            <b-tabs v-model="tabIndex" card pills>
              <b-tab title="Informasi Kepegawaian" :title-link-class="linkClass(0)">
                <b-row>
                  <b-col sm="9">
                <b-card>    
                      <b-row>
                        <b-col sm="6" class="mt-5 ml-4">
                          <b-card style="max-width: 25rem;" border-variant="dark">
                          <div class="ml-4">
                          <img
                                src="@/assets/images/profil.png"
                                width="17"
                                class="img-fluid"
                              />
                              <span class="pl-2"><b>Profile</b></span>
                            </div>
                            <div class="pl-4 pt-2 mt-3">
                              <span>Nama Lengkap
                                <b-form-input id="input-nama-lengkap" type="text" v-model="form.peg_nama_lengkap" :state="getErrorState('peg_nama_lengkap')">
                                </b-form-input>
                                <b-form-invalid-feedback v-if="getErrorState('peg_nama_lengkap') === false">
                                  {{ getErrorMessage('peg_nama_lengkap') }}
                                </b-form-invalid-feedback>
                              </span> 
                            </div>
                            <div class="pl-4 pt-2">
                              <span>NIP/NIPT/NIK
                                <b-form-input id="input-nip" type="text" v-model="form.peg_nip_nipt_nik" :state="getErrorState('peg_nip_nipt_nik')">
                                </b-form-input>
                                <b-form-invalid-feedback v-if="getErrorState('peg_nip_nipt_nik') === false">
                                  {{ getErrorMessage('peg_nip_nipt_nik') }}
                                </b-form-invalid-feedback>
                              </span> 
                            </div>
                            <div class="pl-4 pt-2">
                              <span>Tempat Tanggal Lahir
                                <b-form-input id="input-tempat-lahir" type="text" v-model="form.peg_lahir_tempat" :state="getErrorState('peg_lahir_tempat')">
                                </b-form-input>
                                <input
                                  type="date"
                                  id="input-tanggal-lahir"
                                  class="bg-white border px-2 py-1 rounded"
                                  v-model="form.peg_lahir_tanggal"
                                /> 
                                <b-form-invalid-feedback v-if="getErrorState('peg_lahir_tempat') === false">
                                  {{ getErrorMessage('peg_lahir_tempat') }}
                                </b-form-invalid-feedback>
                              </span> 
                            </div>
                            <div class="pl-4 pt-2">
                              <span>Jenis Kelamin
                                <span v-if="form.peg_jenis_kelamin == 'L'"><p class="pt-1"><b>Laki-laki</b></p></span>
                                <span v-if="form.peg_jenis_kelamin == 'P'"><p class="pt-1"><b>Perempuan</b></p></span>
                              </span> 
                            </div>
                          </b-card>
                          <b-card border-variant="dark" style="max-width: 25rem;">
                          <div class="mt-5 ml-4">
                            <img
                                src="@/assets/images/informasi.png"
                                width="17"
                                class="img-fluid"
                              />
                              <span class="pl-2"><b>Informasi Lainnya</b></span>
                          </div>  
                            <div class="pl-4 pt-2 mt-3">
                              <span>Nomor KTP
                                <b-form-input id="input-ktp" type="text" v-model="form.peg_ktp" :state="getErrorState('peg_ktp')">
                                </b-form-input>
                                <b-form-invalid-feedback v-if="getErrorState('peg_ktp') === false">
                                  {{ getErrorMessage('peg_ktp') }}
                                </b-form-invalid-feedback>
                              </span> 
                            </div>   
                            <div class="pl-4 pt-2">
                              <span>NPWP
                                <b-form-input id="input-peg-npwp" type="text" v-model="form.peg_npwp" :state="getErrorState('peg_npwp')">
                                </b-form-input>
                                <b-form-invalid-feedback v-if="getErrorState('peg_npwp') === false">
                                  {{ getErrorMessage('peg_npwp') }}
                                </b-form-invalid-feedback>
                              </span> 
                            </div>
                            <div class="pl-4 pt-2">
                              <span>No. BPJS
                                <b-form-input id="input-bpjs" type="text" v-model="form.peg_bpjs" :state="getErrorState('peg_bpjs')">
                                </b-form-input>
                                <b-form-invalid-feedback v-if="getErrorState('peg_bpjs') === false">
                                  {{ getErrorMessage('peg_bpjs') }}
                                </b-form-invalid-feedback>
                              </span> 
                            </div>
                            <div class="pl-4 pt-2">
                              <span>Masa Kerja
                                <p class="pt-1"><b>{{ form.peg_masa_kerja }} Tahun</b></p>
                              </span> 
                            </div>
                          </b-card>
                        </b-col>
                        <b-col sm="5" class="mt-5 ml-4">
                          <b-card border-variant="dark" style="max-width: 25rem;">
                          <div class="ml-4">
                          <img
                                src="@/assets/images/ruangan.png"
                                width="17"
                                class="img-fluid"
                              />
                              <span class="pl-2"><b>Ruangan</b></span>
                            </div>
                                <div class="pl-4 pt-2 mt-3">
                                  <span>Ruangan
                                    <p class="pt-1"><b>{{ form.nama_ruangan }}</b></p>
                                  </span> 
                                </div>
                                <div class="pl-4 pt-2">
                                  <span>Unit Kerja
                                    <p class="pt-1"><b>{{ form.unit_kerja_nama }}</b></p>
                                  </span> 
                                </div> 
                                <div class="pl-4 pt-2">
                                  <span>Spesialis
                                    <p class="pt-1"><b>{{ form.spesialis_nama }}</b></p>
                                  </span> 
                                </div>
                              </b-card>
                              <b-card border-variant="dark" style="max-width: 25rem;">
                                <div class="ml-4 mt-5 pt-3">
                                <img
                                src="@/assets/images/jabatan.jpg"
                                width="17"
                                class="img-fluid"
                                />
                                <span class="pl-2"><b>Jabatan</b></span>
                              </div> 
                                <div class="pl-4 pt-2 mt-3">
                                  <span>Nama Jabatan
                                    <p class="pt-1"><b>{{ form.jabatan_nama }}</b></p>
                                  </span> 
                                </div>
                                <div class="pl-4 pt-2">
                                  <span>Jenis Jabatan
                                    <p class="pt-1"><b>{{ form.jenis_jabatan_nama }}</b></p>
                                  </span> 
                                </div>    
                                <div class="pl-4 pt-2">
                                  <span>Jabatan Fungsional
                                    <p class="pt-1"><b>{{ form.jabatan_fungsional_nama }}</b></p>
                                  </span> 
                                </div>
                                <div class="pl-4 pt-2">
                                  <span>Jenjang Jabatan
                                    <p class="pt-1"><b>{{ form.jenjang_jabatan_nama }}</b></p>
                                  </span> 
                                </div>
                                <div class="pl-4 pt-2">
                                  <span>Golongan
                                    <p class="pt-1"><b>{{ form.golongan_nama }}</b></p>
                                  </span> 
                                </div>
                              </b-card>
                        </b-col>
                      </b-row>
                    </b-card>
                    <b-card style="max-width: 65rem;">
                      <div class="ml-4 mt-2">
                      <img
                        src="@/assets/images/sertifikasi.png"
                        width="15"
                        class="img-fluid"
                        />
                        <span class="pl-3"><b>Sertifikasi</b></span>
                      </div>
                      <b-row class="pt-3 pl-5">
                        <b-col>
                          <b-card>
                          <img
                          src="@/assets/images/sertifikasi.png"
                          width="15"
                          class="img-fluid"
                          />
                          <span class="pl-2"><b>Pelatihan</b></span>
                        </b-card>
                        </b-col>
                        <b-col>
                          <b-card>
                          <img
                          src="@/assets/images/sertifikasi.png"
                          width="15"
                          class="img-fluid"
                          />
                          <span class="pl-2"><b>Pelatihan</b></span>
                        </b-card>
                        </b-col>
                      </b-row>
                    </b-card>
                    
                  </b-col>
                  <b-col>
                    <b-card>
                      <img
                        src="@/assets/images/kanan.png"
                        width="15"
                        class="img-fluid"
                        />
                        <span class="pl-3"><b>Tentang Saya</b></span>
                    </b-card>
                    <b-card>
                      <img
                        src="@/assets/images/kanan.png"
                        width="15"
                        class="img-fluid"
                        />
                        <span class="pl-3"><b>SIP</b></span>
                        <div class="pl-4 pt-2 mt-3">
                          <span>
                            <p class="text-blue-500 pt-1"><b>{{ form.nomor_sip }}</b></p>
                            <p>Berlaku s.d {{ form.tanggal_kadaluarsa_sip | formatDate }}</p>
                            <div class="pr-1">
                              <span class="" v-if="form.status_sip == 1">
                                  <img
                                    src="@/assets/images/aktif.png"
                                    width="15"
                                    class="img-fluid"
                                    />
                                </span>
                                <span class="" v-if="form.status_sip == 0">
                                  <img
                                    src="@/assets/images/tidakaktif.png"
                                    width="230"
                                    class="img-fluid"
                                    />
                                </span>
                              </div>
                          </span> 
                        </div>
                    </b-card>
                    <b-card>
                      <img
                        src="@/assets/images/kanan.png"
                        width="15"
                        class="img-fluid"
                        />
                        <span class="pl-3"><b>STR</b></span>
                        <div class="pl-4 pt-2 mt-3">
                          <span>
                            <p class="text-blue-500 pt-1"><b>{{ form.nomor_str }}</b></p>
                            <p>Berlaku s.d {{ form.tanggal_kadaluarsa_str | formatDate }}</p>
                            <div class="pr-1">
                            <span class="" v-if="form.status_str == 1">
                                <img
                                  src="@/assets/images/aktif.png"
                                  width="15"
                                  class="img-fluid"
                                  />
                              </span>
                              <span class="" v-if="form.status_str == 0">
                                <img
                                  src="@/assets/images/tidakaktif.png"
                                  width="230"
                                  class="img-fluid"
                                  />
                              </span>
                            </div>
                          </span> 
                        </div>
                    </b-card>
                    <b-card>
                      <img
                        src="@/assets/images/kanan.png"
                        width="15"
                        class="img-fluid"
                        />
                        <span class="pl-3"><b>Nama Atasan</b></span>
                        <div class="pl-4 pt-2 mt-3">
                          <span>
                            <p>Direktur</p>
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
                    <img
                        src="@/assets/images/sertifikasi.png"
                        width="15"
                        class="img-fluid"
                        />
                        <span class="pl-3"><b>Dukumen / Arsip Kepegawaian</b></span>
                      </b-col>
                    </b-row>
                    <b-row class="pt-3">
                      <b-col sm="6" class="ml-5">
                        <b-card>
                        <img
                        src="@/assets/images/kanan.png"
                        width="15"
                        class="img-fluid"
                        />
                        <span class="pl-3"><b>SIP</b></span>
                        <div class="pt-3">
                        <p> {{ form.nomor_sip }} </p>
                        <b-button
                          variant="outline-success"
                          size="lg"
                          :to="{ name: 'sip.create' }">
                          <b-icon icon="plus"></b-icon> Tambah File
                        </b-button>
                      </div>
                      </b-card>
                      </b-col>
                      <b-col sm="5">
                        <b-card>
                        <img
                        src="@/assets/images/kanan.png"
                        width="15"
                        class="img-fluid"
                        />
                        <span class="pl-3"><b>STR</b></span>
                        <div class="pt-3">
                        <p> {{ form.nomor_str }} </p>
                        <b-button
                          variant="outline-success"
                          size="lg"
                          :to="{ name: 'str.create' }">
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
                    <img
                        src="@/assets/images/sertifikasi.png"
                        width="15"
                        class="img-fluid"
                        />
                        <span class="pl-3"><b>Dukumen / Arsip Pribadi</b></span>
                      </b-col>
                    </b-row>
                    <b-row class="pt-3">
                      <b-col sm="6" class="ml-5">
                        <b-card>
                        <img
                        src="@/assets/images/kanan.png"
                        width="15"
                        class="img-fluid"
                        />
                        <span class="pl-3"><b>Ijazah</b></span>
                        <div class="pt-3">
                        <p> {{ form.nomor_ijazah }} </p>
                        <b-button
                          variant="outline-success"
                          size="lg"
                          :to="{ name: 'ijazah.create' }">
                          <b-icon icon="plus"></b-icon> Tambah File
                        </b-button>
                      </div>
                      </b-card>
                      </b-col>
                    </b-row>
              </b-card>
              </b-tab>
              <b-tab title="Riwayat" :title-link-class="linkClass(2)">
                <b-card>
                  <b-row>
                    <b-col>
                      <b-card>
                        <img
                        src="@/assets/images/pendidikan.png"
                        width="15"
                        class="img-fluid"
                        />
                        <span class="pl-3"><b>Riwayat Pendidikan</b></span>
                        <div class="mt-3 pl-4">
                          <b-form-input id="input-almamater" type="text" v-model="form.peg_almamater_nama" :state="getErrorState('peg_almamater_nama')">
                          </b-form-input>
                          <b-form-invalid-feedback v-if="getErrorState('peg_almamater_nama') === false">
                            {{ getErrorMessage('peg_almamater_nama') }}
                          </b-form-invalid-feedback>
                          <v-select id="input-kualifikasi-pendidikan" :options="KPOptions" :reduce="item => item.kualifikasi_pendidikan_id"
                            label="kualifikasi_pendidikan" placeholder="Pilih Kualifikasi Pendidikan" v-model="form.kualifikasi_pendidikan_id"
                            :class="{ 'is-invalid': !getErrorState('kualifikasi_pendidikan_id') }">
                          </v-select>
                          <b-form-invalid-feedback v-if="getErrorState('kualifikasi_pendidikan_id') === false">
                            {{ getErrorMessage('kualifikasi_pendidikan_id') }}
                          </b-form-invalid-feedback>
                          <v-select id="input-pendidikan" :options="PDOptions" :reduce="item => item.pendidikan_id"
                            label="pendidikan_nama" placeholder="Pilih Pendidikan" v-model="form.pendidikan_id"
                            :class="{ 'is-invalid': !getErrorState('pendidikan_id') }">
                          </v-select>
                          <b-form-invalid-feedback v-if="getErrorState('pendidikan_id') === false">
                            {{ getErrorMessage('pendidikan_id') }}
                          </b-form-invalid-feedback>
                        </div>
                      </b-card>
                    </b-col>
                    <b-col>
                      <b-card>
                        <img
                        src="@/assets/images/sertifikasi.png"
                        width="15"
                        class="img-fluid"
                        />
                        <span class="pl-3"><b>Riwayat Golongan</b></span>
                        <div class="mt-3 pl-4">
                          <p class="tex-green-400"> {{ form.golongan_nama }} {{ form.jabatan_fungsional_nama }} </p>
                          <p> {{ form.jabatan_nama }} </p>
                        </div>
                      </b-card>
                    </b-col>
                  </b-row>
                </b-card>
              </b-tab>
              <b-tab title="Kinerja" :title-link-class="linkClass(3)">Ciluk Ba!</b-tab>
            </b-tabs>
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

  let f3 = axios.get('/jenis-jabatan', {
    params: {
      perpage: 'all',
      sortby: 'jenis_jabatan_nama'
    }
  })
  let f4 = axios.get('/jabatan', {
    params: {
      perpage: 'all',
      sortby: 'jabatan_nama'
    }
  })
  let f5 = axios.get('/jenjang-jabatan', {
    params: {
      perpage: 'all',
      sortby: 'jenjang_jabatan_nama'
    }
  })
  let f6 = axios.get('/unit-kerja', {
    params: {
      perpage: 'all',
      sortby: 'unit_kerja_nama'
    }
  })
  let f7 = axios.get('/spesialis', {
    params: {
      perpage: 'all',
      sortby: 'spesialis_nama'
    }
  })
  let f8 = axios.get('/golongan', {
    params: {
      perpage: 'all',
      sortby: 'golongan_nama'
    }
  })
  let f9 = axios.get('/kualifikasi-pendidikan', {
    params: {
      perpage: 'all',
      sortby: 'kualifikasi_pendidikan'
    }
  })
  let f10 = axios.get('/bidang', {
    params: {
      perpage: 'all',
      sortby: 'bidang_nama'
    }
  })
  let f11 = axios.get('/jenis-tenaga-kerja', {
    params: {
      perpage: 'all',
      sortby: 'jenis_tenaga_kerja_nama'
    }
  })
  let f12 = axios.get('/status-pegawai', {
    params: {
      perpage: 'all',
      sortby: 'status_kepegawaian'
    }
  })
  let f13 = axios.get('/pendidikan', {
    params: {
      perpage: 'all',
      sortby: 'pendidikan_nama'
    }
  })
  let f14 = axios.get('/str', {
    params: {
      perpage: 'all',
      sortby: 'nomor_str'
    }
  })
  let f15 = axios.get('/sip', {
    params: {
      perpage: 'all',
      sortby: 'nomor_sip'
    }
  })
  let f16 = axios.get('/ijazah', {
    params: {
      perpage: 'all',
      sortby: 'nomor_ijazah'
    }
  })

  let f3resp = (await f3).data
  let f4resp = (await f4).data
  let f5resp = (await f5).data
  let f6resp = (await f6).data
  let f7resp = (await f7).data
  let f8resp = (await f8).data
  let f9resp = (await f9).data
  let f10resp = (await f10).data
  let f11resp = (await f11).data
  let f12resp = (await f12).data
  let f13resp = (await f13).data
  let f14resp = (await f14).data
  let f15resp = (await f15).data
  let f16resp = (await f16).data

  
  let JJOptions = f3resp.data
  let JBOptions = f4resp.data
  let JENJOptions = f5resp.data
  let UKEROptions = f6resp.data
  let SPOptions = f7resp.data
  let GOLOptions = f8resp.data
  let KPOptions = f9resp.data
  let BIDOptions = f10resp.data
  let JTKOptions = f11resp.data
  let SKOptions = f12resp.data
  let PDOptions = f13resp.data
  let STOptions = f14resp.data
  let SIOptions = f15resp.data
  let IJOptions = f16resp.data

  return {
    form,
    JJOptions,
    JBOptions,
    JENJOptions,
    UKEROptions,
    SPOptions,
    GOLOptions,
    KPOptions,
    BIDOptions,
    JTKOptions,
    SKOptions,
    PDOptions,
    STOptions,
    SIOptions,
    IJOptions

  }
},

data: () => ({
  loading: false,
  errors: {},
  tabIndex: 0
}),

head () {
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
        return ['bg-success', 'text-light']
      } else {
        return ['bg-light', 'text-dark']
      }
    },  
  formatDate(value){
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


