<template>
  <b-modal id="modal-master-mcu" ref="modal-master-mcu" title="Tambah Master Penyakit">
    <template #modal-header="{}">
      <h5>Informasi Master Penyakit</h5>
    </template>
    <template #default="{}">
      <b-row class="my-1">
        <b-col sm="12">
          <label>Nama Penyakit</label>
        </b-col>
        <b-col sm="12">
          <b-form-input type="text" class="form-control" v-model="form_master_mcu.nama_penyakit"
            :state="getErrorState('nama_penyakit')"></b-form-input>
          <p style="color:red;" v-if="getErrorState('nama_penyakit') === false">
            {{ getErrorMessage('nama_penyakit') }}
          </p>
        </b-col>
        <!-- <b-col sm="12">
          <label>Kategori</label>
        </b-col>
        <b-col sm="12">
          <b-form-input type="text" class="form-control" v-model="form_master_mcu.kategori"
            :state="getErrorState('kategori')" value="mcu" readonly></b-form-input>
          <p style="color:red;" v-if="getErrorState('kategori') === false">
            {{ getErrorMessage('kategori') }}
          </p>
        </b-col> -->
      </b-row>
    </template>
    <template #modal-footer="{}">
      <!-- Emulate built in modal footer ok and cancel button actions -->
      <b-button size="md" variant="info" @click="saveMasterMcu()" :disabled="loading">
        <i class="fa fa-save"></i> {{ loading === 2 ? 'Sedang mengunggah' : loading ? 'Sedang Menyimpan' : 'Simpan' }}
      </b-button>
      <b-button size="md" variant="light" @click="cancelMasterMcu()">
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
      form_master_mcu: null,
      loading: false,
      errors: {}
    }
  },
  methods: {
    openMasterMcuModal(data) {
      if (data) {
        this.form_master_mcu = {
          id: data.id,
          nama_penyakit: data.nama_penyakit,
        };
      } else {
        this.form_master_mcu = {};
      }
      this.errors = {}
      this.$refs['modal-master-mcu'].show()
    },
    async saveMasterMcu() {
      this.loading = true
      try {
        if (this.form_master_mcu.id) {
          let resp = (await axios.patch('pegawai/master-mcu/' + this.form_master_mcu.id, this.form_master_mcu)).data
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        } else {
          let resp = (await axios.post('pegawai/master-mcu', this.form_master_mcu)).data
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        }
        this.loading = false
        this.$emit('onSave')
        this.$refs['modal-master-mcu'].hide()
      } catch (err) {
        if (err.response && err.response.status == 422) {
          this.errors = err.response.data.errors;
        }
        this.loading = false
      }
    },
    cancelMasterMcu() {
      this.$refs['modal-master-mcu'].hide()
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