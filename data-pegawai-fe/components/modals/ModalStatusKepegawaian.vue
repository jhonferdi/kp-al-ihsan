<template>
  <modal :open="open" :size="size" @close="$emit('close')" :title="title">
    <label>Status Kepegawaian</label>
      <b-form-input placeholder="Masukkan Status Kepegawaian" v-model="form.status_kepegawaian" :state="getErrorState('status_kepegawaian')">
      </b-form-input>
      <p style="color:red;" v-if="getErrorState('status_kepegawaian') === false">
        {{ getErrorMessage('status_kepegawaian') }}
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
  props: ['open', 'title', 'size', 'statusPegawai'],
  data() {
    return {
      loading: false,
      form: {},
      errors: {},
    }
  },
  watch: {
    open() {
      this.resetForm()
      if (this.statusPegawai) {
        this.form = _.clone(this.statusPegawai)
      } else {
        this.form = {}
      }
    },
  },
  methods: {
    async save() {
      this.loading = true
      try {
        if (this.form.status_kepegawaian_id) {
          let resp = (await axios.patch('status-pegawai/' + this.form.status_kepegawaian_id, this.form)).data
          if (resp.success) {
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: 'Data berhasil disimpan',
              confirmButtonText: 'ok',
            })
          }
        } else {
          let resp = (await axios.post('status-pegawai', this.form)).data
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
    cancelStatusPegawai() {
      this.$router.push({ name: 'status-pegawai' })
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
