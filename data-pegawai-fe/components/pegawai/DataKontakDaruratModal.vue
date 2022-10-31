<template>
  <b-modal id="modal-data-kontak-darurat" ref="modal-data-kontak-darurat" title="Tambah Data Kontak Darurat">
    <template #modal-header="{}">
      <h5>Informasi Data Kontak Darurat</h5>
    </template>
    <template #default="{}">
      <b-row class="my-1">
        <b-col sm="12">
          <label>Nama</label>
        </b-col>
        <b-col sm="12">
          <b-form-input type="text" class="mb-3" v-model="form_data_kontak_darurat.nama" :state="getErrorState('nama')"></b-form-input>
          <p style="color:red;" v-if="getErrorState('nama') === false">
            {{ getErrorMessage('nama') }}
            </p>
        </b-col>
        <b-col sm="12">
          <label>Hubungan Kerabat</label>
        </b-col>
        <b-col sm="12">
          <b-form-input class="mb-3" type="text" v-model="form_data_kontak_darurat.hubungan_kerabat" :state="getErrorState('hubungan_kerabat')"></b-form-input>
          <p style="color:red;" v-if="getErrorState('hubungan_kerabat') === false">
            {{ getErrorMessage('hubungan_kerabat') }}
            </p>
        </b-col>
        <b-col sm="12">
          <label>No Handphone</label>
        </b-col>
        <b-col sm="12">
          <b-form-input type="number" class="mb-3" v-model="form_data_kontak_darurat.no_hp" :state="getErrorState('no_hp')"></b-form-input>
          <p style="color:red;" v-if="getErrorState('no_hp') === false">
            {{ getErrorMessage('no_hp') }}
            </p>
        </b-col>
        <b-col sm="12">
          <label>Alamat</label>
        </b-col>
        <b-col sm="12">
          <b-form-input type="text" class="mb-3" v-model="form_data_kontak_darurat.alamat" :state="getErrorState('alamat')"></b-form-input>
          <p style="color:red;" v-if="getErrorState('alamat') === false">
            {{ getErrorMessage('alamat') }}
            </p>
        </b-col>
      </b-row>
    </template>
    <template #modal-footer="{}">
      <!-- Emulate built in modal footer ok and cancel button actions -->
      <b-button size="md" variant="info" @click="saveDataKontakDarurat()" :disabled="loading">
        <i class="fa fa-save"></i> {{ loading ? 'Sedang Menyimpan' : 'Simpan' }}
      </b-button>
      <b-button size="md" variant="light" @click="cancelDataKontakDarurat()">
        Batal
      </b-button>
    </template>
  </b-modal>
</template>

<script>
import Swal from 'sweetalert2'
import axios from 'axios'

export default {
  props: ['pegawai'],
  data() {
    return {
      form_data_kontak_darurat: null,
      loading: false,
      errors:{}
    }
  },
  methods: {
    openDataKontakDaruratModal(data) {
      if (data) {
        this.form_data_kontak_darurat = {
          id: data.id,
          peg_id: data.peg_id,
          hubungan_kerabat: data.hubungan_kerabat,
          nama: data.nama,
          no_hp: data.no_hp,
          alamat: data.alamat,
        };
      } else {
        this.form_data_kontak_darurat = {
          peg_id: this.pegawai.peg_id
        };
      }
      this.errors={}
      this.$refs['modal-data-kontak-darurat'].show()
    },
    async saveDataKontakDarurat() {
      this.loading = true
      try {
        if (this.form_data_kontak_darurat.id) {
        let resp = (await axios.patch('pegawai/data-kontak-darurat/' + this.form_data_kontak_darurat.id, this.form_data_kontak_darurat)).data
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: resp.message,
          confirmButtonText: 'ok',
        })
      } else {
        let resp = (await axios.post('pegawai/data-kontak-darurat', this.form_data_kontak_darurat)).data
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: resp.message,
          confirmButtonText: 'ok',
        })
      }
      this.loading = false
      this.$emit('onSave')
      this.$refs['modal-data-kontak-darurat'].hide()
      } catch (err) {
        if(err.response && err.response.status == 422){
            this.errors = err.response.data.errors;
          }
          this.loading = false
      }
    },
    cancelDataKontakDarurat() {
      this.$refs['modal-data-kontak-darurat'].hide()
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

<style>

</style>