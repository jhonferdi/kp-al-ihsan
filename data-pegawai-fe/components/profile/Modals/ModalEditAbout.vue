<template>
  <modal :open="open" @close="$emit('close')" :title="title">
    <div class="mb-2">
      <label>Tentang Saya</label>
      <b-form-textarea id="textarea" placeholder="Masukan Tentang Saya" rows="3" max-rows="6"
        v-model="form.tentang_saya">
      </b-form-textarea>
    </div>
    <template #footer>
      <b-button variant="lightgreen" @click="save" :disabled="loading">
        <i class="fa fa-save"></i> {{ loading ? 'Sedang Menyimpan' : 'Simpan' }}
      </b-button>
    </template>
  </modal>
</template>
<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import {_} from 'vue-underscore'

export default {
  props: ['open', 'title', 'pegawai'],
  data() {
    return {
      form: {},
      // tentang_saya: this.pegawai.tentang_saya
    }
  },
  watch:{
    open(){
      this.form = _.clone(this.pegawai)
    }
  },
  methods: {
    async save() {
      this.loading = true
      try {
        let resp = (await axios.put('pegawai/update-about/' + this.form.peg_id, this.form)).data
        if (resp.success) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data berhasil disimpan',
            confirmButtonText: 'ok',
          })
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
      this.$emit('close')
      this.$emit('onSave')
    },
  }
}
</script>