<template>
  <b-card title="Manajemen Jam Kerja">
    <b-container fluid>
      <!-- <b-row class="my-1">
        <b-col sm="12">
          <label>Satuan Kerja</label>
        </b-col>
        <b-col sm="6">
          <select
            class="form-control"
            v-model="form.satuan_kerja_id"
          >
            <option value="">Semua satuan kerja</option>
            <option :value="item.satuan_kerja_id" v-for="(item,id) in satuanKerjaList" :key="id">{{ item.satuan_kerja_nama }}</option>
          </select>
        </b-col>
      </b-row> -->
      <b-row class="my-1">
        <b-col sm="12">
          <label>Kondisi</label>
        </b-col>
        <b-col sm="6">
          <select
            class="form-control"
            v-model="form.condition_type"
          >
            <option value="reguler">Reguler</option>
            <option value="ramadhan">Ramadhan</option>
          </select>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label for="input-name">Nama Jam Kerja</label>
        </b-col>
        <b-col sm="6">
          <b-form-input id="input-name" type="text" v-model="form.name"></b-form-input>
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
              <b-form-input id="input-name" type="number" v-model="form.duration_days" min="0"></b-form-input>
            </b-col>
          </b-row>
        </b-col>
        <b-col sm="3">
          <b-row>
            <b-col sm="12">
              <label>Durasi Kerja (Jam)</label>
            </b-col>
            <b-col sm="6">
              <b-form-input id="input-name" type="number" v-model="form.duration_hours" min="0"></b-form-input>
            </b-col>
          </b-row>
        </b-col>
        <b-col sm="3">
          <b-row>
            <b-col sm="12">
              <label>Durasi Menit</label>
            </b-col>
            <b-col sm="6">
              <b-form-input id="input-name" type="number" v-model="form.duration_minutes" min="0"></b-form-input>
            </b-col>
          </b-row>
        </b-col>
      </b-row>
      <b-row class="my-1" style="padding-top: 10px">
        <b-col sm="12">
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" v-model="form.is_shift">
            <label class="form-check-label" for="formCheck1">
                Shift
            </label>
          </div>
        </b-col>
      </b-row>
      <b-row class="my-1" v-if="form.is_shift">
        <b-col sm="12">
          <label for="input-name">Jam Pergantian</label>
        </b-col>
        <b-col sm="3">
          <b-form-timepicker v-model="form.shift_time" :hour12="false"></b-form-timepicker>
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
              <b-form-timepicker v-model="form.open_checkin" :hour12="false"></b-form-timepicker>
            </b-col>
          </b-row>
        </b-col>
        <b-col sm="4">
          <b-row>
            <b-col sm="12">
              <label>Jam Masuk</label>
            </b-col>
            <b-col sm="9">
              <b-form-timepicker v-model="form.start_time" :hour12="false"></b-form-timepicker>
            </b-col>
          </b-row>
        </b-col>
        <b-col sm="4">
          <b-row>
            <b-col sm="12">
              <label>Tutup Absen Masuk</label>
            </b-col>
            <b-col sm="9">
              <b-form-timepicker v-model="form.close_checkin" :hour12="false"></b-form-timepicker>
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
              <b-form-timepicker v-model="form.rest_start" :hour12="false"></b-form-timepicker>
            </b-col>
          </b-row>
        </b-col>
        <b-col sm="4">
          <b-row>
            <b-col sm="12">
              <label>Jam Selesai Istirahat</label>
            </b-col>
            <b-col sm="9">
              <b-form-timepicker v-model="form.rest_end" :hour12="false"></b-form-timepicker>
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
              <b-form-timepicker v-model="form.end_time" :hour12="false"></b-form-timepicker>
            </b-col>
          </b-row>
        </b-col>
        <b-col sm="4">
          <b-row>
            <b-col sm="12">
              <label>Tutup Absen Pulang</label>
            </b-col>
            <b-col sm="9">
              <b-form-timepicker v-model="form.close_checkout" :hour12="false"></b-form-timepicker>
            </b-col>
          </b-row>
        </b-col>
      </b-row>
      
      
    </b-container>
    
    <div class="mt-3">
      <b-button variant="info" @click="save()"><i class="fa fa-save"></i> Simpan</b-button>
      <nuxt-link :to="'/master-jam-kerja/'">
        <b-button variant="light">Batal</b-button>
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
      condition_type: 'reguler',
      name: '',
      duration_days: '',
      duration_hours: '',
      duration_minutes: '',
      is_shift: false,
      shift_time: '',
      open_checkin: '06:00',
      start_time: '07:00',
      close_checkin: '08:00',
      rest_start: '12:00',
      rest_end: '13:00',
      end_time: '16:00',
      open_checkout: '16:00',
      close_checkout: '18:00',
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
      let f1 = await axios.get("/form-data/satuan-kerja-list");
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
  watch: {
    'form.duration_days': {
      handler(val){
        this.form.duration_minutes = parseFloat(this.form.duration_days) * parseFloat(this.form.duration_hours) * 60
      },
    },
    'form.duration_hours': {
      handler(val){
        this.form.duration_minutes = parseFloat(this.form.duration_days) * parseFloat(this.form.duration_hours) * 60
      },
    }
  },
  methods: {
    async getViewData(){
      if(this.$route.name == 'update_master_jam_kerja'){
        try{
          let id = this.$route.params.id;
          let f1 = await axios.get("/master-jam-kerja/view/" + id);
          let data = f1.data.data;
          if(!data.satuan_kerja_id){
            data.satuan_kerja_id = '';
          }
          this.form = data;
        } catch (error) {
          console.log(error.response);
        }
      }
    },
    validateForm() {
      // if (!this.form.satuan_kerja_id || this.form.satuan_kerja_id == '') {
      //   Swal.fire({
      //     icon: 'warning',
      //     title: 'Gagal',
      //     text: 'Kolom Satuan Kerja belum diisi',
      //     timer:5000
      //   })
      //   return false
      // }
      if (!this.form.name || this.form.name == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Nama Jam Kerja belum diisi',
          timer:5000
        })
        return false
      }
      if (!this.form.duration_days || this.form.duration_days == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Jumlah Hari belum diisi',
          timer:5000
        })
        return false
      }
      if (!this.form.duration_hours || this.form.duration_hours == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Durasi Kerja belum diisi',
          timer:5000
        })
        return false
      }
      if (!this.form.duration_minutes || this.form.duration_minutes == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Durasi Menit belum diisi',
          timer:5000
        })
        return false
      }
      if(this.form.is_shift){
        if (!this.form.shift_time || this.form.shift_time == '') {
          Swal.fire({
            icon: 'warning',
            title: 'Gagal',
            text: 'Kolom Jam Pergantian belum diisi',
            timer:5000
          })
          return false
        }
      }
      if (!this.form.open_checkin || this.form.open_checkin == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Buka Absen Masuk belum diisi',
          timer:5000
        })
        return false
      }
      if (!this.form.start_time || this.form.start_time == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Jam Masuk belum diisi',
          timer:5000
        })
        return false
      }
      if (!this.form.close_checkin || this.form.close_checkin == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Tutup Absen Masuk belum diisi',
          timer:5000
        })
        return false
      }
      if (!this.form.rest_start || this.form.rest_start == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Jam Mulai Istirahat belum diisi',
          timer:5000
        })
        return false
      }
      if (!this.form.rest_end || this.form.rest_end == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Jam Selesai Istirahat belum diisi',
          timer:5000
        })
        return false
      }
      if (!this.form.end_time || this.form.end_time == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Jam Pulang belum diisi',
          timer:5000
        })
        return false
      }
      // if (!this.form.open_checkout || this.form.open_checkout == '') {
      //   Swal.fire({
      //     icon: 'warning',
      //     title: 'Gagal',
      //     text: 'Kolom Buka Absen Pulang belum diisi',
      //     timer:5000
      //   })
      //   return false
      // }
      if (!this.form.close_checkout || this.form.close_checkout == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Tutup Absen Pulang belum diisi',
          timer:5000
        })
        return false
      }
      
      return true
    },
    async save() {
      if (!this.validateForm()) return
      this.isLoading = true
      Swal.fire({
        title: 'Menyimpan Data!',
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading()
        }
      }).then((result) => {
        /* Read more about handling dismissals below */
      });

      let url = "/master-jam-kerja/store";
      if(this.$route.name == 'update_master_jam_kerja'){
        url = "/master-jam-kerja/update";
      }
      
      await axios.post(url, {
          id: this.form.id,
          satuan_kerja_id: this.form.satuan_kerja_id,
          condition_type: this.form.condition_type,
          name: this.form.name,
          duration_days: this.form.duration_days,
          duration_hours: this.form.duration_hours,
          duration_minutes: this.form.duration_minutes,
          is_shift: this.form.is_shift,
          shift_time: this.form.shift_time,
          open_checkin: this.form.open_checkin,
          start_time: this.form.start_time,
          close_checkin: this.form.close_checkin,
          rest_start: this.form.rest_start,
          rest_end: this.form.rest_end,
          end_time: this.form.end_time,
          open_checkout: this.form.open_checkout,
          close_checkout: this.form.close_checkout
        })
        .then(({ data }) => {
          console.log(data);
          if (data.success) {
            Swal.close()
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: 'Data Telah Tersimpan',
              confirmButtonText: 'Ok',
              timer: 5000
            })
            this.$router.push("/master-jam-kerja")
          } else{
            Swal.close()
            Swal.fire({
              icon: 'warning',
              title: 'Gagal',
              text: data.message,
              confirmButtonText: 'Ok',
              timer: 5000
            })
          }

        })
        .catch((e) => {
          Swal.close()
          this.isLoading = false
          // this.$swal.fire("Gagal", "Gagal Menyimpan Data !", "error");
          Swal.fire({
            icon: 'warning',
            title: 'Gagal',
            text: 'Gagal Menyimpan Data! '+e,
            confirmButtonText: 'Ok'
          })
        });
    },
  },

}
</script>
