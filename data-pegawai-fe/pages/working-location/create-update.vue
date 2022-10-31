<template>
  <b-card title="Update Lokasi Kerja">
    <b-container fluid>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Nama</label>
        </b-col>
        <b-col sm="6">
          <input type="text" class="form-control" v-model="form.name">
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Alamat</label>
        </b-col>
        <b-col sm="6">
          <textarea class="form-control" v-model="form.address"></textarea>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Latitude</label>
        </b-col>
        <b-col sm="6">
          <input type="text" class="form-control" v-model="form.lat">
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Longitude</label>
        </b-col>
        <b-col sm="6">
          <input type="text" class="form-control" v-model="form.lng">
        </b-col>
      </b-row>
      
    </b-container>
    
    <div class="mt-3">
      <b-button variant="info" @click="save()"><i class="fa fa-save"></i> Simpan</b-button>
      <nuxt-link :to="'/user/'">
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
      name: '',
      address: '',
      lat: '',
      lng: ''
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
    let roleList = [];
    try{
      let f1 = await axios.get("/form-data/satuan-kerja-list");
      satuanKerjaList = f1.data.data;

      let f2 = await axios.get("/form-data/role-list");
      roleList = f2.data.data;

      // let f2 = await axios.get("/master-jam-kerja/all");
      // jamKerjaList = f2.data.data;
    } catch (error) {
      console.log(error.response);
    }
    
    return {
      satuanKerjaList,
      roleList,
      jamKerjaList
    };
  },
  mounted: function() {
    this.getViewData();
  },
  methods: {
    async getViewData(){
      if(this.$route.name == 'update_working_location'){
        try{
          let id = this.$route.params.id;
          let f1 = await axios.get("/working-locations/view/" + id);
          let data = f1.data.data;
          this.form = data;
          if(!data.satuan_kerja_id){
            this.form.satuan_kerja_id = '';
          }
          if(!data.unit_kerja_id){
            this.form.unit_kerja_id = '';
          }

          this.getJamkerja();
        } catch (error) {
          console.log(error.response);
        }
      }
    },
    validateForm() {
      if (!this.form.name || this.form.name == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Nama belum diisi',
          timer:5000
        })
        return false
      }
      if (!this.form.address || this.form.address == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Alamat belum diisi',
          timer:5000
        })
        return false
      }
      if (!this.form.lat || this.form.lat == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Latitude belum diisi',
          timer:5000
        })
        return false
      }
      if (!this.form.lng || this.form.lng == '') {
        Swal.fire({
          icon: 'warning',
          title: 'Gagal',
          text: 'Kolom Longitude belum diisi',
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

      let url = "/working-locations/store";
      if(this.$route.name == 'update_working_location'){
        url = "/working-locations/update";
      }
      
      await axios.post(url, {
          id: this.form.id,
          name: this.form.name,
          address: this.form.address,
          lat: this.form.lat,
          lng: this.form.lng
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
            this.$router.push("/working-location")
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
