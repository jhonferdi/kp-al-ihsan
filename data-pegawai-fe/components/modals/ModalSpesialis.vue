<template>
  <modal :open="open" :size="size" @close="$emit('close')" :title="title">
    <div class="mb-3">
      <label>Nama Spesialis</label>
      <b-form-input placeholder="Masukkan Nama Spesialis" v-model="form.spesialis_nama"
        :state="getErrorState('spesialis_nama')"></b-form-input>
      <p style="color:red;" v-if="getErrorState('spesialis_nama') === false">
        {{ getErrorMessage('spesialis_nama') }}
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
  middleware: ['auth'],
  props: ['open', 'title', 'size', 'spesialis'],
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
      if (this.spesialis) {
        this.form = _.clone(this.spesialis)
      } else {
        this.form = {}
      }
    },
  },
  methods: {
    async save() {
      this.loading = true
      try {
        if (this.form.spesialis_id) {
          let resp = (await axios.patch('spesialis/' + this.form.spesialis_id, this.form)).data
          if (resp.success) {
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: 'Data berhasil disimpan',
              confirmButtonText: 'ok',
            })
          }
        } else {
          let resp = (await axios.post('spesialis', this.form)).data
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
    cancelSpesialis() {
      this.$router.push({ name: 'spesialis' })
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