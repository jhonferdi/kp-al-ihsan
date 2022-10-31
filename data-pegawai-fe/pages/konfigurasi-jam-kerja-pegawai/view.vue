<template>
  <b-card title="Manajemen Jam Kerja">
    <b-container fluid>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Satuan Kerja</label>
        </b-col>
        <b-col sm="6">
          <div class="form-value">{{ form.satuan_kerja.satuan_kerja_nama }}</div>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Kondisi</label>
        </b-col>
        <b-col sm="6">
          <div class="form-value">{{ form.condition_type_formatted }}</div>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label for="input-name">Nama Jam Kerja</label>
        </b-col>
        <b-col sm="6">
          <div class="form-value">{{ form.name }}</div>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col class="label-title">
          <label>Durasi</label>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="3">
          <b-row>
            <b-col sm="12">
              <label>Jumlah Hari</label>
            </b-col>
            <b-col sm="6">
              <div class="form-value">{{ form.duration_days }}</div>
            </b-col>
          </b-row>
        </b-col>
        <b-col sm="3">
          <b-row>
            <b-col sm="12">
              <label>Durasi Kerja (Jam)</label>
            </b-col>
            <b-col sm="6">
              <div class="form-value">{{ form.duration_days }}</div>
            </b-col>
          </b-row>
        </b-col>
        <b-col sm="3">
          <b-row>
            <b-col sm="12">
              <label>Durasi Menit</label>
            </b-col>
            <b-col sm="6">
              <div class="form-value">{{ form.duration_minutes }}</div>
            </b-col>
          </b-row>
        </b-col>
      </b-row>
      <b-row class="my-1" v-if="form.is_shift">
        <b-col sm="12">
          <label for="input-name">Shift</label>
        </b-col>
        <b-col sm="3">
          <div class="form-value">{{ form.is_shift ? 'Ya' : 'Tidak' }}</div>
        </b-col>
      </b-row>
      <b-row class="my-1" v-if="form.is_shift">
        <b-col sm="12">
          <label for="input-name">Jam Pergantian</label>
        </b-col>
        <b-col sm="3">
          <div class="form-value">{{ form.shift_time }}</div>
        </b-col>
      </b-row>

      <b-row class="my-1">
        <b-col class="label-title">
          <label>Konfigurasi Jam Kerja</label>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="4">
          <b-row>
            <b-col sm="12">
              <label>Buka Absen Masuk</label>
            </b-col>
            <b-col sm="9">
              <div class="form-value">{{ form.open_checkin }}</div>
            </b-col>
          </b-row>
        </b-col>
        <b-col sm="4">
          <b-row>
            <b-col sm="12">
              <label>Jam Masuk</label>
            </b-col>
            <b-col sm="9">
              <div class="form-value">{{ form.start_time }}</div>
            </b-col>
          </b-row>
        </b-col>
        <b-col sm="4">
          <b-row>
            <b-col sm="12">
              <label>Tutup Absen Masuk</label>
            </b-col>
            <b-col sm="9">
              <div class="form-value">{{ form.close_checkin }}</div>
            </b-col>
          </b-row>
        </b-col>
      </b-row>

      <b-row class="my-1">
        <b-col sm="4">
          <b-row>
            <b-col sm="12">
              <label>Jam Mulai Istirahat</label>
            </b-col>
            <b-col sm="9">
              <div class="form-value">{{ form.rest_start }}</div>
            </b-col>
          </b-row>
        </b-col>
        <b-col sm="4">
          <b-row>
            <b-col sm="12">
              <label>Jam Selesai Istirahat</label>
            </b-col>
            <b-col sm="9">
              <div class="form-value">{{ form.rest_end }}</div>
            </b-col>
          </b-row>
        </b-col>
      </b-row>

      <b-row class="my-1">
        <b-col sm="4">
          <b-row>
            <b-col sm="12">
              <label>Jam Pulang</label>
            </b-col>
            <b-col sm="9">
              <div class="form-value">{{ form.end_time }}</div>
            </b-col>
          </b-row>
        </b-col>
        <b-col sm="4">
          <b-row>
            <b-col sm="12">
              <label>Tutup Absen Pulang</label>
            </b-col>
            <b-col sm="9">
              <div class="form-value">{{ form.close_checkout }}</div>
            </b-col>
          </b-row>
        </b-col>
      </b-row>
      
      
    </b-container>
    
    <div class="mt-3">
      <nuxt-link :to="'/master-jam-kerja/'">
        <b-button variant="light">Kembali</b-button>
      </nuxt-link>
    </div>
  </b-card>
</template>

<style scoped>
.label-title label{
  font-weight: bold;
  border-bottom: 1px solid #999;
  display: block;
  padding-top: 20px;
}

.form-value{
  background: #eee;
  padding: 5px 10px;
  border-radius: 3px;
}
</style>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import UploadFileCard from '@/components/global/uploadFile'
import { mapGetters } from "vuex"
// import DateDropdown from 'vue-date-dropdown'
import DateDropdown from '@/components/global/DateDropdown'

export default {
  middleware: 'auth',
  components: {
    UploadFileCard,
    DateDropdown
  },

  head() {
    return { title: this.$t('project') }
  },

  data: () => ({
    form: {
      id: null,
      satuan_kerja_id: '',
      satuan_kerja: {
        satuan_kerja_nama: ''
      },
      condition_type: 'reguler',
      name: '',
      duration_days: '',
      duration_hours: '',
      duration_minutes: '',
      is_shift: false,
      shift_time: '',
      open_checkin: '',
      start_time: '',
      close_checkin: '',
      rest_start: '',
      rest_end: '',
      end_time: '',
      open_checkout: '',
      close_checkout: '',
    },
    disabled: 0,
    readonly: false,
  }),
  computed: mapGetters({
    user: "auth/user",
  }),
  async asyncData({ app }) {
    let satuanKerjaList = [];
    try{
      let f1 = await axios.get("/satuan-kerja-list");
      satuanKerjaList = f1.data.data;
    } catch (error) {
      console.log(error.response);
    }
    
    return {
      satuanKerjaList
    };
  },
  mounted: function() {
    this.getViewData();
  },
  methods: {
    async getViewData(){
      try{
        let id = this.$route.params.id;
        let f1 = await axios.get("/master-jam-kerja/view/" + id);
        let data = f1.data.data;
        this.form = data;
      } catch (error) {
        console.log(error.response);
      }
    }
  },

}
</script>
