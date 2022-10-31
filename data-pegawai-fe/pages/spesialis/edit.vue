<template>
  <b-card title="Edit Spesialis" class="my-4">
    <b-container fluid>
      <b-row class="my-1">
        <b-col sm="3">
          <label for="input-name">Nama Spesialis:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-name" type="text" v-model="form.spesialis_nama" :state="getErrorState('name')">
          </b-form-input>
          <p style="color:red;" v-if="getErrorState('spesialis_nama') === false">
            {{ getErrorMessage('spesialis_nama') }}
          </p>
        </b-col>
        <div style="margin-bottom:5%;"></div>
        <b-col sm="3">
          <label for="input-fungsional">Sub Spesialis:</label>
        </b-col>
        <b-col sm="9">
          <v-select id="input-subs" :options="SSOptions" :reduce="item => item.sub_spesialis_id"
            label="sub_spesialis_nama" placeholder="Pilih Sub Spesialis" v-model="form.sub_spesialis_id"
            :class="{ 'is-invalid': !getErrorState('sub_spesialis_id') }">
          </v-select>
          <p style="color:red;" v-if="getErrorState('sub_spesialis_id') === false">
            {{ getErrorMessage('sub_spesialis_id') }}
          </p>
        </b-col>

      </b-row>
      <b-row class="my-4">
        <b-col offset-sm="3" sm="9">
          <b-button variant="success" @click="save" :disabled="loading">
            <fa icon="spinner" v-show="loading" spin /> Simpan
          </b-button>
          <b-button variant="light" @click="cancelSpesialis()" :disabled="loading">
          Batal
        </b-button>
        </b-col>
      </b-row>
    </b-container>
  </b-card>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
  // middleware: 'auth',
  middleware: ['auth'],

  head() {
    return { title: "Edit Spesialis" }
  },
  async asyncData({ params }) {
    let f2 = axios.get('spesialis/' + params.id)
    let f2resp = (await f2).data

    let f3 = axios.get('/sub-spesialis', {
      params: {
        perpage: 'all',
        sortby: 'sub_spesialis_nama'
      }
    })

    let f3resp = (await f3).data

    let form = f2resp.model
    let SSOptions = f3resp.data
    return {
      form,
      SSOptions,
    }
  },
  data() {
    return {
      loading: false,
      errors: {},
    }
  },
  methods: {
    async save() {
      this.loading = true
      try {
        let resp = (await axios.patch('spesialis/' + this.$route.params.id, this.form)).data
        if (resp.success) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data berhasil disimpan',
            confirmButtonText: 'ok',
          })
          this.form = {}
          this.$router.push({ name: 'spesialis' })
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
    },
    cancelSpesialis(){
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
  }
}
</script>
