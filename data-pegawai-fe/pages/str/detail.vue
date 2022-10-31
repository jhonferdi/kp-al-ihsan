<template>
  <b-card title="Detail STR" class="my-4">
    <b-container fluid>
      <div class="form-value"> <label>Nama Pegawai</label> : {{ formData.nama_pegawai }}</div>
      <br>
      <div class="form-value"> <label>Nomor STR</label> : {{ formData.nomor_str }}</div>
      <br>
      <div class="form-value"> <label>Tanggal Kadaluarsa STR</label> {{ formData.tanggal_kadaluarsa_str }}</div>
      <br>
      <div class="form-value"> <label>Jenis STR</label> {{ formData.jenis_str }}</div>
      <br>
      <label>Foto STR</label>
      <img v-if="formData.image" :src="formData.image" width="500">
      <br>
      <nuxt-link :to="{name: 'sip'}" class="mr-2">
        <b-button variant="outline-primary" :disabled="loading">
          <fa icon="spinner" v-show="loading" spin /> Kembali
        </b-button>
      </nuxt-link>

    </b-container>
  </b-card>
</template>
  
<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import _ from 'lodash'

export default {
  // middleware: 'auth',
  middleware: ['auth'],

  head() {
    return { title: "Detail STR" }
  },
  async asyncData({ params }) {
    let f2 = axios.get('str/' + params.id)
    let f2resp = (await f2).data
    let formData = f2resp.model
    return {
      formData,
    }
  },
  data() {
    return {
      loading: false,
      errors: {},
    }
  },
  methods: {

  }
}
</script>
  