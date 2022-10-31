<template>
  <modal :open="open" :size="size" @close="$emit('close')" :title="title">
    <label>Nama Pegawai</label>
    <b-form-select v-model="form.peg_id" :options="PEGOptions" 
      value-field="peg_id" text-field="peg_nama_lengkap" placeholder="Pilih Pegawai" :state="getErrorState('peg_id')">
      </b-form-select>
      <p style="color:red;" v-if="getErrorState('peg_id') === false">
        {{ getErrorMessage('peg_id') }}
      </p>
      <label>Ruangan</label>
      <b-form-select v-model="form.ruangan_id" :options="ROptions" 
      value-field="ruangan_id" text-field="nama_ruangan" placeholder="Pilih Ruangan" :state="getErrorState('ruangan_id')">
      </b-form-select>
      <p style="color:red;" v-if="getErrorState('ruangan_id') === false">
        {{ getErrorMessage('ruangan_id') }}
      </p>
      <label>Role</label>
      <b-form-select v-model="form.role_ruangan_id" :options="ROLOptions" 
      value-field="id" text-field="role" placeholder="Pilih Role" :state="getErrorState('role_ruangan_id')">
      </b-form-select>
      <p style="color:red;" v-if="getErrorState('role_ruangan_id') === false">
        {{ getErrorMessage('role_ruangan_id') }}
      </p>
      <template #footer>
        <b-button variant="darkgreen" @click="save()" :disabled="loading">{{ loading ? 'Sedang Menyimpan' : 'Simpan' }}
        </b-button>
      </template>
  </modal>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { _ } from 'vue-underscore'

export default {
  // middleware: 'auth',
  middleware: ['auth'],
  props: ['open', 'title', 'size', 'pegawaiRuangan'],
  data() {
    return {
      loading: false,
      form: {},
      errors: {},
      PEGOptions: [],
      ROptions: [],
      ROLOptions: []
    }
  },
  watch: {
    open() {
      this.resetForm()
      this.getOptions()
      if (this.pegawaiRuangan) {
        this.form = _.clone(this.pegawaiRuangan)
      } else {
        this.form = {}
      }
    },
  },
  methods: {
  async getOptions() {
    let dataResp = (await axios.get('pegawai-ruangan/option')).data.data
    this.PEGOptions = dataResp.pegawai
    this.ROptions = dataResp.ruangan
    this.ROLOptions = dataResp.role
    },
    async save() {
      this.loading = true
      try {
        if (this.form.peg_ruangan_id) {
          let resp = (await axios.patch('/pegawai-ruangan/' + this.form.peg_ruangan_id, this.form)).data
          if (resp.success) {
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: 'Data berhasil disimpan',
              confirmButtonText: 'ok',
            })
          }
        } else {
          let resp = (await axios.post('/pegawai-ruangan/', this.form)).data
          if (resp.success) {
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: 'Data berhasil disimpan',
              confirmButtonText: 'ok',
            })
          }
        }
        this.$emit('close')
        this.$emit('onSave')
        this.errors = {}
      } catch (err) {
        if (err.response && err.response.status == 422) {
          this.errors = err.response.data.errors
        }
      }
      this.loading = false
    },
    cancelPegawaiRuangan() {
      this.$router.push({ name: 'pegawai-ruangan' })
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
    resetForm() {
      this.errors = {}
    },
  }
}
</script>
