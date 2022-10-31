<template>
  <div>
    <page-header :title="`${$metaInfo.title}`" :items="[
      {text: 'Pegawai', to: { name: 'pegawai' }},
      {text: 'Profil - ' + form.peg_nama_lengkap, to: { name: 'pegawai.view', params: {id: form.peg_id} }},
      {text: $metaInfo.title, active: true},
    ]" />
  <b-card title="Edit Pegawai" class="my-4">
    <br>
    <b-container fluid>
      <b-row class="my-2">
        <b-col sm="3">
          <label for="input-nama-lengkap">Nama Lengkap Pegawai:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-nama-lengkap" type="text" v-model="form.peg_nama_lengkap"
            :state="getErrorState('peg_nama_lengkap')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('peg_nama_lengkap') === false">
            {{ getErrorMessage('peg_nama_lengkap') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-gelar-depan">Gelar Depan:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-gelar-depan" type="text" v-model="form.peg_gelar_depan"
            :state="getErrorState('peg_gelar_depan')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('peg_gelar_depan') === false">
            {{ getErrorMessage('peg_gelar_depan') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-gelar-belakang">Gelar Belakang:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-gelar-belakang" type="text" v-model="form.peg_gelar_belakang"
            :state="getErrorState('peg_gelar_belakang')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('peg_gelar_belakang') === false">
            {{ getErrorMessage('peg_gelar_belakang') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-nama-tanpa-gelar">Nama Tanpa Gelar:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-nama-tanpa-gelar" type="text" v-model="form.peg_nama_tanpa_gelar"
            :state="getErrorState('peg_nama_tanpa_gelar')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('peg_nama_tanpa_gelar') === false">
            {{ getErrorMessage('peg_nama_tanpa_gelar') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-jenis-jabatan">Jenis Jabatan :</label>
        </b-col>
        <b-col sm="9">
          <v-select id="input-jenis-jabatan" :options="JJOptions" :reduce="item => item.jenis_jabatan_id"
            label="jenis_jabatan_nama" placeholder="Pilih Jenis Jabatan" v-model="form.jenis_jabatan_id"
            :class="{ 'is-invalid': !getErrorState('jenis_jabatan_id') }">
          </v-select>
          <p style="color:red;" v-if="getErrorState('jenis_jabatan_id') === false">
            {{ getErrorMessage('jenis_jabatan_id') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-jabatan">Jabatan :</label>
        </b-col>
        <b-col sm="9">
          <v-select id="input-jabatan" :options="JBOptions" :reduce="item => item.jabatan_id" label="jabatan_nama"
            placeholder="Pilih Jabatan" v-model="form.jabatan_id"
            :class="{ 'is-invalid': !getErrorState('jabatan_id') }">
          </v-select>
          <p style="color:red;" v-if="getErrorState('jabatan_id') === false">
            {{ getErrorMessage('jabatan_id') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-jenjang-jabatan">Jenjang Jabatan :</label>
        </b-col>
        <b-col sm="9">
          <v-select id="input-jenjang-jabatan" :options="JENJOptions" :reduce="item => item.jenjang_jabatan_id"
            label="jenjang_jabatan_nama" placeholder="Pilih Jenjang Jabatan" v-model="form.jenjang_jabatan_id"
            :class="{ 'is-invalid': !getErrorState('jenjang_jabatan_id') }">
          </v-select>
          <p style="color:red;" v-if="getErrorState('jenjang_jabatan_id') === false">
            {{ getErrorMessage('jenjang_jabatan_id') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-unit-kerja">Unit Kerja :</label>
        </b-col>
        <b-col sm="9">
          <v-select id="input-unit-kerja" :options="UKEROptions" :reduce="item => item.unit_kerja_id"
            label="unit_kerja_nama" placeholder="Pilih Unit Kerja" v-model="form.unit_kerja_id"
            :class="{ 'is-invalid': !getErrorState('unit_kerja_id') }">
          </v-select>
          <p style="color:red;" v-if="getErrorState('unit_kerja_id') === false">
            {{ getErrorMessage('unit_kerja_id') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-spesialis">Spesialis :</label>
        </b-col>
        <b-col sm="9">
          <v-select id="input-spesialis" :options="SPOptions" :reduce="item => item.spesialis_id" label="spesialis_nama"
            placeholder="Pilih Spesialis" v-model="form.spesialis_id"
            :class="{ 'is-invalid': !getErrorState('spesialis_id') }">
          </v-select>
          <p style="color:red;" v-if="getErrorState('spesialis_id') === false">
            {{ getErrorMessage('spesialis_id') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-golongan">Golongan :</label>
        </b-col>
        <b-col sm="9">
          <v-select id="input-golongan" :options="GOLOptions" :reduce="item => item.golongan_id" label="golongan_nama"
            placeholder="Pilih Golongan" v-model="form.golongan_id"
            :class="{ 'is-invalid': !getErrorState('golongan_id') }">
          </v-select>
          <p style="color:red;" v-if="getErrorState('golongan_id') === false">
            {{ getErrorMessage('golongan_id') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-kualifikasi-pendidikan">Kualifikasi Pendidikan :</label>
        </b-col>
        <b-col sm="9">
          <v-select id="input-kualifikasi-pendidikan" :options="KPOptions"
            :reduce="item => item.kualifikasi_pendidikan_id" label="kualifikasi_pendidikan"
            placeholder="Pilih Kualifikasi Pendidikan" v-model="form.kualifikasi_pendidikan_id"
            :class="{ 'is-invalid': !getErrorState('kualifikasi_pendidikan_id') }">
          </v-select>
          <p style="color:red;" v-if="getErrorState('kualifikasi_pendidikan_id') === false">
            {{ getErrorMessage('kualifikasi_pendidikan_id') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-bidang">Bidang :</label>
        </b-col>
        <b-col sm="9">
          <v-select id="input-bidang" :options="BIDOptions" :reduce="item => item.bidang_id" label="bidang_nama"
            placeholder="Pilih Bidang" v-model="form.bidang_id" :class="{ 'is-invalid': !getErrorState('bidang_id') }">
          </v-select>
          <p style="color:red;" v-if="getErrorState('bidang_id') === false">
            {{ getErrorMessage('bidang_id') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-jenis-tenaga-kerja">Jenis Tenaga Kerja :</label>
        </b-col>
        <b-col sm="9">
          <v-select id="input-jenis-tenaga-kerja" :options="JTKOptions" :reduce="item => item.jenis_tenaga_kerja_id"
            label="jenis_tenaga_kerja_nama" placeholder="Pilih Jenis Tenaga Kerja" v-model="form.jenis_tenaga_kerja_id"
            :class="{ 'is-invalid': !getErrorState('jenis_tenaga_kerja_id') }">
          </v-select>
          <p style="color:red;" v-if="getErrorState('jenis_tenaga_kerja_id') === false">
            {{ getErrorMessage('jenis_tenaga_kerja_id') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-status-pegawai">Status Kepegawaian :</label>
        </b-col>
        <b-col sm="9">
          <v-select id="input-status-pegawai" :options="SKOptions" :reduce="item => item.status_kepegawaian_id"
            label="status_kepegawaian" placeholder="Pilih Status Kepegawaian" v-model="form.status_kepegawaian_id"
            :class="{ 'is-invalid': !getErrorState('status_kepegawaian_id') }">
          </v-select>
          <p style="color:red;" v-if="getErrorState('status_kepegawaian_id') === false">
            {{ getErrorMessage('status_kepegawaian_id') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-pendidikan">Pendidikan :</label>
        </b-col>
        <b-col sm="9">
          <v-select id="input-pendidikan" :options="PDOptions" :reduce="item => item.pendidikan_id"
            label="pendidikan_nama" placeholder="Pilih Pendidikan" v-model="form.pendidikan_id"
            :class="{ 'is-invalid': !getErrorState('pendidikan_id') }">
          </v-select>
          <p style="color:red;" v-if="getErrorState('pendidikan_id') === false">
            {{ getErrorMessage('pendidikan_id') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-almamater">Almamater:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-almamater" type="text" v-model="form.peg_almamater_nama"
            :state="getErrorState('peg_almamater_nama')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('peg_almamater_nama') === false">
            {{ getErrorMessage('peg_almamater_nama') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-jenis-kelamin">Jenis Kelamin :</label>
        </b-col>
        <b-col sm="9">
          <v-select v-model="form.peg_jenis_kelamin" :options="JKOptions" :reduce="item => item.value" label="text"
            :state="getErrorState('peg_jenis_kelamin')">
          </v-select>
          <p style="color:red;" v-if="getErrorState('peg_jenis_kelamin') === false">
            {{ getErrorMessage('peg_jenis_kelamin') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-tempat-lahir">Tempat Lahir:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-tempat-lahir" type="text" v-model="form.peg_lahir_tempat"
            :state="getErrorState('peg_lahir_tempat')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('peg_lahir_tempat') === false">
            {{ getErrorMessage('peg_lahir_tempat') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-tanggal-lahir">Tanggal Lahir:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="peg_lahir_tanggal" type="date" v-model="form.peg_lahir_tanggal"
            :state="getErrorState('peg_lahir_tanggal')" />
          <p style="color:red;" v-if="getErrorState('peg_lahir_tanggal') === false">
            {{ getErrorMessage('peg_lahir_tanggal') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-agama">Agama:</label>
        </b-col>
        <b-col sm="9">
          <v-select v-model="form.peg_agama" :options="AOptions" :reduce="item => item.value" label="text"
            :state="getErrorState('peg_agama')">
          </v-select>
          <p style="color:red;" v-if="getErrorState('peg_agama') === false">
            {{ getErrorMessage('peg_agama') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-golongan-darah">Golongan Darah:</label>
        </b-col>
        <b-col sm="9">
          <v-select v-model="form.peg_golongan_darah" :options="GOptions" :reduce="item => item.value" label="text"
            :state="getErrorState('peg_golongan_darah')">
          </v-select>
          <p style="color:red;" v-if="getErrorState('peg_golongan_darah') === false">
            {{ getErrorMessage('peg_golongan_darah') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-nip">NIP/NIPT/NIK:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-nip" type="text" v-model="form.peg_nip_nipt_nik"
            :state="getErrorState('peg_nip_nipt_nik')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('peg_nip_nipt_nik') === false">
            {{ getErrorMessage('peg_nip_nipt_nik') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-ktp">KTP:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-ktp" type="text" v-model="form.peg_ktp" :state="getErrorState('peg_ktp')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('peg_ktp') === false">
            {{ getErrorMessage('peg_ktp') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-no-kk">No Kartu Keluarga :</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-no-kk" type="text" v-model="form.peg_no_kk" :state="getErrorState('peg_no-kk')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('peg_no-kk') === false">
            {{ getErrorMessage('peg_no-kk') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-bpjs">BPJS Kesehatan:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-bpjs" type="text" v-model="form.peg_bpjs" :state="getErrorState('peg_bpjs')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('peg_bpjs') === false">
            {{ getErrorMessage('peg_bpjs') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-bpjs_ketenagakerjaan">BPJS Ketenagakerjaan:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-bpjs_ketenagakerjaan" type="text" v-model="form.peg_bpjs_ketenagakerjaan"
            :state="getErrorState('peg_bpjs_ketenagakerjaan')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('peg_bpjs_ketenagakerjaan') === false">
            {{ getErrorMessage('peg_bpjs_ketenagakerjaan') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-nomor_rekening">Nomor Rekening BJB Gaji/Intensif:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-nomor_rekening" type="text" v-model="form.peg_nomor_rekening"
            :state="getErrorState('peg_nomor_rekening')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('peg_nomor_rekening') === false">
            {{ getErrorMessage('peg_nomor_rekening') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-peg-npwp">NPWP:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-peg-npwp" type="text" v-model="form.peg_npwp" :state="getErrorState('peg_npwp')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('peg_npwp') === false">
            {{ getErrorMessage('peg_npwp') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-tmt">TMT :</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="peg_tmt" type="date" v-model="form.peg_tmt" :state="getErrorState('peg_tmt')" />
          <p style="color:red;" v-if="getErrorState('peg_tmt') === false">
            {{ getErrorMessage('peg_tmt') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-tmt-gol">TMT Golongan Akhir :</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="peg_tmt_golongan_akhir" type="date" v-model="form.peg_tmt_golongan_akhir"
            :state="getErrorState('peg_tmt_golongan_akhir')" />
          <p style="color:red;" v-if="getErrorState('peg_tmt_golongan_akhir') === false">
            {{ getErrorMessage('peg_tmt_golongan_akhir') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-status-kerja">Status Kerja:</label>
        </b-col>
        <b-col sm="9">
          <b-form-select id="input-status-pegawai" label="status_kepegawaian" v-model="form.peg_status_kerja">
            <option disable value="Pilih Status Kerja" :state="getErrorState('peg_status_kerja')"></option>
              <option value="Aktif">Aktif</option>
              <option value="Tidak Aktif">Tidak Aktif</option>
          </b-form-select>
          <!-- <b-form-input id="input-status-kerja" type="text" v-model="form.peg_status_kerja"
            :state="getErrorState('peg_status_kerja')">
          </b-form-input> -->
          <p style="color:red;" v-if="getErrorState('peg_status_kerja') === false">
            {{ getErrorMessage('peg_status_kerja') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-status-pernikahan">Status Pernikahan:</label>
        </b-col>
        <b-col sm="9">
          <v-select id="input-status-pernikahan" type="text" v-model="form.peg_status_pernikahan" :options="SOptions"
            :reduce="item => item.value" label="text" :state="getErrorState('peg_status_pernikahan')">
          </v-select>
          <p style="color:red;" v-if="getErrorState('peg_status_pernikahan') === false">
            {{ getErrorMessage('peg_status_pernikahan') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-sk">SK Pengangkatan:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-sk" type="text" v-model="form.peg_sk_pengangkatan"
            :state="getErrorState('peg_sk_pengangkatan')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('peg_sk_pengangkatan') === false">
            {{ getErrorMessage('peg_sk_pengangkatan') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-telp">Nomor Telp:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-telp" type="text" v-model="form.peg_telp_hp" :state="getErrorState('peg_telp_hp')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('peg_telp_hp') === false">
            {{ getErrorMessage('peg_telp_hp') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-email">Email:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-email" type="text" v-model="form.peg_email" :state="getErrorState('peg_email')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('peg_email') === false">
            {{ getErrorMessage('peg_email') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <fieldset class="custom">
          <legend class="custom">Alamat Saat Ini</legend>
          <b-row>
            <b-col sm="3">
              <label for="input-alamat">Alamat:</label>
            </b-col>
            <b-col sm="9">
              <b-form-input id="input-alamat" type="text" v-model="form.peg_rumah_alamat"
                :state="getErrorState('peg_rumah_alamat')">
              </b-form-input>
              <p style="color:red;" v-if="getErrorState('peg_rumah_alamat') === false">
                {{ getErrorMessage('peg_rumah_alamat') }}
              </p>
            </b-col>
            <div style="margin-bottom:5%;"></div>
            <b-col sm="3">
              <label for="input-provinsi">Provinsi:</label>
            </b-col>
            <b-col sm="9">
              <b-form-select class="font-size-13" v-model="form.prov_id" :options="PROVOptions" value-field="id"
                text-field="nama" :state="getErrorState('prov_id')">
              </b-form-select>
              <p style="color:red;" v-if="getErrorState('prov_id') === false">
                {{ getErrorMessage('prov_id') }}
              </p>
            </b-col>
            <div style="margin-bottom:5%;"></div>
            <b-col sm="3">
              <label for="input-kota">Kota/Kabupaten:</label>
            </b-col>
            <b-col sm="9">
              <b-form-select class="font-size-13" v-model="form.kabkot_id" :options="KABOptions" value-field="id"
                text-field="nama" :state="getErrorState('kabkot_id')">
              </b-form-select>
              <p style="color:red;" v-if="getErrorState('kabkot_id') === false">
                {{ getErrorMessage('kabkot_id') }}
              </p>
            </b-col>
            <div style="margin-bottom:5%;"></div>
            <b-col sm="3">
              <label for="input-kec">Kecamatan:</label>
            </b-col>
            <b-col sm="9">
              <b-form-select class="font-size-13" v-model="form.kec_id" :options="KECOptions" value-field="id"
                text-field="nama" :state="getErrorState('kec_id')">
              </b-form-select>
              <p style="color:red;" v-if="getErrorState('kec_id') === false">
                {{ getErrorMessage('kec_id') }}
              </p>
            </b-col>
            <div style="margin-bottom:5%;"></div>
            <b-col sm="3">
              <label for="input-desa">Kelurahan/Desa:</label>
            </b-col>
            <b-col sm="9">
              <b-form-select class="font-size-13" v-model="form.keldes_id" :options="KELOptions" value-field="id"
                text-field="nama" :state="getErrorState('keldes_id')">
              </b-form-select>
              <p style="color:red;" v-if="getErrorState('keldes_id') === false">
                {{ getErrorMessage('keldes_id') }}
              </p>
            </b-col>
            <div style="margin-bottom:5%;"></div>
            <b-col sm="3">
              <label for="input-peg_alamat_rw">RW:</label>
            </b-col>
            <b-col sm="9">
              <b-form-input id="input-peg_alamat_rw" type="number" v-model="form.peg_alamat_rw"
                :state="getErrorState('peg_alamat_rw')">
              </b-form-input>
              <p style="color:red;" v-if="getErrorState('peg_alamat_rw') === false">
                {{ getErrorMessage('peg_alamat_rw') }}
              </p>
            </b-col>
            <div style="margin-bottom:5%;"></div>
            <b-col sm="3">
              <label for="input-rt">RT:</label>
            </b-col>
            <b-col sm="9">
              <b-form-input id="input-rt" type="number" v-model="form.peg_alamat_rt"
                :state="getErrorState('peg_alamat_rt')">
              </b-form-input>
              <p style="color:red;" v-if="getErrorState('peg_alamat_rt') === false">
                {{ getErrorMessage('peg_alamat_rt') }}
              </p>
            </b-col>
            <div style="margin-bottom:5%;"></div>
            <b-col sm="3">
              <label for="input-kodepos">Kode Pos:</label>
            </b-col>
            <b-col sm="9">
              <b-form-input id="input-kodepos" type="number" v-model="form.peg_kodepos"
                :state="getErrorState('peg_kodepos')">
              </b-form-input>
              <p style="color:red;" v-if="getErrorState('peg_kodepos') === false">
                {{ getErrorMessage('peg_kodepos') }}
              </p>
            </b-col>
          </b-row>
        </fieldset>
        <div style="margin-bottom:5%;"></div>
        <fieldset class="custom">
          <legend class="custom">Alamat KTP</legend>
          <b-row>
            <b-col sm="3">
              <label for="input-ktp-alamat">Alamat:</label>
            </b-col>
            <b-col sm="9">
              <b-form-input id="input-ktp-alamat" type="text" v-model="form.peg_ktp_rumah_alamat"
                :state="getErrorState('peg_ktp_rumah_alamat')">
              </b-form-input>
              <p style="color:red;" v-if="getErrorState('peg_ktp_rumah_alamat') === false">
                {{ getErrorMessage('peg_ktp_rumah_alamat') }}
              </p>
            </b-col>
            <div style="margin-bottom:5%;"></div>
            <b-col sm="3">
              <label for="input-ktp-provinsi">Provinsi:</label>
            </b-col>
            <b-col sm="9">
              <b-form-select class="font-size-13" v-model="form.ktp_prov_id" :options="PROVKOptions" value-field="id"
                text-field="nama" :state="getErrorState('ktp_prov_id')">
              </b-form-select>
              <p style="color:red;" v-if="getErrorState('ktp_prov_id') === false">
                {{ getErrorMessage('ktp_prov_id') }}
              </p>
            </b-col>
            <div style="margin-bottom:5%;"></div>
            <b-col sm="3">
              <label for="input-ktp-kota">Kota/Kabupaten:</label>
            </b-col>
            <b-col sm="9">
              <b-form-select class="font-size-13" v-model="form.ktp_kabkot_id" :options="KABKOptions" value-field="id"
                text-field="nama" :state="getErrorState('ktp_kabkot_id')">
              </b-form-select>
              <p style="color:red;" v-if="getErrorState('ktp_kabkot_id') === false">
                {{ getErrorMessage('ktp_kabkot_id') }}
              </p>
            </b-col>
            <div style="margin-bottom:5%;"></div>
            <b-col sm="3">
              <label for="input-ktp-kec">Kecamatan:</label>
            </b-col>
            <b-col sm="9">
              <b-form-select class="font-size-13" v-model="form.ktp_kec_id" :options="KECKOptions" value-field="id"
                text-field="nama" :state="getErrorState('ktp_kec_id')">
              </b-form-select>
              <p style="color:red;" v-if="getErrorState('ktp_kec_id') === false">
                {{ getErrorMessage('ktp_kec_id') }}
              </p>
            </b-col>
            <div style="margin-bottom:5%;"></div>
            <b-col sm="3">
              <label for="input-ktp-desa">Kelurahan/Desa:</label>
            </b-col>
            <b-col sm="9">
              <b-form-select class="font-size-13" v-model="form.ktp_keldes_id" :options="KELKOptions" value-field="id"
                text-field="nama" :state="getErrorState('ktp_keldes_id')">
              </b-form-select>
              <p style="color:red;" v-if="getErrorState('ktp_keldes_id') === false">
                {{ getErrorMessage('ktp_keldes_id') }}
              </p>
            </b-col>
            <div style="margin-bottom:5%;"></div>
            <b-col sm="3">
              <label for="input-peg_ktp_alamat_rw">RW:</label>
            </b-col>
            <b-col sm="9">
              <b-form-input id="input-peg_ktp_alamat_rw" type="number" v-model="form.peg_ktp_alamat_rw"
                :state="getErrorState('peg_ktp_alamat_rw')">
              </b-form-input>
              <p style="color:red;" v-if="getErrorState('peg_ktp_alamat_rw') === false">
                {{ getErrorMessage('peg_ktp_alamat_rw') }}
              </p>
            </b-col>
            <div style="margin-bottom:5%;"></div>
            <b-col sm="3">
              <label for="input-ktp-rt">RT:</label>
            </b-col>
            <b-col sm="9">
              <b-form-input id="input-ktp-rt" type="number" v-model="form.peg_ktp_alamat_rt"
                :state="getErrorState('peg_ktp_alamat_rt')">
              </b-form-input>
              <p style="color:red;" v-if="getErrorState('peg_ktp_alamat_rt') === false">
                {{ getErrorMessage('peg_ktp_alamat_rt') }}
              </p>
            </b-col>
            <div style="margin-bottom:5%;"></div>
            <b-col sm="3">
              <label for="input-ktp-kodepos">Kode Pos:</label>
            </b-col>
            <b-col sm="9">
              <b-form-input id="input-ktp-kodepos" type="number" v-model="form.peg_ktp_kodepos"
                :state="getErrorState('peg_ktp_kodepos')">
              </b-form-input>
              <p style="color:red;" v-if="getErrorState('peg_ktp_kodepos') === false">
                {{ getErrorMessage('peg_ktp_kodepos') }}
              </p>
            </b-col>
          </b-row>
        </fieldset>
      </b-row>
      <b-row class="my-3">
        <b-button variant="success" @click="save" :disabled="loading">
          <fa icon="spinner" v-show="loading" spin /> Simpan
        </b-button>
        <b-button variant="light" @click="cancelPegawai()" :disabled="loading">
          Batal
        </b-button>
      </b-row>
    </b-container>
  </b-card>
  </div>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
  // middleware: 'auth',
  middleware: ['auth'],

  head() {
    return { title: "Edit Pegawai" }
  },
  async asyncData({ params }) {
    let f2 = axios.get('pegawai/' + params.id)
    let f3 = axios.get('/jenis-jabatan/get-data')
    let f4 = axios.get('/jabatan/get-data')
    let f5 = axios.get('/jenjang-jabatan/get-data')
    let f6 = axios.get('/unit-kerja/get-data')
    let f7 = axios.get('/spesialis/get-data')
    let f8 = axios.get('/golongan/get-data')
    let f9 = axios.get('/kualifikasi-pendidikan/get-data')
    let f10 = axios.get('/bidang/get-data')
    let f11 = axios.get('/jenis-tenaga-kerja/get-data')
    let f12 = axios.get('/status-pegawai/get-data')
    let f13 = axios.get('/pendidikan/get-data')
    let f14 = axios.get('/pegawai/provinsi')

    let f2resp = (await f2).data
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

    let form = f2resp.models
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

    // Alamat Saat Ini
    let PROVPlace = [{ id: null, nama: 'Pilih Provinsi' }]
    let PROVOptions = PROVPlace.concat(f14resp.data)
    let f15 = (await axios.get('/pegawai/kabupaten-kota', {
      params: {
        provinsi_id: form.prov_id
      }
    })).data.data
    let placeholderKab = [{
      id: null, nama: "Pilih Kabupaten/Kota"
    }]
    let KABOptions = placeholderKab.concat(f15)
    let f16 = (await axios.get('/pegawai/kecamatan', {
      params: {
        kabkot_id: form.kabkot_id
      }
    })).data.data
    let placeholderKec = [{
      id: null, nama: "Pilih Kecamatan"
    }]
    let KECOptions = placeholderKec.concat(f16)
    let f17 = (await axios.get('/pegawai/kelurahan-desa', {
      params: {
        kecamatan_id: form.kec_id
      }
    })).data.data
    let placeholderKel = [{
      id: null, nama: "Pilih Kelurahan/Desa"
    }]
    let KELOptions = placeholderKel.concat(f17)

    // Alamat KTP
    let PROVKOptions = PROVPlace.concat(f14resp.data)
    let f15K = (await axios.get('/pegawai/kabupaten-kota', {
      params: {
        provinsi_id: form.ktp_prov_id
      }
    })).data.data
    let placeholderKabK = [{
      id: null, nama: "Pilih Kabupaten/Kota"
    }]
    let KABKOptions = placeholderKabK.concat(f15K)
    let f16K = (await axios.get('/pegawai/kecamatan', {
      params: {
        kabkot_id: form.ktp_kabkot_id
      }
    })).data.data
    let placeholder = [{
      id: null, nama: "Pilih Kecamatan"
    }]
    let KECKOptions = placeholder.concat(f16K)
    let f17K = (await axios.get('/pegawai/kelurahan-desa', {
      params: {
        kecamatan_id: form.ktp_kec_id
      }
    })).data.data
    let placeholderKelK = [{
      id: null, nama: "Pilih Kelurahan/Desa"
    }]
    let KELKOptions = placeholderKelK.concat(f17K)

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
      PROVOptions,
      PROVKOptions,
      KABOptions,
      KECOptions,
      KELOptions,
      KABKOptions,
      KECKOptions,
      KELKOptions
    }
  },
  data() {
    return {
      loading: false,
      errors: {},
      AOptions: [
        { value: '' || null, text: 'Pilih Agama' },
        { value: 'Budha', text: 'Budha' },
        { value: 'Hindu', text: 'Hindu' },
        { value: 'Islam', text: 'Islam' },
        { value: 'Katolik', text: 'Katolik' },
        { value: 'Konghucu', text: 'Konghucu' },
        { value: 'Kristen', text: 'Kristen' },
      ],
      SOptions: [
        { value: '' || null, text: 'Pilih Status Pernikahan' },
        { value: 'Menikah', text: 'Menikah' },
        { value: 'Belum Menikah', text: 'Belum Menikah' },
      ],
      GOptions: [
        { value: '' || null, text: 'Pilih Golongan Darah' },
        { value: 'A+', text: 'A+' },
        { value: 'A-', text: 'A-' },
        { value: 'B+', text: 'B+' },
        { value: 'B-', text: 'B-' },
        { value: 'AB+', text: 'AB+' },
        { value: 'AB-', text: 'AB-' },
        { value: 'O+', text: 'O+' },
        { value: 'O-', text: 'O-' },
        { value: 'A', text: 'A' },
        { value: 'B', text: 'B' },
        { value: 'AB', text: 'AB' },
        { value: 'O', text: 'O' },
      ],
      JKOptions: [
        { value: '' || null, text: 'Pilih Jenis Kelamin' },
        { value: 'L', text: 'Laki Laki' },
        { value: 'P', text: 'Perempuan' },
      ],
      KABOptions: [],
      KABKOptions: [],
      KECOptions: [],
      KECKOptions: [],
      KELOptions: [],
      KELKOptions: [],
      // POptions: [],
    }
  },
  watch: {
    "form.prov_id"() {
      this.getKabKot()
    },
    "form.kabkot_id"() {
      this.getKec()
    },
    "form.kec_id"() {
      this.getKel()
    },
    "form.ktp_prov_id"() {
      this.getKabKotK()
    },
    "form.ktp_kabkot_id"() {
      this.getKecK()
    },
    "form.ktp_kec_id"() {
      this.getKelK()
    },
    // "form.keldes_id"() {
    //   this.getPos()
    // },
  },
  methods: {
    async save() {
      this.loading = true
      try {
        let resp = (await axios.patch('pegawai/' + this.$route.params.id, this.form)).data
        if (resp.success) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data berhasil disimpan',
            confirmButtonText: 'ok',
          })
          this.form = {}
          this.$router.push({ name: 'pegawai.view' })
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
    async getKabKot() {
      let f15 = (await axios.get('/pegawai/kabupaten-kota', {
        params: {
          provinsi_id: this.form.prov_id
        }
      })).data.data
      let placeholder = [{
        id: null, nama: "Pilih Kabupaten/Kota"
      }]
      this.KABOptions = placeholder.concat(f15)
    },
    async getKabKotK() {
      let f15K = (await axios.get('/pegawai/kabupaten-kota', {
        params: {
          provinsi_id: this.form.ktp_prov_id
        }
      })).data.data
      let placeholder = [{
        id: null, nama: "Pilih Kabupaten/Kota"
      }]
      this.KABKOptions = placeholder.concat(f15K)
    },
    async getKec() {
      let f16 = (await axios.get('/pegawai/kecamatan', {
        params: {
          kabkot_id: this.form.kabkot_id
        }
      })).data.data
      let placeholder = [{
        id: null, nama: "Pilih Kecamatan"
      }]
      this.KECOptions = placeholder.concat(f16)
    },
    async getKecK() {
      let f16K = (await axios.get('/pegawai/kecamatan', {
        params: {
          kabkot_id: this.form.ktp_kabkot_id
        }
      })).data.data
      let placeholder = [{
        id: null, nama: "Pilih Kecamatan"
      }]
      this.KECKOptions = placeholder.concat(f16K)
    },
    async getKel() {
      let f17 = (await axios.get('/pegawai/kelurahan-desa', {
        params: {
          kecamatan_id: this.form.kec_id
        }
      })).data.data
      let placeholder = [{
        id: null, nama: "Pilih Kelurahan/Desa"
      }]
      this.KELOptions = placeholder.concat(f17)
    },
    async getKelK() {
      let f17K = (await axios.get('/pegawai/kelurahan-desa', {
        params: {
          kecamatan_id: this.form.ktp_kec_id
        }
      })).data.data
      let placeholder = [{
        id: null, nama: "Pilih Kelurahan/Desa"
      }]
      this.KELKOptions = placeholder.concat(f17K)
    },
    cancelPegawai(){
      this.$router.push({ name: 'pegawai.view' })
    }
    // async getPos() {
    //   let f18 = (await axios.get('/pegawai/kodepos', {
    //     params: {
    //       provinsi_id: this.form.prov_id,
    //       kabkot_id: this.form.kabkot_id,
    //       kecamatan_id: this.form.kec_id,
    //       keldes_id: this.form.keldes_id
    //     }
    //   })).data.data
    //   this.POptions = f18
    // }
  }
}
</script>
<style>
fieldset.custom {
  width: 100%;
  border: 1px groove #ddd !important;
  padding: 0 1.4em 1.4em 1.4em !important;
  margin: 0 0 1.5em 0 !important;
  -webkit-box-shadow: 0px 0px 0px 0px #000;
  box-shadow: 0px 0px 0px 0px #000;
}

legend.custom {
  border: none;
  width: 200px;
  font-size: 1.2em !important;
  font-weight: bold !important;
  text-align: left !important;
}
</style>
