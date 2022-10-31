<template>
  <modal :open="open" :size="size" @close="$emit('close')" :title="title">
    <label>Jabatan</label>
    <b-form-input placeholder="Masukkan Jabatan" v-model="form.jabatan_nama" :state="getErrorState('jabatan_nama')">
    </b-form-input>
    <p style="color:red;" v-if="getErrorState('jabatan_nama') === false">
      {{ getErrorMessage('jabatan_nama') }}
    </p>
    <label>Jabatan Fungsional</label>
    <b-form-select v-model="form.jabatan_fungsional_id" :options="JBOptions" value-field="jabatan_fungsional_id"
      text-field="jabatan_fungsional_nama" placeholder="Pilih Jabatan Fungsional"
      :state="getErrorState('jabatan_fungsional')">
    </b-form-select>
    <p style="color:red;" v-if="getErrorState('jabatan_fungsional') === false">
      {{ getErrorMessage('jabatan_fungsional') }}
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
  props: ['open', 'title', 'size', 'jabatan'],
  data() {
    return {
      loading: false,
      form: {},
      errors: {},
      JBOptions: []
    }
  },
  watch: {
    open() {
      this.resetForm()
      this.getJabFung()
      if (this.jabatan) {
        this.form = _.clone(this.jabatan)
      } else {
        this.form = {}
      }
    },
  },
  methods: {
    async getJabFung() {
      let jabFungResp = (await axios.get('/jabatan/jabatan-fungsional')).data.data
      let placeholderJabFung = [{
        jabatan_fungsional_id: null, jabatan_fungsional_nama: "Pilih jabatan Fungsional"
      }]
      this.JBOptions = placeholderJabFung.concat(jabFungResp)
    },
    async save() {
      this.loading = true
      try {
        if (this.form.jabatan_id) {
          let resp = (await axios.patch('jabatan/' + this.form.jabatan_id, this.form)).data
          if (resp.success) {
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: 'Data berhasil disimpan',
              confirmButtonText: 'ok',
            })
          }
        } else {
          let resp = (await axios.post('jabatan', this.form)).data
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
    canceljabatan() {
      this.$router.push({ name: 'jabatan' })
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
  