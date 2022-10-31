<template>
  <b-modal id="modal-data-hubungan-kerabat" ref="modal-data-hubungan-kerabat" title="Tambah Data Hubungan Kerabat">
    <template #modal-header="{}">
      <h5>Informasi Data Hubungan Kerabat</h5>
    </template>
    <template #default="{}">
      <b-row class="my-1">
        <b-col sm="12">
          <label>Nama Pegawai</label>
        </b-col>
        <b-col sm="12">
          <!-- <v-select :options="PEGOptions" :reduce="item => item.peg_nama_lengkap" label="peg_nama_lengkap"
            placeholder="Pilih Pegawai" v-model="form_data_hubungan_kerabat.nama">
          </v-select> -->
          <b-form-select class="font-size-13" v-model="nama_pegawai" :options="PEGOptions"
            value-field="peg_nama_lengkap" text-field="peg_nama_lengkap">
          </b-form-select>
          <!-- <v-select ></v-select> -->
        </b-col>
        <b-col sm="12">
          <input type="checkbox" id="checkbox" v-model="form_data_hubungan_kerabat.selected" />
          <label for="checkbox">Bila tidak ada nama di opsi tersebut</label>
          <template sm="12" v-if="form_data_hubungan_kerabat.selected">
            <b-form-input id="input-nama" name="nama" label="peg_nama_lengkap"
              v-model="form_data_hubungan_kerabat.nama2"></b-form-input>
          </template>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Hubungan Kerabat</label>
        </b-col>
        <b-col sm="12">
          <b-form-input id="hubungan_kerabat" name="hubungan_kerabat" type="text"
            v-model="form_data_hubungan_kerabat.hubungan_kerabat" :state="getErrorState('hubungan_kerabat')" />
          <p style="color:red;" v-if="getErrorState('hubungan_kerabat') === false">
            {{ getErrorMessage('hubungan_kerabat') }}
          </p>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Unit Kerja</label>
        </b-col>
        <b-col sm="12">
          <b-form-input id="unit_kerja" name="unit_kerja" type="text" v-model="unit_kerja" />
          <!-- <p style="color:red;" v-if="getErrorState('unit_kerja') === false">
            {{ getErrorMessage('unit_kerja') }}
          </p> -->
        </b-col>
      </b-row>
    </template>
    <template #modal-footer="{}">
      <!-- Emulate built in modal footer ok and cancel button actions -->
      <b-button size="md" variant="info" @click="saveDataHubunganKerabat()" :disabled="loading">
        <i class="fa fa-save"></i> {{ loading ? 'Sedang Menyimpan' : 'Simpan' }}
      </b-button>
      <b-button size="md" variant="light" @click="cancelDataHubunganKerabat()">
        Batal
      </b-button>
    </template>
  </b-modal>
</template>

<script>
import Swal from 'sweetalert2'
import axios from 'axios'
import { mapGetters } from 'vuex'

export default {
  props: ['pegawai'],
  computed: mapGetters({
    PEGOptions: 'useroptions/PEGOptions',
  }),
  data() {
    return {
      loading: false,
      form_data_hubungan_kerabat: {},
      nama_pegawai: '',
      unit_kerja: '',
      errors: {}
    }
  },
  watch: {
    nama_pegawai() {
      this.getUnitKerja()
    }
  },
  methods: {
    openDataHubunganKerabatModal(data) {
      this.unit_kerja = null,
      this.nama_pegawai = null
      if (data) {
        this.form_data_hubungan_kerabat = {
          id: data.id,
          peg_id: data.peg_id,
          hubungan_kerabat: data.hubungan_kerabat,
        };
        this.unit_kerja = data.unit_kerja
        this.nama_pegawai = data.nama
      } else {
        this.form_data_hubungan_kerabat = {
          peg_id: this.pegawai.peg_id,
        };
      }
      this.errors = {}
      this.$refs['modal-data-hubungan-kerabat'].show()
    },
    async saveDataHubunganKerabat() {
      this.loading = true
      try {
        if (this.form_data_hubungan_kerabat.id) {
          let formData = new FormData()
          if (this.form_data_hubungan_kerabat.selected) {
            formData.append('nama', this.form_data_hubungan_kerabat.nama2)
          } else {
            formData.append('nama', this.nama_pegawai)
          }
          formData.append('peg_id', this.form_data_hubungan_kerabat.peg_id)
          formData.append('hubungan_kerabat', this.form_data_hubungan_kerabat.hubungan_kerabat)
          formData.append('unit_kerja', this.unit_kerja)
          let jsonData = {
            nama: this.form_data_hubungan_kerabat.selected ? this.form_data_hubungan_kerabat.nama2 : this.nama_pegawai,
            peg_id: this.form_data_hubungan_kerabat.peg_id,
            hubungan_kerabat: this.form_data_hubungan_kerabat.hubungan_kerabat,
            unit_kerja: this.unit_kerja
          }
          let resp = (await axios.patch('pegawai/data-hubungan-kerabat/' + this.form_data_hubungan_kerabat.id, jsonData)).data
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        } else {
          let formData = new FormData()
          if (this.form_data_hubungan_kerabat.selected) {
            formData.append('nama', this.form_data_hubungan_kerabat.nama2)
          } else {
            formData.append('nama', this.nama_pegawai)
          }
          formData.append('peg_id', this.form_data_hubungan_kerabat.peg_id)
          formData.append('hubungan_kerabat', this.form_data_hubungan_kerabat.hubungan_kerabat)
          formData.append('unit_kerja', this.unit_kerja)
          let resp = (await axios.post('pegawai/data-hubungan-kerabat', formData)).data
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: resp.message,
            confirmButtonText: 'ok',
          })
        }
        this.loading = false
        this.$emit('onSave')
        this.$refs['modal-data-hubungan-kerabat'].hide()
      } catch (err) {
        if (err.response && err.response.status == 422) {
          this.errors = err.response.data.errors;
        }
        this.loading = false
      }
    },
    cancelDataHubunganKerabat() {
      this.$refs['modal-data-hubungan-kerabat'].hide()
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
    async getUnitKerja() {
      let getUnit = (await axios.get('/pegawai/unit-kerja', {
        params: {
          peg_nama_lengkap: this.nama_pegawai
        }
      })).data.data
      this.unit_kerja = getUnit.unit_kerja_nama
    }
  }
}
</script>

<style>

</style>