<template>
  <b-card title="Edit Role" class="my-4">
    <b-container fluid>
      <b-row class="my-1">
        <b-col sm="3">
          <label for="input-name">Name:</label>
        </b-col>
        <b-col sm="9">
          <b-form-input id="input-name" type="text" v-model="form.name" :state="getErrorState('name')"></b-form-input>
          <b-form-invalid-feedback v-if="getErrorState('name') === false">
            {{ getErrorMessage('name') }}
          </b-form-invalid-feedback>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="3">
          <label for="input-name">Permissions:</label>
        </b-col>
        <b-col sm="9">
          <b-form-checkbox
            v-for="permission in permissionOptions"
            v-model="form.permission_ids"
            :key="permission.id"
            :value="permission.id"
            name="permission_ids"
            :state="getErrorState('permission_ids')"
          >
            {{ permission.name }}
          </b-form-checkbox>
          <b-form-invalid-feedback v-if="getErrorState('permission_ids') === false">
            {{ getErrorMessage('permission_ids') }}
          </b-form-invalid-feedback>
        </b-col>
      </b-row>
       <b-row class="my-4">
        <b-col offset-sm="3" sm="9">
          <b-button variant="primary" @click="save" :disabled="loading">
            <fa icon="spinner" v-show="loading" spin /> Save
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
  middleware: 'auth',

  head () {
    return { title: "New User" }
  },
  async asyncData({ params }) {
    let f1 = axios.get('role/permission')
    let f2 = axios.get('role/' + params.id)
    let f1resp = (await f1).data
    let f2resp = (await f2).data
    let permissionOptions = f1resp.data
    let form = f2resp.model
    return {
      permissionOptions,
      form,
    }
  },
  data(){
    return{
      loading: false,
      errors: {},
    }
  },
  methods: {
    async save() {
      this.loading = true
      try {
        let resp = (await axios.patch('role/' + this.$route.params.id, this.form)).data
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
