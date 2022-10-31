<template>
  <b-card title="Pengumuman">
    <b-container fluid>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Satuan Kerja</label>
        </b-col>
        <b-col sm="6">
          <select
            class="form-control"
            v-model="form.satuan_kerja_id"
            :disabled="isSatuanKerjaDisabled()"
          >
            <option value="">Semua satuan kerja</option>
            <option :value="item.satuan_kerja_id" v-for="(item,id) in satuanKerjaList" :key="id">{{ item.satuan_kerja_nama }}</option>
          </select>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Unit Kerja</label>
        </b-col>
        <b-col sm="6">
          <select
            class="form-control"
            v-model="form.unit_kerja_id"
            :disabled="isUnitKerjaDisabled()"
          >
            <option value="">Semua unit kerja</option>
            <option :value="item.unit_kerja_id" v-for="(item,id) in unitKerjaList" :key="id">{{ item.unit_kerja_nama }}</option>
          </select>
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
          <datetime v-model="form.date" type="datetime" input-class="form-control" format="dd/MM/yyyy HH:mm"></datetime>
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
      date: ''
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
    
  },
  watch: {
    'form.satuan_kerja_id': {
      handler(val){
        this.getUnitkerja();
        this.getJamkerja();
      },
    }
  },
  methods: {
    isSatuanKerjaDisabled(){
      if(this.user.satuan_kerja_id){
        this.form.satuan_kerja_id = this.user.satuan_kerja_id;
        return true;
      }
      return false;
    },
    isUnitKerjaDisabled(){
      if(this.user.unit_kerja_id){
        this.form.unit_kerja_id = this.user.unit_kerja_id;
        return true;
      }
      return false;
    },
    async getUnitkerja(){
      this.unitKerjaList = [];
      if(this.form.satuan_kerja_id){
        try{
          let f1 = await axios.get("/form-data/unit-kerja-list/" + this.form.satuan_kerja_id);
          let data = f1.data.data;
          console.log(data);
          this.unitKerjaList = data;
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
        console.log(data);
        this.jamKerjaList = data;
      } catch (error) {
        console.log(error.response);
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

      let url = "/announcements/store";
      if(this.$route.name == 'update_pengumuman'){
        url = "/announcements/update";
      }
      let date = moment.tz(this.form.date, "Asia/Jakarta");
      
      await axios.post(url, {
          id: this.form.id,
          satuan_kerja_id: this.form.satuan_kerja_id,
          unit_kerja_id: this.form.unit_kerja_id,
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
