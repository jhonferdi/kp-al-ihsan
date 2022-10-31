<template>
  <b-card title="Konfigurasi Jam Kerja Pegawai">
    <b-container fluid>
      <b-row class="my-1">
        <b-col sm="12">
          <label>NIP</label>
        </b-col>
        <b-col sm="6">
          <input type="text" class="form-control" v-model="form.pegawai.peg_nip" disabled>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Nama</label>
        </b-col>
        <b-col sm="6">
          <input type="text" class="form-control" v-model="form.pegawai.peg_nama" disabled>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Satuan Kerja</label>
        </b-col>
        <b-col sm="6">
          <input type="text" class="form-control" v-model="form.pegawai.satuan_kerja.satuan_kerja_nama" disabled>
        </b-col>
      </b-row>
      <b-row class="my-1">
        <b-col sm="12">
          <label>Unit Kerja</label>
        </b-col>
        <b-col sm="6">
          <input type="text" class="form-control" v-model="form.pegawai.unit_kerja.unit_kerja_nama" disabled>
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
      <b-row class="my-1">
        <b-col sm="12">
          <label>Status</label>
        </b-col>
        <b-col sm="6">
          <b-form-checkbox v-model="form.status" name="check-button" switch></b-form-checkbox>
        </b-col>
      </b-row>
    </b-container>
    
    <div class="mt-3">
      <b-button variant="info" @click="save()"><i class="fa fa-save"></i> Simpan</b-button>
      <nuxt-link :to="'/konfigurasi-jam-kerja-pegawai/'">
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
      type: 'nip',
      nip: '',
      satuan_kerja_id: '',
      unit_kerja_id: '',
      working_hour_id: '',
      selected_unit_kerja_id: '',
      status: true,
      pegawai: {
        peg_nip: '',
        peg_nama: '',
        satuan_kerja: {
          satuan_kerja_nama: ''
        },
        unit_kerja: {
          unit_kerja_nama: ''
        }
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
    'form.satuan_kerja_id': {
      handler(val){
        this.getUnitkerja();
        this.getJamkerja();
      },
    }
  },
  methods: {
    async getViewData(){
      try{
        let id = this.$route.params.id;
        let f1 = await axios.get("/konfigurasi-jam-kerja-pegawai/view/" + id);
        let data = f1.data.data;
        console.log(data);
        this.form = data;
        this.form.satuan_kerja_id = data.pegawai.satuan_kerja_id;

        this.getJamkerja();
      } catch (error) {
        console.log(error.response);
      }
    },
    async getUnitkerja(){
      this.unitKerjaList = [];
      if(this.form.satuan_kerja_id){
        try{
          let f1 = await axios.get("/form-data/unit-kerja-list/" + this.form.satuan_kerja_id + '?has_pegawai=1');
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
      console.log(this.form.satuan_kerja_id);
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

      let url = "/konfigurasi-jam-kerja-pegawai/update";
      
      await axios.post(url, {
          id: this.form.id,
          working_hour_id: this.form.working_hour_id,
          status: this.form.status
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
            this.$router.push("/konfigurasi-jam-kerja-pegawai")
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
