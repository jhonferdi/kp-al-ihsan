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
            <option :value="item.satuan_kerja_id" v-for="(item,id) in satuanKerjaList" :key="id">{{ item.satuan_kerja_nama }}</option>
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
          <label>Jam Kerja</label>
        </b-col>
        <b-col sm="6">
          <select
            class="form-control"
            v-model="form.working_hour_id"
          >
            <option value="">Pilih jam kerja</option>
            <option :value="item.id" v-for="(item,id) in jamKerjaList" :key="id">{{ item.name }}</option>
          </select>
        </b-col>
      </b-row>
    </b-container>
    
    <div class="mt-3">
      <b-button variant="info" @click="save()"><i class="fa fa-save"></i> Simpan</b-button>
      <nuxt-link :to="'/konfigurasi-jam-kerja-default/'">
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
      peg_id: '',
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
      if(this.$route.name == 'update_konfigurasi_jam_kerja_default'){
        try{
          let id = this.$route.params.id;
          let f1 = await axios.get("/konfigurasi-jam-kerja-default/view/" + id);
          let data = f1.data.data;
          this.form = data;
          console.log('viewdata', data);

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
        this.jamKerjaList = data;
      } catch (error) {
        console.log(error.response);
      }
    },
    validateForm() {
      if (!this.form.working_hour_id || this.form.working_hour_id == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Jam Kerja belum diisi',
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

      let url = "/konfigurasi-jam-kerja-default/update";
      
      await axios.post(url, {
          id: this.form.id,
          working_hour_id: this.form.working_hour_id
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
            this.$router.push("/konfigurasi-jam-kerja-default")
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
