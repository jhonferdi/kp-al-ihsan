<template>
  <modal :open="open" @close="$emit('close')" :title="title">
    <div class="mb-2">
      <label>Nama Pegawai</label>
      <b-form-select type="text" class="form-control" v-model="form_user.nama" :options="pegawai"
            value-field="peg_nama_lengkap" text-field="peg_nama_lengkap">
      </b-form-select>
      <label>Username</label>
      <b-form-input type="text" class="form-control" v-model="form_user.username">
      </b-form-input>
      <label>Role</label>
      <b-form-select type="text" class="form-control" v-model="form_user.role" :options="role"
            value-field="nama_role" text-field="nama_role">
      </b-form-select>
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
import { mapGetters } from 'vuex'

export default {
  props: ['open', 'title'],
  inject:[
    'role',
    'pegawai'
  ],
  data() {
    return {
      loading : false,
      errors: {},
      form_user: {},
    }
  },
  methods: {
    async save() {
      this.loading = true
      try {
        let resp = (await axios.put('pegawai/user/' + this.form.peg_id, this.form)).data
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
        this.$emit('close')
        this.$emit('onSave')
      } catch (err) {
        if (err.response && err.response.status == 422) {
          this.errors = err.response.data.errors
        }
      }
      this.loading = false
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
  },
}
</script>