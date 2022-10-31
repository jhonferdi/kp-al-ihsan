<template>
  <modal :open="open" :size="size" @close="$emit('close')" :title="title">
    <div class="mb-3">
      <label>Ruangan</label>
      <b-form-input placeholder="Masukkan Ruangan" v-model="form.nama_ruangan" :state="getErrorState('nama_ruangan')">
      </b-form-input>
      <p style="color:red;" v-if="getErrorState('nama_ruangan') === false">
        {{ getErrorMessage('nama_ruangan') }}
      </p>
    </div>
    <div class="mb-3">
      <label>Unit Kerja Fungsional</label>
      <b-form-select id="input-role" type="text" v-model="form.unit_kerja_id" :options="UKOptions" value-field="unit_kerja_id"
        text-field="unit_kerja_nama" class="font-size-13" :state="getErrorState('unit_kerja_id')">
      </b-form-select>
      <p style="color:red;" v-if="getErrorState('unit_kerja_id') === false">
        {{ getErrorMessage('unit_kerja_id') }}
      </p>
    </div>
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
  props: ['open', 'title', 'size', 'ruangan'],
  data() {
    return {
      loading: false,
      form: {},
      errors: {},
      UKOptions: [],
    }
  },
  watch: {
    open() {
      this.resetForm()
      this.getOptions()
      if (this.ruangan) {
        this.form = _.clone(this.ruangan)
      } else {
        this.form = {}
      }
    },
  },
  methods: {
    async getOptions() {
      let dataResp = (await axios.get('unit-kerja')).data
      this.UKOptions = dataResp.data
    },
    async save() {
      this.loading = true
      try {
        if (this.form.ruangan_id) {
          let resp = (await axios.patch('ruangan/' + this.form.ruangan_id, this.form)).data
          if (resp.success) {
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: 'Data berhasil disimpan',
              confirmButtonText: 'ok',
            })
          }
        } else {
          let resp = (await axios.post('ruangan/', this.form)).data
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
    cancelRuangan() {
      this.$router.push({ name: 'ruangan' })
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
