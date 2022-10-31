<template>
  <b-card title="Konfigurasi Jam Kerja Dengan Satuan Kerja">
    <b-container fluid>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Satuan Kerja</label>
        </b-col>
        <b-col sm="6">
          <select
            class="form-control"
            v-model="form.satuan_kerja_id"
            :disabled="form.id || isSatuanKerjaDisabled()"
          >
            <option value="">Semua satuan kerja</option>
            <option :value="item.satuan_kerja_id" v-for="(item,id) in satuanKerjaList" :key="id">{{ item.satuan_kerja_nama }}</option>
          </select>
        </b-col>
      </b-row>
      <b-row class="my-1" v-if="!isEdit">
        <b-col sm="12">
          <label>Jam Kerja</label>
        </b-col>
        <b-col sm="6">
          <v-select multiple v-model="form.working_hour_ids" :options="jamKerjaList" :reduce="(option) => option.id" label="name" />
        </b-col>
      </b-row>
      <b-row class="my-1" v-if="isEdit">
        <b-col sm="12">
          <label>Jam Kerja</label>
        </b-col>
        <b-col sm="6">
          <v-select v-model="form.working_hour_id" :options="jamKerjaList" :reduce="(option) => option.id" label="name" />
        </b-col>
      </b-row>
    </b-container>
    
    <div class="mt-3">
      <b-button variant="info" @click="save()"><i class="fa fa-save"></i> Simpan</b-button>
      <nuxt-link :to="'/konfigurasi-jam-kerja-opd/'">
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
      unit_kerja_id: '',
      working_hour_id: '',
      working_hour_ids: []
    },
    disabled: 0,
    readonly: false,
    unitKerjaList: [],
    isEdit: false
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

      let f2 = await axios.get("/master-jam-kerja/all");
      jamKerjaList = f2.data.data;
    } catch (error) {
      console.log(error.response);
    }
    
    return {
      satuanKerjaList,
      jamKerjaList
    };
  },
  mounted: function() {
    if(this.$route.name == 'update_konfigurasi_jam_kerja_opd'){
      this.getViewData();
      this.getMasterJamKerja();
      this.isEdit = true;
    }
  },
  watch: {
    'form.satuan_kerja_id': {
      handler(val){
        this.getJamKerja();
      },
    },
  },
  methods: {
    isSatuanKerjaDisabled(){
      if(this.user.satuan_kerja_id){
        this.form.satuan_kerja_id = this.user.satuan_kerja_id;
        return true;
      }
      return false;
    },
    async getViewData(){
      try{
        let id = this.$route.params.id;
        let f1 = await axios.get("/konfigurasi-jam-kerja-opd/view/" + id);
        let data = f1.data.data;
        console.log(data);
        this.form = data;
        this.form.working_hour_ids = [];
      } catch (error) {
        console.log(error.response);
      }
    },
    async getMasterJamKerja(){
      let params = '?available_only=1';
      try{
        let f1 = await axios.get("/konfigurasi-jam-kerja-opd/view-by-opd/" + this.form.satuan_kerja_id + params);
        let data = f1.data.data;
        console.log(data);
        // this.form = data;
        this.form.working_hour_ids = [];
        if(data.length > 0){
          for(let i = 0; i < data.length; i++){
            this.form.working_hour_ids.push(data[i].working_hour_id);
          }
        }
      } catch (error) {
        console.log(error.response);
      }
    },
    async getJamKerja(){
      try{
        let f1 = await axios.get("/konfigurasi-jam-kerja-opd/view-by-opd/" + this.form.satuan_kerja_id);
        let data = f1.data.data;
        console.log(data);
        // this.form = data;
        this.form.working_hour_ids = [];
        if(data.length > 0){
          for(let i = 0; i < data.length; i++){
            this.form.working_hour_ids.push(data[i].working_hour_id);
          }
        }
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
      if(this.$route.name == 'update_konfigurasi_jam_kerja_opd'){
        if (!this.form.working_hour_id || this.form.working_hour_id.length == '') {
          Swal.fire({
            icon: 'warning',
            title: 'Gagal',
            text: 'Kolom Jam Kerja belum diisi',
            timer:5000
          })
          return false
        }
      } else {
        if (!this.form.working_hour_ids || this.form.working_hour_ids.length <= 0) {
          Swal.fire({
            icon: 'warning',
            title: 'Gagal',
            text: 'Kolom Jam Kerja belum diisi',
            timer:5000
          })
          return false
        }
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

      let url = "/konfigurasi-jam-kerja-opd/store";
      let working_hour_id = this.form.working_hour_ids;
      if(this.$route.name == 'update_konfigurasi_jam_kerja_opd'){
        url = "/konfigurasi-jam-kerja-opd/update";
        working_hour_id = this.form.working_hour_id;
      }
      
      await axios.post(url, {
          id: this.form.id,
          satuan_kerja_id: this.form.satuan_kerja_id,
          unit_kerja_id: this.form.unit_kerja_id,
          working_hour_id: working_hour_id
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
            this.$router.push("/konfigurasi-jam-kerja-opd")
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
