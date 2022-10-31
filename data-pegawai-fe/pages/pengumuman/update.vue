<template>
  <b-card title="Konfigurasi Jam Kerja Default">
    <b-container fluid>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Satuan Kerja</label>
        </b-col>
        <b-col sm="6">
          <select
            class="form-control"
            v-model="form.satuan_kerja_id"
            disabled
          >
            <option value="">Semua satuan kerja</option>
            <option :value="form.satuan_kerja_id" v-for="(item,id) in satuanKerjaList" :key="id">{{ item.satuan_kerja_nama }}</option>
          </select>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label for="input-name">Unit Kerja</label>
        </b-col>
        <b-col sm="6">
          <b-form-input id="input-name" type="text" v-model="form.unit_kerja.unit_kerja_nama" disabled></b-form-input>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Judul</label>
        </b-col>
        <b-col sm="6">
          <b-form-input type="text" v-model="form.title"></b-form-input>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Deskripsi</label>
        </b-col>
        <b-col sm="6">
          <b-textarea v-model="form.description"></b-textarea>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Tanggal Kirim</label>
        </b-col>
        <b-col sm="3">
          <!-- <b-form-input type="text" v-model="form.name"></b-form-input> -->
          <datetime v-model="form.date" type="datetime" input-class="form-control" format="dd/MM/yyyy HH:mm" zone="Asia/Jakarta"></datetime>
        </b-col>
      </b-row>
    </b-container>
    
    <div class="mt-3">
      <b-button variant="info" @click="save()"><i class="fa fa-save"></i> Simpan</b-button>
      <nuxt-link :to="'/pengumuman/'">
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
import Vue from 'vue'
import { Datetime } from 'vue-datetime'
// You need a specific loader for CSS files
import 'vue-datetime/dist/vue-datetime.css'
import VueMoment from "vue-moment";
import moment from "moment-timezone";

Vue.component('datetime', Datetime);
Vue.use(VueMoment, { moment });

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
      unit_kerja_id: '',
      title: '',
      description: '',
      date: '',
      satuan_kerja: {
        satuan_kerja_nama: ''
      },
      unit_kerja: {
        unit_kerja_nama: ''
      }
    },
    disabled: 0,
    readonly: false,
    unitKerjaList: []
  }),
  computed: mapGetters({
    user: "auth/user",
  }),
  async asyncData({ app }) {
    let satuanKerjaList = [];
    let jamKerjaList = [];
    try{
      let f1 = await axios.get("/form-data/satuan-kerja-list");
      satuanKerjaList = f1.data.data;

      // let f2 = await axios.get("/master-jam-kerja/all");
      // jamKerjaList = f2.data.data;
    } catch (error) {
      console.log(error.response);
    }
    
    return {
      satuanKerjaList,
      jamKerjaList
    };
  },
  mounted: function() {
    this.getViewData();
  },
  watch: {
    
  },
  methods: {
    async getViewData(){
      if(this.$route.name == 'update_pengumuman'){
        try{
          let id = this.$route.params.id;
          let f1 = await axios.get("/announcements/view/" + id);
          let data = f1.data.data;
          this.form = data;
          let date = moment.tz(this.form.date_time, "Asia/Jakarta");
          this.form.date = date.format()

          this.getJamkerja();
        } catch (error) {
          console.log(error.response);
        }
      }
    },
    async getJamkerja(){
      this.jamKerjaList = [];
      try{
        let f1 = await axios.get("/form-data/jam-kerja-list/" + (this.form.satuan_kerja_id ? this.form.satuan_kerja_id : 0));
        let data = f1.data.data;
        console.table(data);
        this.jamKerjaList = data;
      } catch (error) {
        console.log(error.response);
      }
    },
    validateForm() {
      if (!this.form.title || this.form.title == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Judul belum diisi',
          timer:5000
        })
        return false
      }
      if (!this.form.description || this.form.description == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Deskripsi belum diisi',
          timer:5000
        })
        return false
      }
      if (!this.form.date || this.form.date == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Tanggal kirim belum diisi',
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

      let url = "/announcements/update";
      let date = moment.tz(this.form.date, "Asia/Jakarta");
      
      await axios.post(url, {
          id: this.form.id,
          title: this.form.title,
          description: this.form.description,
          date_time: date.format()
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
            this.$router.push("/pengumuman")
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
