<template>
  <modal :open="open" :size="size" @close="$emit('close')" :title="title">
    <div class="mb-3">
      <label>Nama Spesialis</label>
      <b-form-select class="font-size-13" v-model="form.spesialis_id" :options="SPESOptions" value-field="spesialis_id"
        text-field="spesialis_nama" :state="getErrorState('spesialis_id')">
      </b-form-select>
      <p style="color:red;" v-if="getErrorState('spesialis_id') === false">
        {{ getErrorMessage('spesialis_id') }}
      </p>
    </div>
    <div class="mb-3">
      <label>Nama Sub Spesialis</label>
      <b-form-input placeholder="Masukkan Nama Sub Spesialis" v-model="form.sub_spesialis_nama"
        :state="getErrorState('sub_spesialis_nama')"></b-form-input>
      <p style="color:red;" v-if="getErrorState('sub_spesialis_nama') === false">
        {{ getErrorMessage('sub_spesialis_nama') }}
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
  props: ['open', 'title', 'size', 'sub_spesialis'],
  data() {
    return {
      loading: false,
      form: {},
      errors: {},
      SPESOptions: []
    }
  },
  watch: {
    open() {
      this.resetForm()
      this.getSpesialis()
      if (this.sub_spesialis) {
        this.form = _.clone(this.sub_spesialis)
      } else {
        this.form = {}
      }
    },
  },
  methods: {
    async getSpesialis() {
      let dataResp = (await axios.get('/spesialis')).data.data
      let placeholderSpes = [{
        spesialis_id: null, spesialis_nama: "Pilih Spesialis"
      }]
      this.SPESOptions = placeholderSpes.concat(dataResp)
    },
    async save() {
      this.loading = true
      try {
        if (this.form.sub_spesialis_id) {
          let resp = (await axios.patch('sub-spesialis/' + this.form.sub_spesialis_id, this.form)).data
          if (resp.success) {
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: 'Data berhasil disimpan',
              confirmButtonText: 'ok',
            })
          }
        } else {
          let resp = (await axios.post('sub-spesialis', this.form)).data
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
      this.$router.push({ name: 'sub_spesialis' })
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