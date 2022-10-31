<template>
  <modal :open="open" :size="size" @close="$emit('close')" :title="title">
    <div class="mb-3">
      <label>Nama Pegawai</label>
      <b-form-select v-if="form.id == null" class="font-size-13" v-model="form.peg_id" :options="PEGOptions"
        value-field="peg_id" text-field="peg_nama_lengkap" :state="getErrorState('peg_id')"></b-form-select>
      <b-form-input v-else v-model="form.pegawai.peg_nama_lengkap" readonly></b-form-input>
      <p style="color:red;" v-if="getErrorState('peg_id') === false">
        {{ getErrorMessage('peg_id') }}
      </p>
    </div>
    <div class="mb-3">
      <label>Username</label>
      <b-form-input placeholder="Masukkan Username" v-model="form.username" :state="getErrorState('username')">
      </b-form-input>
      <p style="color:red;" v-if="getErrorState('username') === false">
        {{ getErrorMessage('username') }}
      </p>
    </div>
    <!-- <div class="mb-3">
      <label>Email</label>
      <b-form-input placeholder="Masukkan Email" v-model="form.peg_email" :state="getErrorState('peg_email')">
      </b-form-input>
      <p style="color:red;" v-if="getErrorState('peg_email') === false">
        {{ getErrorMessage('peg_email') }}
      </p>
    </div> -->
    <div class="mb-3">
      <label>Role</label>
      <b-form-select id="input-role" type="text" v-model="form.role_id" :options="ROptions" value-field="role_id"
        text-field="nama_role" class="font-size-13" :state="getErrorState('role_id')">
      </b-form-select>
      <p style="color:red;" v-if="getErrorState('role_id') === false">
        {{ getErrorMessage('role_id') }}
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
  props: ['open', 'title', 'size', 'user'],
  data() {
    return {
      loading: false,
      form: {},
      errors: {},
      PEGOptions: [],
      ROptions: [],
    }
  },
  watch: {
    open() {
      this.resetForm()
      this.getOptions()
      if (this.user) {
        this.form = _.clone(this.user)
      } else {
        this.form = {}
      }
    },
  },
  methods: {
    async getOptions() {
      let dataResp = (await axios.get('user/get-options')).data.data
      let placeholderPeg = [{
        peg_id: null, peg_nama_lengkap: "Pilih Pegawai"
      }]
      this.PEGOptions = placeholderPeg.concat(dataResp.pegawai)
      let placeholderRole = [{
        role_id: null, nama_role: "Pilih Role"
      }]
      this.ROptions = placeholderRole.concat(dataResp.role)
    },
    async save() {
      this.loading = true
      try {
        if (this.form.id) {
          let resp = (await axios.put('/user/edit/' + this.form.id, this.form)).data
          if (resp.success) {
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: 'Data berhasil disimpan',
              confirmButtonText: 'ok',
            })
          }
        } else {
          let resp = (await axios.post('/user/', this.form)).data
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
    cancelUser() {
      this.$router.push({ name: 'user' })
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
  