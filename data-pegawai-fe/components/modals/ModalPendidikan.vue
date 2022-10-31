<template>
  <modal :open="open" :size="size" @close="$emit('close')" :title="title">
    <label>Gelar Pendidikan</label>
    <b-form-input placeholder="Masukkan Gelar Pendidikan" v-model="form.pendidikan_nama" :state="getErrorState('pendidikan_nama')">
    </b-form-input>
    <p style="color:red;" v-if="getErrorState('pendidikan_nama') === false">
      {{ getErrorMessage('pendidikan_nama') }}
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
  props: ['open', 'title', 'size', 'pendidikan'],
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
      if (this.pendidikan) {
        this.form = _.clone(this.pendidikan)
      } else {
        this.form = {}
      }
    },
  },
  methods: {
    async save() {
      this.loading = true
      try {
        if (this.form.pendidikan_id) {
          let resp = (await axios.patch('pendidikan/' + this.form.pendidikan_id, this.form)).data
          if (resp.success) {
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: 'Data berhasil disimpan',
              confirmButtonText: 'ok',
            })
          }
        } else {
          let resp = (await axios.post('pendidikan', this.form)).data
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
    cancelPendidikan() {
      this.$router.push({ name: 'pendidikan' })
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
